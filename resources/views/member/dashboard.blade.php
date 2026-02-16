{{-- resources/views/member/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Member Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div>
            <a href="{{ route('member.loans.apply') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Apply for Loan
            </a>
            <a href="{{ route('member.savings.my-savings') }}" class="btn btn-info btn-sm">
                <i class="fas fa-piggy-bank"></i> My Savings
            </a>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="alert alert-primary mb-4">
        <h4 class="alert-heading">Welcome back, {{ $user->name }}!</h4>
        <p class="mb-0">Here's what's happening with your account today.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Savings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($stats['total_savings'], 2) }}</div>
                            <small class="text-muted">Pending: UGX {{ number_format($stats['pending_savings'], 2) }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Savings Transactions</h6>
                    <a href="{{ route('member.savings.transactions') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_transactions'] as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->type_color }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="text-right">UGX {{ number_format($transaction->amount, 2) }}</td>
                                    <td class="text-right">UGX {{ number_format($transaction->balance_after, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No transactions yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Loan Applications -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Loan Applications</h6>
                    <a href="{{ route('member.loans.my-loans') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Application #</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_loans'] as $loan)
                                <tr>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('member.loans.show', $loan) }}">
                                            {{ $loan->application_number }}
                                        </a>
                                    </td>
                                    <td class="text-right">UGX {{ number_format($loan->amount_applied, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'secondary',
                                                'under_verification' => 'info',
                                                'verified' => 'primary',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'disbursed' => 'primary',
                                                'completed' => 'success',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                            {{ str_replace('_', ' ', ucfirst($loan->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No loan applications yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Repayments -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Repayments</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Loan #</th>
                                    <th>Installment</th>
                                    <th>Due Date</th>
                                    <th>Amount Due</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingRepayments as $item)
                                <tr class="{{ $item['repayment']->due_date < now() ? 'table-danger' : '' }}">
                                    <td>
                                        <a href="{{ route('member.loans.show', $item['loan']) }}">
                                            {{ $item['loan']->application_number }}
                                        </a>
                                    </td>
                                    <td>Installment #{{ $item['repayment']->installment_number }}</td>
                                    <td>
                                        {{ $item['repayment']->due_date->format('d M Y') }}
                                        @if($item['repayment']->due_date < now())
                                            <span class="badge badge-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="text-right">UGX {{ number_format($item['repayment']->amount_due - $item['repayment']->amount_paid, 2) }}</td>
                                    <td>
                                        @if($item['repayment']->status == 'pending')
                                            <span class="badge badge-secondary">Pending</span>
                                        @elseif($item['repayment']->status == 'partial')
                                            <span class="badge badge-warning">Partial</span>
                                        @elseif($item['repayment']->status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('member.loans.repayment-schedule', $item['loan']) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-calendar"></i> View Schedule
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No upcoming repayments</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('member.loans.apply') }}" class="btn btn-outline-primary btn-block p-3">
                                <i class="fas fa-file-invoice fa-2x mb-2"></i>
                                <br>Apply for Loan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('member.savings.transactions') }}" class="btn btn-outline-success btn-block p-3">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <br>Transaction History
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('member.loans.my-loans') }}" class="btn btn-outline-info btn-block p-3">
                                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                <br>My Loans
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-block p-3">
                                <i class="fas fa-user fa-2x mb-2"></i>
                                <br>My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
