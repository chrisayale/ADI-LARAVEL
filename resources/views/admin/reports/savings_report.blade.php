{{-- resources/views/admin/reports/savings_report.blade.php --}}
@extends('layouts.app')

@section('title', __('savings_report'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('savings_report') }}</h1>
        <div>
            <a href="{{ route('admin.reports.loans') }}" class="btn btn-info">
                <i class="fas fa-hand-holding-usd"></i> {{ __('loans_report') }}
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
            <form method="GET" action="{{ route('admin.reports.savings') }}" class="row">
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
                    <label for="user_id">{{ __('member_optional') }}</label>
                    <select class="form-control" id="user_id" name="user_id">
                        <option value="">{{ __('all_members') }}</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->membership_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> {{ __('generate_report') }}
                    </button>
                    <a href="{{ route('admin.reports.savings') }}" class="btn btn-secondary">{{ __('reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('total_deposits') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($summary['total_deposits'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
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
                                {{ __('total_withdrawals') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($summary['total_withdrawals'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
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
                                {{ __('pending_amount') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($summary['pending_amount'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                {{ __('transactions') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['transaction_count'] }} ({{ $summary['approved_count'] }} {{ __('approved') }})
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Trends Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('daily_transaction_trends') }}</h6>
        </div>
        <div class="card-body">
            <canvas id="dailyTrendsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('transaction_details') }}</h6>
            <span class="badge badge-info">{{ __('period') }}: {{ $dateFrom->format('d M Y') }} - {{ $dateTo->format('d M Y') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('date') }}</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('transaction_number') }}</th>
                            <th>{{ __('type') }}</th>
                            <th>{{ __('amount') }}</th>
                            <th>{{ __('balance_after') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('recorded_by') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td>
                                <strong>{{ $transaction->user->name }}</strong>
                                @if($transaction->user->membership_number)
                                    <br><small class="text-muted">{{ $transaction->user->membership_number }}</small>
                                @endif
                            </td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->type_color }}">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </span>
                            </td>
                            <td class="text-right">CDF {{ number_format($transaction->amount, 2) }}</td>
                            <td class="text-right">CDF {{ number_format($transaction->balance_after, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->status_color }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->recorder->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_transactions_found_period') }}</p>
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
        const ctx = document.getElementById('dailyTrendsChart').getContext('2d');

        const dailyData = @json($dailyTotals);

        // Get translations for chart labels
        const depositsLabel = '{{ __('deposits') }}';
        const withdrawalsLabel = '{{ __('withdrawals') }}';
        const ugxLabel = '{{ __('ugx') }}';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => item.date),
                datasets: [{
                    label: depositsLabel,
                    data: dailyData.map(item => item.deposits),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    tension: 0.3,
                    fill: true
                }, {
                    label: withdrawalsLabel,
                    data: dailyData.map(item => item.withdrawals),
                    borderColor: '#36b9cc',
                    backgroundColor: 'rgba(54, 185, 204, 0.1)',
                    tension: 0.3,
                    fill: true
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
                                return ugxLabel + ' ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += ugxLabel + ' ' + context.parsed.y.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
