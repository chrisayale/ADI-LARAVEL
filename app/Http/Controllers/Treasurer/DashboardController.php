<?php
// app/Http/Controllers/Treasurer/DashboardController.php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\SavingsTransaction;
use App\Models\DefaulterTracking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $treasurerId = auth()->id();

        // Initialize all variables with default values
        $stats = [
            'assigned_loans' => 0,
            'pending_verification' => 0,
            'verified_loans' => 0,
            'today_transactions' => 0,
            'today_deposits' => 0,
            'today_withdrawals' => 0,
            'pending_repayments' => 0,
            'assigned_defaulters' => 0,
        ];

        $recentLoans = collect();
        $recentTransactions = collect();
        $defaulters = collect();

        try {
            // Statistics for the treasurer
            $stats = [
                'assigned_loans' => LoanApplication::where('treasurer_id', $treasurerId)
                    ->whereIn('status', ['pending', 'under_verification'])
                    ->count() ?? 0,

                'pending_verification' => LoanApplication::where('treasurer_id', $treasurerId)
                    ->where('status', 'under_verification')
                    ->count() ?? 0,

                'verified_loans' => LoanApplication::where('treasurer_id', $treasurerId)
                    ->where('status', 'verified')
                    ->count() ?? 0,

                'today_transactions' => SavingsTransaction::where('recorded_by', $treasurerId)
                    ->whereDate('created_at', today())
                    ->count() ?? 0,

                'today_deposits' => SavingsTransaction::where('recorded_by', $treasurerId)
                    ->where('transaction_type', 'deposit')
                    ->whereDate('created_at', today())
                    ->sum('amount') ?? 0,

                'today_withdrawals' => SavingsTransaction::where('recorded_by', $treasurerId)
                    ->where('transaction_type', 'withdrawal')
                    ->whereDate('created_at', today())
                    ->sum('amount') ?? 0,

                'pending_repayments' => 0,
                'assigned_defaulters' => 0,
            ];

            // Safely get pending repayments
            try {
                $stats['pending_repayments'] = DB::table('loan_repayments')
                    ->join('loan_applications', 'loan_repayments.loan_application_id', '=', 'loan_applications.id')
                    ->where('loan_applications.treasurer_id', $treasurerId)
                    ->whereIn('loan_repayments.status', ['pending', 'partial'])
                    ->where('loan_repayments.due_date', '<', now())
                    ->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Could not fetch pending repayments: ' . $e->getMessage());
                $stats['pending_repayments'] = 0;
            }

            // Safely get assigned defaulters
            try {
                $stats['assigned_defaulters'] = DefaulterTracking::where('assigned_to', $treasurerId)
                    ->whereIn('status', ['new', 'follow_up_1', 'follow_up_2', 'final_notice'])
                    ->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Could not fetch assigned defaulters: ' . $e->getMessage());
                $stats['assigned_defaulters'] = 0;
            }

            // Recent loans assigned to this treasurer
            try {
                $recentLoans = LoanApplication::with(['user'])
                    ->where('treasurer_id', $treasurerId)
                    ->whereIn('status', ['under_verification', 'verified'])
                    ->latest()
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Could not fetch recent loans: ' . $e->getMessage());
                $recentLoans = collect();
            }

            // Recent savings transactions recorded by this treasurer
            try {
                $recentTransactions = SavingsTransaction::with(['user'])
                    ->where('recorded_by', $treasurerId)
                    ->latest()
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Could not fetch recent transactions: ' . $e->getMessage());
                $recentTransactions = collect();
            }

            // Defaulters assigned to this treasurer
            try {
                $defaulters = DefaulterTracking::with(['user', 'loanApplication'])
                    ->where('assigned_to', $treasurerId)
                    ->whereIn('status', ['new', 'follow_up_1', 'follow_up_2', 'final_notice'])
                    ->latest()
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Could not fetch defaulters: ' . $e->getMessage());
                $defaulters = collect();
            }

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            // Keep the default empty values if there's a major error
            // $stats, $recentLoans, $recentTransactions, $defaulters are already set to defaults
        }

        // Debug: Log the variables to see what's being passed
        Log::info('Dashboard data:', [
            'stats_count' => count($stats),
            'recentLoans_count' => $recentLoans->count(),
            'recentTransactions_count' => $recentTransactions->count(),
            'defaulters_count' => $defaulters->count(),
        ]);

        return view('treasurer.dashboard', compact('stats', 'recentLoans', 'recentTransactions', 'defaulters'));
    }
}
