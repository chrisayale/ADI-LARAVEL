<?php
// app/Http/Controllers/Treasurer/RepaymentController.php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RepaymentController extends Controller
{
    /**
     * Display all repayments recorded by this treasurer
     */
    public function index(Request $request)
    {
        $treasurerId = auth()->id();

        $query = PaymentTransaction::with(['user', 'loanRepayment.loanApplication'])
            ->where('recorded_by', $treasurerId)
            ->where('payment_type', 'loan_repayment');

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Filter by member
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->orderBy('payment_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Summary stats
        $totalCollected = PaymentTransaction::where('recorded_by', $treasurerId)
            ->where('payment_type', 'loan_repayment')
            ->whereDate('payment_date', today())
            ->sum('amount');

        $todayCount = PaymentTransaction::where('recorded_by', $treasurerId)
            ->where('payment_type', 'loan_repayment')
            ->whereDate('payment_date', today())
            ->count();

        $members = User::role('member')->orderBy('name')->get(['id', 'name', 'membership_number']);

        return view('treasurer.repayments.index', compact('transactions', 'totalCollected', 'todayCount', 'members'));
    }

    /**
     * Show payment collection form
     */
    public function collect(Request $request)
    {
        $search = $request->get('search');
        $loan = null;
        $member = null;

        if ($search) {
            // Search by membership number, phone_number, or name
            $member = User::role('member')
                ->where(function($query) use ($search) {
                    $query->where('membership_number', $search)
                        ->orWhere('phone_number', $search)
                        ->orWhere('name', 'LIKE', "%{$search}%");
                })
                ->first();

            if ($member) {
                // Get active loans for this member assigned to this treasurer
                $loans = LoanApplication::with(['repayments' => function($query) {
                        $query->whereIn('status', ['pending', 'partial', 'overdue'])
                            ->orderBy('due_date');
                    }])
                    ->where('user_id', $member->id)
                    ->where('treasurer_id', auth()->id()) // Only loans assigned to this treasurer
                    ->whereIn('status', ['disbursed', 'approved'])
                    ->get();

                return view('treasurer.repayments.collect', compact('member', 'loans', 'search'));
            }
        }

        return view('treasurer.repayments.collect', compact('search'));
    }

    /**
     * Get loan repayment details via AJAX
     */
    public function getLoanRepayments(Request $request)
    {
        $loan = LoanApplication::with(['repayments' => function($query) {
                $query->whereIn('status', ['pending', 'partial', 'overdue'])
                    ->orderBy('due_date');
            }])
            ->where('treasurer_id', auth()->id()) // Ensure loan is assigned to this treasurer
            ->find($request->loan_id);

        if (!$loan) {
            return response()->json(['error' => 'Loan not found or not assigned to you'], 404);
        }

        $totalDue = $loan->repayments->sum('amount_due') - $loan->repayments->sum('amount_paid');
        $overdueCount = $loan->repayments->filter(function($repayment) {
            return $repayment->due_date < now() && $repayment->status != 'paid';
        })->count();

        return response()->json([
            'loan' => $loan,
            'repayments' => $loan->repayments,
            'total_due' => $totalDue,
            'overdue_count' => $overdueCount
        ]);
    }

    /**
     * Process payment
     */
    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'loan_id' => 'required|exists:loan_applications,id',
            'repayment_ids' => 'required|array|min:1',
            'repayment_ids.*' => 'exists:loan_repayments,id',
            'amount' => 'required|numeric|min:0.001',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Verify the loan is assigned to this treasurer
            $loan = LoanApplication::where('id', $request->loan_id)
                ->where('treasurer_id', auth()->id())
                ->firstOrFail();

            // Get the repayments being paid
            $repayments = LoanRepayment::whereIn('id', $request->repayment_ids)
                ->where('loan_application_id', $loan->id)
                ->orderBy('due_date')
                ->get();

            if ($repayments->isEmpty()) {
                throw new \Exception('No valid repayments selected');
            }

            $remainingAmount = $request->amount;

            // Generate receipt number
            $receiptNumber = 'RCT-' . date('Ymd') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT);

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'transaction_number' => 'TXN-' . date('Y') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'user_id' => $request->user_id,
                'loan_repayment_id' => $repayments->first()->id,
                'amount' => $request->amount,
                'payment_type' => 'loan_repayment',
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'payment_date' => now(),
                'receipt_number' => $receiptNumber,
                'notes' => $request->notes,
                'recorded_by' => auth()->id(),
                'status' => 'completed'
            ]);

            // Distribute payment across selected repayments
            foreach ($repayments as $repayment) {
                if ($remainingAmount <= 0) break;

                $balance = $repayment->amount_due - $repayment->amount_paid;
                $paymentToApply = min($remainingAmount, $balance);

                if ($paymentToApply > 0) {
                    $repayment->amount_paid += $paymentToApply;
                    $repayment->total_paid += $paymentToApply;

                    if ($repayment->amount_paid >= $repayment->amount_due) {
                        $repayment->status = 'paid';
                        $repayment->paid_date = now();
                    } elseif ($repayment->amount_paid > 0) {
                        $repayment->status = 'partial';
                    }

                    $repayment->save();
                    $remainingAmount -= $paymentToApply;
                }
            }

            // Check if loan is fully paid
            $totalDue = $loan->repayments->sum('amount_due');
            $totalPaid = $loan->repayments->sum('amount_paid');

            if ($totalPaid >= $totalDue) {
                $loan->status = 'completed';
                $loan->save();
            }

            DB::commit();

            return redirect()->route('treasurer.repayments.receipt', $transaction)
                ->with('success', 'Payment processed successfully. Receipt #: ' . $receiptNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Repayment processing error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error processing payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show loans assigned to this treasurer
     */
    public function assignedLoans(Request $request)
{
    $treasurerId = auth()->id();

    $query = LoanApplication::with(['user'])
        ->where('treasurer_id', $treasurerId)
        ->whereIn('status', ['disbursed', 'approved']);

    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('membership_number', 'like', "%{$search}%");
        });
    }

    $loans = $query->latest()->paginate(15);

    // Debug: Log the loans
    Log::info('Assigned loans for treasurer ' . $treasurerId . ': ' . $loans->count() . ' loans found');
    foreach($loans as $loan) {
        Log::info('Loan ID: ' . $loan->id . ', Status: ' . $loan->status . ', Treasurer ID: ' . $loan->treasurer_id);
    }

    return view('treasurer.repayments.assigned', compact('loans'));
}
    /**
     * Show loan repayment schedule
     */
    /**
 * Show loan repayment schedule
 */
public function loanRepayments(LoanApplication $loan)
{
    $treasurerId = auth()->id();

    // Check if user has access to this loan
    $hasAccess = false;

    // Case 1: Loan is currently assigned to this treasurer
    if ($loan->treasurer_id == $treasurerId) {
        $hasAccess = true;
    }

    // Case 2: This treasurer verified the loan
    if ($loan->verified_by == $treasurerId) {
        $hasAccess = true;
    }

    // Case 3: This treasurer has recorded payments for this loan
    $hasRecordedPayments = PaymentTransaction::where('recorded_by', $treasurerId)
        ->whereHas('loanRepayment', function($query) use ($loan) {
            $query->where('loan_application_id', $loan->id);
        })
        ->exists();

    if ($hasRecordedPayments) {
        $hasAccess = true;
    }

    // If no access, deny
    if (!$hasAccess) {
        \Log::warning('Unauthorized access attempt to loan ' . $loan->id . ' by user ' . $treasurerId);
        abort(403, 'You do not have permission to view this loan.');
    }

    // Load the loan with relationships
    $loan->load([
        'user',
        'repayments' => function($query) {
            $query->orderBy('installment_number');
        }
    ]);

    // Calculate summary
    $totalPaid = $loan->repayments->sum('amount_paid');
    $totalDue = $loan->repayments->sum('amount_due');
    $nextPayment = $loan->repayments()
        ->whereIn('status', ['pending', 'partial'])
        ->orderBy('due_date')
        ->first();

    return view('treasurer.repayments.loan-repayments', compact('loan', 'totalPaid', 'totalDue', 'nextPayment'));
}
    /**
     * Print receipt
     */
    public function printReceipt(PaymentTransaction $transaction)
    {
        // Ensure this transaction was recorded by the current treasurer
        if ($transaction->recorded_by != auth()->id()) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        $transaction->load(['user', 'loanRepayment.loanApplication', 'recorder']);

        return view('treasurer.repayments.receipt', compact('transaction'));
    }

    /**
     * Get member loan summary (AJAX)
     */
    public function getMemberSummary(User $member)
    {
        $treasurerId = auth()->id();

        $activeLoans = LoanApplication::with(['repayments' => function($query) {
                $query->whereIn('status', ['pending', 'partial', 'overdue']);
            }])
            ->where('user_id', $member->id)
            ->where('treasurer_id', $treasurerId)
            ->whereIn('status', ['disbursed'])
            ->get();

        $totalDue = 0;
        $overdueAmount = 0;
        $nextPayment = null;

        foreach ($activeLoans as $loan) {
            foreach ($loan->repayments as $repayment) {
                $balance = $repayment->amount_due - $repayment->amount_paid;
                $totalDue += $balance;

                if ($repayment->due_date < now()) {
                    $overdueAmount += $balance;
                }

                if (!$nextPayment || ($repayment->due_date < $nextPayment->due_date && $repayment->status != 'paid')) {
                    $nextPayment = $repayment;
                }
            }
        }

        return response()->json([
            'active_loans_count' => $activeLoans->count(),
            'total_due' => $totalDue,
            'overdue_amount' => $overdueAmount,
            'next_payment' => $nextPayment ? [
                'due_date' => $nextPayment->due_date->format('Y-m-d'),
                'amount' => $nextPayment->amount_due - $nextPayment->amount_paid,
                'installment' => $nextPayment->installment_number
            ] : null
        ]);
    }
}
