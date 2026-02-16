{{-- resources/views/treasurer/repayments/collect.blade.php --}}
@extends('layouts.app')

@section('title', 'Collect Loan Repayment')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Collect Loan Repayment</h1>
        <div>
            <a href="{{ route('treasurer.repayments.index') }}" class="btn btn-info">
                <i class="fas fa-history"></i> Payment History
            </a>
            <a href="{{ route('treasurer.loans.assigned') }}" class="btn btn-secondary">
                <i class="fas fa-tasks"></i> My Assigned Loans
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Validation Errors:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Member Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Find Member</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.repayments.collect') }}" class="row">
                <div class="col-md-10 mb-3">
                    <label for="search">Search by Membership Number, Phone Number, or Name</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ $search ?? '' }}" placeholder="Enter membership number, phone, or name..." autofocus>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($member))
        <!-- Member Information -->
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-user"></i> {{ $member->name }}</h5>
                    <p class="mb-1"><strong>Membership #:</strong> {{ $member->membership_number }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $member->phone_number }}</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshMemberSummary({{ $member->id }})">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        @if($loans->isEmpty())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                No active loans found for this member that are assigned to you.
            </div>
        @else
            <!-- Active Loans -->
            @foreach($loans as $loan)
                <div class="card mb-3 border-{{ $loan->repayments->where('due_date', '<', now())->where('status', '!=', 'paid')->count() > 0 ? 'danger' : 'success' }}">
                    <div class="card-header bg-{{ $loan->repayments->where('due_date', '<', now())->where('status', '!=', 'paid')->count() > 0 ? 'danger' : 'success' }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-file-invoice"></i>
                                Loan #{{ $loan->application_number }}
                            </h5>
                            <span class="badge bg-light text-dark">
                                Balance: UGX {{ number_format($loan->balance, 2) }}
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
                                    <th>Installment</th>
                                    <th>Due Date</th>
                                    <th>Amount Due</th>
                                    <th>Amount Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
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
                                                   value="{{ $repayment->id }}"
                                                   data-amount="{{ $balance }}"
                                                   data-loan="{{ $loan->id }}">
                                        </td>
                                        <td>{{ $repayment->installment_number }}</td>
                                        <td>{{ $repayment->due_date->format('d/m/Y') }}</td>
                                        <td>UGX {{ number_format($repayment->amount_due, 2) }}</td>
                                        <td>UGX {{ number_format($repayment->amount_paid, 2) }}</td>
                                        <td class="font-weight-bold">UGX {{ number_format($balance, 2) }}</td>
                                        <td>
                                            @if($repayment->status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($repayment->status == 'partial')
                                                <span class="badge bg-warning">Partial</span>
                                            @elseif($isOverdue)
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
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
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Process Payment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('treasurer.repayments.process') }}" id="paymentForm">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                        <input type="hidden" name="loan_id" id="selected_loan_id" value="">
                        <div id="selectedRepayments"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selected Installments</label>
                                    <div class="form-control bg-light" id="selectedCount">0</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Amount Due</label>
                                    <div class="form-control bg-light font-weight-bold" id="totalAmount">0.00</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Payment Amount *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">UGX</span>
                                        </div>
                                        <input type="number"
                                               name="amount"
                                               id="amount"
                                               class="form-control form-control-lg"
                                               required
                                               step="0.01"
                                               min="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method *</label>
                                    <select name="payment_method" id="payment_method" class="form-control form-control-lg" required>
                                        <option value="">Select Method</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="mobile_money">Mobile Money</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_number">Reference Number (Optional)</label>
                                    <input type="text"
                                           name="reference_number"
                                           id="reference_number"
                                           class="form-control"
                                           placeholder="e.g., Transaction ID, Cheque Number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes (Optional)</label>
                                    <input type="text"
                                           name="notes"
                                           id="notes"
                                           class="form-control"
                                           placeholder="Any additional notes">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn" disabled>
                                    <i class="fas fa-check-circle"></i> Process Payment
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
            No member found with "{{ $search }}". Please try again.
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-hand-holding-usd fa-4x text-muted mb-3"></i>
            <h4>Search for a member to collect payment</h4>
            <p class="text-muted">Enter membership number, phone number, or name above</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedRepayments = new Map();
    let selectedLoanId = null;

    // Handle checkbox changes
    $('.repayment-checkbox').change(function() {
        const repaymentId = $(this).val();
        const amount = parseFloat($(this).data('amount'));
        const loanId = $(this).data('loan');

        if ($(this).is(':checked')) {
            // Check if this repayment belongs to the same loan as already selected ones
            if (selectedRepayments.size > 0 && selectedLoanId && selectedLoanId !== loanId) {
                alert('You can only select repayments from one loan at a time.');
                $(this).prop('checked', false);
                return;
            }

            selectedRepayments.set(repaymentId, {
                id: repaymentId,
                amount: parseFloat(amount.toFixed(2)),
                loanId: loanId
            });

            // Set the loan ID
            selectedLoanId = loanId;
            $('#selected_loan_id').val(loanId);
        } else {
            selectedRepayments.delete(repaymentId);

            // If no repayments selected, clear the loan ID
            if (selectedRepayments.size === 0) {
                selectedLoanId = null;
                $('#selected_loan_id').val('');
            }
        }

        updateSelectedInputs();
        updateTotals();
    });

    // Select all per loan
    $('.select-all').change(function() {
        const loanId = $(this).data('loan');
        const isChecked = $(this).is(':checked');

        $(`.repayment-checkbox.loan-${loanId}`).each(function() {
            $(this).prop('checked', isChecked).trigger('change');
        });
    });

    // Update hidden inputs
    function updateSelectedInputs() {
        const container = $('#selectedRepayments');
        container.empty();

        selectedRepayments.forEach((value, key) => {
            container.append(`<input type="hidden" name="repayment_ids[]" value="${key}">`);
        });

        $('#selectedCount').text(selectedRepayments.size);

        // Enable/disable submit button based on selection
        $('#submitBtn').prop('disabled', selectedRepayments.size === 0);

        // Also disable if amount is invalid
        validateAmount();
    }

    // Update totals
    function updateTotals() {
        let total = 0;
        selectedRepayments.forEach((value) => {
            total = parseFloat((total + value.amount).toFixed(2));
        });

        $('#totalAmount').text(total.toFixed(2));
        $('#amount').attr('max', total.toFixed(2));

        // Re-validate amount when totals change
        validateAmount();
    }

    // Validate payment amount
    function validateAmount() {
        let amount = parseFloat($('#amount').val()) || 0;
        let totalDue = parseFloat($('#totalAmount').text()) || 0;

        amount = parseFloat(amount.toFixed(2));
        totalDue = parseFloat(totalDue.toFixed(2));

        if (amount > totalDue) {
            $('#amount').addClass('is-invalid');
            $('#submitBtn').prop('disabled', true);
            return false;
        } else if (amount <= 0) {
            $('#amount').addClass('is-invalid');
            $('#submitBtn').prop('disabled', true);
            return false;
        } else {
            $('#amount').removeClass('is-invalid');
            if (selectedRepayments.size > 0) {
                $('#submitBtn').prop('disabled', false);
            }
            return true;
        }
    }

    // Amount input handler
    $('#amount').on('input', function() {
        validateAmount();
    });

    // Form submission validation
    $('#paymentForm').submit(function(e) {
        if (selectedRepayments.size === 0) {
            e.preventDefault();
            alert('Please select at least one repayment to pay.');
            return false;
        }

        if (!selectedLoanId) {
            e.preventDefault();
            alert('Loan ID is missing. Please select repayments from a loan.');
            return false;
        }

        const amount = parseFloat($('#amount').val()) || 0;
        if (amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid amount.');
            return false;
        }

        const totalDue = parseFloat($('#totalAmount').text()) || 0;
        if (amount > totalDue) {
            e.preventDefault();
            alert('Payment amount cannot exceed the total due.');
            return false;
        }

        return true;
    });

    // Function to refresh member summary (placeholder)
    window.refreshMemberSummary = function(memberId) {
        // You can implement this if needed
        location.reload();
    };
});
</script>
@endpush
