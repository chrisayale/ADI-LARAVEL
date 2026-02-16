<?php
// app/Models/LoanApplication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_number',
        'user_id',
        'treasurer_id',
        'amount_applied',
        'amount_approved',
        'interest_rate',
        'duration_months',
        'monthly_repayment',
        'total_repayable',
        'purpose',
        'has_collateral',
        'collateral_type',
        'collateral_description',
        'collateral_value',
        'status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'approved_by',
        'approved_at',
        'approval_notes',
        'disbursed_by',
        'disbursed_at',
        'first_payment_date',
        'maturity_date'
    ];

    protected $casts = [
        'amount_applied' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_repayment' => 'decimal:2',
        'total_repayable' => 'decimal:2',
        'collateral_value' => 'decimal:2',
        'has_collateral' => 'boolean',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'first_payment_date' => 'date',
        'maturity_date' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function treasurer()
    {
        return $this->belongsTo(User::class, 'treasurer_id');
    }


    // In app/Models/LoanApplication.php

public function approve($data)
{
    // Calculate monthly repayment using standard loan formula
    $principal = $data['amount_approved'];
    $annualRate = $data['interest_rate'] / 100;
    $monthlyRate = $annualRate / 12;
    $months = $data['duration_months'];

    // Standard loan amortization formula
    if ($monthlyRate > 0) {
        $monthlyPayment = $principal * $monthlyRate * pow(1 + $monthlyRate, $months) / (pow(1 + $monthlyRate, $months) - 1);
    } else {
        $monthlyPayment = $principal / $months;
    }

    $totalRepayable = $monthlyPayment * $months;

    $this->update([
        'amount_approved' => $data['amount_approved'],
        'interest_rate' => $data['interest_rate'],
        'duration_months' => $data['duration_months'],
        'monthly_repayment' => round($monthlyPayment, 2),
        'total_repayable' => round($totalRepayable, 2),
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
        'approval_notes' => $data['approval_notes'] ?? null,
        'first_payment_date' => now()->addMonth(),
        'maturity_date' => now()->addMonths($data['duration_months'])
    ]);

    // Create repayment schedule - THIS SHOULD BE CALLED HERE
    $this->createRepaymentSchedule();
}

// Make sure createRepaymentSchedule method exists
private function createRepaymentSchedule()
{
    // Delete any existing repayments first
    $this->repayments()->delete();

    $dueDate = now()->addMonth();

    for ($i = 1; $i <= $this->duration_months; $i++) {
        $this->repayments()->create([
            'repayment_number' => 'RPT-' . $this->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'installment_number' => $i,
            'amount_due' => $this->monthly_repayment,
            'due_date' => $dueDate->copy(),
            'status' => 'pending'
        ]);

        $dueDate->addMonth();
    }
}

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

      public function guarantors()
    {
        return $this->hasMany(LoanGuarantor::class);
    }

    public function approvedGuarantors()
    {
        return $this->hasMany(LoanGuarantor::class)->where('status', 'approved');
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function paidRepayments()
    {
        return $this->hasMany(LoanRepayment::class)->where('status', 'paid');
    }

    public function overdueRepayments()
    {
        return $this->hasMany(LoanRepayment::class)
            ->whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', now());
    }

    public function defaulterTracking()
    {
        return $this->hasOne(DefaulterTracking::class, 'loan_application_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderVerification($query)
    {
        return $query->where('status', 'under_verification');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDefaulted($query)
    {
        return $query->where('status', 'defaulted');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['disbursed', 'approved']);
    }

    // Accessors
    public function getBalanceAttribute()
    {
        $totalPaid = $this->repayments->sum('amount_paid');
        return $this->total_repayable - $totalPaid;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_repayable == 0) return 0;
        $totalPaid = $this->repayments->sum('amount_paid');
        return round(($totalPaid / $this->total_repayable) * 100, 2);
    }

    public function getIsOverdueAttribute()
    {
        return $this->repayments()
            ->whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', now())
            ->exists();
    }

    public function getDaysOverdueAttribute()
    {
        $latestOverdue = $this->repayments()
            ->whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->first();

        return $latestOverdue ? now()->diffInDays($latestOverdue->due_date) : 0;
    }

    // Methods
    public function assignTreasurer($treasurerId)
    {
        $this->update([
            'treasurer_id' => $treasurerId,
            'status' => 'under_verification'
        ]);
    }

    public function verify($notes = null)
    {
        $this->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'verification_notes' => $notes
        ]);
    }



    public function reject($notes)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'verification_notes' => $notes
        ]);
    }

    public function disburse()
    {
        $this->update([
            'status' => 'disbursed',
            'disbursed_by' => auth()->id(),
            'disbursed_at' => now()
        ]);

        // Create repayment schedule
        $this->createRepaymentSchedule();
    }


    public function markAsDefaulted()
    {
        $this->update(['status' => 'defaulted']);

        // Create defaulter tracking record
        DefaulterTracking::updateOrCreate(
            ['loan_application_id' => $this->id],
            [
                'user_id' => $this->user_id,
                'amount_overdue' => $this->balance,
                'days_overdue' => $this->days_overdue,
                'assigned_to' => $this->treasurer_id,
                'status' => 'new'
            ]
        );
    }

    public function checkAndUpdateDefaultStatus()
    {
        if ($this->status !== 'disbursed') return;

        $overdueDays = $this->days_overdue;

        if ($overdueDays >= 90) {
            $this->markAsDefaulted();
        } elseif ($overdueDays >= 60) {
            // Update defaulter tracking to final notice
            if ($this->defaulterTracking) {
                $this->defaulterTracking->update(['status' => 'final_notice']);
            }
        } elseif ($overdueDays >= 30) {
            // Update defaulter tracking to follow up
            if ($this->defaulterTracking) {
                $this->defaulterTracking->update(['status' => 'follow_up_2']);
            }
        } elseif ($overdueDays >= 15) {
            // Update defaulter tracking to first follow up
            if ($this->defaulterTracking) {
                $this->defaulterTracking->update(['status' => 'follow_up_1']);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            $loan->application_number = 'LN-' . date('Y') . '-' . str_pad(static::max('id') + 1, 5, '0', STR_PAD_LEFT);
        });
    }

    public function documents()
{
    return $this->hasMany(LoanDocument::class);
}

public function verifiedDocuments()
{
    return $this->hasMany(LoanDocument::class)->where('verification_status', 'verified');
}

public function pendingDocuments()
{
    return $this->hasMany(LoanDocument::class)->where('verification_status', 'pending');
}
}
