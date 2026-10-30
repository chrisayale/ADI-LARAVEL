{{-- resources/views/member/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', __('member_dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('member_dashboard') }}</h1>
        <div>
            <a href="{{ route('member.loans.apply') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> {{ __('apply_for_loan') }}
            </a>
            <a href="{{ route('member.savings.my-savings') }}" class="btn btn-info btn-sm">
                <i class="fas fa-piggy-bank"></i> {{ __('my_savings') }}
            </a>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="alert alert-primary mb-4">
        <h4 class="alert-heading">{{ __('welcome_back', ['name' => $user->name]) }}</h4>
        <p class="mb-0">{{ __('account_activity') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('total_savings_short') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('ugx') }} {{ number_format($stats['total_savings'], 2) }}</div>
                            <small class="text-muted">{{ __('pending') }}: {{ __('ugx') }} {{ number_format($stats['pending_savings'], 2) }}</small>
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
                                {{ __('active_loans') }}</div>
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
                                {{ __('pending_loans_short') }}</div>
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
                                {{ __('total_loans') }}</div>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_savings_transactions') }}</h6>
                    <a href="{{ route('member.savings.transactions') }}" class="btn btn-sm btn-primary">{{ __('view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('balance') }}</th>
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
                                    <td class="text-right">{{ __('ugx') }} {{ number_format($transaction->amount, 2) }}</td>
                                    <td class="text-right">{{ __('ugx') }} {{ number_format($transaction->balance_after, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">{{ __('no_data') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_loan_applications_short') }}</h6>
                    <a href="{{ route('member.loans.my-loans') }}" class="btn btn-sm btn-primary">{{ __('view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('application_no') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('status') }}</th>
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
                                    <td class="text-right">{{ __('ugx') }} {{ number_format($loan->amount_applied, 2) }}</td>
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
                                    <td colspan="4" class="text-center py-3">{{ __('no_data') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('upcoming_repayments') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('loan_no') }}</th>
                                    <th>{{ __('installment') }}</th>
                                    <th>{{ __('due_date') }}</th>
                                    <th>{{ __('amount_due') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('action') }}</th>
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
                                    <td>{{ __('installment') }} #{{ $item['repayment']->installment_number }}</td>
                                    <td>
                                        {{ $item['repayment']->due_date->format('d M Y') }}
                                        @if($item['repayment']->due_date < now())
                                            <span class="badge badge-danger">{{ __('overdue_badge') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ __('ugx') }} {{ number_format($item['repayment']->amount_due - $item['repayment']->amount_paid, 2) }}</td>
                                    <td>
                                        @if($item['repayment']->status == 'pending')
                                            <span class="badge badge-secondary">{{ __('pending') }}</span>
                                        @elseif($item['repayment']->status == 'partial')
                                            <span class="badge badge-warning">{{ __('partial') }}</span>
                                        @elseif($item['repayment']->status == 'paid')
                                            <span class="badge badge-success">{{ __('paid') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('member.loans.repayment-schedule', $item['loan']) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-calendar"></i> {{ __('view_schedule') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">{{ __('no_upcoming_repayments') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('quick_actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('member.loans.apply') }}" class="btn btn-outline-primary btn-block p-3">
                                <i class="fas fa-file-invoice fa-2x mb-2"></i>
                                <br>{{ __('apply_for_loan') }}
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('member.savings.transactions') }}" class="btn btn-outline-success btn-block p-3">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <br>{{ __('transaction_history') }}
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('member.loans.my-loans') }}" class="btn btn-outline-info btn-block p-3">
                                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                <br>{{ __('my_loans') }}
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-block p-3">
                                <i class="fas fa-user fa-2x mb-2"></i>
                                <br>{{ __('my_profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
