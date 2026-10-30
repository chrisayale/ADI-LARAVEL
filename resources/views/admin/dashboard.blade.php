@extends('layouts.app')

@section('title', __('admin_dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('admin_dashboard') }}</h1>
        <a href="{{ route('admin.reports.financial-summary') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> {{ __('generate_report') }}
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Members Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('total_members') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMembers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Members Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('active_members') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeMembers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Savings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('total_savings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('ugx') }} {{ number_format($totalSavings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
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
                                {{ __('pending_loans') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->hasRole('super_admin'))
        <!-- Pending SACCO Requests Card (Super Admin only) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                {{ __('pending_sacco_requests') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingSaccoRequests }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Monthly Savings Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('monthly_savings_overview', ['year' => date('Y')]) }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlySavingsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('loan_status_overview') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="loanStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> {{ __('pending') }}
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> {{ __('verified') }}
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> {{ __('approved') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Row -->
    <div class="row">
        <!-- Recent Members -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_members') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('membership_no') }}</th>
                                    <th>{{ __('joined') }}</th>
                                    <th>{{ __('status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMembers as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->membership_number ?? 'N/A' }}</td>
                                    <td>{{ $member->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $member->status === 'active' ? 'success' : ($member->status === 'inactive' ? 'warning' : 'danger') }}">
                                            {{ translate_status($member->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ __('no_members_found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">{{ __('view_all_members') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Loan Applications -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_loan_applications') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('member') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLoans as $loan)
                                <tr>
                                    <td>{{ $loan->user->name }}</td>
                                    <td>{{ __('ugx') }} {{ number_format($loan->amount_applied, 2) }}</td>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{
                                            $loan->status === 'approved' ? 'success' :
                                            ($loan->status === 'pending' ? 'warning' :
                                            ($loan->status === 'rejected' ? 'danger' : 'info'))
                                        }}">
                                            {{ str_replace('_', ' ', ucfirst($loan->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ __('no_loan_applications_found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.loans.index') }}" class="btn btn-primary btn-sm">{{ __('view_all_loans') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Savings Chart
    const monthlyData = @json($monthlySavings);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    const deposits = new Array(12).fill(0);
    const withdrawals = new Array(12).fill(0);

    monthlyData.forEach(item => {
        deposits[item.month - 1] = item.deposits || 0;
        withdrawals[item.month - 1] = item.withdrawals || 0;
    });

    new Chart(document.getElementById('monthlySavingsChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: '{{ __('deposits') }}',
                data: deposits,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3
            }, {
                label: '{{ __('withdrawals') }}',
                data: withdrawals,
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '{{ __('ugx') }} ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Loan Status Chart (you can calculate actual data in controller)
    new Chart(document.getElementById('loanStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['{{ __('pending') }}', '{{ __('verified') }}', '{{ __('approved') }}'],
            datasets: [{
                data: [{{ $pendingLoans }}, 5, 8], // Replace with actual data
                backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a'],
                hoverBackgroundColor: ['#f4b619', '#2c9faf', '#17a673'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>

