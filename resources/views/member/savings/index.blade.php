@extends('layouts.app')

@section('title', 'My Savings')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Savings</h1>
        <div>
            <a href="{{ route('member.savings.transactions') }}" class="btn btn-info">
                <i class="fas fa-history"></i> Transaction History
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Savings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totalSavings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
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
                                Pending Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($pendingSavings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Total Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentTransactions->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Activity ({{ date('Y') }})</h6>
        </div>
        <div class="card-body">
            <canvas id="savingsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
            <a href="{{ route('member.savings.transactions') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $transaction->receipt_number ?? $transaction->transaction_number }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->type_color }}">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </span>
                            </td>
                            <td class="text-right">CDF {{ number_format($transaction->amount, 2) }}</td>
                            <td class="text-right">CDF {{ number_format($transaction->balance_after, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->status_color }}">
                                    {{ translate_status($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">No transactions yet</td>
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
        const ctx = document.getElementById('savingsChart').getContext('2d');
        const monthlyData = @json($monthlyData);

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const deposits = new Array(12).fill(0);
        const withdrawals = new Array(12).fill(0);

        monthlyData.forEach(item => {
            deposits[item.month - 1] = item.deposits;
            withdrawals[item.month - 1] = item.withdrawals;
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Deposits',
                    data: deposits,
                    backgroundColor: '#1cc88a',
                    borderRadius: 4
                }, {
                    label: 'Withdrawals',
                    data: withdrawals,
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
                                return 'CDF ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
