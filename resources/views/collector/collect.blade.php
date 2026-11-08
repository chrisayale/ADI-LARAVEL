@extends('layouts.app')

@section('title', __('collect_payment'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave"></i> {{ __('collect_payment') }}
        </h1>
        <div class="text-right">
            <small class="text-muted">
                {{ __('collector_zone') }}: {{ auth()->user()->collection_zone ?? __('all_zones') }}
            </small>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('today_collections') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($todayCollections, 2) }} {{ __('currency') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('monthly_collections') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($monthlyCollections, 2) }} {{ __('currency') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ __('pending_repayments') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pendingRepayments->total() }}
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

    <!-- Pending Repayments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('pending_repayments_to_collect') }}</h6>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('collector.transactions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history"></i> {{ __('view_transactions') }}
                </a>
                <a href="{{ route('collector.savings.create') }}" class="btn btn-outline-success">
                    <i class="fas fa-piggy-bank"></i> {{ __('make_deposit_withdrawal') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($pendingRepayments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-gray-600">{{ __('no_pending_repayments') }}</h5>
                    <p class="text-muted">{{ __('all_caught_up') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="repaymentsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('member') }}</th>
                                <th>{{ __('loan_ref') }}</th>
                                <th>{{ __('due_date') }}</th>
                                <th>{{ __('installment') }}</th>
                                <th>{{ __('principal_due') }}</th>
                                <th>{{ __('interest_due') }}</th>
                                <th>{{ __('penalty') }}</th>
                                <th>{{ __('total_due') }}</th>
                                <th>{{ __('status') }}</th>
                                <th>{{ __('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingRepayments as $repayment)
                                <tr class="{{ $repayment->is_overdue ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>{{ $repayment->loanApplication->user->name }}</strong><br>
                                        <small class="text-muted">{{ $repayment->loanApplication->user->membership_number }}</small>
                                    </td>
                                    <td>
                                        <code>{{ $repayment->loanApplication->loan_reference }}</code>
                                    </td>
                                    <td>
                                        {{ $repayment->due_date->format('d/m/Y') }}
                                        @if ($repayment->is_overdue)
                                            <span class="badge badge-danger ml-1">
                                                <i class="fas fa-exclamation-triangle"></i> {{ __('overdue') }} ({{ $repayment->due_date->diffInDays(now()) }} {{ __('days') }})
                                            </span>
                                        @endif
                                    </td>
                                    <td>#{{ $repayment->installment_number }}</td>
                                    <td>{{ number_format($repayment->principal_due, 2) }}</td>
                                    <td>{{ number_format($repayment->interest_due, 2) }}</td>
                                    <td>
                                        @if ($repayment->calculated_penalty > 0)
                                            <span class="text-danger font-weight-bold">
                                                {{ number_format($repayment->calculated_penalty, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ number_format($repayment->effective_amount_due - $repayment->amount_paid + $repayment->calculated_penalty, 2) }}
                                    </td>
                                    <td>
                                        @switch($repayment->status)
                                            @case('pending')
                                                <span class="badge badge-warning">{{ __('pending') }}</span>
                                                @break
                                            @case('partial')
                                                <span class="badge badge-info">{{ __('partial') }}</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $repayment->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-primary collect-btn"
                                                data-repayment-id="{{ $repayment->id }}"
                                                data-member="{{ $repayment->loanApplication->user->name }}"
                                                data-loan-ref="{{ $repayment->loanApplication->loan_reference }}"
                                                data-installment="{{ $repayment->installment_number }}"
                                                data-amount-due="{{ $repayment->effective_amount_due - $repayment->amount_paid }}"
                                                data-penalty="{{ $repayment->calculated_penalty }}"
                                                data-total-due="{{ $repayment->effective_amount_due - $repayment->amount_paid + $repayment->calculated_penalty }}">
                                            <i class="fas fa-hand-holding-usd"></i> {{ __('collect') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $pendingRepayments->links() }}
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Collect Payment Modal
    $(document).on('click', '.collect-btn', function() {
        const btn = $(this);
        const repaymentId = btn.data('repayment-id');
        const member = btn.data('member');
        const loanRef = btn.data('loan-ref');
        const installment = btn.data('installment');
        const amountDue = parseFloat(btn.data('amount-due'));
        const penalty = parseFloat(btn.data('penalty'));
        const totalDue = parseFloat(btn.data('total-due'));

        $('#collectRepaymentId').val(repaymentId);
        $('#collectMemberName').text(member);
        $('#collectLoanRef').text(loanRef);
        $('#collectInstallment').text(installment);
        $('#collectAmountDue').text(amountDue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#collectPenalty').text(penalty.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#collectTotalDue').text(totalDue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

        // Reset form
        $('#collectForm')[0].reset();
        $('#collectAmount').val(totalDue.toFixed(2));
        $('#collectAmount').attr('max', totalDue.toFixed(2));

        $('#collectModal').modal('show');
    });

    // Form submission
    $('#collectForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __('processing') }}...');

        $.ajax({
            url: '{{ route("collector.payments.process") }}',
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#collectModal').modal('hide');
                    toastr.success(response.message || '{{ __('payment_collected_success') }}');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(response.message || '{{ __('error_occurred') }}');
                }
            },
            error: function(xhr) {
                let message = '{{ __('error_occurred') }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                toastr.error(message);
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Initialize DataTable
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#repaymentsTable').DataTable({
                pageLength: 10,
                order: [[2, 'asc']], // Sort by due date
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        }
    });
</script>
@endpush

<!-- Collect Payment Modal -->
<div class="modal fade" id="collectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-hand-holding-usd"></i> {{ __('collect_payment_for') }}: <span id="collectMemberName"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="collectForm">
                @csrf
                <input type="hidden" name="loan_repayment_id" id="collectRepaymentId">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">{{ __('loan_reference') }}</label>
                            <p class="mb-0"><code id="collectLoanRef"></code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">{{ __('installment') }}</label>
                            <p class="mb-0">#<span id="collectInstallment"></span></p>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <small class="text-muted d-block">{{ __('principal_interests_due') }}</small>
                                    <strong id="collectAmountDue">0.00</strong>
                                </div>
                                <div class="col-md-4 text-center">
                                    <small class="text-muted d-block">{{ __('late_penalty') }}</small>
                                    <strong class="text-danger" id="collectPenalty">0.00</strong>
                                </div>
                                <div class="col-md-4 text-center">
                                    <small class="text-muted d-block">{{ __('total_due') }}</small>
                                    <strong class="text-primary h5 mb-0" id="collectTotalDue">0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="collectAmount">{{ __('amount_collected') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="collectAmount" name="amount" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="collectPaymentMethod">{{ __('payment_method') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="collectPaymentMethod" name="payment_method" required>
                                <option value="cash">{{ __('cash') }}</option>
                                <option value="mpesa">{{ __('mpesa') }}</option>
                                <option value="airtel_money">{{ __('airtel_money') }}</option>
                                <option value="bank_transfer">{{ __('bank_transfer') }}</option>
                                <option value="cheque">{{ __('cheque') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="collectReference">{{ __('transaction_reference') }}</label>
                        <input type="text" class="form-control" id="collectReference" name="transaction_reference" placeholder="{{ __('reference_placeholder') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="collectNotes">{{ __('notes') }}</label>
                        <textarea class="form-control" id="collectNotes" name="notes" rows="2" placeholder="{{ __('notes_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('confirm_collection') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>