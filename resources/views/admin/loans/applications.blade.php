@extends('layouts.app')

@section('title', __('all_loan_applications'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('all_loan_applications') }}</h1>
        <div>
            <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('new_loan_application') }}
            </a>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> {{ __('back_to_dashboard') }}
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
                                {{ __('total_applications') }}</div>
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

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('total_amount') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($totalAmount ?? 0, 2) }}
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
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_applications') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.applications') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">{{ __('search_member') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('search_member_placeholder') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">{{ __('application_status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">{{ __('all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('pending') }}</option>
                        <option value="under_verification" {{ request('status') == 'under_verification' ? 'selected' : '' }}>{{ __('under_verification') }}</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>{{ __('verified') }}</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('approved') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('rejected') }}</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>{{ __('disbursed') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('completed') }}</option>
                        <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>{{ __('defaulted') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_range">{{ __('date_range') }}</label>
                    <select class="form-control" id="date_range" name="date_range">
                        <option value="">{{ __('all_time') }}</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>{{ __('today') }}</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>{{ __('this_week') }}</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>{{ __('this_month') }}</option>
                        <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>{{ __('this_quarter') }}</option>
                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>{{ __('this_year') }}</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> {{ __('filter') }}
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
            <h6 class="m-0 font-weight-bold text-primary">{{ __('applications_list') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('application_no') }}</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('membership_no') }}</th>
                            <th>{{ __('amount_applied') }}</th>
                            <th>{{ __('amount_approved') }}</th>
                            <th>{{ __('purpose') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('treasurer') }}</th>
                            <th>{{ __('applied_date') }}</th>
                            <th>{{ __('actions') }}</th>
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
                            <td>{{ $application->user->membership_number ?? __('n_a') }}</td>
                            <td>CDF {{ number_format($application->amount_applied, 2) }}</td>
                            <td>
                                @if($application->amount_approved)
                                    CDF {{ number_format($application->amount_approved, 2) }}
                                @else
                                    <span class="text-muted">{{ __('n_a') }}</span>
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
                                    {{ translate_status($application->status) }}
                                </span>
                            </td>
                            <td>
                                @if($application->treasurer)
                                    {{ $application->treasurer->name }}
                                @else
                                    <span class="text-muted">{{ __('not_assigned') }}</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $application) }}" class="btn btn-info btn-sm" title="{{ __('view_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($application->status == 'pending')
                                    <button type="button" class="btn btn-primary btn-sm" title="{{ __('assign_treasurer') }}"
                                            onclick="openAssignTreasurerModal({{ $application->id }})">
                                        <i class="fas fa-user-tie"></i>
                                    </button>
                                @endif

                                @if($application->status == 'approved')
                                    <form action="{{ route('admin.loans.disburse', $application) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="{{ __('disburse_loan') }}"
                                                onclick="return confirm('{{ __('confirm_disburse') }}')">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">{{ __('no_applications_found') }}</td>
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

    // Auto-submit form when filters change
    document.getElementById('status')?.addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('date_range')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush