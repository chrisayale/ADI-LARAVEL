<?php
// app/Http/Controllers/Member/DashboardController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load relationships
        $user->load([
            'savingsTransactions' => function($query) {
                $query->latest()->limit(5);
            },
            'loanApplications' => function($query) {
                $query->latest()->limit(5);
            }
        ]);

        // Calculate statistics
        $stats = [
            'total_savings' => $user->total_savings,
            'pending_savings' => $user->pending_savings,
            'active_loans' => $user->loanApplications()
                ->whereIn('status', ['approved', 'disbursed'])
                ->count(),
            'pending_loans' => $user->loanApplications()
                ->whereIn('status', ['pending', 'under_verification'])
                ->count(),
            'total_loans' => $user->loanApplications()->count(),
            'recent_transactions' => $user->savingsTransactions()->latest()->limit(5)->get(),
            'recent_loans' => $user->loanApplications()->latest()->limit(5)->get(),
        ];

        // Get upcoming repayments
        $upcomingRepayments = [];
        foreach ($user->loanApplications as $loan) {
            foreach ($loan->repayments as $repayment) {
                if ($repayment->status != 'paid' && $repayment->due_date >= now()) {
                    $upcomingRepayments[] = [
                        'loan' => $loan,
                        'repayment' => $repayment
                    ];
                }
            }
        }

        // Sort by due date
        usort($upcomingRepayments, function($a, $b) {
            return $a['repayment']->due_date <=> $b['repayment']->due_date;
        });

        $upcomingRepayments = array_slice($upcomingRepayments, 0, 5);

        return view('member.dashboard', compact('user', 'stats', 'upcomingRepayments'));
    }
}
