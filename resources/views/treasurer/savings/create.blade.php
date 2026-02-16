{{-- resources/views/treasurer/savings/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Record Savings Transaction')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Record Savings Transaction</h1>
        <a href="{{ route('treasurer.savings.transactions') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
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

    <div class="row">
        <!-- Member Selection Column -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Select Member</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="user_id">Member <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;" required>
                            <option value="">Select a member...</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}"
                                        data-membership="{{ $member->membership_number }}"
                                        data-email="{{ $member->email }}">
                                    {{ $member->name }} ({{ $member->membership_number ?? 'No #' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Member Details Card (initially hidden) -->
                    <div id="member-details-card" class="mt-3" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold">Member Details</h6>
                                <hr class="mt-0">
                                <div id="member-details">
                                    <!-- Filled via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Card (initially hidden) -->
            <div id="recent-transactions-card" class="card shadow mb-4" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody id="recent-transactions-body">
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        Select a member to view transactions
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Form Column -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('treasurer.savings.store') }}" id="transactionForm">
                        @csrf
                        <input type="hidden" id="selected_user_id" name="user_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="transaction_type">Transaction Type <span class="text-danger">*</span></label>
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-success">
                                        <input type="radio" name="transaction_type" id="deposit" value="deposit" checked autocomplete="off">
                                        <i class="fas fa-arrow-down mr-1"></i> Deposit
                                    </label>
                                    <label class="btn btn-outline-info">
                                        <input type="radio" name="transaction_type" id="withdrawal" value="withdrawal" autocomplete="off">
                                        <i class="fas fa-arrow-up mr-1"></i> Withdrawal
                                    </label>
                                </div>
                                @error('transaction_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amount">Amount (UGX) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                       id="amount" name="amount" value="{{ old('amount') }}" required min="1">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="transaction_date">Transaction Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                                       id="transaction_date" name="transaction_date"
                                       value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="notes">Notes / Description</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Transaction Summary -->
                        <div class="alert alert-info mt-3" id="transaction-summary" style="display: none;">
                            <h6 class="alert-heading">Transaction Summary</h6>
                            <hr class="mt-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Current Balance:</strong> <span id="summary-current">UGX 0</span></p>
                                    <p class="mb-1"><strong>Transaction Amount:</strong> <span id="summary-amount">UGX 0</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>New Balance:</strong> <span id="summary-new">UGX 0</span></p>
                                    <p class="mb-1"><strong>Receipt #:</strong> <span id="summary-receipt">Will be generated</span></p>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="text-right">
                            <button type="reset" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Record Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .btn-group-toggle .btn {
        border-radius: 0.35rem !important;
        margin-right: 5px;
    }
    .btn-group-toggle .btn.active {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#user_id').select2({
            placeholder: 'Search for a member...',
            allowClear: true
        });

        // Load member details when selected
        $('#user_id').on('change', function() {
            const userId = $(this).val();
            $('#selected_user_id').val(userId);

            if (userId) {
                loadMemberDetails(userId);
            } else {
                $('#member-details-card').hide();
                $('#recent-transactions-card').hide();
                $('#transaction-summary').hide();
            }
        });

        // Load member details via AJAX
        function loadMemberDetails(userId) {
    console.log('Loading details for user ID:', userId);

    $.ajax({
        url: '/treasurer/savings/get-member-details',
        type: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            console.log('Response received:', response);
            updateMemberDetails(response);
            updateRecentTransactions(response.recent_transactions);
            $('#member-details-card').show();
            $('#recent-transactions-card').show();

            // Trigger summary update
            updateTransactionSummary();
        },
        error: function(xhr, status, error) {
            console.error('Error loading member details:', {
                status: status,
                error: error,
                response: xhr.responseJSON,
                statusCode: xhr.status
            });

            let errorMessage = 'Failed to load member details.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            alert(errorMessage);
        }
    });
}
       function updateMemberDetails(data) {
    // Format the balance properly
    const balance = data.savings_balance || 0;
    const formattedBalance = new Intl.NumberFormat(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(balance);

    let html = `
        <p class="mb-1"><strong>Name:</strong> ${data.name}</p>
        <p class="mb-1"><strong>Membership #:</strong> ${data.member_number || 'N/A'}</p>
        <p class="mb-1"><strong>Email:</strong> ${data.email}</p>
        <p class="mb-1"><strong>Phone:</strong> ${data.phone || 'N/A'}</p>
        <hr>
        <p class="mb-1"><strong>Current Balance:</strong> <span class="h5 text-success" id="current-balance">UGX ${formattedBalance}</span></p>
    `;
    $('#member-details').html(html);
}
        function updateRecentTransactions(transactions) {
            let html = '';
            if (transactions && transactions.length > 0) {
                transactions.forEach(t => {
                    html += `
                        <tr>
                            <td>${t.date}</td>
                            <td><span class="badge badge-${t.type === 'deposit' ? 'success' : 'info'}">${t.type}</span></td>
                            <td class="text-right">UGX ${t.amount}</td>
                            <td class="text-right">UGX ${t.balance}</td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="4" class="text-center py-3 text-muted">No recent transactions</td></tr>';
            }
            $('#recent-transactions-body').html(html);
        }

        function updateTransactionSummary() {
            const amount = parseFloat($('#amount').val()) || 0;
            const type = $('input[name="transaction_type"]:checked').val();
            const currentBalanceText = $('#current-balance').text();
            const currentBalance = parseFloat(currentBalanceText.replace(/[^0-9.-]+/g, '')) || 0;

            if (amount > 0 && type && currentBalanceText !== 'UGX undefined') {
                let newBalance = type === 'deposit' ? currentBalance + amount : currentBalance - amount;

                $('#summary-current').text('UGX ' + currentBalance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('#summary-amount').text('UGX ' + amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('#summary-new').text('UGX ' + newBalance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                let receiptPreview = 'RCT-' + new Date().toISOString().slice(0,10).replace(/-/g, '') + '-xxxxxx';
                $('#summary-receipt').text(receiptPreview);

                $('#transaction-summary').show();
            } else {
                $('#transaction-summary').hide();
            }
        }

        $('#amount, #transaction_type').on('input change', updateTransactionSummary);
        $('input[name="transaction_type"]').on('change', updateTransactionSummary);

        $('#transactionForm').on('submit', function(e) {
            const type = $('input[name="transaction_type"]:checked').val();
            const amount = parseFloat($('#amount').val()) || 0;
            const currentBalanceText = $('#current-balance').text();

            if (currentBalanceText === 'UGX undefined') {
                e.preventDefault();
                alert('Please select a member first.');
                return false;
            }

            const currentBalance = parseFloat(currentBalanceText.replace(/[^0-9.-]+/g, '')) || 0;

            if (type === 'withdrawal' && amount > currentBalance) {
                e.preventDefault();
                alert('Insufficient balance! Current balance: UGX ' + currentBalance.toLocaleString());
                return false;
            }
        });

        function resetForm() {
            $('#user_id').val(null).trigger('change');
            $('#selected_user_id').val('');
            $('#amount').val('');
            $('#notes').val('');
            $('#member-details-card').hide();
            $('#recent-transactions-card').hide();
            $('#transaction-summary').hide();
            $('input[name="transaction_type"][value="deposit"]').parent().addClass('active').siblings().removeClass('active');
            $('input[name="transaction_type"][value="deposit"]').prop('checked', true);
        }
    });
</script>
@endpush
