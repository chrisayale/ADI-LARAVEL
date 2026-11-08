<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavingsTransaction;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use Carbon\Carbon;

class CollectorController extends Controller
{
    /**
     * Display the payment collection page for the collector.
     */
    public function collect()
    {
        $collector = auth()->user();
        $saccoId = $collector->sacco_id;

        // Get pending loan repayments assigned to this collector's zone/members
        $pendingRepayments = LoanRepayment::whereHas('loanApplication', function ($query) use ($saccoId, $collector) {
            $query->where('sacco_id', $saccoId)
                  ->where('status', 'disbursed')
                  ->where(function ($q) use ($collector) {
                      // Filter by collector's zone or members they're responsible for
                      if ($collector->collection_zone) {
                          $q->whereHas('user', function ($mq) use ($collector) {
                              $mq->where('collection_zone', $collector->collection_zone);
                          });
                      }
                  });
        })
        ->where('status', 'pending')
        ->where('due_date', '<=', now())
        ->with(['loanApplication.user', 'loanApplication.loanProduct'])
        ->orderBy('due_date', 'asc')
        ->paginate(20);

        // Get today's collections
        $todayCollections = PaymentTransaction::where('recorded_by', $collector->id)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('amount');

        // Get this month's collections
        $monthlyCollections = PaymentTransaction::where('recorded_by', $collector->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('amount');

        return view('collector.collect', compact('pendingRepayments', 'todayCollections', 'monthlyCollections'));
    }

    /**
     * Process a loan repayment collection.
     */
    public function processCollection(Request $request)
    {
        $request->validate([
            'loan_repayment_id' => 'required|exists:loan_repayments,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,mpesa,airtel_money,bank_transfer,cheque',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $collector = auth()->user();
        $repayment = LoanRepayment::with('loanApplication')->findOrFail($request->loan_repayment_id);

        // Verify this repayment belongs to the collector's zone
        if ($collector->collection_zone && $repayment->loanApplication->user->collection_zone !== $collector->collection_zone) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à collecter ce paiement.');
        }

        // Check if already paid
        if ($repayment->status === 'paid') {
            return back()->with('error', 'Ce remboursement a déjà été payé.');
        }

        // Calculate amount due (principal + interest + penalties)
        $amountDue = $repayment->effective_amount_due - $repayment->amount_paid;
        $calculatedPenalty = $repayment->calculated_penalty;

        if ($request->amount > $amountDue + $calculatedPenalty) {
            return back()->with('error', 'Le montant ne peut pas dépasser le total dû (capital + intérêts + pénalités).');
        }

        // Create payment transaction
        $transaction = PaymentTransaction::create([
            'sacco_id' => $collector->sacco_id,
            'user_id' => $repayment->loanApplication->user_id,
            'loan_repayment_id' => $repayment->id,
            'amount' => $request->amount,
            'payment_type' => 'loan_repayment',
            'payment_method' => $request->payment_method,
            'reference_number' => $request->transaction_reference,
            'notes' => $request->notes,
            'recorded_by' => $collector->id,
            'status' => 'completed',
            'payment_date' => now(),
        ]);

        // Update loan repayment
        $repayment->amount_paid += $request->amount;
        $repayment->total_paid += $request->amount;
        $repayment->paid_date = now();
        $repayment->recorded_by = $collector->id;

        if ($repayment->amount_paid >= $repayment->effective_amount_due) {
            $repayment->status = 'paid';
        } elseif ($repayment->amount_paid > 0) {
            $repayment->status = 'partial';
        }

        $repayment->save();

        // Check if loan is completed
        $loan = $repayment->loanApplication;
        $allPaid = $loan->repayments()->where('status', '!=', 'paid')->count() === 0;
        if ($allPaid) {
            $loan->status = 'completed';
            $loan->save();
        }

        return back()->with('success', 'Paiement collecté avec succès. Reçu généré.');
    }

    /**
     * Display the savings deposit/withdrawal form.
     */
    public function savingsCreate()
    {
        $collector = auth()->user();
        $saccoId = $collector->sacco_id;

        // Get members in collector's zone
        $membersQuery = User::where('sacco_id', $saccoId)
            ->where('status', 'active')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'member');
            });

        if ($collector->collection_zone) {
            $membersQuery->where('collection_zone', $collector->collection_zone);
        }

        $members = $membersQuery->orderBy('name')->get();

