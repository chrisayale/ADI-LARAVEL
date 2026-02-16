@extends('layouts.app')

@section('title', 'Loan Defaulters')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Defaulters</h1>
        <div>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <button type="button" class="btn btn-primary" onclick="exportDefaulterList()">
                <i class="fas fa-download"></i> Export List
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Defaulters</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDefaulters ?? $defaulters->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Total Overdue Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totalOverdue ?? 0, 2) }}
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
                                Average Overdue Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ round($avgOverdueDays ?? 0) }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Highest Risk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $highRiskCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Defaulters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.defaulters') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">Search Member</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Name or Membership No...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">Follow-up Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="follow_up_1" {{ request('status') == 'follow_up_1' ? 'selected' : '' }}>First Follow-up</option>
                        <option value="follow_up_2" {{ request('status') == 'follow_up_2' ? 'selected' : '' }}>Second Follow-up</option>
                        <option value="final_notice" {{ request('status') == 'final_notice' ? 'selected' : '' }}>Final Notice</option>
                        <option value="legal_action" {{ request('status') == 'legal_action' ? 'selected' : '' }}>Legal Action</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="assigned_to">Assigned To</label>
                    <select class="form-control" id="assigned_to" name="assigned_to">
                        <option value="all">All Treasurers</option>
                        @foreach($treasurers ?? [] as $treasurer)
                            <option value="{{ $treasurer->id }}" {{ request('assigned_to') == $treasurer->id ? 'selected' : '' }}>
                                {{ $treasurer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.loans.defaulters') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Defaulters Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Defaulters List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Loan Details</th>
                            <th>Overdue Amount</th>
                            <th>Days Overdue</th>
                            <th>Last Payment</th>
                            <th>Follow-up Status</th>
                            <th>Assigned To</th>
                            <th>Last Follow-up</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($defaulters as $index => $defaulter)
                        <tr>
                            <td>{{ $defaulters->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($defaulter->user->profile_photo)
                                        <img src="{{ asset('storage/' . $defaulter->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="40" height="40" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($defaulter->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $defaulter->user->name }}</strong><br>
                                        <small class="text-muted">{{ $defaulter->user->membership_number ?? 'N/A' }}</small>
                                        <br>
                                        <small>{{ $defaulter->user->phone_number ?? 'No phone' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $defaulter->loanApplication->application_number }}</strong><br>
                                <small>Amount: UGX {{ number_format($defaulter->loanApplication->amount_approved, 2) }}</small><br>
                                <small>Monthly: UGX {{ number_format($defaulter->loanApplication->monthly_repayment, 2) }}</small>
                            </td>
                            <td>
                                <span class="badge badge-danger px-3 py-2">
                                    UGX {{ number_format($defaulter->amount_overdue, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $defaulter->days_overdue >= 90 ? 'danger' : ($defaulter->days_overdue >= 60 ? 'warning' : 'info') }} px-3 py-2">
                                    {{ $defaulter->days_overdue }} days
                                </span>
                            </td>
                            <td>
                                @php
                                    $lastRepayment = $defaulter->loanApplication->repayments()
                                        ->where('amount_paid', '>', 0)
                                        ->latest('paid_date')
                                        ->first();
                                @endphp
                                @if($lastRepayment)
                                    {{ $lastRepayment->paid_date->format('d M Y') }}<br>
                                    <small>UGX {{ number_format($lastRepayment->amount_paid, 2) }}</small>
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'new' => 'danger',
                                        'follow_up_1' => 'warning',
                                        'follow_up_2' => 'warning',
                                        'final_notice' => 'danger',
                                        'legal_action' => 'dark'
                                    ];
                                    $statusLabels = [
                                        'new' => 'New',
                                        'follow_up_1' => '1st Follow-up',
                                        'follow_up_2' => '2nd Follow-up',
                                        'final_notice' => 'Final Notice',
                                        'legal_action' => 'Legal Action'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$defaulter->status] ?? 'secondary' }} px-3 py-2">
                                    {{ $statusLabels[$defaulter->status] ?? ucfirst($defaulter->status) }}
                                </span>
                            </td>
                            <td>
                                @if($defaulter->assignedTo)
                                    {{ $defaulter->assignedTo->name }}
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @if($defaulter->last_follow_up)
                                    {{ $defaulter->last_follow_up->format('d M Y') }}
                                    @if($defaulter->next_follow_up)
                                        <br>
                                        <small>Next: {{ $defaulter->next_follow_up->format('d M Y') }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Not followed up</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm mb-1" title="Follow Up"
                                        onclick="openFollowUpModal({{ $defaulter->id }})">
                                    <i class="fas fa-phone-alt"></i>
                                </button>

                                <button type="button" class="btn btn-info btn-sm mb-1" title="Assign"
                                        onclick="openAssignModal({{ $defaulter->id }})">
                                    <i class="fas fa-user-tie"></i>
                                </button>

                                <a href="{{ route('admin.loans.show', $defaulter->loanApplication) }}"
                                   class="btn btn-info btn-sm mb-1" title="View Loan">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">No defaulters found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $defaulters->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Follow Up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="followUpForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Follow-up</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="follow_up_notes">Follow-up Notes</label>
                        <textarea class="form-control" id="follow_up_notes" name="follow_up_notes"
                                  rows="4" required placeholder="Enter details of the follow-up..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="next_follow_up">Next Follow-up Date</label>
                        <input type="date" class="form-control" id="next_follow_up" name="next_follow_up"
                               min="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Follow-up</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Treasurer Modal -->
<div class="modal fade" id="assignDefaulterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="assignDefaulterForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Defaulter for Follow-up</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="assigned_to">Select Treasurer</label>
                        <select class="form-control" id="assigned_to" name="assigned_to" required>
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
    function openFollowUpModal(defaulterId) {
        const form = document.getElementById('followUpForm');
        form.action = `/admin/loans/defaulters/${defaulterId}/follow-up`;
        $('#followUpModal').modal('show');
    }

    function openAssignModal(defaulterId) {
        const form = document.getElementById('assignDefaulterForm');
        form.action = `/admin/loans/defaulters/${defaulterId}/assign`;
        $('#assignDefaulterModal').modal('show');
    }

    function exportDefaulterList() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '{{ route("admin.loans.export-report") }}?type=defaulters&' + params.toString();
    }

    // Auto-submit form when filters change
    document.getElementById('status')?.addEventListener('change', function() {
        this.form.submit();
    });
    document.getElementById('assigned_to')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
