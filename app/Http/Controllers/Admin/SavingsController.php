<?php
// app/Http/Controllers/Admin/SavingsController.php

namespace App\Http\Controllers\Admin;

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
     * Display a listing of all savings transactions
     */
    public function index(Request $request)
    {
        $query = SavingsTransaction::with(['user', 'recorder']);

        // Search by user
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by transaction type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('transaction_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        // Calculate summary statistics
        $summary = [
            'total_deposits' => SavingsTransaction::where('transaction_type', 'deposit')->where('status', 'approved')->sum('amount'),
            'total_withdrawals' => SavingsTransaction::where('transaction_type', 'withdrawal')->where('status', 'approved')->sum('amount'),
            'pending_count' => SavingsTransaction::where('status', 'pending')->count(),
            'today_count' => SavingsTransaction::whereDate('transaction_date', today())->count(),
            'today_amount' => SavingsTransaction::whereDate('transaction_date', today())->where('status', 'approved')->sum('amount'),
        ];

        return view('admin.savings.index', compact('transactions', 'summary'));
    }

    /**
     * Show form to create new transaction with member selection
     */
    public function create(Request $request)
    {
        $members = User::role('member')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'membership_number', 'email', 'phone_number']);

        $selectedMember = null;
        $recentTransactions = collect();
        $currentBalance = 0;

        // If a member is selected, load their info
        if ($request->has('user_id') && $request->user_id) {
            $selectedMember = User::with(['savingsTransactions' => function($q) {
                $q->where('status', 'approved')->latest();
            }])->find($request->user_id);

            if ($selectedMember) {
                $currentBalance = $selectedMember->total_savings;
                $recentTransactions = $selectedMember->savingsTransactions()
                    ->with('recorder')
                    ->where('status', 'approved')
                    ->latest()
                    ->limit(10)
                    ->get();
            }
        }

        return view('admin.savings.create', compact(
            'members',
            'selectedMember',
            'recentTransactions',
            'currentBalance'
        ));
    }

    /**
     * Get member details via AJAX for the create form
     */
    public function getMemberDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $member = User::find($request->user_id);

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

        $recentTransactions = SavingsTransaction::with('recorder')
            ->where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'date' => $transaction->transaction_date->format('d M Y'),
                    'type' => $transaction->transaction_type,
                    'amount' => number_format($transaction->amount, 2),
                    'status' => $transaction->status,
                    'status_color' => $transaction->status_color,
                    'reference' => $transaction->transaction_number,
                    'balance' => number_format($transaction->balance_after, 2)
                ];
            });

        return response()->json([
            'name' => $member->name,
            'membership_number' => $member->membership_number,
            'email' => $member->email,
            'phone' => $member->phone_number,
            'current_balance' => number_format($currentBalance, 2),
            'recent_transactions' => $recentTransactions
        ]);
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
        'status' => 'required|in:pending,approved',
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
            ->with('error', 'Insufficient balance. Current balance: UGX ' . number_format($currentBalance, 2))
            ->withInput();
    }

    DB::beginTransaction();

    try {
        // Calculate new balance
        $balanceAfter = $request->transaction_type === 'deposit'
            ? $currentBalance + $request->amount
            : $currentBalance - $request->amount;

        // Get the next ID for transaction number
        $nextId = SavingsTransaction::withTrashed()->max('id') + 1;

        // Generate receipt number if approved
        $receiptNumber = null;
        if ($request->status === 'approved') {
            $receiptNumber = 'RCT-' . date('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }

        // Create transaction
        $transaction = SavingsTransaction::create([
            'transaction_number' => 'TXN-' . date('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'transaction_date' => $request->transaction_date,
            'receipt_number' => $receiptNumber,
            'notes' => $request->notes,
            'recorded_by' => auth()->id(),
            'status' => $request->status,
            'balance_after' => $balanceAfter
        ]);

        DB::commit();

        $message = $request->transaction_type === 'deposit' ? 'Deposit' : 'Withdrawal';
        $statusText = $request->status === 'approved' ? 'approved' : 'pending';

        return redirect()->route('admin.savings.show', $transaction)
            ->with('success', $message . ' transaction recorded successfully and is ' . $statusText . '.');

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        Log::error('SQL Error in savings transaction: ' . $e->getMessage());
        Log::error('SQL: ' . $e->getSql());
        Log::error('Bindings: ' . json_encode($e->getBindings()));

        return redirect()->back()
            ->with('error', 'Database error: ' . $e->getMessage())
            ->withInput();

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('General error in savings transaction: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        return redirect()->back()
            ->with('error', 'Failed to record transaction. Error: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Display the specified transaction
     */
    public function show(SavingsTransaction $saving)
    {
        $saving->load(['user', 'recorder']);

        // Calculate running balance for this user
        $previousTransactions = SavingsTransaction::where('user_id', $saving->user_id)
            ->where('status', 'approved')
            ->where('id', '<', $saving->id)
            ->orderBy('id')
            ->get();

        $balanceBefore = 0;
        foreach ($previousTransactions as $trans) {
            if ($trans->transaction_type === 'deposit') {
                $balanceBefore += $trans->amount;
            } else {
                $balanceBefore -= $trans->amount;
            }
        }

        return view('admin.savings.show', compact('saving', 'balanceBefore'));
    }

    /**
     * Display deposits only
     */
    public function deposits(Request $request)
    {
        $query = SavingsTransaction::with(['user', 'recorder'])
            ->where('transaction_type', 'deposit');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $deposits = $query->latest()->paginate(15)->withQueryString();

        $totalDeposits = SavingsTransaction::where('transaction_type', 'deposit')
            ->where('status', 'approved')
            ->sum('amount');

        $todayDeposits = SavingsTransaction::where('transaction_type', 'deposit')
            ->where('status', 'approved')
            ->whereDate('transaction_date', today())
            ->sum('amount');

        return view('admin.savings.deposits', compact('deposits', 'totalDeposits', 'todayDeposits'));
    }

    /**
     * Display withdrawals only
     */
    public function withdrawals(Request $request)
    {
        $query = SavingsTransaction::with(['user', 'recorder'])
            ->where('transaction_type', 'withdrawal');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $withdrawals = $query->latest()->paginate(15)->withQueryString();

        $totalWithdrawals = SavingsTransaction::where('transaction_type', 'withdrawal')
            ->where('status', 'approved')
            ->sum('amount');

        $todayWithdrawals = SavingsTransaction::where('transaction_type', 'withdrawal')
            ->where('status', 'approved')
            ->whereDate('transaction_date', today())
            ->sum('amount');

        return view('admin.savings.withdrawals', compact('withdrawals', 'totalWithdrawals', 'todayWithdrawals'));
    }

    /**
     * Approve a pending transaction
     */
    public function approve(SavingsTransaction $saving)
    {
        if ($saving->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending transactions can be approved.');
        }

        DB::beginTransaction();

        try {
            // Generate receipt number
            $receiptNumber = 'RCT-' . date('Ymd') . '-' . str_pad($saving->id, 6, '0', STR_PAD_LEFT);

            $saving->status = 'approved';
            $saving->receipt_number = $receiptNumber;
            $saving->save();

            DB::commit();

            return redirect()->route('admin.savings.show', $saving)
                ->with('success', 'Transaction approved successfully. Receipt #: ' . $receiptNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve transaction error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to approve transaction.');
        }
    }

    /**
     * Reject a pending transaction
     */
    public function reject(Request $request, SavingsTransaction $saving)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($saving->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending transactions can be rejected.');
        }

        $saving->status = 'rejected';
        $saving->notes = ($saving->notes ? $saving->notes . "\n" : '') . "Rejection Reason: " . $request->rejection_reason;
        $saving->save();

        return redirect()->route('admin.savings.index')
            ->with('success', 'Transaction rejected successfully.');
    }

    /**
     * Print receipt
     */
    public function printReceipt(SavingsTransaction $saving)
    {
        if ($saving->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Receipt is only available for approved transactions.');
        }

        return view('admin.savings.receipt', compact('saving'));
    }

    /**
     * Member savings summary (for AJAX)
     */
    public function memberSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);

        $deposits = SavingsTransaction::where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->where('transaction_type', 'deposit')
            ->sum('amount');

        $withdrawals = SavingsTransaction::where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');

        $balance = $deposits - $withdrawals;

        $monthlyStats = SavingsTransaction::where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw("SUM(CASE WHEN transaction_type = 'deposit' THEN amount ELSE 0 END) as deposits"),
                DB::raw("SUM(CASE WHEN transaction_type = 'withdrawal' THEN amount ELSE 0 END) as withdrawals")
            )
            ->whereYear('transaction_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'current_balance' => number_format($balance, 2),
            'total_deposits' => number_format($deposits, 2),
            'total_withdrawals' => number_format($withdrawals, 2),
            'transaction_count' => SavingsTransaction::where('user_id', $request->user_id)->count(),
            'monthly_stats' => $monthlyStats
        ]);
    }
}
