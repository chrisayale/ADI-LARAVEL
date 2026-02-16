<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'loan_repayment_id',
        'amount',
        'payment_type',
        'payment_method',
        'reference_number',
        'payment_date',
        'receipt_number',
        'notes',
        'recorded_by',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanRepayment()
    {
        return $this->belongsTo(LoanRepayment::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }
}
