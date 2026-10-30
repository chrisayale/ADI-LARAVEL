@extends('layouts.app')

@section('title', __('verified_loan_applications_ready'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('verified_loan_applications_ready') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.loans.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('back_to_all_loans') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $loans->total() }}</h3>
                                    <p>{{ __('total_verified_applications') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    @php
                                        $totalAmount = $loans->sum('amount_applied');
                                    @endphp
                                    <h3>{{ number_format($totalAmount, 2) }}</h3>
                                    <p>{{ __('total_amount_applied') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    @php
                                        $avgAmount = $loans->count() > 0 ? $totalAmount / $loans->count() : 0;
                                    @endphp
                                    <h3>{{ number_format($avgAmount, 2) }}</h3>
                                    <p>{{ __('average_loan_amount') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.loans.verified') }}" class="form-inline">
                                <div class="input-group w-100">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="{{ __('search_verified_placeholder') }}"
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i> {{ __('search') }}
                                        </button>
                                        @if(request('search') || request('treasurer_id') || request('date_from') || request('date_to'))
                                            <a href="{{ route('admin.loans.verified') }}" class="btn btn-outline-danger">
                                                <i class="fas fa-times"></i> {{ __('clear_filters') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Advanced Filters (Collapsible) -->
                    <div class="mb-4">
                        <button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#advancedFilters">
                            <i class="fas fa-filter"></i> {{ __('advanced_filters') }}
                        </button>
                        <div class="collapse {{ request('date_from') || request('date_to') || request('treasurer_id') ? 'show' : '' }}" id="advancedFilters">
                            <div class="card card-body bg-light">
                                <form method="GET" action="{{ route('admin.loans.verified') }}">
                                    @if(request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('date_from') }}</label>
                                                <input type="date"
                                                       name="date_from"
                                                       class="form-control"
                                                       value="{{ request('date_from') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('date_to') }}</label>
                                                <input type="date"
                                                       name="date_to"
                                                       class="form-control"
                                                       value="{{ request('date_to') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('min_amount') }}</label>
                                                <input type="number"
                                                       name="min_amount"
                                                       class="form-control"
                                                       value="{{ request('min_amount') }}"
                                                       step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('max_amount') }}</label>
                                                <input type="number"
                                                       name="max_amount"
                                                       class="form-control"
                                                       value="{{ request('max_amount') }}"
                                                       step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-filter"></i> {{ __('apply_filters') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Loans Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th width="50">#</th>
                                    <th>{{ __('application_number') }}</th>
                                    <th>{{ __('member_details') }}</th>
                                    <th>{{ __('loan_details') }}</th>
                                    <th>{{ __('verification_info') }}</th>
                                    <th>{{ __('guarantors') }}</th>
                                    <th>{{ __('documents') }}</th>
                                    <th width="150">{{ __('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $index => $loan)
                                <tr>
                                    <td>{{ $loans->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $loan->application_number }}</strong>
                                        <br>
                                        <small class="text-muted">{{ __('applied') }}: {{ $loan->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card"></i> {{ $loan->user->membership_number ?? __('n_a') }}
                                            <br>
                                            <i class="fas fa-phone"></i> {{ $loan->user->phone ?? __('n_a') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($loan->amount_applied, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> {{ $loan->duration_months ?? __('n_a') }} {{ __('months') }}
                                            <br>
                                            <span class="badge bg-success">{{ translate_status('verified') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ __('verified_by') }}: {{ $loan->verifier->name ?? $loan->treasurer->name ?? __('n_a') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $loan->verified_at ? $loan->verified_at->format('d/m/Y H:i') : __('n_a') }}
                                        </small>
                                        @if($loan->verification_notes)
                                            <br>
                                            <span class="badge bg-info" title="{{ $loan->verification_notes }}">
                                                <i class="fas fa-sticky-note"></i> {{ __('notes') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $verifiedCount = $loan->guarantors->where('status', 'verified')->count();
                                            $totalCount = $loan->guarantors->count();
                                        @endphp
                                        <span class="badge bg-{{ $verifiedCount == $totalCount ? 'success' : 'warning' }}">
                                            {{ $verifiedCount }}/{{ $totalCount }} {{ __('verified') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user-check"></i> {{ __('all_verified') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $docCount = $loan->documents->count();
                                            $verifiedDocCount = $loan->documents->where('verification_status', 'verified')->count();
                                        @endphp
                                        <span class="badge bg-{{ $docCount > 0 ? 'success' : 'secondary' }}">
                                            {{ $docCount }} {{ __('files') }}
                                        </span>
                                        @if($docCount > 0)
                                            <br>
                                            <small class="text-muted">
                                                {{ $verifiedDocCount }} {{ __('verified') }}
                                            </small>
                                        @endif
                                    </td>
                                   <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.loans.show', $loan) }}"
                                           class="btn btn-sm btn-info"
                                           title="{{ __('view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                title="{{ __('actions') }}">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.loans.show', $loan) }}">
                                                <i class="fas fa-eye text-info"></i> {{ __('view_details') }}
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#approveModal{{ $loan->id }}">
                                                <i class="fas fa-check-circle text-success"></i> {{ __('approve_loan') }}
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $loan->id }}">
                                                <i class="fas fa-times-circle text-danger"></i> {{ __('reject_loan') }}
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('admin.loans.show', $loan) }}#documents">
                                                <i class="fas fa-file-alt text-warning"></i> {{ __('view_documents') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">{{ __('no_verified_loan_applications_found') }}</p>
                                        <p class="text-muted small">{{ __('verified_loans_will_appear') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center">
                                {{ $loans->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modals -->
@foreach($loans as $loan)
<div class="modal fade" id="approveModal{{ $loan->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel{{ $loan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.approve', $loan) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel{{ $loan->id }}">
                        <i class="fas fa-check-circle"></i> {{ __('approve_loan_application') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('application_number') }}: <strong>{{ $loan->application_number }}</strong><br>
                        {{ __('member') }}: <strong>{{ $loan->user->name }}</strong><br>
                        {{ __('amount_applied') }}: <strong>{{ number_format($loan->amount_applied, 2) }}</strong>
                    </div>

                    <div class="form-group">
                        <label for="amount_approved{{ $loan->id }}">{{ __('approved_amount') }} *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">UGX</span>
                            </div>
                            <input type="number"
                                   name="amount_approved"
                                   id="amount_approved{{ $loan->id }}"
                                   class="form-control"
                                   required
                                   step="0.01"
                                   min="1"
                                   max="{{ $loan->amount_applied }}"
                                   value="{{ $loan->amount_applied }}">
                        </div>
                        <small class="text-muted">{{ __('maximum_amount') }}: {{ number_format($loan->amount_applied, 2) }}</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interest_rate{{ $loan->id }}">{{ __('interest_rate') }} (%) *</label>
                                <div class="input-group">
                                    <input type="number"
                                           name="interest_rate"
                                           id="interest_rate{{ $loan->id }}"
                                           class="form-control"
                                           required
                                           step="0.1"
                                           min="0"
                                           max="100"
                                           value="12">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_months{{ $loan->id }}">{{ __('duration_months') }} *</label>
                                <input type="number"
                                       name="duration_months"
                                       id="duration_months{{ $loan->id }}"
                                       class="form-control"
                                       required
                                       min="1"
                                       max="60"
                                       value="12">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="approval_notes{{ $loan->id }}">{{ __('approval_notes') }}</label>
                        <textarea name="approval_notes"
                                  id="approval_notes{{ $loan->id }}"
                                  class="form-control"
                                  rows="3"
                                  placeholder="{{ __('approval_notes_placeholder') }}"></textarea>
                    </div>

                    <!-- Calculated Summary -->
                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h6>{{ __('loan_summary_calculated') }}</h6>
                            <div class="row">
                                <div class="col-6">{{ __('monthly_payment') }}:</div>
                                <div class="col-6 text-right">
                                    <strong id="monthly{{ $loan->id }}">-</strong>
                                </div>
                                <div class="col-6">{{ __('total_interest') }}:</div>
                                <div class="col-6 text-right">
                                    <strong id="interest{{ $loan->id }}">-</strong>
                                </div>
                                <div class="col-6">{{ __('total_repayable') }}:</div>
                                <div class="col-6 text-right">
                                    <strong id="total{{ $loan->id }}">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> {{ __('cancel') }}
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> {{ __('approve_loan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $loan->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $loan->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.reject', $loan) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel{{ $loan->id }}">
                        <i class="fas fa-times-circle"></i> {{ __('reject_loan_application') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ __('reject_action_warning') }}
                    </div>

                    <div class="form-group">
                        <label for="rejection_reason{{ $loan->id }}">{{ __('rejection_reason') }} *</label>
                        <textarea name="rejection_reason"
                                  id="rejection_reason{{ $loan->id }}"
                                  class="form-control"
                                  rows="4"
                                  required
                                  placeholder="{{ __('rejection_reason_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> {{ __('cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i> {{ __('reject_loan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-calculate loan summary for each modal
    @foreach($loans as $loan)
    (function(loanId, maxAmount) {
        $('#amount_approved' + loanId + ', #interest_rate' + loanId + ', #duration_months' + loanId).on('input', function() {
            calculateLoan(loanId);
        });

        function calculateLoan(loanId) {
            let principal = parseFloat($('#amount_approved' + loanId).val()) || 0;
            let rate = parseFloat($('#interest_rate' + loanId).val()) || 0;
            let months = parseInt($('#duration_months' + loanId).val()) || 1;

            // Validate amount doesn't exceed max
            if (principal > maxAmount) {
                $('#amount_approved' + loanId).val(maxAmount);
                principal = maxAmount;
            }

            // Calculate using simple interest (adjust formula as needed)
            let annualRate = rate / 100;
            let totalInterest = principal * annualRate * (months / 12);
            let totalRepayable = principal + totalInterest;
            let monthlyPayment = totalRepayable / months;

            // Update display
            $('#monthly' + loanId).text(monthlyPayment.toFixed(2));
            $('#interest' + loanId).text(totalInterest.toFixed(2));
            $('#total' + loanId).text(totalRepayable.toFixed(2));
        }

        // Initial calculation
        calculateLoan(loanId);
    })({{ $loan->id }}, {{ $loan->amount_applied }});
    @endforeach
});
</script>
@endpush

@push('styles')
<style>
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        display: block;
        margin-bottom: 20px;
        position: relative;
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px;
        padding: 0;
        white-space: nowrap;
    }
    .small-box p {
        font-size: 1rem;
    }
    .small-box .icon {
        color: rgba(0,0,0,.15);
        position: absolute;
        right: 10px;
        top: 10px;
        z-index: 0;
    }
    .small-box .icon i {
        font-size: 70px;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endpush