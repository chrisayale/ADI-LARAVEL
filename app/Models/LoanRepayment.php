<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'repayment_number',
        'loan_application_id',
        'sacco_id',
        'installment_number',
        'amount_due',
        'adjusted_amount',
        'difference_amount',
        'difference_type',
        'amount_paid',
        'penalty_amount',
        'total_paid',
        'due_date',
        'paid_date',
        'receipt_number',
        'recorded_by',
        'status',
        'notes'
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'adjusted_amount' => 'decimal:2',
        'difference_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date'
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function transaction()
    {
        return $this->hasOne(PaymentTransaction::class, 'loan_repayment_id');
    }

    public function getBalanceAttribute()
    {
        return $this->amount_due - $this->amount_paid;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->amount_due == 0) return 0;
        return round(($this->amount_paid / $this->amount_due) * 100, 2);
    }

    // Accessors for adjustment tracking
    public function getHasAdjustmentAttribute()
    {
        return $this->adjusted_amount !== null && $this->adjusted_amount != $this->amount_due;
    }

    public function getEffectiveAmountDueAttribute()
    {
        // Return adjusted_amount if set, otherwise amount_due
        return $this->adjusted_amount ?? $this->amount_due;
    }

    public function getSurplusBalanceAttribute()
    {
        // Cumulative surplus across all repayments for this loan
        if (!$this->loanApplication) return $this->difference_amount;
        return $this->loanApplication->repayments->sum('difference_amount');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', ['pending', 'partial'])
                     ->where('due_date', '<', now());
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
