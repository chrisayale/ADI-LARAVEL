{{-- resources/views/admin/reports/loan_report.blade.php --}}
@extends('layouts.app')

@section('title', __('loans_report'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('loans_report') }}</h1>
        <div>
            <a href="{{ route('admin.reports.savings') }}" class="btn btn-info">
                <i class="fas fa-piggy-bank"></i> {{ __('savings_report') }}
            </a>
            <a href="{{ route('admin.reports.financial-summary') }}" class="btn btn-primary">
                <i class="fas fa-chart-pie"></i> {{ __('financial_summary') }}
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_report') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.loans') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="date_from">{{ __('date_from') }}</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                           value="{{ $dateFrom->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="date_to">{{ __('date_to') }}</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                           value="{{ $dateTo->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status">{{ __('loan_status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">{{ __('all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('pending') }}</option>
                        <option value="under_verification" {{ request('status') == 'under_verification' ? 'selected' : '' }}>{{ __('under_verification') }}</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>{{ __('verified') }}</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('approved') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('rejected') }}</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>{{ __('disbursed') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('completed') }}</option>
                        <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>{{ __('defaulted') }}</option>
                    </select>
                </div>
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> {{ __('generate_report') }}
                    </button>
                    <a href="{{ route('admin.reports.loans') }}" class="btn btn-secondary">{{ __('reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('total_applications') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_applications'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
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
                                {{ __('amount_applied') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($summary['total_amount_applied'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                {{ __('amount_approved') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($summary['total_amount_approved'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                {{ __('amount_repaid') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($summary['total_repaid'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown Chart -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('status_breakdown') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('monthly_trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('loan_details') }}</h6>
            <span class="badge badge-info">{{ __('period') }}: {{ $dateFrom->format('d M Y') }} - {{ $dateTo->format('d M Y') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('date') }}</th>
                            <th>{{ __('application_number') }}</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('amount_applied') }}</th>
                            <th>{{ __('amount_approved') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('treasurer') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $loan) }}">
                                    {{ $loan->application_number }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $loan->user->name }}</strong>
                                @if($loan->user->membership_number)
                                    <br><small class="text-muted">{{ $loan->user->membership_number }}</small>
                                @endif
                            </td>
                            <td class="text-right">UGX {{ number_format($loan->amount_applied, 2) }}</td>
                            <td class="text-right">
                                @if($loan->amount_approved)
                                    UGX {{ number_format($loan->amount_approved, 2) }}
                                @else
                                    -
                                @endif
                            </td>
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
                            <td>{{ $loan->treasurer->name ?? __('not_assigned') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_loans_found_period') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get translations for chart labels
        const pendingLabel = '{{ __('pending') }}';
        const underVerificationLabel = '{{ __('under_verification') }}';
        const verifiedLabel = '{{ __('verified') }}';
        const approvedLabel = '{{ __('approved') }}';
        const rejectedLabel = '{{ __('rejected') }}';
        const disbursedLabel = '{{ __('disbursed') }}';
        const completedLabel = '{{ __('completed') }}';
        const defaultedLabel = '{{ __('defaulted') }}';
        const amountAppliedLabel = '{{ __('amount_applied_ugx') }}';
        const amountApprovedLabel = '{{ __('amount_approved_ugx') }}';
        const ugxLabel = '{{ __('ugx') }}';

        // Status Breakdown Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($statusBreakdown);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [pendingLabel, underVerificationLabel, verifiedLabel, approvedLabel, rejectedLabel, disbursedLabel, completedLabel, defaultedLabel],
                datasets: [{
                    data: [
                        statusData.pending,
                        statusData.under_verification,
                        statusData.verified,
                        statusData.approved,
                        statusData.rejected,
                        statusData.disbursed,
                        statusData.completed,
                        statusData.defaulted
                    ],
                    backgroundColor: [
                        '#858796', '#36b9cc', '#4e73df', '#1cc88a', '#e74a3b', '#f6c23e', '#1cc88a', '#e74a3b'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Trend Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = @json($monthlyTrend);

        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: amountAppliedLabel,
                    data: monthlyData.map(item => item.amount_applied),
                    backgroundColor: '#4e73df',
                    yAxisID: 'y'
                }, {
                    label: amountApprovedLabel,
                    data: monthlyData.map(item => item.amount_approved),
                    backgroundColor: '#1cc88a',
                    yAxisID: 'y'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return ugxLabel + ' ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
