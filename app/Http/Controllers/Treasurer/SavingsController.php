<?php
// app/Http/Controllers/Treasurer/SavingsController.php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SavingsController extends Controller
{
    /**
     * Display all savings transactions recorded by this treasurer
     */
    public function index(Request $request)
    {
        $query = SavingsTransaction::with(['user'])
            ->where('recorded_by', auth()->id());

        // Search by user
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('transaction_type', $request->type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        // Calculate totals
        $totals = [
            'total_deposits' => SavingsTransaction::where('recorded_by', auth()->id())
                ->where('transaction_type', 'deposit')
                ->where('status', 'approved')
                ->sum('amount'),
            'total_withdrawals' => SavingsTransaction::where('recorded_by', auth()->id())
                ->where('transaction_type', 'withdrawal')
                ->where('status', 'approved')
                ->sum('amount'),
            'today_count' => SavingsTransaction::where('recorded_by', auth()->id())
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('treasurer.savings.index', compact('transactions', 'totals'));
    }

    /**
     * Show form to create new transaction
     */
    public function create()
    {
        $members = User::role('member')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'membership_number', 'email']);

        return view('treasurer.savings.create', compact('members'));
    }

    /**
     * Store a new savings transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'transaction_type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:1|max:10000000',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate current balance
        $deposits = SavingsTransaction::where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->where('transaction_type', 'deposit')
            ->sum('amount');

        $withdrawals = SavingsTransaction::where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');

        $currentBalance = $deposits - $withdrawals;

        // Validate withdrawal amount
        if ($request->transaction_type === 'withdrawal' && $request->amount > $currentBalance) {
            return redirect()->back()
                ->with('error', 'Insufficient balance. Current balance: CDF ' . number_format($currentBalance, 2))
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Calculate new balance
            $balanceAfter = $request->transaction_type === 'deposit'
                ? $currentBalance + $request->amount
                : $currentBalance - $request->amount;

            // Get next ID for transaction number
            $nextId = SavingsTransaction::withTrashed()->max('id') + 1;

            // Create transaction (automatically approved for treasurer)
            $transaction = SavingsTransaction::create([
                'transaction_number' => 'TXN-' . date('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'transaction_type' => $request->transaction_type,
                'transaction_date' => $request->transaction_date,
                'receipt_number' => 'RCT-' . date('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                'notes' => $request->notes,
                'recorded_by' => auth()->id(),
                'status' => 'approved',
                'balance_after' => $balanceAfter
            ]);

            DB::commit();

            return redirect()->route('treasurer.savings.show', $transaction)
                ->with('success', 'Transaction recorded successfully. Receipt #: ' . $transaction->receipt_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Savings transaction error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to record transaction. Please try again.')
                ->withInput();
        }
    }

     /**
     * Get member details for savings transaction
     */
    public function getMemberDetails(Request $request)
    {
        try {
            Log::info('getMemberDetails called', ['request_data' => $request->all()]);

            $userId = $request->get('user_id');

            if (!$userId) {
                Log::warning('User ID is missing in request');
                return response()->json([
                    'error' => 'User ID is required'
                ], 400);
            }

            Log::info('Fetching member details for user', ['user_id' => $userId]);

            // Load user with role
            $user = User::with(['roles'])->where('id', $userId)->first();

            if (!$user) {
                Log::warning('User not found', ['user_id' => $userId]);
                return response()->json([
                    'error' => 'Member not found'
                ], 404);
            }

            Log::info('User found', ['user' => $user->toArray()]);

            // Check if user has member role
            if (!$user->hasRole('member')) {
                Log::warning('User is not a member', ['user_id' => $userId, 'roles' => $user->getRoleNames()]);
                return response()->json([
                    'error' => 'Selected user is not a member'
                ], 400);
            }

            // Calculate savings balance from transactions
            $totalDeposits = SavingsTransaction::where('user_id', $userId)
                ->where('transaction_type', 'deposit')
                ->where('status', 'approved')
                ->sum('amount');

            $totalWithdrawals = SavingsTransaction::where('user_id', $userId)
                ->where('transaction_type', 'withdrawal')
                ->where('status', 'approved')
                ->sum('amount');

            $savingsBalance = $totalDeposits - $totalWithdrawals;

            // Get recent transactions
            try {
                $recentTransactions = SavingsTransaction::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($transaction) {
                        return [
                            'id' => $transaction->id,
                            'date' => $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d') : $transaction->created_at->format('Y-m-d'),
                            'type' => $transaction->transaction_type ?? 'unknown',
                            'amount' => (float)($transaction->amount ?? 0),
                            'status' => $transaction->status ?? 'pending',
                            'reference' => $transaction->transaction_number ?? $transaction->receipt_number ?? 'N/A'
                        ];
                    });

                Log::info('Recent transactions fetched', ['count' => count($recentTransactions)]);
            } catch (\Exception $e) {
                Log::error('Failed to fetch recent transactions', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId
                ]);
                $recentTransactions = []; // Empty array on error
            }

            // Get member number (adjust field name based on your actual database)
            $memberNumber = $user->member_number ??
                           $user->membership_number ??
                           $user->member_id ??
                           'MEM-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            // Prepare response data
            $response = [
                'id' => $user->id,
                'member_number' => $memberNumber,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'savings_balance' => $savingsBalance,
                'account_status' => 'active', // You might want to add a status field to users table
                'account_number' => 'SAV-' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate a virtual account number
                'member_since' => $user->created_at ? $user->created_at->format('M d, Y') : null,
                'recent_transactions' => $recentTransactions
            ];

            Log::info('Successfully prepared member details response', ['response' => $response]);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Unexpected error in getMemberDetails', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user_id ?? 'not provided'
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show transaction details
     */
    public function show(SavingsTransaction $saving)
    {
        // Ensure this transaction was recorded by the current treasurer
        if ($saving->recorded_by != auth()->id()) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $saving->load(['user', 'recorder']);

        return view('treasurer.savings.show', compact('saving'));
    }

    /**
     * Display deposits only
     */
    public function deposits(Request $request)
    {
        $query = SavingsTransaction::with(['user'])
            ->where('recorded_by', auth()->id())
            ->where('transaction_type', 'deposit');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $deposits = $query->latest()->paginate(15);

        $totalDeposits = SavingsTransaction::where('recorded_by', auth()->id())
            ->where('transaction_type', 'deposit')
            ->sum('amount');

        return view('treasurer.savings.deposits', compact('deposits', 'totalDeposits'));
    }

    /**
     * Display withdrawals only
     */
    public function withdrawals(Request $request)
    {
        $query = SavingsTransaction::with(['user'])
            ->where('recorded_by', auth()->id())
            ->where('transaction_type', 'withdrawal');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        $withdrawals = $query->latest()->paginate(15);

        $totalWithdrawals = SavingsTransaction::where('recorded_by', auth()->id())
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');

        return view('treasurer.savings.withdrawals', compact('withdrawals', 'totalWithdrawals'));
    }

    /**
     * Print receipt
     */
    public function printReceipt(SavingsTransaction $saving)
    {
        if ($saving->recorded_by != auth()->id()) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        return view('treasurer.savings.receipt', compact('saving'));
    }
}
