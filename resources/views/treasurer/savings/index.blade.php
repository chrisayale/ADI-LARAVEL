{{-- resources/views/treasurer/savings/index.blade.php --}}
@extends('layouts.app')

@section('title', __('savings_transactions'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('savings_transactions') }}</h1>
        <div>
            <a href="{{ route('treasurer.savings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('new_transaction') }}
            </a>
            <a href="{{ route('treasurer.savings.deposits') }}" class="btn btn-success">
                <i class="fas fa-arrow-down"></i> {{ __('deposits') }}
            </a>
            <a href="{{ route('treasurer.savings.withdrawals') }}" class="btn btn-info">
                <i class="fas fa-arrow-up"></i> {{ __('withdrawals') }}
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
                                {{ __('total_deposits') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totals['total_deposits'], 2) }}</div>
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
                                {{ __('total_withdrawals') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totals['total_withdrawals'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('todays_transactions') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totals['today_count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_transactions') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.savings.transactions') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">{{ __('search_member') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('name_or_membership_number') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type">{{ __('transaction_type') }}</label>
                    <select class="form-control" id="type" name="type">
                        <option value="all">{{ __('all_types') }}</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>{{ __('deposits') }}</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>{{ __('withdrawals') }}</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_from">{{ __('date_from') }}</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_to">{{ __('date_to') }}</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> {{ __('filter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('all_transactions') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('receipt_number') }}</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('type') }}</th>
                            <th>{{ __('amount') }}</th>
                            <th>{{ __('balance_after') }}</th>
                            <th>{{ __('date') }}</th>
                            <th>{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('treasurer.savings.show', $transaction) }}" class="font-weight-bold">
                                    {{ $transaction->receipt_number ?? $transaction->transaction_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($transaction->user->profile_photo)
                                        <img src="{{ asset('storage/' . $transaction->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="30" height="30" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($transaction->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $transaction->user->name }}</strong>
                                        @if($transaction->user->membership_number)
                                            <br><small class="text-muted">#{{ $transaction->user->membership_number }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $transaction->type_color }} p-2">
                                    <i class="fas fa-{{ $transaction->type_icon }} mr-1"></i>
                                    {{ ucfirst($transaction->transaction_type) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <strong>CDF {{ number_format($transaction->amount, 2) }}</strong>
                            </td>
                            <td class="text-right">CDF {{ number_format($transaction->balance_after, 2) }}</td>
                            <td>
                                {{ $transaction->transaction_date->format('d M Y') }}
                                <br><small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('treasurer.savings.show', $transaction) }}"
                                   class="btn btn-sm btn-info" title="{{ __('view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('treasurer.savings.print-receipt', $transaction) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-secondary" title="{{ __('print_receipt') }}">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_transactions_found') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection