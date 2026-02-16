{{-- resources/views/treasurer/repayments/assigned.blade.php --}}
@extends('layouts.app')

@section('title', 'Assigned Loans')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assigned Loans</h1>
        <div>
            <a href="{{ route('treasurer.repayments.collect') }}" class="btn btn-success">
                <i class="fas fa-hand-holding-usd"></i> Collect Payment
            </a>
            <a href="{{ route('treasurer.repayments.index') }}" class="btn btn-info">
                <i class="fas fa-history"></i> Payment History
            </a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Loans</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.repayments.assigned') }}" class="row">
                <div class="col-md-8 mb-3">
                    <label for="search">Search by Member Name or Membership Number</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Enter name or membership number...">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loans Assigned to You</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application #</th>
                            <th>Member</th>
                            <th>Amount Approved</th>
                            <th>Monthly Payment</th>
                            <th>Balance</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                        <tr>
                            <td>{{ $loans->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $loan->application_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($loan->user->profile_photo)
                                        <img src="{{ asset('storage/' . $loan->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="30" height="30" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $loan->user->name }}</strong>
                                        <br><small class="text-muted">{{ $loan->user->membership_number ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <strong>UGX {{ number_format($loan->amount_approved, 2) }}</strong>
                            </td>
                            <td class="text-right">UGX {{ number_format($loan->monthly_repayment, 2) }}</td>
                            <td class="text-right {{ $loan->balance > 0 ? 'text-danger' : 'text-success' }}">
                                <strong>UGX {{ number_format($loan->balance, 2) }}</strong>
                            </td>
                            <td style="min-width: 150px;">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $loan->progress_percentage }}%"></div>
                                </div>
                                <small class="text-muted">{{ $loan->progress_percentage }}% paid</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $loan->status == 'disbursed' ? 'primary' : 'warning' }} p-2">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('treasurer.repayments.loan-repayments', $loan) }}"
                                   class="btn btn-sm btn-info" title="View Repayments">
                                    <i class="fas fa-calendar-alt"></i> Schedule
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No loans assigned to you</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $loans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
