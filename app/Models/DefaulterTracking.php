<?php
// app/Models/DefaulterTracking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaulterTracking extends Model
{
    use HasFactory;

    /**
     * Specify the table name
     */
    protected $table = 'defaulters_tracking';

    protected $fillable = [
        'loan_application_id',
        'user_id',
        'amount_overdue',
        'days_overdue',
        'status',
        'assigned_to',
        'follow_up_notes',
        'last_follow_up',
        'next_follow_up'
    ];

    protected $casts = [
        'last_follow_up' => 'date',
        'next_follow_up' => 'date',
        'amount_overdue' => 'decimal:2'
    ];

    /**
     * Get the loan application that this defaulter tracking belongs to
     */
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    /**
     * Get the user (member) who is in default
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the treasurer assigned to follow up
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope a query to only include active defaulters
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['new', 'follow_up_1', 'follow_up_2', 'final_notice']);
    }

    /**
     * Scope a query to only include legal cases
     */
    public function scopeLegal($query)
    {
        return $query->where('status', 'legal_action');
    }
}
