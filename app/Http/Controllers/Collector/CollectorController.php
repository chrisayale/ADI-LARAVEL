<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavingsTransaction;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use App\Models\Zone;
use App\Services\LoanPenaltyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CollectorController extends Controller
{
    /**
     * Get the zone IDs for the authenticated collector.
     */
    protected function getCollectorZoneIds(): array
    {
        $collector = auth()->user();
        return $collector->zones()->pluck('zones.id')->toArray();
    }

    /**
     * Display the payment collection page for the collector.
     * Supports member search and QR code lookup like the admin/cashier interface.
     */
    public function collect(Request $request)
    {
        $collector = auth()->user();
        $saccoId = $collector->sacco_id;
        $zoneIds = $this->getCollectorZoneIds();

        // Load SACCO with currency info
        $sacco = $collector->sacco;

        $search = $request->get('search');
        $member = null;
        $loans = collect();
        $pendingRepayments = collect();

        if ($search) {
            // Search by membership number, phone_number, or name within collector's zones
            $query = User::role('member')
                ->where('sacco_id', $saccoId)
                ->where('status', 'active')
                ->where(function($q) use ($search) {
                    $q->where('membership_number', $search)
                        ->orWhere('phone_number', $search)
                        ->orWhere('name', 'LIKE', "%{$search}%");
                });

            // Filter by collector's assigned zones
            if (!empty($zoneIds)) {
                $query->whereIn('zone_id', $zoneIds);
            }

            $member = $query->first();

            if ($member) {
                // Get active loans for this member
                $loans = LoanApplication::with(['repayments' => function($q) {
                        $q->whereIn('status', ['pending', 'partial', 'overdue'])
                            ->orderBy('due_date');
                    }])
                    ->where('user_id', $member->id)
                    ->whereIn('status', ['disbursed', 'approved'])
                    ->get();

                // Collect all pending/partial/overdue repayments from these loans
                $pendingRepayments = $loans->flatMap(function($loan) {
                    return $loan->repayments;
                })->filter(function($repayment) {
                    return in_array($repayment->status, ['pending', 'partial', 'overdue'])
                        && $repayment->due_date <= now()
                        && ($repayment->effective_amount_due - $repayment->amount_paid) > 0;
                })->sortBy('due_date')->values();
            }
        }

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

        return view('collector.collect', compact(
            'pendingRepayments',
            'todayCollections',
            'monthlyCollections',
            'sacco',
            'search',
            'member',
            'loans'
        ));
    }

    /**
     * Find member by QR code / membership number for auto-selection
     */
    public function findMemberByQrCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Code QR invalide'], 422);
        }

        $collector = auth()->user();
        $qrCode = trim($request->qr_code);
        $zoneIds = $this->getCollectorZoneIds();

        // Try to find member by membership_number (which is what QR code contains)
        $query = User::role('member')
            ->where('sacco_id', $collector->sacco_id)
            ->where('status', 'active')
            ->where('membership_number', $qrCode);

        // Filter by collector's assigned zones
        if (!empty($zoneIds)) {
            $query->whereIn('zone_id', $zoneIds);
        }

        $member = $query->first();

        if (!$member) {
            // Also try to extract membership number from formats like "#MEM-2024-001"
            if (preg_match('/MEM-?(\d+)-?(\d+)/i', $qrCode, $matches)) {
                $membershipNumber = strtoupper($matches[0]);
                $query = User::role('member')
                    ->where('sacco_id', $collector->sacco_id)
                    ->where('status', 'active')
                    ->where('membership_number', $membershipNumber);

                if (!empty($zoneIds)) {
                    $query->whereIn('zone_id', $zoneIds);
                }

                $member = $query->first();
            }
        }

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun membre trouvé avec ce code QR : ' . $qrCode
            ], 404);
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'membership_number' => $member->membership_number,
                'email' => $member->email,
                'phone_number' => $member->phone_number,
            ]
        ]);
    }

    /**
     * Process a loan repayment collection.
     * Supports multiple repayment_ids like the admin/cashier interface.
     */
    public function processCollection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'repayment_ids' => 'required|array|min:1',
            'repayment_ids.*' => 'exists:loan_repayments,id',
            'amount' => 'required|numeric|min:0.001',
            'payment_method' => 'required|in:cash,mpesa,airtel_money,bank_transfer,cheque',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $collector = auth()->user();
        $zoneIds = $this->getCollectorZoneIds();

        try {
            DB::beginTransaction();

            // Get the repayments being paid
            $repayments = LoanRepayment::whereIn('id', $request->repayment_ids)
                ->orderBy('due_date')
                ->get();

            if ($repayments->isEmpty()) {
                throw new \Exception('Aucun remboursement valide sélectionné');
            }

            $loan = $repayments->first()->loanApplication;

            // Verify this loan belongs to the collector's zones
            if (!empty($zoneIds) && !$zoneIds->contains($loan->user->zone_id)) {
                throw new \Exception('Vous n\'êtes pas autorisé à collecter ce paiement pour un membre en dehors de vos zones assignées.');
            }

            // Sync penalties for all overdue repayments before processing
            LoanPenaltyService::syncPenaltiesForLoan($loan);

            // Reload repayments to get updated penalty amounts
            $repayments = $loan->repayments()->whereIn('id', $request->repayment_ids)
                ->orderBy('due_date')
                ->get();

            $remainingAmount = $request->amount;

            // Generate receipt number
            $receiptNumber = 'RCT-' . date('Ymd') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT);

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'transaction_number' => 'TXN-' . date('Y') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'user_id' => $request->user_id,
                'loan_repayment_id' => $repayments->first()->id, // Link to first repayment
                'sacco_id' => $collector->sacco_id,
                'amount' => $request->amount,
                'payment_type' => 'loan_repayment',
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'payment_date' => now(),
                'receipt_number' => $receiptNumber,
                'notes' => $request->notes,
                'recorded_by' => $collector->id,
                'status' => 'completed'
            ]);

            // Distribute payment across selected repayments (oldest first)
            // IMPORTANT: Payment goes ONLY to principal/interest (effective_amount_due)
            // Penalty is tracked separately and NOT automatically deducted from payment
            foreach ($repayments as $repayment) {
                if ($remainingAmount <= 0) break;

                // Ensure penalty is calculated for overdue repayments
                if ($repayment->is_overdue && $repayment->penalty_amount == 0) {
                    LoanPenaltyService::applyPenalty($repayment);
                    $repayment->refresh();
                }

                // Principal/interest balance only (EXCLUDING penalty)
                $principalBalance = $repayment->effective_amount_due - $repayment->amount_paid;
                $paymentToApply = min($remainingAmount, $principalBalance);

                if ($paymentToApply > 0) {
                    // Update repayment - payment goes to principal only
                    $repayment->amount_paid += $paymentToApply;
                    $repayment->total_paid += $paymentToApply;

                    // Update status based on PRINCIPAL only (penalty is separate)
                    if ($repayment->amount_paid >= $repayment->effective_amount_due) {
                        $repayment->status = 'paid';
                        $repayment->paid_date = now();
                    } elseif ($repayment->amount_paid > 0) {
                        $repayment->status = 'partial';
                    }

                    $repayment->recorded_by = $collector->id;
                    $repayment->save();

                    $remainingAmount -= $paymentToApply;
                }
            }

            // Check if loan is fully paid (principal only - penalties are separate)
            $totalPrincipalDue = $loan->repayments->sum('effective_amount_due');
            $totalPrincipalPaid = $loan->repayments->sum('amount_paid');

            if ($totalPrincipalPaid >= $totalPrincipalDue) {
                $loan->status = 'completed';
                $loan->save();
            }

            DB::commit();

            return redirect()->route('collector.payments.receipt', $transaction)
                ->with('success', 'Paiement collecté avec succès. Reçu généré : ' . $receiptNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors du traitement du paiement : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the savings deposit/withdrawal form.
     */
    public function savingsCreate()
    {
        $collector = auth()->user();
        $saccoId = $collector->sacco_id;
        $sacco = $collector->sacco;
        $zoneIds = $this->getCollectorZoneIds();

        // Get members in collector's zones
        $membersQuery = User::where('sacco_id', $saccoId)
            ->where('status', 'active')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'member');
            });

        // Filter by collector's assigned zones
        if (!empty($zoneIds)) {
            $membersQuery->whereIn('zone_id', $zoneIds);
        }

        $members = $membersQuery->orderBy('name')->get();

        return view('collector.savings.create', compact('members', 'sacco'));
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
        $zoneIds = $this->getCollectorZoneIds();

        // Verify member belongs to collector's zones
        if (!empty($zoneIds) && !$zoneIds->contains($member->zone_id)) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer une opération pour un membre en dehors de vos zones assignées.');
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
        $zoneIds = $this->getCollectorZoneIds();

        // Verify member belongs to collector's zones
        if (!empty($zoneIds) && !$zoneIds->contains($member->zone_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ce membre n\'est pas dans vos zones de collecte assignées.'
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
                'collection_zone' => $member->zone?->name ?? $member->collection_zone,
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
            'sacco',
            'loanTransactions',
            'savingsTransactions',
            'totalCollected',
            'totalDeposits',
            'totalWithdrawals'
        ));
    }

    /**
     * Print receipt for a payment transaction
     */
    public function printReceipt(PaymentTransaction $transaction)
    {
        $collector = auth()->user();

        // Verify this transaction belongs to the collector
        if ($transaction->recorded_by !== $collector->id) {
            abort(403, 'Accès non autorisé à ce reçu.');
        }

        $transaction->load(['user', 'loanRepayment.loanApplication', 'recorder']);
        $sacco = $collector->sacco;

        return view('collector.payments.receipt', compact('transaction', 'sacco'));
    }
}