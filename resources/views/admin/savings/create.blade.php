@extends('layouts.app')

@section('title', __('record_savings_transaction'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('record_savings_transaction') }}</h1>
        <a href="{{ route('admin.savings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- Error Message -->
    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">{{ __('validation_errors') }}:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Member Selection Column -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('select_member') }}</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="member_search">{{ __('search_member') }}</label>
                        <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                            <option value="">{{ __('select_a_member') }}</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}"
                                    data-membership="{{ $member->membership_number }}"
                                    data-email="{{ $member->email }}"
                                    data-phone="{{ $member->phone_number }}"
                                    {{ $selectedMember && $selectedMember->id == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->membership_number ?? __('no_number') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Member Details Card (shown when member selected) -->
                    <div id="member-details-card" class="mt-3" style="{{ $selectedMember ? 'display: block;' : 'display: none;' }}">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold">{{ __('member_details') }}</h6>
                                <hr class="mt-0">
                                <div id="member-details">
                                    @if($selectedMember)
                                    <p class="mb-1"><strong>{{ __('name') }}:</strong> {{ $selectedMember->name }}</p>
                                    <p class="mb-1"><strong>{{ __('membership_number') }}:</strong> {{ $selectedMember->membership_number ?? __('n_a') }}</p>
                                    <p class="mb-1"><strong>{{ __('email') }}:</strong> {{ $selectedMember->email }}</p>
                                    <p class="mb-1"><strong>{{ __('phone') }}:</strong> {{ $selectedMember->phone_number ?? __('n_a') }}</p>
                                    <hr>
                                    <p class="mb-1"><strong>{{ __('current_balance') }}:</strong> <span class="h5 text-success" id="current-balance">UGX {{ number_format($currentBalance, 2) }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Card -->
            <div id="recent-transactions-card" class="card shadow mb-4" style="{{ $selectedMember ? 'display: block;' : 'display: none;' }}">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_transactions') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('balance') }}</th>
                                </tr>
                            </thead>
                            <tbody id="recent-transactions-body">
                                @if($selectedMember && $recentTransactions->count() > 0)
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->type_color }}">
                                                {{ $transaction->transaction_type == 'deposit' ? __('deposit') : __('withdrawal') }}
                                            </span>
                                        </td>
                                        <td class="text-right">UGX {{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-right">UGX {{ number_format($transaction->balance_after, 2) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">
                                            {{ __('no_recent_transactions') }}
                                        </td>
                                    </tr>
                                @endif
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('transaction_details') }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.savings.store') }}" id="transactionForm">
                        @csrf
                        <input type="hidden" id="selected_user_id" name="user_id" value="{{ $selectedMember->id ?? '' }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="transaction_type">{{ __('transaction_type') }} <span class="text-danger">*</span></label>
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-success {{ old('transaction_type') == 'deposit' ? 'active' : '' }}">
                                        <input type="radio" name="transaction_type" id="deposit" value="deposit"
                                            {{ old('transaction_type', 'deposit') == 'deposit' ? 'checked' : '' }} autocomplete="off">
                                        <i class="fas fa-arrow-down mr-1"></i> {{ __('deposit') }}
                                    </label>
                                    <label class="btn btn-outline-info {{ old('transaction_type') == 'withdrawal' ? 'active' : '' }}">
                                        <input type="radio" name="transaction_type" id="withdrawal" value="withdrawal"
                                            {{ old('transaction_type') == 'withdrawal' ? 'checked' : '' }} autocomplete="off">
                                        <i class="fas fa-arrow-up mr-1"></i> {{ __('withdrawal') }}
                                    </label>
                                </div>
                                @error('transaction_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amount">{{ __('amount_ugx') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                       id="amount" name="amount" value="{{ old('amount') }}" required min="1">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="transaction_date">{{ __('transaction_date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                                       id="transaction_date" name="transaction_date"
                                       value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status">{{ __('status') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="approved" {{ old('status', 'approved') == 'approved' ? 'selected' : '' }}>{{ translate_status('approved') }} ({{ __('complete_now') }})</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ translate_status('pending') }} ({{ __('requires_approval') }})</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('select_pending_if_review') }}</small>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">{{ __('notes_description') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaction Summary -->
                        <div class="alert alert-info mt-3" id="transaction-summary" style="display: none;">
                            <h6 class="alert-heading">{{ __('transaction_summary') }}</h6>
                            <hr class="mt-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>{{ __('current_balance') }}:</strong> <span id="summary-current">UGX 0</span></p>
                                    <p class="mb-1"><strong>{{ __('transaction_amount') }}:</strong> <span id="summary-amount">UGX 0</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>{{ __('new_balance') }}:</strong> <span id="summary-new">UGX 0</span></p>
                                    <p class="mb-1"><strong>{{ __('status') }}:</strong> <span id="summary-status">{{ translate_status('approved') }}</span></p>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="text-right">
                            <button type="reset" class="btn btn-secondary">{{ __('reset') }}</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> {{ __('record_transaction') }}
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
<!-- jQuery is already loaded in your layout, but if not, uncomment the next line -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        console.log('Document ready, initializing Select2...');

        // Initialize Select2
        $('#user_id').select2({
            placeholder: '{{ __('search_for_a_member') }}',
            allowClear: true,
            theme: 'default'
        });

        // Load member details when selected
        $('#user_id').on('change', function() {
            const userId = $(this).val();
            console.log('Selected user ID:', userId);

            $('#selected_user_id').val(userId);

            if (userId) {
                loadMemberDetails(userId);
            } else {
                $('#member-details-card').hide();
                $('#recent-transactions-card').hide();
                $('#selected_user_id').val('');
                $('#transaction-summary').hide();
            }
        });

        // Load member details via AJAX
        function loadMemberDetails(userId) {
            console.log('Loading details for user ID:', userId);

            $.ajax({
                url: '/admin/savings/get-member-details',
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

                    let errorMessage = '{{ __('failed_to_load_member_details') }}';
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
        <p class="mb-1"><strong>{{ __('name') }}:</strong> ${data.name}</p>
        <p class="mb-1"><strong>{{ __('membership_number') }}:</strong> ${data.member_number || '{{ __('n_a') }}'}</p>
        <p class="mb-1"><strong>{{ __('email') }}:</strong> ${data.email}</p>
        <p class="mb-1"><strong>{{ __('phone') }}:</strong> ${data.phone || '{{ __('n_a') }}'}</p>
        <hr>
        <p class="mb-1"><strong>{{ __('current_balance') }}:</strong> <span class="h5 text-success" id="current-balance">UGX ${formattedBalance}</span></p>
    `;
    $('#member-details').html(html);
}

function updateRecentTransactions(transactions) {
    let html = '';
    if (transactions && transactions.length > 0) {
        transactions.forEach(t => {
            // Format amount with commas
            const amount = new Intl.NumberFormat(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(t.amount);

            // Determine badge class based on transaction type
            const badgeClass = t.type === 'deposit' ? 'success' :
                              t.type === 'withdrawal' ? 'info' : 'secondary';

            html += `
                <tr>
                    <td>${t.date}</td>
                    <td><span class="badge badge-${badgeClass}">${t.type}</span></td>
                    <td class="text-right">UGX ${amount}</td>
                    <td class="text-right">UGX ${t.balance || '{{ __('n_a') }}'}</td>
                </tr>
            `;
        });
    } else {
        html = '<tr><td colspan="4" class="text-center py-3 text-muted">{{ __('no_recent_transactions') }}</td></tr>';
    }
    $('#recent-transactions-body').html(html);
}

function updateTransactionSummary() {
    const amount = parseFloat($('#amount').val()) || 0;
    const type = $('input[name="transaction_type"]:checked').val();

    // Get current balance from the displayed text
    const currentBalanceText = $('#current-balance').text();
    // Extract number from "UGX 880,000.00" format
    const currentBalance = parseFloat(currentBalanceText.replace(/[^0-9.-]+/g, '')) || 0;

    if (amount > 0 && type && currentBalanceText !== 'UGX undefined') {
        let newBalance = type === 'deposit' ? currentBalance + amount : currentBalance - amount;

        // Format numbers with commas
        const formatNumber = (num) => {
            return new Intl.NumberFormat(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        };

        $('#summary-current').text('UGX ' + formatNumber(currentBalance));
        $('#summary-amount').text('UGX ' + formatNumber(amount));
        $('#summary-new').text('UGX ' + formatNumber(newBalance));

        // Generate receipt preview
        let receiptPreview = 'RCT-' + new Date().toISOString().slice(0,10).replace(/-/g, '') + '-xxxxxx';
        $('#summary-receipt').text(receiptPreview);

        $('#transaction-summary').show();
    } else {
        $('#transaction-summary').hide();
    }
}
        // Event listeners for summary updates
        $('#amount, #status').on('input change', function() {
            updateTransactionSummary();
        });

        $('input[name="transaction_type"]').on('change', function() {
            updateTransactionSummary();
        });

        // Validate withdrawal amount
        $('#transactionForm').on('submit', function(e) {
    const type = $('input[name="transaction_type"]:checked').val();
    const amount = parseFloat($('#amount').val()) || 0;
    const currentBalanceText = $('#current-balance').text();

    if (currentBalanceText === 'UGX undefined' || currentBalanceText === 'UGX NaN') {
        e.preventDefault();
        alert('{{ __('please_select_valid_member') }}');
        return false;
    }

    const currentBalance = parseFloat(currentBalanceText.replace(/[^0-9.-]+/g, '')) || 0;

    if (type === 'withdrawal' && amount > currentBalance) {
        e.preventDefault();
        alert('{{ __('insufficient_balance') }}! {{ __('current_balance') }}: UGX ' +
              new Intl.NumberFormat(undefined, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
              }).format(currentBalance));
        return false;
    }

    // Make sure user_id is set
    if (!$('#selected_user_id').val()) {
        e.preventDefault();
        alert('{{ __('please_select_member_first') }}');
        return false;
    }
});

        // If member is pre-selected, load their details
        @if($selectedMember)
            loadMemberDetails({{ $selectedMember->id }});
        @endif
    });
</script>
@endpush