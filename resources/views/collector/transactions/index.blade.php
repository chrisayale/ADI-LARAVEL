@extends('layouts.app')

@section('title', __('my_transactions'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history"></i> {{ __('my_transactions') }}
        </h1>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('collector.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-money-bill-wave"></i> {{ __('collect_payments') }}
            </a>
            <a href="{{ route('collector.savings.create') }}" class="btn btn-outline-success">
                <i class="fas fa-piggy-bank"></i> {{ __('make_deposit_withdrawal') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('total_loan_collections') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalCollected, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                {{ __('total_deposits') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalDeposits, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
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
                                {{ __('total_withdrawals') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalWithdrawals, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filters') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('collector.transactions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('date_from') }}</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('date_to') }}</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('transaction_type') }}</label>
                    <select class="form-control" name="type">
                        <option value="">{{ __('all_types') }}</option>
                        <option value="loan" {{ request('type') == 'loan' ? 'selected' : '' }}>{{ __('loan_repayments') }}</option>
                        <option value="savings" {{ request('type') == 'savings' ? 'selected' : '' }}>{{ __('savings_transactions') }}</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> {{ __('filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loan Repayment Transactions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('loan_repayment_collections') }}</h6>
            <span class="badge badge-primary">{{ $loanTransactions->total() }} {{ __('records') }}</span>
        </div>
        <div class="card-body">
            @if ($loanTransactions->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-receipt fa-2x text-muted mb-2"></i>
                    <p class="text-muted">{{ __('no_loan_transactions_found') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="loanTransactionsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('member') }}</th>
                                <th>{{ __('loan_ref') }}</th>
                                <th>{{ __('installment') }}</th>
                                <th>{{ __('amount') }}</th>
                                <th>{{ __('payment_method') }}</th>
                                <th>{{ __('reference') }}</th>
                                <th>{{ __('status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loanTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $transaction->user->name }}</strong><br>
                                        <small class="text-muted">{{ $transaction->user->membership_number }}</small>
                                    </td>
                                    <td><code>{{ $transaction->loanRepayment->loanApplication->loan_reference }}</code></td>
                                    <td>#{{ $transaction->loanRepayment->installment_number }}</td>
                                    <td class="font-weight-bold text-success">{{ number_format($transaction->amount, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->payment_method === 'cash' ? 'success' : ($transaction->payment_method === 'mpesa' ? 'info' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->reference_number ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $loanTransactions->links('loan_page') }}
            @endif
        </div>
    </div>

    <!-- Savings Transactions -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('savings_transactions') }}</h6>
            <span class="badge badge-success">{{ $savingsTransactions->total() }} {{ __('records') }}</span>
        </div>
        <div class="card-body">
            @if ($savingsTransactions->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-piggy-bank fa-2x text-muted mb-2"></i>
                    <p class="text-muted">{{ __('no_savings_transactions_found') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="savingsTransactionsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('member') }}</th>
                                <th>{{ __('type') }}</th>
                                <th>{{ __('amount') }}</th>
                                <th>{{ __('balance_after') }}</th>
                                <th>{{ __('payment_method') }}</th>
                                <th>{{ __('reference') }}</th>
                                <th>{{ __('status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($savingsTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $transaction->member->name }}</strong><br>
                                        <small class="text-muted">{{ $transaction->member->membership_number }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->transaction_type === 'deposit' ? 'success' : 'warning' }}">
                                            <i class="fas fa-{{ $transaction->transaction_type === 'deposit' ? 'arrow-down' : 'arrow-up' }}"></i>
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold {{ $transaction->transaction_type === 'deposit' ? 'text-success' : 'text-warning' }}">
                                        {{ number_format($transaction->amount, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
                                    </td>
                                    <td>{{ number_format($transaction->balance_after, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->payment_method === 'cash' ? 'success' : ($transaction->payment_method === 'mpesa' ? 'info' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->transaction_reference ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $savingsTransactions->links('savings_page') }}
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#loanTransactionsTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });

            $('#savingsTransactionsTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        }
    });
</script>
@endpush