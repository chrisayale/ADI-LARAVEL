<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\SavingsTransaction;
use App\Models\User;
use App\Models\LoanRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display savings report
     */
    public function savings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Default to current month if no dates provided
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfDay();

        // Build query for savings transactions
        $query = SavingsTransaction::with(['user', 'recorder'])
            ->whereBetween('transaction_date', [$dateFrom, $dateTo]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_deposits' => $transactions->where('transaction_type', 'deposit')->where('status', 'approved')->sum('amount'),
            'total_withdrawals' => $transactions->where('transaction_type', 'withdrawal')->where('status', 'approved')->sum('amount'),
            'pending_amount' => $transactions->where('status', 'pending')->sum('amount'),
            'transaction_count' => $transactions->count(),
            'approved_count' => $transactions->where('status', 'approved')->count(),
        ];

        // Get daily totals for chart
        $dailyTotals = $transactions->groupBy(function($item) {
            return $item->transaction_date->format('Y-m-d');
        })->map(function($day) {
            return [
                'date' => $day->first()->transaction_date->format('d M'),
                'deposits' => $day->where('transaction_type', 'deposit')->where('status', 'approved')->sum('amount'),
                'withdrawals' => $day->where('transaction_type', 'withdrawal')->where('status', 'approved')->sum('amount'),
            ];
        })->values();

        // Get members for filter dropdown
        $members = User::role('member')->where('status', 'active')->orderBy('name')->get(['id', 'name', 'membership_number']);

        return view('admin.reports.savings_report', compact(
            'transactions',
            'summary',
            'dailyTotals',
            'members',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Display loans report
     */
    public function loans(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:pending,under_verification,verified,approved,rejected,disbursed,completed,defaulted,all',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Default to current year if no dates provided
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfYear();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfDay();

        // Build query for loan applications
        $query = LoanApplication::with(['user', 'treasurer', 'approver'])
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_applications' => $loans->count(),
            'total_amount_applied' => $loans->sum('amount_applied'),
            'total_amount_approved' => $loans->whereIn('status', ['approved', 'disbursed', 'completed'])->sum('amount_approved'),
            'total_amount_disbursed' => $loans->where('status', 'disbursed')->sum('amount_approved'),
            'total_repaid' => LoanRepayment::whereIn('loan_application_id', $loans->pluck('id'))->where('status', 'paid')->sum('amount_paid'),
        ];

        // Status breakdown
        $statusBreakdown = [
            'pending' => $loans->where('status', 'pending')->count(),
            'under_verification' => $loans->where('status', 'under_verification')->count(),
            'verified' => $loans->where('status', 'verified')->count(),
            'approved' => $loans->where('status', 'approved')->count(),
            'rejected' => $loans->where('status', 'rejected')->count(),
            'disbursed' => $loans->where('status', 'disbursed')->count(),
            'completed' => $loans->where('status', 'completed')->count(),
            'defaulted' => $loans->where('status', 'defaulted')->count(),
        ];

        // Monthly trend data
        $monthlyTrend = $loans->groupBy(function($item) {
            return $item->created_at->format('Y-m');
        })->map(function($month) {
            return [
                'month' => $month->first()->created_at->format('M Y'),
                'applications' => $month->count(),
                'amount_applied' => $month->sum('amount_applied'),
                'amount_approved' => $month->whereIn('status', ['approved', 'disbursed', 'completed'])->sum('amount_approved'),
            ];
        })->values();

        return view('admin.reports.loan_report', compact(
            'loans',
            'summary',
            'statusBreakdown',
            'monthlyTrend',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Display financial summary report
     */
    public function financialSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'nullable|integer|min:2020|max:' . (now()->year + 1),
        ]);

        $year = $request->year ?? now()->year;

        // Get monthly savings data
        $monthlySavings = SavingsTransaction::whereYear('transaction_date', $year)
            ->where('status', 'approved')
            ->select(
                DB::raw('CAST(strftime(\'%m\', transaction_date) AS INTEGER) as month'),
                DB::raw("SUM(CASE WHEN transaction_type = 'deposit' THEN amount ELSE 0 END) as deposits"),
                DB::raw("SUM(CASE WHEN transaction_type = 'withdrawal' THEN amount ELSE 0 END) as withdrawals")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Get monthly loans data
        $monthlyLoans = LoanApplication::whereYear('created_at', $year)
            ->whereIn('status', ['approved', 'disbursed', 'completed'])
            ->select(
                DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'),
                DB::raw('SUM(amount_approved) as approved_amount'),
                DB::raw('COUNT(*) as loan_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Prepare chart data for all months
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $savingsData = [];
        $loansData = [];

        for ($i = 1; $i <= 12; $i++) {
            $savingsData['deposits'][] = $monthlySavings[$i]->deposits ?? 0;
            $savingsData['withdrawals'][] = $monthlySavings[$i]->withdrawals ?? 0;
            $loansData['approved'][] = $monthlyLoans[$i]->approved_amount ?? 0;
        }

        // Calculate overall totals
        $totals = [
            'total_deposits' => array_sum($savingsData['deposits']),
            'total_withdrawals' => array_sum($savingsData['withdrawals']),
            'net_savings' => array_sum($savingsData['deposits']) - array_sum($savingsData['withdrawals']),
            'total_loans_approved' => array_sum($loansData['approved']),
            'total_loans_count' => LoanApplication::whereYear('created_at', $year)->whereIn('status', ['approved', 'disbursed', 'completed'])->count(),
            'active_loans_count' => LoanApplication::whereIn('status', ['approved', 'disbursed'])->count(),
            'total_members' => User::role('member')->count(),
            'active_members' => User::role('member')->where('status', 'active')->count(),
        ];

        return view('admin.reports.financial_summary', compact(
            'months',
            'savingsData',
            'loansData',
            'totals',
            'year'
        ));
    }

    /**
     * Export report as PDF/Excel (placeholder for future implementation)
     */
    public function export(Request $request)
    {
        $type = $request->type; // 'savings', 'loans', 'financial'
        $format = $request->format; // 'pdf', 'excel', 'csv'

        // This would be implemented with a package like maatwebsite/excel or barryvdh/laravel-dompdf
        // For now, redirect back with a message

        return redirect()->back()->with('info', 'Export functionality coming soon.');
    }
}
