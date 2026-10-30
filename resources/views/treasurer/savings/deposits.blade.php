{{-- resources/views/treasurer/savings/deposits.blade.php --}}
@extends('layouts.app')

@section('title', 'Deposits')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Deposits</h1>
        <div>
            <a href="{{ route('treasurer.savings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Deposit
            </a>
            <a href="{{ route('treasurer.savings.transactions') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> All Transactions
            </a>
            <a href="{{ route('treasurer.savings.withdrawals') }}" class="btn btn-info">
                <i class="fas fa-arrow-up"></i> Withdrawals
            </a>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Deposits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totalDeposits, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Deposits</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.savings.deposits') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership #">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_from">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 mb-3">
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

    <!-- Deposits Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Deposit Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Receipt #</th>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $index => $deposit)
                        <tr>
                            <td>{{ $deposits->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('treasurer.savings.show', $deposit) }}" class="font-weight-bold">
                                    {{ $deposit->receipt_number }}
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
                                <a href="{{ route('treasurer.savings.show', $deposit) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('treasurer.savings.print-receipt', $deposit) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-secondary">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-arrow-down fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No deposits found</p>
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
