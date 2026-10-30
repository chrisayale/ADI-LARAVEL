@extends('layouts.app')

@section('title', __('disbursed_loans'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('disbursed_loans') }}</h1>
        <div>
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
                                {{ __('total_disbursed') }}</div>
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
                                {{ __('total_amount') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($totalDisbursed ?? 0, 2) }}
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
                                {{ __('total_repaid') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($totalRepaid ?? 0, 2) }}
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
                                {{ __('outstanding') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                CDF {{ number_format($outstandingBalance ?? 0, 2) }}
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
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_disbursed_loans') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.loans.disbursed') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">{{ __('search_member') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('search_member_placeholder') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="repayment_status">{{ __('repayment_status') }}</label>
                    <select class="form-control" id="repayment_status" name="repayment_status">
                        <option value="all">{{ __('all') }}</option>
                        <option value="on_track" {{ request('repayment_status') == 'on_track' ? 'selected' : '' }}>{{ __('on_track') }}</option>
                        <option value="overdue" {{ request('repayment_status') == 'overdue' ? 'selected' : '' }}>{{ __('overdue') }}</option>
                        <option value="completed" {{ request('repayment_status') == 'completed' ? 'selected' : '' }}>{{ __('completed') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="disbursed_date">{{ __('disbursed_date') }}</label>
                    <input type="month" class="form-control" id="disbursed_date" name="disbursed_date"
                           value="{{ request('disbursed_date') }}">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> {{ __('filter') }}
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
            <h6 class="m-0 font-weight-bold text-primary">{{ __('disbursed_loans_list') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('application_no') }}</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('amount_disbursed') }}</th>
                            <th>{{ __('interest_rate') }}</th>
                            <th>{{ __('duration') }}</th>
                            <th>{{ __('monthly_payment') }}</th>
                            <th>{{ __('total_repayable') }}</th>
                            <th>{{ __('disbursed_date') }}</th>
                            <th>{{ __('amount_repaid') }}</th>
                            <th>{{ __('balance') }}</th>
                            <th>{{ __('progress') }}</th>
                            <th>{{ __('actions') }}</th>
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
                                        <small class="text-muted">{{ $loan->user->membership_number ?? __('n_a') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><strong>CDF {{ number_format($loan->amount_approved, 2) }}</strong></td>
                            <td>{{ $loan->interest_rate }}%</td>
                            <td>{{ $loan->duration_months }} {{ __('months') }}</td>
                            <td>CDF {{ number_format($loan->monthly_repayment, 2) }}</td>
                            <td>CDF {{ number_format($loan->total_repayable, 2) }}</td>
                            <td>{{ $loan->disbursed_at->format('d M Y') }}</td>
                            <td>CDF {{ number_format($totalPaid, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $balance > 0 ? 'warning' : 'success' }} px-3 py-2">
                                    CDF {{ number_format($balance, 2) }}
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
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-info btn-sm" title="{{ __('view_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($balance == 0)
                                <form action="{{ route('admin.loans.mark-completed', $loan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="{{ __('mark_as_completed') }}"
                                            onclick="return confirm('{{ __('confirm_mark_completed') }}')">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center py-4">{{ __('no_disbursed_loans_found') }}</td>
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