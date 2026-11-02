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
        'sacco_id',
        'amount_applied',
        'amount_approved',
        'interest_rate',
        'duration_months',
        'duration_value',
        'repayment_frequency',
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
    // Calculate repayment based on frequency
    $principal = $data['amount_approved'];
    $annualRate = $data['interest_rate'] / 100;
    $frequency = $data['repayment_frequency'] ?? 'monthly';
    $durationValue = $data['duration_value'] ?? $data['duration_months'] ?? 12;

    $calculation = $this->calculateRepayment($principal, $annualRate, $frequency, $durationValue);

    $this->update([
        'amount_approved' => $data['amount_approved'],
        'interest_rate' => $data['interest_rate'],
        'duration_months' => $frequency === 'monthly' ? $durationValue : null,
        'duration_value' => $durationValue,
        'repayment_frequency' => $frequency,
        'monthly_repayment' => round($calculation['periodic_payment'], 2),
        'total_repayable' => round($calculation['total_repayable'], 2),
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
        'approval_notes' => $data['approval_notes'] ?? null,
        'first_payment_date' => $calculation['first_payment_date'],
        'maturity_date' => $calculation['maturity_date']
    ]);

    // Create repayment schedule - THIS SHOULD BE CALLED HERE
    $this->createRepaymentSchedule();
}

public function calculateRepayment($principal, $annualRate, $frequency, $durationValue)
{
    switch ($frequency) {
        case 'daily':
            // Use 365 days for daily frequency
            $periodicRate = $annualRate / 365;
            $periods = $durationValue;
            $firstPaymentDate = now()->addDay();
            $maturityDate = now()->addDays($durationValue);
            break;
        case 'weekly':
            $periodicRate = $annualRate / 52;
            $periods = $durationValue;
            $firstPaymentDate = now()->addWeek();
            $maturityDate = now()->addWeeks($durationValue);
            break;
        case 'monthly':
        default:
            $periodicRate = $annualRate / 12;
            $periods = $durationValue;
            $firstPaymentDate = now()->addMonth();
            $maturityDate = now()->addMonths($durationValue);
            break;
    }

    if ($periodicRate > 0) {
        $periodicPayment = $principal * $periodicRate * pow(1 + $periodicRate, $periods) / (pow(1 + $periodicRate, $periods) - 1);
    } else {
        $periodicPayment = $principal / $periods;
    }

    $totalRepayable = $periodicPayment * $periods;

    return [
        'periodic_payment' => $periodicPayment,
        'total_repayable' => $totalRepayable,
        'periodic_rate' => $periodicRate,
        'periods' => $periods,
        'first_payment_date' => $firstPaymentDate,
        'maturity_date' => $maturityDate
    ];
}

// Make sure createRepaymentSchedule method exists
private function createRepaymentSchedule()
{
    // Delete any existing repayments first
    $this->repayments()->delete();

    $frequency = $this->repayment_frequency ?? 'monthly';
    $durationValue = $this->duration_value ?? $this->duration_months ?? 12;

    $dueDate = match ($frequency) {
        'daily' => now()->addDay(),
        'weekly' => now()->addWeek(),
        'monthly' => now()->addMonth(),
        default => now()->addMonth()
    };

    for ($i = 1; $i <= $durationValue; $i++) {
        $this->repayments()->create([
            'repayment_number' => 'RPT-' . $this->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'sacco_id' => $this->sacco_id,
            'installment_number' => $i,
            'amount_due' => $this->monthly_repayment,
            'due_date' => $dueDate->copy(),
            'status' => 'pending'
        ]);

        $dueDate = match ($frequency) {
            'daily' => $dueDate->copy()->addDay(),
            'weekly' => $dueDate->copy()->addWeek(),
            'monthly' => $dueDate->copy()->addMonth(),
            default => $dueDate->copy()->addMonth()
        };
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
                'sacco_id' => $this->sacco_id,
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
