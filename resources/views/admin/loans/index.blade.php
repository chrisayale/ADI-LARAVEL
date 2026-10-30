@extends('layouts.app')

@section('title', __('manage_loan_applications'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('loan_applications') }}</h1>
        <div>
            <a href="{{ route('admin.loans.applications') }}" class="btn btn-info mr-2">
                <i class="fas fa-file-alt"></i> {{ __('all_applications') }}
            </a>
            <a href="{{ route('admin.loans.approved') }}" class="btn btn-success mr-2">
                <i class="fas fa-check-circle"></i> {{ __('approved') }}
            </a>
            <a href="{{ route('admin.loans.disbursed') }}" class="btn btn-primary mr-2">
                <i class="fas fa-hand-holding-usd"></i> {{ __('disbursed') }}
            </a>
            <a href="{{ route('admin.loans.defaulters') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> {{ __('defaulters') }}
            </a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_loans') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.index') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">{{ __('search_member') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('search_member_placeholder') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">{{ __('status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">{{ __('all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('pending') }}</option>
                        <option value="under_verification" {{ request('status') == 'under_verification' ? 'selected' : '' }}>{{ __('under_verification') }}</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('approved') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('rejected') }}</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>{{ __('disbursed') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('completed') }}</option>
                        <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>{{ __('defaulted') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_from">{{ __('date_from') }}</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> {{ __('filter') }}
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
                                {{ __('total_loans') }}</div>
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
                                {{ __('pending') }}</div>
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
                                {{ __('under_verification') }}</div>
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
                                {{ __('approved_loans') }}</div>
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
                                {{ __('disbursed_loans') }}</div>
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
                                {{ __('defaulters') }}</div>
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
            <h6 class="m-0 font-weight-bold text-primary">{{ __('loan_applications_list') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('membership_no') }}</th>
                            <th>{{ __('amount_applied') }}</th>
                            <th>{{ __('amount_approved') }}</th>
                            <th>{{ __('interest_rate') }}</th>
                            <th>{{ __('duration') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('treasurer') }}</th>
                            <th>{{ __('applied_date') }}</th>
                            <th>{{ __('actions') }}</th>
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
                            <td>{{ $loan->user->membership_number ?? __('n_a') }}</td>
                            <td>CDF {{ number_format($loan->amount_applied, 2) }}</td>
                            <td>
                                @if($loan->amount_approved)
                                    CDF {{ number_format($loan->amount_approved, 2) }}
                                @else
                                    <span class="text-muted">{{ __('n_a') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->interest_rate)
                                    {{ $loan->interest_rate }}%
                                @else
                                    <span class="text-muted">{{ __('n_a') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->duration_months)
                                    {{ $loan->duration_months }} {{ __('months') }}
                                @else
                                    <span class="text-muted">{{ __('n_a') }}</span>
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
        {{ translate_status($loan->status) }}
    </span>
</td>


                            <td>
                                @if($loan->treasurer)
                                    {{ $loan->treasurer->name }}
                                @else
                                    <span class="text-muted">{{ __('not_assigned') }}</span>
                                @endif
                            </td>
                            <td>{{ $loan->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-info btn-sm" title="{{ __('view_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($loan->status == 'pending')
                                    <button type="button" class="btn btn-primary btn-sm" title="{{ __('assign_treasurer') }}"
                                            onclick="openAssignTreasurerModal({{ $loan->id }})">
                                        <i class="fas fa-user-tie"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">{{ __('no_loan_applications_found') }}</td>
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
                    <h5 class="modal-title">{{ __('assign_treasurer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="treasurer_id">{{ __('select_treasurer') }}</label>
                        <select class="form-control" id="treasurer_id" name="treasurer_id" required>
                            <option value="">{{ __('choose_treasurer') }}</option>
                            @foreach($treasurers ?? [] as $treasurer)
                                <option value="{{ $treasurer->id }}">{{ $treasurer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('assign') }}</button>
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