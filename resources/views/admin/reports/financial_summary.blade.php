{{-- resources/views/admin/reports/financial-summary.blade.php --}}
@extends('layouts.app')

@section('title', 'Financial Summary')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Financial Summary - {{ $year }}</h1>
        <div>
            <a href="{{ route('admin.reports.savings') }}" class="btn btn-info">
                <i class="fas fa-piggy-bank"></i> Savings Report
            </a>
            <a href="{{ route('admin.reports.loans') }}" class="btn btn-primary">
                <i class="fas fa-hand-holding-usd"></i> Loans Report
            </a>
        </div>
    </div>

    <!-- Year Selection -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.financial-summary') }}" class="row align-items-center">
                <div class="col-md-3">
                    <label for="year">Select Year</label>
                    <select class="form-control" id="year" name="year" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-9 text-right">
                    <a href="{{ route('admin.reports.export') }}?type=financial&year={{ $year }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Export Report
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Members</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totals['total_members'] }}</div>
                            <small class="text-success">{{ $totals['active_members'] }} active</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Total Deposits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totals['total_deposits'], 2) }}
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
                                Total Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totals['total_withdrawals'], 2) }}
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
                                Net Savings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totals['net_savings'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Loans Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totals['total_loans_approved'], 2) }}
                            </div>
                            <small>{{ $totals['total_loans_count'] }} loans</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Active Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totals['active_loans_count'] }}</div>
                            <small>Currently disbursed/approved</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
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
                                Average Loan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totals['total_loans_count'] > 0 ? $totals['total_loans_approved'] / $totals['total_loans_count'] : 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Savings Overview</h6>
                </div>
                <div class="card-body">
                    <canvas id="savingsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Loans Approved</h6>
                </div>
                <div class="card-body">
                    <canvas id="loansChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Summary - {{ $year }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th class="text-right">Deposits (UGX)</th>
                            <th class="text-right">Withdrawals (UGX)</th>
                            <th class="text-right">Net Savings (UGX)</th>
                            <th class="text-right">Loans Approved (UGX)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($months as $index => $month)
                        <tr>
                            <td><strong>{{ $month }}</strong></td>
                            <td class="text-right text-success">
                                {{ number_format($savingsData['deposits'][$index], 2) }}
                            </td>
                            <td class="text-right text-info">
                                {{ number_format($savingsData['withdrawals'][$index], 2) }}
                            </td>
                            <td class="text-right {{ $savingsData['deposits'][$index] - $savingsData['withdrawals'][$index] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($savingsData['deposits'][$index] - $savingsData['withdrawals'][$index], 2) }}
                            </td>
                            <td class="text-right text-primary">
                                {{ number_format($loansData['approved'][$index], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th><strong>Total</strong></th>
                            <th class="text-right text-success">
                                <strong>{{ number_format(array_sum($savingsData['deposits']), 2) }}</strong>
                            </th>
                            <th class="text-right text-info">
                                <strong>{{ number_format(array_sum($savingsData['withdrawals']), 2) }}</strong>
                            </th>
                            <th class="text-right {{ array_sum($savingsData['deposits']) - array_sum($savingsData['withdrawals']) >= 0 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ number_format(array_sum($savingsData['deposits']) - array_sum($savingsData['withdrawals']), 2) }}</strong>
                            </th>
                            <th class="text-right text-primary">
                                <strong>{{ number_format(array_sum($loansData['approved']), 2) }}</strong>
                            </th>
                        </tr>
                    </tfoot>
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
        // Savings Chart
        const savingsCtx = document.getElementById('savingsChart').getContext('2d');
        const months = @json($months);
        const savingsData = @json($savingsData);

        new Chart(savingsCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Deposits',
                    data: savingsData.deposits,
                    backgroundColor: '#1cc88a',
                    borderRadius: 4
                }, {
                    label: 'Withdrawals',
                    data: savingsData.withdrawals,
                    backgroundColor: '#36b9cc',
                    borderRadius: 4
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
                                return 'UGX ' + (value / 1000000).toFixed(1) + 'M';
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
                                    label += 'UGX ' + context.parsed.y.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Loans Chart
        const loansCtx = document.getElementById('loansChart').getContext('2d');

        new Chart(loansCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Loans Approved',
                    data: @json($loansData['approved']),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
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
                                return 'UGX ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
