<?php
// app/Http/Controllers/Member/SavingsController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsController extends Controller
{
    /**
     * Display member's savings overview
     */
    public function index()
    {
        $user = Auth::user();

        $totalSavings = $user->total_savings;
        $pendingSavings = $user->pending_savings;

        // Get recent transactions
        $recentTransactions = $user->savingsTransactions()
            ->with('recorder')
            ->latest()
            ->limit(10)
            ->get();

        // Monthly summary for chart
        $monthlyData = $user->savingsTransactions()
            ->select(
                \DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'),
                \DB::raw('CAST(strftime(\'%Y\', created_at) AS INTEGER) as year'),
                \DB::raw("SUM(CASE WHEN transaction_type = 'deposit' THEN amount ELSE 0 END) as deposits"),
                \DB::raw("SUM(CASE WHEN transaction_type = 'withdrawal' THEN amount ELSE 0 END) as withdrawals")
            )
            ->whereYear('created_at', date('Y'))
            ->where('status', 'approved')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('member.savings.index', compact('totalSavings', 'pendingSavings', 'recentTransactions', 'monthlyData'));
    }

    /**
     * Display all savings transactions
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();

        $query = $user->savingsTransactions()->with('recorder');

        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('transaction_type', $request->type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        // Summary
        $totalDeposits = $user->savingsTransactions()
            ->where('transaction_type', 'deposit')
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawals = $user->savingsTransactions()
            ->where('transaction_type', 'withdrawal')
            ->where('status', 'approved')
            ->sum('amount');

        return view('member.savings.transactions', compact('transactions', 'totalDeposits', 'totalWithdrawals'));
    }
}
