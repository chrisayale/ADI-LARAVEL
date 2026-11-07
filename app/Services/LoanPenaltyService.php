<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use Carbon\Carbon;

class LoanPenaltyService
{
    /**
     * Calculate the late penalty for an overdue repayment.
     *
     * @param LoanRepayment $repayment
     * @param Carbon|null $paymentDate
     * @return float
     */
    public static function calculatePenalty(LoanRepayment $repayment, $paymentDate = null): float
    {
        $loan = $repayment->loanApplication;

        // Check if loan has late penalty enabled
        if (!$loan->apply_late_penalty) {
            return 0;
        }

        // Check if repayment is overdue
        if (!$repayment->is_overdue) {
            return 0;
        }

        $paymentDate = $paymentDate ?? now();
        $daysOverdue = $paymentDate->diffInDays($repayment->due_date);

        // Get penalty amount from loan (fixed amount per period)
        $penaltyAmount = $loan->late_penalty_amount ?? 0;

        if ($penaltyAmount <= 0) {
            return 0;
        }

        // Calculate penalty based on days overdue
        // Default: penalty is applied per period (month/week/day) overdue
        $penalty = 0;

        switch ($loan->repayment_frequency) {
            case 'daily':
                // Penalty per day overdue
                $penalty = $penaltyAmount * $daysOverdue;
                break;
            case 'weekly':
                // Penalty per week overdue (rounded up)
                $weeksOverdue = ceil($daysOverdue / 7);
                $penalty = $penaltyAmount * $weeksOverdue;
                break;
            case 'monthly':
            default:
                // Penalty per month overdue (rounded up)
                $monthsOverdue = ceil($daysOverdue / 30);
                $penalty = $penaltyAmount * $monthsOverdue;
                break;
        }

        return round($penalty, 2);
    }

    /**
     * Check if a repayment has unpaid penalty.
     *
     * @param LoanRepayment $repayment
     * @return bool
     */
    public static function hasUnpaidPenalty(LoanRepayment $repayment): bool
    {
        return $repayment->penalty_amount > 0 && $repayment->amount_paid < ($repayment->effective_amount_due + $repayment->penalty_amount);
    }

    /**
     * Get total outstanding including penalties for a loan.
     *
     * @param LoanApplication $loan
     * @return float
     */
    public static function getTotalOutstandingWithPenalties(LoanApplication $loan): float
    {
        $total = 0;

        foreach ($loan->repayments as $repayment) {
            if (in_array($repayment->status, ['pending', 'partial'])) {
                $balance = $repayment->effective_amount_due - $repayment->amount_paid;
                $penalty = $repayment->is_overdue ? self::calculatePenalty($repayment) : 0;
                $total += $balance + $penalty;
            }
        }

        return round($total, 2);
    }

    /**
     * Calculate penalty for partial payment scenario.
     * When a partial payment is made on an overdue installment,
     * the penalty must be calculated and added to the balance.
     *
     * @param LoanRepayment $repayment
     * @param float $paymentAmount
     * @param Carbon|null $paymentDate
     * @return array ['penalty' => float, 'remaining_balance' => float, 'status' => string]
     */
    public static function calculatePartialPaymentWithPenalty(LoanRepayment $repayment, float $paymentAmount, $paymentDate = null): array
    {
        $paymentDate = $paymentDate ?? now();

        // Calculate applicable penalty
        $penalty = self::calculatePenalty($repayment, $paymentDate);

        // Total amount due including penalty
        $totalDue = $repayment->effective_amount_due + $penalty;

        // Balance before this payment
        $currentBalance = $totalDue - $repayment->amount_paid;

        // Apply payment
        $paymentToApply = min($paymentAmount, $currentBalance);
        $newAmountPaid = $repayment->amount_paid + $paymentToApply;
        $remainingBalance = $totalDue - $newAmountPaid;

        // Determine new status
        $status = 'pending';
        if ($newAmountPaid >= $totalDue) {
            $status = 'paid';
        } elseif ($newAmountPaid > 0) {
            $status = 'partial';
        }

        return [
            'penalty' => $penalty,
            'total_due' => $totalDue,
            'payment_applied' => $paymentToApply,
            'new_amount_paid' => $newAmountPaid,
            'remaining_balance' => max(0, $remainingBalance),
            'status' => $status
        ];
    }

    /**
     * Apply penalty to a repayment and update its penalty_amount.
     *
     * @param LoanRepayment $repayment
     * @param Carbon|null $paymentDate
     * @return float The penalty amount applied
     */
    public static function applyPenalty(LoanRepayment $repayment, $paymentDate = null): float
    {
        $penalty = self::calculatePenalty($repayment, $paymentDate);

        if ($penalty > 0) {
            $repayment->penalty_amount = $penalty;
            $repayment->save();
        }

        return $penalty;
    }

    /**
     * Recalculate and sync penalties for all overdue repayments of a loan.
     * Called when viewing loan details or before payment processing.
     *
     * @param LoanApplication $loan
     * @param Carbon|null $asOfDate
     * @return int Number of repayments updated
     */
    public static function syncPenaltiesForLoan(LoanApplication $loan, $asOfDate = null): int
    {
        $asOfDate = $asOfDate ?? now();
        $updated = 0;

        $overdueRepayments = $loan->repayments()
            ->whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', $asOfDate)
            ->get();

        foreach ($overdueRepayments as $repayment) {
            $newPenalty = self::calculatePenalty($repayment, $asOfDate);
            if ($repayment->penalty_amount != $newPenalty) {
                $repayment->penalty_amount = $newPenalty;
                $repayment->save();
                $updated++;
            }
        }

        return $updated;
    }
}