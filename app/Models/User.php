<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'national_id',
        'membership_number',
        'membership_date',
        'status',
        'next_of_kin_name',
        'next_of_kin_phone',
        'next_of_kin_relationship',
        'profile_photo',
        'sacco_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'membership_date' => 'date',
    ];

    /**
     * Get the SACCO that the user belongs to.
     */
    public function sacco()
    {
        return $this->belongsTo(Sacco::class);
    }


/**
 * Get the loan applications where this user is the primary applicant
 */
public function loanApplications()
{
    return $this->hasMany(LoanApplication::class);
}

/**
 * Get the loan applications where this user is a guarantor (for external guarantors)
 * Note: This is for when the user is a guarantor for other people's loans
 */
public function guaranteedLoans()
{
    return $this->belongsToMany(LoanApplication::class, 'loan_guarantors', 'user_id', 'loan_application_id')
                ->withPivot('status', 'verified_at')
                ->withTimestamps();
}


public function guarantorLoans()
{
    return $this->hasMany(LoanGuarantor::class, 'id');
}


    public function assignedLoans()
    {
        return $this->hasMany(LoanApplication::class, 'treasurer_id');
    }

    /**
     * Get the member notifications for the user.
     */
    public function memberNotifications()
    {
        return $this->hasMany(MemberNotification::class);
    }

    /**
     * Get unread member notifications.
     */
    public function unreadMemberNotifications()
    {
        return $this->memberNotifications()->whereNull('read_at');
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTreasurer(): bool
    {
        return $this->hasRole('treasurer');
    }

    public function isMember(): bool
    {
        return $this->hasRole('member');
    }

    public function getTotalSavingsAttribute()
    {
        $deposits = $this->savingsTransactions()
            ->where('transaction_type', 'deposit')
            ->where('status', 'approved')
            ->sum('amount');

        $withdrawals = $this->savingsTransactions()
            ->where('transaction_type', 'withdrawal')
            ->where('status', 'approved')
            ->sum('amount');

        return $deposits - $withdrawals;
    }


public function completedSavingsTransactions()
{
    return $this->hasMany(SavingsTransaction::class)->where('status', 'completed');
}


public function getPendingSavingsAttribute()
{
    return $this->savingsTransactions()
        ->where('status', 'pending')
        ->sum('amount');
}


public function approvedSavingsTransactions()
{
    return $this->hasMany(SavingsTransaction::class)->where('status', 'approved');
}


public function getLastTransactionAttribute()
{
    return $this->savingsTransactions()
        ->where('status', 'approved')
        ->latest()
        ->first();

}
  public function savingsTransactions()
    {
        return $this->hasMany(SavingsTransaction::class);
    }

    /**
     * Get approved/deposit transactions only.
     */
    public function savingsDeposits()
    {
        return $this->hasMany(SavingsTransaction::class)
                    ->where('transaction_type', 'deposit')
                    ->where('status', 'approved');
    }

    /**
     * Get withdrawal transactions.
     */
    public function savingsWithdrawals()
    {
        return $this->hasMany(SavingsTransaction::class)
                    ->where('transaction_type', 'withdrawal')
                    ->where('status', 'approved');
    }

    /**
     * Calculate current savings balance.
     */
    public function getSavingsBalanceAttribute()
    {
        $deposits = $this->savingsTransactions()
                        ->where('transaction_type', 'deposit')
                        ->where('status', 'approved')
                        ->sum('amount');

        $withdrawals = $this->savingsTransactions()
                          ->where('transaction_type', 'withdrawal')
                          ->where('status', 'approved')
                          ->sum('amount');

        return $deposits - $withdrawals;
    }

    /**
     * Get the profile associated with the user.
     */
}
