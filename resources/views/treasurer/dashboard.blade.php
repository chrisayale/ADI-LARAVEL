{{-- resources/views/treasurer/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Treasurer Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Treasurer Dashboard</h1>
        <div>
            <a href="{{ route('treasurer.savings.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Record Transaction
            </a>
            <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-info btn-sm">
                <i class="fas fa-check-circle"></i> Pending Verifications
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Assigned Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['assigned_loans'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
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
                                Pending Verification</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_verification'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Verified Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified_loans'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Today's Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_transactions'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Deposits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($stats['today_deposits'] ?? 0, 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($stats['today_withdrawals'] ?? 0, 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue Repayments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_repayments'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Loans for Verification -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Loans to Verify</h6>
                    <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Amount</th>
                                    <th>Applied</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLoans->where('status', 'under_verification') as $loan)
                                <tr>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        <br><small class="text-muted">{{ $loan->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>UGX {{ number_format($loan->amount_applied, 0) }}</td>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('treasurer.verifications.show', $loan) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Verify
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No loans pending verification</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Savings Transactions -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                    <a href="{{ route('treasurer.savings.transactions') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <strong>{{ $transaction->user->name }}</strong>
                                        <br><small class="text-muted">{{ $transaction->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->type_color ?? 'secondary' }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>UGX {{ number_format($transaction->amount, 0) }}</td>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No recent transactions</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Assigned Defaulters -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">Assigned Defaulters</h6>
                    <a href="{{ route('treasurer.loans.repayments') }}" class="btn btn-sm btn-danger">Manage</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Loan Amount</th>
                                    <th>Overdue</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($defaulters as $defaulter)
                                <tr>
                                    <td>
                                        <strong>{{ $defaulter->user->name }}</strong>
                                        <br><small class="text-muted">{{ $defaulter->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>UGX {{ number_format($defaulter->loanApplication->amount_approved ?? 0, 0) }}</td>
                                    <td class="text-danger">UGX {{ number_format($defaulter->amount_overdue, 0) }}</td>
                                    <td><span class="badge badge-danger">{{ $defaulter->days_overdue }} days</span></td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'new' => 'secondary',
                                                'follow_up_1' => 'info',
                                                'follow_up_2' => 'warning',
                                                'final_notice' => 'danger',
                                                'legal_action' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusMap[$defaulter->status] ?? 'secondary' }}">
                                            {{ str_replace('_', ' ', ucfirst($defaulter->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('treasurer.loans.loan-repayments', $defaulter->loanApplication) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No defaulters assigned</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
