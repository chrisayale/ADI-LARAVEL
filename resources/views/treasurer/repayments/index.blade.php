{{-- resources/views/treasurer/repayments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Loan Repayments')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Repayments</h1>
        <div>
            <a href="{{ route('treasurer.repayments.collect') }}" class="btn btn-success">
                <i class="fas fa-hand-holding-usd"></i> Collect Payment
            </a>
            <a href="{{ route('treasurer.repayments.assigned') }}" class="btn btn-info">
                <i class="fas fa-list"></i> Assigned Loans
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Collections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totalCollected, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('treasurer.repayments.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="user_id">Member</label>
                    <select class="form-control" id="user_id" name="user_id">
                        <option value="">All Members</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->membership_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
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
            <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Receipt #</th>
                            <th>Member</th>
                            <th>Loan #</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $transaction->receipt_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($transaction->user->profile_photo)
                                        <img src="{{ asset('storage/' . $transaction->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="30" height="30" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($transaction->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $transaction->user->name }}</strong>
                                        <br><small class="text-muted">{{ $transaction->user->membership_number ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($transaction->loanRepayment && $transaction->loanRepayment->loanApplication)
                                    {{ $transaction->loanRepayment->loanApplication->application_number }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-right">
                                <strong>UGX {{ number_format($transaction->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-{{
                                    $transaction->payment_method == 'cash' ? 'success' :
                                    ($transaction->payment_method == 'bank_transfer' ? 'info' : 'warning')
                                }} p-2">
                                    {{ str_replace('_', ' ', ucfirst($transaction->payment_method)) }}
                                </span>
                            </td>
                            <td>{{ $transaction->payment_date->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('treasurer.repayments.receipt', $transaction) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-info" title="Print Receipt">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No payment transactions found</p>
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
