@extends('layouts.app')

@section('title', 'Manage Loan Applications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Applications</h1>
        <div>
            <a href="{{ route('admin.loans.applications') }}" class="btn btn-info mr-2">
                <i class="fas fa-file-alt"></i> All Applications
            </a>
            <a href="{{ route('admin.loans.approved') }}" class="btn btn-success mr-2">
                <i class="fas fa-check-circle"></i> Approved
            </a>
            <a href="{{ route('admin.loans.disbursed') }}" class="btn btn-primary mr-2">
                <i class="fas fa-hand-holding-usd"></i> Disbursed
            </a>
            <a href="{{ route('admin.loans.defaulters') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> Defaulters
            </a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Loans</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.index') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership Number...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_verification" {{ request('status') == 'under_verification' ? 'selected' : '' }}>Under Verification</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_from">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLoans ?? $loans->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
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

        <div class="col-xl-2 col-md-4 mb-4">
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

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Disbursed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $disbursedCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Defaulters</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $defaultedCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loan Applications List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Membership No.</th>
                            <th>Amount Applied</th>
                            <th>Amount Approved</th>
                            <th>Interest Rate</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Treasurer</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                        <tr>
                            <td>{{ $loans->firstItem() + $index }}</td>
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
                                        <small class="text-muted">{{ $loan->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $loan->user->membership_number ?? 'N/A' }}</td>
                            <td>UGX {{ number_format($loan->amount_applied, 2) }}</td>
                            <td>
                                @if($loan->amount_approved)
                                    UGX {{ number_format($loan->amount_approved, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->interest_rate)
                                    {{ $loan->interest_rate }}%
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->duration_months)
                                    {{ $loan->duration_months }} months
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                           <td>
    @php
        $statusColors = [
            'pending' => 'secondary',
            'under_verification' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'disbursed' => 'primary',
            'completed' => 'success',
            'defaulted' => 'danger'
        ];
        $color = $statusColors[$loan->status] ?? 'secondary';
    @endphp

    <span class="badge bg-{{ $color }} px-3 py-2">
        {{ ucfirst($loan->status) }}
    </span>
</td>


                            <td>
                                @if($loan->treasurer)
                                    {{ $loan->treasurer->name }}
                                @else
                                    <span class="text-muted">Not Assigned</span>
                                @endif
                            </td>
                            <td>{{ $loan->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-info btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($loan->status == 'pending')
                                    <button type="button" class="btn btn-primary btn-sm" title="Assign Treasurer"
                                            onclick="openAssignTreasurerModal({{ $loan->id }})">
                                        <i class="fas fa-user-tie"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">No loan applications found</td>
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

    // Auto-submit form when status filter changes
    document.getElementById('status')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
