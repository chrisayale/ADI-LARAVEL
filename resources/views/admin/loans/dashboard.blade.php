@extends('layouts.app')

@section('title', 'Loans Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loans Dashboard</h1>
        <div>
            <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Loan Application
            </a>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-info">
                <i class="fas fa-list"></i> All Loans
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Loans Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
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

        <!-- Pending Loans Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_loans'] }}</div>
                            <small>CDF {{ number_format($stats['pending_amount'], 0) }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Under Verification Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Under Verification</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['under_verification'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Verified</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Approved Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved_loans'] }}</div>
                            <small>CDF {{ number_format($stats['approved_amount'], 0) }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disbursed Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Disbursed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['disbursed_loans'] }}</div>
                            <small>CDF {{ number_format($stats['total_disbursed'], 0) }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defaulted Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Defaulted</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['defaulted_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Disbursed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($stats['total_disbursed'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Repaid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($stats['total_repaid'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Outstanding Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($stats['total_outstanding'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Loan Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="loanStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
<div class="col-xl-4 col-lg-5">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="small text-gray-500">Approval Rate</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $stats['total_loans'] > 0 ? round(($stats['approved_loans'] / $stats['total_loans']) * 100, 1) : 0 }}%
                </div>
                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar"
                         style="width: {{ $stats['total_loans'] > 0 ? ($stats['approved_loans'] / $stats['total_loans']) * 100 : 0 }}%">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="small text-gray-500">Disbursement Rate</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $stats['approved_loans'] > 0 ? round(($stats['disbursed_loans'] / $stats['approved_loans']) * 100, 1) : 0 }}%
                </div>
                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-info" role="progressbar"
                         style="width: {{ $stats['approved_loans'] > 0 ? ($stats['disbursed_loans'] / $stats['approved_loans']) * 100 : 0 }}%">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="small text-gray-500">Default Rate</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{ $stats['disbursed_loans'] > 0 ? round(($stats['defaulted_loans'] / $stats['disbursed_loans']) * 100, 1) : 0 }}%
                </div>
                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-danger" role="progressbar"
                         style="width: {{ $stats['disbursed_loans'] > 0 ? ($stats['defaulted_loans'] / $stats['disbursed_loans']) * 100 : 0 }}%">
                    </div>
                </div>
            </div>

            <hr>

            <div class="text-center">
                @php
                    $avgLoan = 0;
                    if ($stats['disbursed_loans'] > 0) {
                        $avgLoan = $stats['total_disbursed'] / $stats['disbursed_loans'];
                    }

                    $recoveryRate = 0;
                    if ($stats['total_disbursed'] > 0) {
                        $recoveryRate = ($stats['total_repaid'] / $stats['total_disbursed']) * 100;
                    }
                @endphp
                <span class="badge badge-primary p-2 mr-2">Avg Loan: CDF {{ number_format($avgLoan, 0) }}</span>
                <span class="badge badge-info p-2">Recovery Rate: {{ round($recoveryRate, 1) }}%</span>
            </div>
        </div>
    </div>
</div>
    </div>

    <!-- Recent Loans Table -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Loan Applications</h6>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLoans as $loan)
                                <tr>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        <br><small class="text-muted">{{ $loan->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-right">CDF {{ number_format($loan->amount_applied, 0) }}</td>
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
                                                'defaulted' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                            {{ translate_status($loan->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No recent loans</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defaulters Table -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">Recent Defaulters</h6>
                    <a href="{{ route('admin.loans.defaulters') }}" class="btn btn-sm btn-danger">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Overdue</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($defaulters as $defaulter)
                                <tr>
                                    <td>
                                        <strong>{{ $defaulter->user->name }}</strong>
                                        <br><small class="text-muted">{{ $defaulter->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-right text-danger">CDF {{ number_format($defaulter->amount_overdue, 0) }}</td>
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
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No defaulters found</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loanStatusChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Under Verification', 'Verified', 'Approved', 'Disbursed', 'Completed', 'Defaulted'],
                datasets: [{
                    label: 'Number of Loans',
                    data: [
                        {{ $stats['pending_loans'] }},
                        {{ $stats['under_verification'] }},
                        {{ $stats['verified_loans'] }},
                        {{ $stats['approved_loans'] }},
                        {{ $stats['disbursed_loans'] }},
                        {{ $stats['completed_loans'] }},
                        {{ $stats['defaulted_loans'] }}
                    ],
                    backgroundColor: [
                        '#858796', '#36b9cc', '#4e73df', '#1cc88a', '#f6c23e', '#1cc88a', '#e74a3b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush
