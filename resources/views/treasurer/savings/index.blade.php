{{-- resources/views/treasurer/savings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Savings Transactions')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Savings Transactions</h1>
        <div>
            <a href="{{ route('treasurer.savings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Transaction
            </a>
            <a href="{{ route('treasurer.savings.deposits') }}" class="btn btn-success">
                <i class="fas fa-arrow-down"></i> Deposits
            </a>
            <a href="{{ route('treasurer.savings.withdrawals') }}" class="btn btn-info">
                <i class="fas fa-arrow-up"></i> Withdrawals
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
                                Total Deposits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totals['total_deposits'], 2) }}</div>
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
                                Total Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totals['total_withdrawals'], 2) }}</div>
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
                                Today's Transactions</div>
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
            <h6 class="m-0 font-weight-bold text-primary">Filter Transactions</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.savings.transactions') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership #">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type">Transaction Type</label>
                    <select class="form-control" id="type" name="type">
                        <option value="all">All Types</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposits</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawals</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_from">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_to">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Receipt #</th>
                            <th>Member</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Date</th>
                            <th>Actions</th>
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
                                <strong>UGX {{ number_format($transaction->amount, 2) }}</strong>
                            </td>
                            <td class="text-right">UGX {{ number_format($transaction->balance_after, 2) }}</td>
                            <td>
                                {{ $transaction->transaction_date->format('d M Y') }}
                                <br><small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('treasurer.savings.show', $transaction) }}"
                                   class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('treasurer.savings.print-receipt', $transaction) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-secondary" title="Print Receipt">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No transactions found</p>
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
