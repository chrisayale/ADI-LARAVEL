@extends('layouts.app')

@section('title', 'Disbursed Loans')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Disbursed Loans</h1>
        <div>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Disbursed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoans ?? $disbursedLoans->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
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
                                Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totalDisbursed ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Total Repaid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totalRepaid ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Outstanding</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($outstandingBalance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Disbursed Loans</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.disbursed') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership Number...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="repayment_status">Repayment Status</label>
                    <select class="form-control" id="repayment_status" name="repayment_status">
                        <option value="all">All</option>
                        <option value="on_track" {{ request('repayment_status') == 'on_track' ? 'selected' : '' }}>On Track</option>
                        <option value="overdue" {{ request('repayment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="completed" {{ request('repayment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="disbursed_date">Disbursed Date</label>
                    <input type="month" class="form-control" id="disbursed_date" name="disbursed_date"
                           value="{{ request('disbursed_date') }}">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.loans.disbursed') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Disbursed Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Disbursed Loans List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No.</th>
                            <th>Member</th>
                            <th>Amount Disbursed</th>
                            <th>Interest Rate</th>
                            <th>Duration</th>
                            <th>Monthly Payment</th>
                            <th>Total Repayable</th>
                            <th>Disbursed Date</th>
                            <th>Amount Repaid</th>
                            <th>Balance</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disbursedLoans as $index => $loan)
                        @php
                            $totalPaid = $loan->repayments->sum('amount_paid');
                            $balance = $loan->total_repayable - $totalPaid;
                            $progressPercentage = $loan->total_repayable > 0 ? round(($totalPaid / $loan->total_repayable) * 100, 1) : 0;

                            // Check if loan is overdue
                            $hasOverdue = $loan->repayments->filter(function($repayment) {
                                return $repayment->status == 'pending' && $repayment->due_date < now();
                            })->isNotEmpty();
                        @endphp
                        <tr class="{{ $hasOverdue ? 'table-warning' : '' }}">
                            <td>{{ $disbursedLoans->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $loan->application_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($loan->user->profile_photo)
                                        <img src="{{ asset('storage/' . $loan->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="40" height="40" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $loan->user->name }}</strong><br>
                                        <small class="text-muted">{{ $loan->user->membership_number ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><strong>UGX {{ number_format($loan->amount_approved, 2) }}</strong></td>
                            <td>{{ $loan->interest_rate }}%</td>
                            <td>{{ $loan->duration_months }} months</td>
                            <td>UGX {{ number_format($loan->monthly_repayment, 2) }}</td>
                            <td>UGX {{ number_format($loan->total_repayable, 2) }}</td>
                            <td>{{ $loan->disbursed_at->format('d M Y') }}</td>
                            <td>UGX {{ number_format($totalPaid, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $balance > 0 ? 'warning' : 'success' }} px-3 py-2">
                                    UGX {{ number_format($balance, 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $progressPercentage }}%;"
                                         aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $progressPercentage }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-info btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($balance == 0)
                                <form action="{{ route('admin.loans.mark-completed', $loan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Mark as Completed"
                                            onclick="return confirm('Mark this loan as completed?')">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center py-4">No disbursed loans found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $disbursedLoans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when filter changes
    document.getElementById('repayment_status')?.addEventListener('change', function() {
        this.form.submit();
    });
    document.getElementById('disbursed_date')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
