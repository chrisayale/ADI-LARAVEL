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
        'amortization_method',
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
        'maturity_date',
        'apply_late_penalty',
        'late_penalty_amount'
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
        'maturity_date' => 'date',
        'apply_late_penalty' => 'boolean',
        'late_penalty_amount' => 'decimal:2'
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
    // Calculate repayment based on frequency and amortization method
    $principal = $data['amount_approved'];
    $annualRate = $data['interest_rate'] / 100;
    $frequency = $data['repayment_frequency'] ?? 'monthly';
    $durationValue = $data['duration_value'] ?? $data['duration_months'] ?? 12;
    $amortizationMethod = $data['amortization_method'] ?? 'french';

    $calculation = $this->calculateRepayment($principal, $annualRate, $frequency, $durationValue, $amortizationMethod);

    $this->update([
        'amount_approved' => $data['amount_approved'],
        'interest_rate' => $data['interest_rate'],
        'duration_months' => $frequency === 'monthly' ? $durationValue : null,
        'duration_value' => $durationValue,
        'repayment_frequency' => $frequency,
        'amortization_method' => $amortizationMethod,
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

public function calculateRepayment($principal, $annualRate, $frequency, $durationValue, $amortizationMethod = 'french')
{
    // AUTO-CALCULATE INTEREST RATE for Daily Flat Rate
    // When frequency is daily AND amortization is flat_rate: Taux (%) = (Durée en jours / 30) * 10%
    $isDailyFlatRate = ($frequency === 'daily' && ($amortizationMethod === 'flat_rate' || $amortizationMethod === 'pato_ndogo'));
    if ($isDailyFlatRate) {
        // Override annualRate based on duration in days
        $annualRate = (($durationValue / 30) * 10) / 100;
    }

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

    switch ($amortizationMethod) {
        case 'flat_rate':
        case 'pato_ndogo':
            if ($isDailyFlatRate) {
                // DAILY FLAT RATE: Special calculation per requirements
                // Intérêt Total = Capital × Taux (%)
                // Montant Total = Capital + Intérêt Total
                // Paiement Journalier = Montant Total / Nombre de Jours
                // Part Capital journalier = Capital / Nombre de jours
                // Part Intérêt journalier = Intérêt Total / Nombre de jours
                // annualRate is already the full rate (e.g., 0.10 for 30 days, 0.20 for 60 days)
                $totalInterest = $principal * $annualRate;
                $totalRepayable = $principal + $totalInterest;
                $periodicPayment = $totalRepayable / $periods;
            } else {
                // Regular flat rate (weekly/monthly)
                // Flat Rate: Interest calculated on original principal for each period
                // Periodic interest = Principal × Periodic Rate
                // Periodic principal = Principal / Periods
                // Periodic payment = Periodic principal + Periodic interest
                $periodicPrincipal = $principal / $periods;
                $periodicInterest = $principal * $periodicRate;
                $periodicPayment = $periodicPrincipal + $periodicInterest;
                $totalInterest = $periodicInterest * $periods;
                $totalRepayable = $principal + $totalInterest;
            }
            break;

        case 'constant_capital':
            // Constant Capital (Constant Principal): Fixed principal per period, decreasing interest
            // Periodic principal = Principal / Periods
            // Periodic interest = Remaining balance × Periodic Rate
            // We'll return the first period's payment as reference
            $periodicPrincipal = $principal / $periods;
            $firstPeriodInterest = $principal * $periodicRate;
            $periodicPayment = $periodicPrincipal + $firstPeriodInterest; // First payment amount
            // Total interest = sum of declining balance interest
            $totalInterest = 0;
            $remainingBalance = $principal;
            for ($i = 1; $i <= $periods; $i++) {
                $totalInterest += $remainingBalance * $periodicRate;
                $remainingBalance -= $periodicPrincipal;
            }
            $totalRepayable = $principal + $totalInterest;
            break;

        case 'french':
        default:
            // French (Standard amortization): Constant payment, declining interest, increasing principal
            if ($periodicRate > 0) {
                $periodicPayment = $principal * $periodicRate * pow(1 + $periodicRate, $periods) / (pow(1 + $periodicRate, $periods) - 1);
            } else {
                $periodicPayment = $principal / $periods;
            }
            $totalRepayable = $periodicPayment * $periods;
            $totalInterest = $totalRepayable - $principal;
            break;
    }

    return [
        'periodic_payment' => $periodicPayment,
        'total_repayable' => $totalRepayable,
        'total_interest' => $totalInterest,
        'periodic_rate' => $periodicRate,
        'periods' => $periods,
        'amortization_method' => $amortizationMethod,
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
    $amortizationMethod = $this->amortization_method ?? 'french';
    $principal = $this->amount_approved;
    $annualRate = $this->interest_rate / 100;

    // AUTO-CALCULATE INTEREST RATE for Daily Flat Rate
    $isDailyFlatRate = ($frequency === 'daily' && ($amortizationMethod === 'flat_rate' || $amortizationMethod === 'pato_ndogo'));
    if ($isDailyFlatRate) {
        // Override annualRate based on duration in days: Taux (%) = (Durée en jours / 30) * 10%
        $annualRate = (($durationValue / 30) * 10) / 100;
    }

    // Calculate periodic rate based on frequency
    switch ($frequency) {
        case 'daily':
            $periodicRate = $annualRate / 365;
            break;
        case 'weekly':
            $periodicRate = $annualRate / 52;
            break;
        case 'monthly':
        default:
            $periodicRate = $annualRate / 12;
            break;
    }

    $dueDate = match ($frequency) {
        'daily' => now()->addDay(),
        'weekly' => now()->addWeek(),
        'monthly' => now()->addMonth(),
        default => now()->addMonth()
    };

    $remainingBalance = $principal;

    // Pre-calculate values for daily flat rate
    if ($isDailyFlatRate) {
        $totalInterest = $principal * $annualRate;
        $totalRepayable = $principal + $totalInterest;
        $dailyPayment = $totalRepayable / $durationValue;
        $dailyPrincipal = $principal / $durationValue;
        $dailyInterest = $totalInterest / $durationValue;
    }

    for ($i = 1; $i <= $durationValue; $i++) {
        $amountDue = 0;
        $principalDue = 0;
        $interestDue = 0;

        switch ($amortizationMethod) {
            case 'flat_rate':
        case 'pato_ndogo':
                if ($isDailyFlatRate) {
                    // DAILY FLAT RATE: Equal daily payments with equal principal and interest portions
                    $amountDue = $dailyPayment;
                    $principalDue = $dailyPrincipal;
                    $interestDue = $dailyInterest;
                } else {
                    // Flat Rate: Constant principal + constant interest
                    $principalDue = $principal / $durationValue;
                    $interestDue = $principal * $periodicRate;
                    $amountDue = $principalDue + $interestDue;
                }
                break;

            case 'constant_capital':
                // Constant Capital: Fixed principal, declining interest
                $principalDue = $principal / $durationValue;
                $interestDue = $remainingBalance * $periodicRate;
                $amountDue = $principalDue + $interestDue;
                $remainingBalance -= $principalDue;
                break;

            case 'french':
            default:
                // French: Constant payment, declining interest, increasing principal
                if ($periodicRate > 0) {
                    $amountDue = $principal * $periodicRate * pow(1 + $periodicRate, $durationValue) / (pow(1 + $periodicRate, $durationValue) - 1);
                } else {
                    $amountDue = $principal / $durationValue;
                }
                $interestDue = $remainingBalance * $periodicRate;
                $principalDue = $amountDue - $interestDue;
                $remainingBalance -= $principalDue;
                break;
        }

        $this->repayments()->create([
            'repayment_number' => 'RPT-' . $this->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'sacco_id' => $this->sacco_id,
            'installment_number' => $i,
            'amount_due' => round($amountDue, 2),
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

        static::creating(function ($model) {
            if (empty($model->application_number)) {
                $year = date('Y');
                // Recherche le dernier numéro de l'année en cours
                $latest = self::where('application_number', 'like', "LN-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = 1;
                if ($latest && preg_match('/-(\d+)$/', $latest->application_number, $matches)) {
                    $nextNumber = intval($matches[1]) + 1;
                }

                // Boucle de sécurité pour s'assurer que le numéro n'existe pas déjà
                do {
                    $applicationNumber = 'LN-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                    $exists = self::where('application_number', $applicationNumber)->exists();
                    if ($exists) {
                        $nextNumber++;
                    }
                } while ($exists);

                $model->application_number = $applicationNumber;
            }
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
