@extends('layouts.app')

@section('title', 'All Loan Applications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">All Loan Applications</h1>
        <div>
            <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Loan Application
            </a>
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
                                Total Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applications->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
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
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount ?? 0 }}</div>
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
                                Under Verification</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $verificationCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
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
                                UGX {{ number_format($totalAmount ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Applications</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.applications') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership Number...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">Application Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_verification" {{ request('status') == 'under_verification' ? 'selected' : '' }}>Under Verification</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_range">Date Range</label>
                    <select class="form-control" id="date_range" name="date_range">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.loans.applications') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Applications List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No.</th>
                            <th>Member</th>
                            <th>Membership No.</th>
                            <th>Amount Applied</th>
                            <th>Amount Approved</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Treasurer</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $application)
                        <tr>
                            <td>{{ $applications->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $application->application_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($application->user->profile_photo)
                                        <img src="{{ asset('storage/' . $application->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="40" height="40" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($application->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $application->user->name }}</strong><br>
                                        <small class="text-muted">{{ $application->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $application->user->membership_number ?? 'N/A' }}</td>
                            <td>UGX {{ number_format($application->amount_applied, 2) }}</td>
                            <td>
                                @if($application->amount_approved)
                                    UGX {{ number_format($application->amount_approved, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span title="{{ $application->purpose }}">
                                    {{ Str::limit($application->purpose, 30) }}
                                </span>
                            </td>
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
                                        'defaulted' => 'danger'
                                    ];
                                    $color = $statusColors[$application->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $color }} px-3 py-2">
                                    {{ str_replace('_', ' ', ucfirst($application->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($application->treasurer)
                                    {{ $application->treasurer->name }}
                                @else
                                    <span class="text-muted">Not Assigned</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $application) }}" class="btn btn-info btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($application->status == 'pending')
                                    <button type="button" class="btn btn-primary btn-sm" title="Assign Treasurer"
                                            onclick="openAssignTreasurerModal({{ $application->id }})">
                                        <i class="fas fa-user-tie"></i>
                                    </button>
                                @endif

                                @if($application->status == 'approved')
                                    <form action="{{ route('admin.loans.disburse', $application) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Disburse Loan"
                                                onclick="return confirm('Are you sure you want to disburse this loan?')">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">No applications found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $applications->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Assign Treasurer Modal -->
<div class="modal fade" id="assignTreasurerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="assignTreasurerForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Treasurer</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="treasurer_id">Select Treasurer</label>
                        <select class="form-control" id="treasurer_id" name="treasurer_id" required>
                            <option value="">Choose Treasurer...</option>
                            @foreach($treasurers ?? [] as $treasurer)
                                <option value="{{ $treasurer->id }}">{{ $treasurer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openAssignTreasurerModal(loanId) {
        const form = document.getElementById('assignTreasurerForm');
        form.action = `/admin/loans/${loanId}/assign-treasurer`;
        $('#assignTreasurerModal').modal('show');
    }

    // Auto-submit form when filters change
    document.getElementById('status')?.addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('date_range')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
