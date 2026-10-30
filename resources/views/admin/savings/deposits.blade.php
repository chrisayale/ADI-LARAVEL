@extends('layouts.app')

@section('title', __('deposits'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('deposits') }}</h1>
        <div>
            <a href="{{ route('admin.savings.create') }}?type=deposit" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('new_deposit') }}
            </a>
            <a href="{{ route('admin.savings.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> {{ __('all_transactions') }}
            </a>
            <a href="{{ route('admin.savings.withdrawals') }}" class="btn btn-info">
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totalDeposits, 2) }}</div>
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
                                {{ __('todays_deposits') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($todayDeposits, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                {{ __('average_deposit') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($totalDeposits / max($deposits->total(), 1), 2) }}
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

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_deposits') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.savings.deposits') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">{{ __('search_member') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('name_or_membership') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">{{ __('status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">{{ __('all_status') }}</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ translate_status('approved') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ translate_status('pending') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ translate_status('rejected') }}</option>
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
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deposits Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('deposit_transactions') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('transaction_number') }}</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('amount') }}</th>
                            <th>{{ __('balance_after') }}</th>
                            <th>{{ __('date') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('recorded_by') }}</th>
                            <th>{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $index => $deposit)
                        <tr>
                            <td>{{ $deposits->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('admin.savings.show', $deposit) }}" class="font-weight-bold">
                                    {{ $deposit->transaction_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($deposit->user->profile_photo)
                                        <img src="{{ asset('storage/' . $deposit->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="30" height="30" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($deposit->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $deposit->user->name }}</strong>
                                        @if($deposit->user->membership_number)
                                            <br><small class="text-muted">#{{ $deposit->user->membership_number }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">CDF {{ number_format($deposit->amount, 2) }}</strong>
                            </td>
                            <td class="text-right">CDF {{ number_format($deposit->balance_after, 2) }}</td>
                            <td>{{ $deposit->transaction_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $deposit->status_color }} p-2">
                                    {{ translate_status($deposit->status) }}
                                </span>
                            </td>
                            <td>{{ $deposit->recorder->name }}</td>
                            <td>
                                <a href="{{ route('admin.savings.show', $deposit) }}" class="btn btn-sm btn-info" title="{{ __('view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-arrow-down fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_deposits_found') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $deposits->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection