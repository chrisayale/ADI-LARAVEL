<?php
// app/Http/Controllers/Admin/PaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Show payment collection form
     */
   /**
 * Show payment collection form
 */
public function collect(Request $request)
{
    $search = $request->get('search');
    $loan = null;
    $repayments = collect();
    $member = null;

    if ($search) {
        // Search by membership number, phone_number, or name
        $member = User::role('member')
            ->where(function($query) use ($search) {
                $query->where('membership_number', $search)
                    ->orWhere('phone_number', $search)  // Changed from 'phone' to 'phone_number'
                    ->orWhere('name', 'LIKE', "%{$search}%");
            })
            ->first();

        if ($member) {
            // Get active loans for this member
            $loans = LoanApplication::with(['repayments' => function($query) {
                    $query->whereIn('status', ['pending', 'partial', 'overdue'])
                        ->orderBy('due_date');
                }])
                ->where('user_id', $member->id)
                ->whereIn('status', ['disbursed', 'approved']) // Added 'approved' as possible status
                ->get();

            return view('admin.payments.collect', compact('member', 'loans', 'search'));
        }
    }

    return view('admin.payments.collect', compact('search'));
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
            ->find($request->loan_id);

        if (!$loan) {
            return response()->json(['error' => 'Loan not found'], 404);
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

            // Get the repayments being paid
            $repayments = LoanRepayment::whereIn('id', $request->repayment_ids)
                ->orderBy('due_date')
                ->get();

            if ($repayments->isEmpty()) {
                throw new \Exception('No valid repayments selected');
            }

            $loan = $repayments->first()->loanApplication;
            $remainingAmount = $request->amount;

            // Generate receipt number
            $receiptNumber = 'RCT-' . date('Ymd') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT);

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'transaction_number' => 'TXN-' . date('Y') . '-' . str_pad(PaymentTransaction::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'user_id' => $request->user_id,
                'loan_repayment_id' => $repayments->first()->id, // Link to first repayment
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

            // Distribute payment across selected repayments (oldest first)
            foreach ($repayments as $repayment) {
                if ($remainingAmount <= 0) break;

                $balance = $repayment->amount_due - $repayment->amount_paid;
                $paymentToApply = min($remainingAmount, $balance);

                if ($paymentToApply > 0) {
                    // Update repayment
                    $repayment->amount_paid += $paymentToApply;
                    $repayment->total_paid += $paymentToApply;

                    // Update status
                    if ($repayment->amount_paid >= $repayment->amount_due) {
                        $repayment->status = 'paid';
                        $repayment->paid_date = now();
                    } elseif ($repayment->amount_paid > 0) {
                        $repayment->status = 'partial';
                    }

                    $repayment->save();

                    $remainingAmount -= $paymentToApply;

                    // Create transaction record for this repayment (optional - you might want a pivot table)
                    // For now, we'll just update the main transaction's notes
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

            return redirect()->route('admin.payments.receipt', $transaction)
                ->with('success', 'Payment processed successfully. Receipt #: ' . $receiptNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show payment history
     */
    public function history(Request $request)
    {
        $query = PaymentTransaction::with(['user', 'loanRepayment.loanApplication', 'recorder'])
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
        $totalCollected = PaymentTransaction::where('payment_type', 'loan_repayment')
            ->whereDate('payment_date', today())
            ->sum('amount');

        $members = User::role('member')->orderBy('name')->get();

        return view('admin.payments.history', compact('transactions', 'totalCollected', 'members'));
    }

    /**
     * Print receipt
     */
    public function printReceipt(PaymentTransaction $transaction)
    {
        $transaction->load(['user', 'loanRepayment.loanApplication', 'recorder']);

        return view('admin.payments.receipt', compact('transaction'));
    }

    /**
     * Get member loan summary
     */
    public function getMemberSummary(User $member)
    {
        $activeLoans = LoanApplication::with(['repayments' => function($query) {
                $query->whereIn('status', ['pending', 'partial', 'overdue']);
            }])
            ->where('user_id', $member->id)
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
