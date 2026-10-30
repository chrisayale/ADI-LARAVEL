{{-- resources/views/admin/payments/collect.blade.php --}}
@extends('layouts.app')

@section('title', __('collect_loan_payment'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('collect_loan_payment') }}</h3>
                </div>
                <div class="card-body">
                    <!-- Member Search -->
                    <div class="row mb-4">
                        <div class="col-md-8 offset-md-2">
                            <form method="GET" action="{{ route('admin.payments.collect') }}" class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="{{ __('search_payment_member_placeholder') }}"
                                           value="{{ $search }}"
                                           autofocus>
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-search"></i> {{ __('find_member') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(isset($member))
                        <!-- Member Info -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-user"></i> {{ $member->name }}</h5>
                                    <p class="mb-1"><strong>{{ __('membership_number') }}:</strong> {{ $member->membership_number }}</p>
                                    <p class="mb-1"><strong>{{ __('phone') }}:</strong> {{ $member->phone }}</p>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshMemberSummary({{ $member->id }})">
                                        <i class="fas fa-sync-alt"></i> {{ __('refresh') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if($loans->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('no_active_loans_found_for_member') }}
                            </div>
                        @else
                            <!-- Active Loans -->
                            @foreach($loans as $loan)
                                <div class="card mb-3 border-{{ $loan->repayments->where('due_date', '<', now())->where('status', '!=', 'paid')->count() > 0 ? 'danger' : 'success' }}">
                                    <div class="card-header bg-{{ $loan->repayments->where('due_date', '<', now())->where('status', '!=', 'paid')->count() > 0 ? 'danger' : 'success' }} text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-file-invoice"></i>
                                                {{ __('loan') }} #{{ $loan->application_number }}
                                            </h5>
                                            <span class="badge bg-light text-dark">
                                                {{ __('balance') }}: {{ number_format($loan->balance, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th width="50">
                                                        <input type="checkbox"
                                                               class="select-all"
                                                               data-loan="{{ $loan->id }}">
                                                    </th>
                                                    <th>{{ __('installment') }}</th>
                                                    <th>{{ __('due_date') }}</th>
                                                    <th>{{ __('amount_due') }}</th>
                                                    <th>{{ __('amount_paid') }}</th>
                                                    <th>{{ __('balance') }}</th>
                                                    <th>{{ __('status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($loan->repayments as $repayment)
                                                    @php
                                                        $balance = $repayment->amount_due - $repayment->amount_paid;
                                                        $isOverdue = $repayment->due_date < now() && $repayment->status != 'paid';
                                                    @endphp
                                                    @if($balance > 0)
                                                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                                        <td>
                                                            <input type="checkbox"
                                                                   class="repayment-checkbox loan-{{ $loan->id }}"
                                                                   name="repayments[]"
                                                                   value="{{ $repayment->id }}"
                                                                   data-amount="{{ $balance }}"
                                                                   data-loan="{{ $loan->id }}">
                                                        </td>
                                                        <td>{{ $repayment->installment_number }}</td>
                                                        <td>{{ $repayment->due_date->format('d/m/Y') }}</td>
                                                        <td>{{ number_format($repayment->amount_due, 2) }}</td>
                                                        <td>{{ number_format($repayment->amount_paid, 2) }}</td>
                                                        <td class="font-weight-bold">{{ number_format($balance, 2) }}</td>
                                                        <td>
                                                            @if($repayment->status == 'paid')
                                                                <span class="badge bg-success">{{ translate_status('paid') }}</span>
                                                            @elseif($repayment->status == 'partial')
                                                                <span class="badge bg-warning">{{ translate_status('partial') }}</span>
                                                            @elseif($isOverdue)
                                                                <span class="badge bg-danger">{{ translate_status('overdue') }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ translate_status('pending') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Payment Form -->
                            <div class="card mt-4 border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> {{ __('process_payment') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.payments.process') }}" id="paymentForm">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <div id="selectedRepayments"></div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('selected_installments') }}</label>
                                                    <div class="form-control bg-light" id="selectedCount">0</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('total_amount_due') }}</label>
                                                    <div class="form-control bg-light font-weight-bold" id="totalAmount">0.00</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="amount">{{ __('payment_amount') }} *</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">CDF</span>
                                                        </div>
                                                        <input type="number"
                                                               name="amount"
                                                               id="amount"
                                                               class="form-control form-control-lg"
                                                               required
                                                               step="0.01"
                                                               >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_method">{{ __('payment_method') }} *</label>
                                                    <select name="payment_method" id="payment_method" class="form-control form-control-lg" required>
                                                        <option value="cash">{{ __('cash') }}</option>
                                                        <option value="bank_transfer">{{ __('bank_transfer') }}</option>
                                                        <option value="mobile_money">{{ __('mobile_money') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="reference_number">{{ __('reference_number') }} ({{ __('optional') }})</label>
                                                    <input type="text"
                                                           name="reference_number"
                                                           id="reference_number"
                                                           class="form-control"
                                                           placeholder="e.g., Transaction ID, Cheque Number">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="notes">{{ __('notes') }} ({{ __('optional') }})</label>
                                                    <input type="text"
                                                           name="notes"
                                                           id="notes"
                                                           class="form-control"
                                                           placeholder="{{ __('any_additional_notes') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn" disabled>
                                                    <i class="fas fa-check-circle"></i> {{ __('process_payment') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @elseif($search)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ __('no_member_found_with_search') }} "{{ $search }}". {{ __('please_try_again') }}.
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-hand-holding-usd fa-4x text-muted mb-3"></i>
                            <h4>{{ __('search_member_to_collect') }}</h4>
                            <p class="text-muted">{{ __('enter_membership_number_above') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    let selectedRepayments = new Map();

    // ===============================
    // Handle checkbox changes
    // ===============================
    $('.repayment-checkbox').change(function() {
        const repaymentId = $(this).val();
        const amount = parseFloat($(this).data('amount'));
        const loanId = $(this).data('loan');

        if ($(this).is(':checked')) {
            selectedRepayments.set(repaymentId, {
                id: repaymentId,
                amount: parseFloat(amount.toFixed(2)), // FIX rounding
                loanId: loanId
            });
        } else {
            selectedRepayments.delete(repaymentId);
        }

        updateSelectedInputs();
        updateTotals();
    });

    // ===============================
    // Select all per loan
    // ===============================
    $('.select-all').change(function() {
        const loanId = $(this).data('loan');
        const isChecked = $(this).is(':checked');

        $(`.repayment-checkbox.loan-${loanId}`).each(function() {
            $(this).prop('checked', isChecked).trigger('change');
        });
    });

    // ===============================
    // Update hidden inputs
    // ===============================
    function updateSelectedInputs() {
        const container = $('#selectedRepayments');
        container.empty();

        selectedRepayments.forEach((value, key) => {
            container.append(`<input type="hidden" name="repayment_ids[]" value="${key}">`);
        });

        $('#selectedCount').text(selectedRepayments.size);

        $('#submitBtn').prop('disabled', selectedRepayments.size === 0);
    }

    // ===============================
    // Update totals (SAFE MONEY CALCULATION)
    // ===============================
    function updateTotals() {
        let total = 0;

        selectedRepayments.forEach((value) => {
            total = parseFloat((total + value.amount).toFixed(2)); // FIX
        });

        $('#totalAmount').text(total.toFixed(2));

        // Set max allowed payment
        $('#amount').attr('max', total.toFixed(2));
    }

    // ===============================
    // Validate payment amount
    // ===============================
    $('#amount').on('input', function() {

        let amount = parseFloat($(this).val()) || 0;
        let totalDue = parseFloat($('#totalAmount').text()) || 0;

        // 🔥 FIX FLOATING PRECISION
        amount = parseFloat(amount.toFixed(2));
        totalDue = parseFloat(totalDue.toFixed(2));

        if (amount > totalDue) {
            $(this).addClass('is-invalid');
            $('#submitBtn').prop('disabled', true);

        } else if (amount <= 0) {
            $(this).addClass('is-invalid');
            $('#submitBtn').prop('disabled', true);

        } else {
            $(this).removeClass('is-invalid');

            // Only enable if repayments selected
            if (selectedRepayments.size > 0) {
                $('#submitBtn').prop('disabled', false);
            }
        }
    });

});
</script>
@endpush