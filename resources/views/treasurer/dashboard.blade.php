{{-- resources/views/treasurer/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', __('treasurer_dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('treasurer_dashboard') }}</h1>
        <div>
            <a href="{{ route('treasurer.savings.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> {{ __('record_transaction') }}
            </a>
            <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-info btn-sm">
                <i class="fas fa-check-circle"></i> {{ __('pending_verifications') }}
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
                                {{ __('assigned_loans') }}</div>
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
                                {{ __('pending_verification') }}</div>
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
                                {{ __('verified_loans') }}</div>
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
                                {{ __('todays_transactions') }}</div>
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
                                {{ __('todays_deposits') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('ugx') }} {{ number_format($stats['today_deposits'] ?? 0, 0) }}</div>
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
                                {{ __('todays_withdrawals') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('ugx') }} {{ number_format($stats['today_withdrawals'] ?? 0, 0) }}</div>
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
                                {{ __('overdue_repayments') }}</div>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('loans_to_verify') }}</h6>
                    <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-sm btn-primary">{{ __('view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('member') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('applied') }}</th>
                                    <th>{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLoans->where('status', 'under_verification') as $loan)
                                <tr>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        <br><small class="text-muted">{{ $loan->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ __('ugx') }} {{ number_format($loan->amount_applied, 0) }}</td>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('treasurer.verifications.show', $loan) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{ __('verify') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">{{ __('no_loans_pending_verification') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_transactions') }}</h6>
                    <a href="{{ route('treasurer.savings.transactions') }}" class="btn btn-sm btn-primary">{{ __('view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('member') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('date') }}</th>
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
                                    <td>{{ __('ugx') }} {{ number_format($transaction->amount, 0) }}</td>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">{{ __('no_recent_transactions') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-danger">{{ __('assigned_defaulters') }}</h6>
                    <a href="{{ route('treasurer.loans.repayments') }}" class="btn btn-sm btn-danger">{{ __('manage') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('member') }}</th>
                                    <th>{{ __('loan_amount') }}</th>
                                    <th>{{ __('overdue') }}</th>
                                    <th>{{ __('days') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($defaulters as $defaulter)
                                <tr>
                                    <td>
                                        <strong>{{ $defaulter->user->name }}</strong>
                                        <br><small class="text-muted">{{ $defaulter->user->membership_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ __('ugx') }} {{ number_format($defaulter->loanApplication->amount_approved ?? 0, 0) }}</td>
                                    <td class="text-danger">{{ __('ugx') }} {{ number_format($defaulter->amount_overdue, 0) }}</td>
                                    <td><span class="badge badge-danger">{{ $defaulter->days_overdue }} {{ __('days') }}</span></td>
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
                                            <i class="fas fa-eye"></i> {{ __('view') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">{{ __('no_defaulters_assigned') }}</td>
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
