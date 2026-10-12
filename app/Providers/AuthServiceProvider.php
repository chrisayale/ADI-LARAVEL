<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Sacco;
use App\Models\SavingsTransaction;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use App\Models\LoanGuarantor;
use App\Models\PaymentTransaction;
use App\Models\DefaulterTracking;
use App\Models\MeetingNotice;
use App\Models\MemberNotification;
use App\Models\LoanDocument;
use App\Models\MeetingAttendance;
use App\Policies\UserPolicy;
use App\Policies\SaccoPolicy;
use App\Policies\SavingsTransactionPolicy;
use App\Policies\LoanApplicationPolicy;
use App\Policies\LoanRepaymentPolicy;
use App\Policies\LoanGuarantorPolicy;
use App\Policies\PaymentTransactionPolicy;
use App\Policies\DefaulterTrackingPolicy;
use App\Policies\MeetingNoticePolicy;
use App\Policies\MemberNotificationPolicy;
use App\Policies\LoanDocumentPolicy;
use App\Policies\MeetingAttendancePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // User model policy
        User::class => UserPolicy::class,

        // Sacco model policy
        Sacco::class => SaccoPolicy::class,

        // Savings transaction model policy
        SavingsTransaction::class => SavingsTransactionPolicy::class,

        // Loan application model policy
        LoanApplication::class => LoanApplicationPolicy::class,

        // Loan repayment model policy
        LoanRepayment::class => LoanRepaymentPolicy::class,

        // Loan guarantor model policy
        LoanGuarantor::class => LoanGuarantorPolicy::class,

        // Payment transaction model policy
        PaymentTransaction::class => PaymentTransactionPolicy::class,

        // Defaulter tracking model policy
        DefaulterTracking::class => DefaulterTrackingPolicy::class,

        // Meeting notice model policy
        MeetingNotice::class => MeetingNoticePolicy::class,

        // Member notification model policy
        MemberNotification::class => MemberNotificationPolicy::class,

        // Loan document model policy
        LoanDocument::class => LoanDocumentPolicy::class,

        // Meeting attendance model policy
        MeetingAttendance::class => MeetingAttendancePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}