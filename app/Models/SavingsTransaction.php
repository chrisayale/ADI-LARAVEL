<?php
// app/Models/SavingsTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingsTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'amount',
        'transaction_type', // Changed from 'type' to 'transaction_type'
        'transaction_date', // ADD THIS - it was missing
        'receipt_number',
        'notes',
        'recorded_by',
        'status',
        'balance_after' // Changed from 'balance_before' and 'balance_after' to just 'balance_after'
        ,
        'sacco_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'transaction_date' => 'date',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeDeposits($query)
    {
        return $query->where('transaction_type', 'deposit'); // Changed from 'type'
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('transaction_type', 'withdrawal'); // Changed from 'type'
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query) // Changed from 'completed' to match your enum
    {
        return $query->where('status', 'approved');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'CDF ' . number_format($this->amount, 2);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'success', // Changed from 'completed'
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    public function getTypeColorAttribute()
    {
        return $this->transaction_type === 'deposit' ? 'success' : 'info'; // Changed from 'type'
    }

    public function getTypeIconAttribute()
    {
        return $this->transaction_type === 'deposit' ? 'arrow-down' : 'arrow-up'; // Changed from 'type'
    }

    // Methods
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Generate transaction number if not set
            if (empty($transaction->transaction_number)) {
                $nextId = static::withTrashed()->max('id') + 1;
                $transaction->transaction_number = 'TXN-' . date('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
