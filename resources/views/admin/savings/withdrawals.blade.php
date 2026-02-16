@extends('layouts.app')

@section('title', 'Withdrawals')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Withdrawals</h1>
        <div>
            <a href="{{ route('admin.savings.create') }}?type=withdrawal" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Withdrawal
            </a>
            <a href="{{ route('admin.savings.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> All Transactions
            </a>
            <a href="{{ route('admin.savings.deposits') }}" class="btn btn-success">
                <i class="fas fa-arrow-down"></i> Deposits
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totalWithdrawals, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
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
                                Today's Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($todayWithdrawals, 2) }}</div>
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
                                Average Withdrawal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totalWithdrawals / max($withdrawals->total(), 1), 2) }}
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
            <h6 class="m-0 font-weight-bold text-primary">Filter Withdrawals</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.savings.withdrawals') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership #">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Withdrawal Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Transaction #</th>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $index => $withdrawal)
                        <tr>
                            <td>{{ $withdrawals->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('admin.savings.show', $withdrawal) }}" class="font-weight-bold">
                                    {{ $withdrawal->transaction_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($withdrawal->user->profile_photo)
                                        <img src="{{ asset('storage/' . $withdrawal->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="30" height="30" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($withdrawal->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $withdrawal->user->name }}</strong>
                                        @if($withdrawal->user->membership_number)
                                            <br><small class="text-muted">#{{ $withdrawal->user->membership_number }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <strong class="text-info">UGX {{ number_format($withdrawal->amount, 2) }}</strong>
                            </td>
                            <td class="text-right">UGX {{ number_format($withdrawal->balance_after, 2) }}</td>
                            <td>{{ $withdrawal->transaction_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $withdrawal->status_color }} p-2">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td>{{ $withdrawal->recorder->name }}</td>
                            <td>
                                <a href="{{ route('admin.savings.show', $withdrawal) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-arrow-up fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No withdrawals found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $withdrawals->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
