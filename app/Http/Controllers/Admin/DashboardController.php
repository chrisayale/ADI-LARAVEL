<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoanApplication;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics for admin dashboard
        $totalMembers = User::count();
        $activeMembers = User::where('status', 'active')->count();

        $totalSavings = SavingsTransaction::where('status', 'approved')
            ->where('transaction_type', 'deposit')
            ->sum('amount') -
            SavingsTransaction::where('status', 'approved')
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');

        $pendingLoans = LoanApplication::where('status', 'pending')->count();
        $activeLoans = LoanApplication::whereIn('status', ['approved', 'disbursed'])->count();

        $recentMembers = User::latest()->take(5)->get();
        $recentLoans = LoanApplication::with('user')->latest()->take(5)->get();

        // Monthly savings chart data
        $monthlySavings = SavingsTransaction::select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('SUM(CASE WHEN transaction_type = "deposit" THEN amount ELSE 0 END) as deposits'),
                DB::raw('SUM(CASE WHEN transaction_type = "withdrawal" THEN amount ELSE 0 END) as withdrawals')
            )
            ->where('status', 'approved')
            ->whereYear('transaction_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'activeMembers',
            'totalSavings',
            'pendingLoans',
            'activeLoans',
            'recentMembers',
            'recentLoans',
            'monthlySavings'
        ));
    }
}