        return view('collector.savings.create', compact('members'));
    }

    /**
     * Store a savings transaction (deposit/withdrawal).
     */
    public function storeSavings(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'transaction_type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,mpesa,airtel_money,bank_transfer,cheque',
            'status' => 'required|in:completed,pending',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $collector = auth()->user();
        $member = User::findOrFail($request->member_id);

        // Verify member belongs to collector's zone
        if ($collector->collection_zone && $member->collection_zone !== $collector->collection_zone) {
            return back()->with('error', 'Ce membre n\'est pas dans votre zone de collecte.');
        }

        // Get member's current savings balance
        $lastTransaction = SavingsTransaction::where('member_id', $member->id)
            ->where('sacco_id', $collector->sacco_id)
            ->latest('transaction_date')
            ->first();

        $currentBalance = $lastTransaction ? $lastTransaction->balance_after : 0;

        if ($request->transaction_type === 'withdrawal' && $request->amount > $currentBalance) {
            return back()->with('error', 'Solde insuffisant pour ce retrait.');
        }

        $newBalance = $request->transaction_type === 'deposit'
            ? $currentBalance + $request->amount
            : $currentBalance - $request->amount;

        // Use transaction_date from request or default to now
        $transactionDate = $request->filled('transaction_date') ? $request->transaction_date : now();

        // Create savings transaction
        SavingsTransaction::create([
            'sacco_id' => $collector->sacco_id,
            'member_id' => $member->id,
            'transaction_type' => $request->transaction_type,
            'amount' => $request->amount,
            'balance_after' => $newBalance,
            'payment_method' => $request->payment_method,
            'transaction_reference' => $request->transaction_reference,
            'notes' => $request->notes,
            'recorded_by' => $collector->id,
            'status' => $request->status,
            'transaction_date' => $transactionDate,
        ]);

        return back()->with('success', 'Transaction d\'épargne enregistrée avec succès.');
    }

    /**
     * Get member details for AJAX lookup (savings balance, etc.)
     */
    public function getMemberDetails(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
        ]);

        $collector = auth()->user();
        $member = User::findOrFail($request->member_id);

        // Verify member belongs to collector's zone
        if ($collector->collection_zone && $member->collection_zone !== $collector->collection_zone) {
            return response()->json([
                'success' => false,
                'message' => 'Ce membre n\'est pas dans votre zone de collecte.'
            ], 403);
        }

        // Get member's current savings balance
        $lastTransaction = SavingsTransaction::where('member_id', $member->id)
            ->where('sacco_id', $collector->sacco_id)
            ->latest('transaction_date')
            ->first();

        $currentBalance = $lastTransaction ? $lastTransaction->balance_after : 0;

        // Get pending loan repayments count
        $pendingRepayments = LoanRepayment::whereHas('loanApplication', function ($query) use ($member) {
            $query->where('user_id', $member->id)
                  ->where('status', 'disbursed');
        })
        ->where('status', 'pending')
        ->where('due_date', '<=', now())
        ->count();

        // Get total loan balance (sum of pending repayments) - use accessors
        $unpaidRepayments = LoanRepayment::whereHas('loanApplication', function ($query) use ($member) {
            $query->where('user_id', $member->id)
                  ->where('status', 'disbursed');
        })
        ->where('status', '!=', 'paid')
        ->with('loanApplication')
        ->get();

        $totalLoanBalance = $unpaidRepayments->sum(function ($repayment) {
            return $repayment->effective_amount_due - $repayment->amount_paid + $repayment->calculated_penalty;
        });

        // Get recent savings transactions
        $recentTransactions = SavingsTransaction::where('member_id', $member->id)
            ->where('sacco_id', $collector->sacco_id)
            ->latest('transaction_date')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'date' => $transaction->transaction_date->format('d M Y'),
                    'type' => $transaction->transaction_type,
                    'amount' => $transaction->amount,
                    'balance' => $transaction->balance_after,
                ];
            });

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'membership_number' => $member->membership_number,
                'email' => $member->email,
                'phone_number' => $member->phone_number,
                'collection_zone' => $member->collection_zone,
                'savings_balance' => $currentBalance,
                'pending_repayments_count' => $pendingRepayments,
                'total_loan_balance' => $totalLoanBalance,
                'recent_transactions' => $recentTransactions,
            ]
        ]);
    }

    /**
     * Display collector's transactions.
     */
    public function transactionsIndex(Request $request)
    {
        $collector = auth()->user();

        // Loan repayment collections
        $loanTransactions = PaymentTransaction::where('recorded_by', $collector->id)
            ->where('sacco_id', $collector->sacco_id)
            ->where('payment_type', 'loan_repayment')
            ->with(['loanRepayment.loanApplication.user', 'user'])
            ->latest('created_at');

        // Savings transactions
        $savingsTransactions = SavingsTransaction::where('recorded_by', $collector->id)
            ->where('sacco_id', $collector->sacco_id)
            ->with('member')
            ->latest('transaction_date');

        // Apply filters
        if ($request->filled('date_from')) {
            $loanTransactions->whereDate('created_at', '>=', $request->date_from);
            $savingsTransactions->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $loanTransactions->whereDate('created_at', '<=', $request->date_to);
            $savingsTransactions->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('type')) {
            if ($request->type === 'loan') {
                $savingsTransactions = $savingsTransactions->whereRaw('1=0'); // Empty result
            } elseif ($request->type === 'savings') {
                $loanTransactions = $loanTransactions->whereRaw('1=0'); // Empty result
            }
        }

        $loanTransactions = $loanTransactions->paginate(15, ['*'], 'loan_page');
        $savingsTransactions = $savingsTransactions->paginate(15, ['*'], 'savings_page');

        // Summary stats
        $totalCollected = PaymentTransaction::where('recorded_by', $collector->id)
            ->where('sacco_id', $collector->sacco_id)
            ->where('payment_type', 'loan_repayment')
            ->where('status', 'completed')
            ->sum('amount');

        $totalDeposits = SavingsTransaction::where('recorded_by', $collector->id)
            ->where('sacco_id', $collector->sacco_id)
            ->where('transaction_type', 'deposit')
            ->where('status', 'completed')
            ->sum('amount');

        $totalWithdrawals = SavingsTransaction::where('recorded_by', $collector->id)
            ->where('sacco_id', $collector->sacco_id)
            ->where('transaction_type', 'withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        return view('collector.transactions.index', compact(
            'loanTransactions',
            'savingsTransactions',
            'totalCollected',
            'totalDeposits',
            'totalWithdrawals'
        ));
    }
}