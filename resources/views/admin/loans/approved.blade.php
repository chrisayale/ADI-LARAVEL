@extends('layouts.app')

@section('title', __('approved_loans'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('approved_loans') }}</h1>
        <div>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> {{ __('back_to_dashboard') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('total_approved') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalApproved ?? $approvedLoans->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                {{ __('total_amount') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totalApprovedAmount ?? 0, 2) }}
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
                                {{ __('awaiting_disbursement') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $awaitingDisbursement ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                {{ __('total_interest') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                UGX {{ number_format($totalInterest ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_approved_loans') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.approved') }}" class="row">
                <div class="col-md-5 mb-3">
                    <label for="search">{{ __('search_member') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('search_member_placeholder') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="date_range">{{ __('approval_date') }}</label>
                    <select class="form-control" id="date_range" name="date_range">
                        <option value="all">{{ __('all_time') }}</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>{{ __('today') }}</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>{{ __('this_week') }}</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>{{ __('this_month') }}</option>
                        <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>{{ __('this_quarter') }}</option>
                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>{{ __('this_year') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> {{ __('filter') }}
                    </button>
                    <a href="{{ route('admin.loans.approved') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Approved Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('approved_loans_list') }}</h6>
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
                            <th>{{ __('amount_approved') }}</th>
                            <th>{{ __('interest_rate') }}</th>
                            <th>{{ __('duration') }}</th>
                            <th>{{ __('monthly_payment') }}</th>
                            <th>{{ __('total_repayable') }}</th>
                            <th>{{ __('approved_date') }}</th>
                            <th>{{ __('approved_by') }}</th>
                            <th>{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedLoans as $index => $loan)
                        <tr>
                            <td>{{ $approvedLoans->firstItem() + $index }}</td>
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
                                        <small class="text-muted">{{ $loan->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $loan->user->membership_number ?? __('n_a') }}</td>
                            <td><strong>UGX {{ number_format($loan->amount_approved, 2) }}</strong></td>
                            <td>{{ $loan->interest_rate }}%</td>
                            <td>{{ $loan->duration_months }} {{ __('months') }}</td>
                            <td>UGX {{ number_format($loan->monthly_repayment, 2) }}</td>
                            <td>UGX {{ number_format($loan->total_repayable, 2) }}</td>
                            <td>{{ $loan->approved_at->format('d M Y') }}</td>
                            <td>{{ $loan->approver->name ?? __('n_a') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-info btn-sm" title="{{ __('view_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($loan->status == 'approved')
                                <form action="{{ route('admin.loans.disburse', $loan) }}" method="POST" class="d-inline">
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
                            <td colspan="12" class="text-center py-4">{{ __('no_approved_loans_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $approvedLoans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection