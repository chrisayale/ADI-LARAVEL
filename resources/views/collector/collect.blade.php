@extends('layouts.app')

@section('title', __('collect_payment'))

@section('styles')
<style>
    /* QR Scanner Modal Styles */
    #qr-reader {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    #qr-reader video {
        width: 100% !important;
        border-radius: 8px;
    }
    .qr-scanner-overlay {
        position: relative;
    }
    .qr-scanner-overlay::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border: 3px solid rgba(78, 115, 223, 0.8);
        border-radius: 8px;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);
        pointer-events: none;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-money-bill-wave"></i> {{ __('collect_payment') }}
                        </h5>
                        <div>
                            <a href="{{ route('collector.transactions.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-history me-1"></i> {{ __('view_transactions') }}
                            </a>
                            <a href="{{ route('collector.savings.create') }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-piggy-bank me-1"></i> {{ __('make_deposit_withdrawal') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ __('today_collections') }}
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($todayCollections, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                {{ __('monthly_collections') }}
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($monthlyCollections, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                {{ __('pending_repayments') }}
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $pendingRepayments->count() }}
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

                    <!-- Member Search -->
                    <div class="row mb-4">
                        <div class="col-md-8 offset-md-2">
                            <form method="GET" action="{{ route('collector.payments.collect') }}" class="mb-4" id="memberSearchForm">
                                <div class="input-group input-group-lg">
                                    <input type="text"
                                           name="search"
                                           id="search-input"
                                           class="form-control"
                                           placeholder="{{ __('search_payment_member_placeholder') }}"
                                           value="{{ $search }}"
                                           autofocus>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center px-4" id="btn-qr-scanner"
                                                title="{{ __('scan_member_qr_code') }}" data-toggle="tooltip"
                                                style="min-width: 56px; height: 100%;">
                                            <i class="fas fa-qrcode fa-lg"></i>
                                        </button>
                                        <button class="btn btn-success d-flex align-items-center px-4" type="submit"
                                                style="height: 100%;">
                                            <i class="fas fa-search mr-2"></i> {{ __('find_member') }}
                                        </button>
                                    </div>
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
                                    <p class="mb-1"><strong>{{ __('phone') }}:</strong> {{ $member->phone_number }}</p>
                                    <p class="mb-1"><strong>{{ __('zone') }}:</strong> {{ $member->collection_zone ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshMemberSummary({{ $member->id }})">
                                        <i class="fas fa-sync-alt"></i> {{ __('refresh') }}
                                    </button>
                                    <a href="{{ route('collector.payments.collect') }}" class="btn btn-sm btn-outline-secondary ml-2">
                                        <i class="fas fa-times"></i> {{ __('clear_search') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($loans->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('no_active_loans_found_for_member') }}
                            </div>
                        @else
                            <!-- Active Loans with Repayments -->
                            @foreach($loans as $loan)
                                @php
                                    $hasOverdue = $loan->repayments->where('due_date', '<', now())->where('status', '!=', 'paid')->count() > 0;
                                    $totalBalance = $loan->repayments->sum('effective_amount_due') - $loan->repayments->sum('amount_paid');
                                @endphp
                                <div class="card mb-3 border-{{ $hasOverdue ? 'danger' : 'success' }}">
                                    <div class="card-header bg-{{ $hasOverdue ? 'danger' : 'success' }} text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-file-invoice"></i>
                                                {{ __('loan') }} #{{ $loan->loan_reference }}
                                            </h5>
                                            <span class="badge bg-light text-dark">
                                                {{ __('balance') }}: {{ number_format($totalBalance, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}
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
                                                    <th>{{ __('amount_due') }} ({{ $sacco->primary_currency ?? 'CDF' }})</th>
                                                    <th>{{ __('amount_paid') }} ({{ $sacco->primary_currency ?? 'CDF' }})</th>
                                                    <th>{{ __('balance') }} ({{ $sacco->primary_currency ?? 'CDF' }})</th>
                                                    <th>{{ __('status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($loan->repayments as $repayment)
                                                    @php
                                                        $effectiveDue = $repayment->effective_amount_due;
                                                        $balance = $effectiveDue - $repayment->amount_paid;
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
                                                        <td>{{ number_format($effectiveDue, 2) }}</td>
                                                        <td>{{ number_format($repayment->amount_paid, 2) }}</td>
                                                        <td class="font-weight-bold">{{ number_format($balance, 2) }}</td>
                                                        <td>
                                                            @if($repayment->status == 'paid')
                                                                <span class="badge badge-success">{{ __('paid') }}</span>
                                                            @elseif($repayment->status == 'partial')
                                                                <span class="badge badge-warning">{{ __('partial') }}</span>
                                                            @elseif($isOverdue)
                                                                <span class="badge badge-danger">{{ __('overdue') }}</span>
                                                            @else
                                                                <span class="badge badge-secondary">{{ __('pending') }}</span>
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
                                    <form method="POST" action="{{ route('collector.payments.process') }}" id="paymentForm">
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
                                                    <label for="amount">{{ __('payment_amount') }} <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">{{ $sacco->primary_currency ?? 'CDF' }}</span>
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
                                                    <label for="payment_method">{{ __('payment_method') }} <span class="text-danger">*</span></label>
                                                    <select name="payment_method" id="payment_method" class="form-control form-control-lg" required>
                                                        <option value="cash">{{ __('cash') }}</option>
                                                        <option value="mpesa">{{ __('mpesa') }}</option>
                                                        <option value="airtel_money">{{ __('airtel_money') }}</option>
                                                        <option value="bank_transfer">{{ __('bank_transfer') }}</option>
                                                        <option value="cheque">{{ __('cheque') }}</option>
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
// ============================================
// QR CODE / MANUAL MEMBER SELECTION HANDLERS
// Global functions - must be defined outside document.ready for inline onclick handlers
// ============================================
let html5QrCodeScanner = null;
let isScanning = false;

// QR Code Scanner Modal HTML
const qrScannerModalHtml = `
    <div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0 rounded-lg">
                <div class="modal-header bg-primary text-white border-0 rounded-top-lg py-3 px-4">
                    <h5 class="modal-title mb-0" id="qrScannerModalLabel"><i class="fas fa-qrcode mr-2"></i>{{ __('scan_member_qr_code') }}</h5>
                    <button type="button" class="close text-white opacity-75 hover-opacity-100" data-dismiss="modal" aria-label="Close" onclick="stopQrScanner(); $('#qrScannerModal').modal('hide');" style="font-size: 1.5rem; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div id="qr-reader" class="mx-auto" style="width: 100%; max-width: 360px;"></div>
                    <div class="mt-4 text-center">
                        <div class="alert alert-info" id="qr-scanner-status" style="display: none;"></div>
                        <div class="alert alert-warning" id="https-warning" style="display: none;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>HTTPS requis :</strong> La caméra nécessite une connexion sécurisée (HTTPS).
                            <br>Sur localhost, utilisez <a href="#" onclick="showManualInputModal(); return false;" class="alert-link font-weight-bold">{{ __('manual_input') }}</a> ou configurez HTTPS local.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-lg py-3 px-4">
                    <div class="w-100 d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4 py-2 rounded-pill shadow-sm" data-dismiss="modal" onclick="stopQrScanner(); $('#qrScannerModal').modal('hide');">
                            <i class="fas fa-times mr-2"></i>{{ __('cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary btn-lg px-4 py-2 rounded-pill shadow-sm" onclick="showManualInputModal(); stopQrScanner();">
                            <i class="fas fa-keyboard mr-2"></i>{{ __('manual_input') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
`;

// Manual Input Modal HTML
const manualInputModalHtml = `
    <div class="modal fade" id="manualInputModal" tabindex="-1" role="dialog" aria-labelledby="manualInputModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0 rounded-lg">
                <div class="modal-header bg-secondary text-white border-0 rounded-top-lg py-3 px-4">
                    <h5 class="modal-title mb-0" id="manualInputModalLabel"><i class="fas fa-keyboard mr-2"></i>{{ __('manual_qr_code_entry') }}</h5>
                    <button type="button" class="close text-white opacity-75 hover-opacity-100" data-dismiss="modal" aria-label="Close" onclick="$('#manualInputModal').modal('hide');" style="font-size: 1.5rem; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="manual_qr_code" class="font-weight-bold text-gray-700 mb-2">{{ __('membership_number_or_qr') }}</label>
                        <input type="text" class="form-control form-control-lg text-center border-0 shadow-sm rounded-lg py-3" id="manual_qr_code"
                               placeholder="Ex: MEM-2024-001" autocomplete="off" autofocus
                               style="font-size: 1.25rem; letter-spacing: 0.1em;">
                        <small class="text-muted d-block mt-2">{{ __('enter_member_number_or_qr') }}</small>
                    </div>
                    <div class="alert alert-info mt-3" id="manual-input-status" style="display: none;"></div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-lg py-3 px-4">
                    <div class="w-100 d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4 py-2 rounded-pill shadow-sm" data-dismiss="modal" onclick="$('#manualInputModal').modal('hide');">
                            <i class="fas fa-times mr-2"></i>{{ __('cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary btn-lg px-4 py-2 rounded-pill shadow-sm" onclick="searchMemberByManualCode()">
                            <i class="fas fa-search mr-2"></i>{{ __('search') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
`;

// Global function: Show manual input modal
function showManualInputModal() {
    $('#manualInputModal').modal('show');
    setTimeout(() => {
        const input = document.getElementById('manual_qr_code');
        if (input) input.focus();
    }, 300);
}

// Global function: Stop QR scanner
function stopQrScanner() {
    if (html5QrCodeScanner && isScanning) {
        html5QrCodeScanner.clear().catch(err => console.warn('QR Scanner clear error:', err));
        isScanning = false;
    }
    $('#qrScannerModal').modal('hide');
}

// Global function: Search member by manual code input
function searchMemberByManualCode() {
    const input = document.getElementById('manual_qr_code');
    const statusDiv = document.getElementById('manual-input-status');
    const qrCode = input?.value?.trim();

    if (!qrCode) {
        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> {{ __('enter_member_code') }}';
            statusDiv.className = 'alert alert-warning';
            statusDiv.style.display = 'block';
        }
        input?.focus();
        return;
    }

    if (statusDiv) {
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('searching_member') }}...';
        statusDiv.className = 'alert alert-info';
        statusDiv.style.display = 'block';
    }

    fetch('{{ route("collector.payments.find-member-qr") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ qr_code: qrCode })
    })
    .then(response => {
        if (!response.ok) {
            let errorMsg = '';
            switch (response.status) {
                case 404:
                    errorMsg = '{{ __('route_not_found') }}';
                    break;
                case 422:
                    errorMsg = '{{ __('invalid_data_sent') }}';
                    break;
                case 500:
                    errorMsg = '{{ __('server_error') }}';
                    break;
                case 419:
                    errorMsg = '{{ __('session_expired') }}';
                    break;
                default:
                    errorMsg = '{{ __('server_communication_error') }} (' + response.status + ').';
            }
            return response.json().catch(() => ({})).then(err => {
                throw new Error(errorMsg + (err.message ? ' - ' + err.message : ''));
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.member) {
            selectMember(data.member);
            $('#manualInputModal').modal('hide');
            showToast('success', '{{ __('member_found') }} ' + data.member.name);
        } else {
            throw new Error(data.message || '{{ __('member_not_found') }}');
        }
    })
    .catch(error => {
        console.error('Manual Search error:', error);
        let msg = error.message || '{{ __('search_error') }}';

        if (msg.includes('{{ __('no_member_found_with_search') }}')) {
            msg = '{{ __('no_member_found_verify') }}';
        }

        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
            statusDiv.className = 'alert alert-danger';
            statusDiv.style.display = 'block';
        }
    });
}

// Global function: Check camera permission and HTTPS requirement
function checkCameraPermission() {
    const statusDiv = document.getElementById('qr-scanner-status');
    const httpsWarning = document.getElementById('https-warning');
    const readerDiv = document.getElementById('qr-reader');

    // If elements don't exist, fail silently (modal not ready or not our modal)
    if (!statusDiv || !httpsWarning || !readerDiv) {
        console.debug('QR scanner modal elements not found');
        return;
    }

    const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    const isSecureContext = window.isSecureContext || window.location.protocol === 'https:';

    if (!isSecureContext && isLocalhost) {
        // Show HTTPS warning
        httpsWarning.style.display = 'block';
        readerDiv.style.display = 'none';
        statusDiv.style.display = 'none';
        return;
    } else {
        httpsWarning.style.display = 'none';
        readerDiv.style.display = 'block';
    }

    // Try to start scanner
    startQrScanner();
}

// Global function: Start QR Code Scanner using html5-qrcode library
function startQrScanner() {
    if (isScanning) return;

    // Check if html5-qrcode is available
    if (typeof Html5QrcodeScanner === 'undefined') {
        // Load the library dynamically
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js';
        script.onload = () => initQrScanner();
        script.onerror = () => {
            const statusDiv = document.getElementById('qr-scanner-status');
            const readerDiv = document.getElementById('qr-reader');
            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> {{ __('scanner_load_error') }}';
                statusDiv.style.display = 'block';
            }
            if (readerDiv) {
                readerDiv.style.display = 'none';
            }
        };
        document.head.appendChild(script);
    } else {
        initQrScanner();
    }
}

// Global function: Initialize QR Scanner
function initQrScanner() {
    const readerDiv = document.getElementById('qr-reader');
    const statusDiv = document.getElementById('qr-scanner-status');

    if (!readerDiv) return;

    // Check camera permission before initializing
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                // Stop the test stream
                stream.getTracks().forEach(track => track.stop());
                initHtml5QrCodeScanner();
            })
            .catch(err => {
                console.error('Camera access error:', err);
                if (statusDiv) {
                    let msg = '{{ __('camera_access_error') }} ';
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        msg += '{{ __('camera_permission_denied') }}';
                    } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                        msg += '{{ __('no_camera_detected') }}';
                    } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                        msg += '{{ __('camera_in_use') }}';
                    } else if (err.name === 'OverconstrainedError') {
                        msg += '{{ __('camera_config_error') }}';
                    } else {
                        msg += '{{ __('error') }}: ' + err.message;
                    }
                    msg += ' <button type="button" class="btn btn-sm btn-outline-primary ml-2" onclick="showManualInputModal(); stopQrScanner();"><i class="fas fa-keyboard"></i> {{ __('manual_input') }}</button>';
                    statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
                    statusDiv.className = 'alert alert-danger';
                    statusDiv.style.display = 'block';
                }
                if (readerDiv) {
                    readerDiv.style.display = 'none';
                }
            });
    } else {
        // Fallback if getUserMedia not available
        initHtml5QrCodeScanner();
    }

    function initHtml5QrCodeScanner() {
        html5QrCodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                rememberLastUsedCamera: true,
                showTorchButtonIfSupported: true,
                showZoomSliderIfSupported: true
            },
            false
        );

        html5QrCodeScanner.render(
            onQrScanSuccess,
            onQrScanFailure
        );

        isScanning = true;
        if (statusDiv) statusDiv.style.display = 'none';
        readerDiv.style.display = 'block';
    }
}

// Global function: QR Scan Success Callback
function onQrScanSuccess(decodedText, decodedResult) {
    // Stop scanner after successful scan
    stopQrScanner();

    // Search for member
    searchMemberByQrCode(decodedText);
}

// Global function: QR Scan Failure Callback
function onQrScanFailure(error) {
    // Silently handle scan failures (continuous scanning)
    // Only show if it's a permission error
    if (error && error.name === 'NotAllowedError') {
        const statusDiv = document.getElementById('qr-scanner-status');
        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> {{ __('camera_access_denied') }}';
            statusDiv.className = 'alert alert-danger';
            statusDiv.style.display = 'block';
        }
        stopQrScanner();
    }
}

// Global function: Search member by QR code (AJAX)
function searchMemberByQrCode(qrCode) {
    const statusDiv = document.getElementById('qr-scanner-status');
    if (statusDiv) {
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('searching_member') }}...';
        statusDiv.className = 'alert alert-info';
        statusDiv.style.display = 'block';
    }

    fetch('{{ route("collector.payments.find-member-qr") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ qr_code: qrCode })
    })
    .then(response => {
        // Handle HTTP errors (404, 500, etc.) with user-friendly messages
        if (!response.ok) {
            let errorMsg = '';
            switch (response.status) {
                case 404:
                    errorMsg = '{{ __('route_not_found') }}';
                    break;
                case 422:
                    errorMsg = '{{ __('invalid_data_sent') }}';
                    break;
                case 500:
                    errorMsg = '{{ __('server_error') }}';
                    break;
                case 419:
                    errorMsg = '{{ __('session_expired') }}';
                    break;
                default:
                    errorMsg = '{{ __('server_communication_error') }} (' + response.status + ').';
            }
            return response.json().catch(() => ({})).then(err => {
                throw new Error(errorMsg + (err.message ? ' - ' + err.message : ''));
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.member) {
            selectMember(data.member);
            $('#qrScannerModal').modal('hide');
            showToast('success', '{{ __('member_found') }} ' + data.member.name);
        } else {
            throw new Error(data.message || '{{ __('member_not_found') }}');
        }
    })
    .catch(error => {
        console.error('QR Search error:', error);
        let msg = error.message || '{{ __('search_error') }}';

        // Don't show technical 404 message for "member not found" (that's expected)
        if (msg.includes('{{ __('no_member_found_with_search') }}')) {
            msg = '{{ __('no_member_found_verify') }}';
        }

        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
            statusDiv.className = 'alert alert-danger';
            statusDiv.style.display = 'block';
        }
        // Don't hide modal, let user retry or switch to manual
    });
}

// Global function: Fill search input and submit form
function selectMember(member) {
    const searchInput = document.getElementById('search-input');
    if (!searchInput) return;

    // Fill the search input with membership number
    searchInput.value = member.membership_number || member.id;

    // Visual feedback
    searchInput.classList.add('is-valid');
    searchInput.classList.remove('is-invalid');
    setTimeout(() => searchInput.classList.remove('is-valid'), 2000);

    // Submit the form
    const form = searchInput.closest('form');
    if (form) {
        form.submit();
    }
}

// Global function: Toast notification helper
function showToast(type, message) {
    // Create toast if not exists
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    toast.style.cssText = 'min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    toast.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    `;
    toastContainer.appendChild(toast);

    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
}

$(document).ready(function() {

    // Check if we're on localhost (insecure context for camera)
    const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    const isSecureContext = window.isSecureContext || window.location.protocol === 'https:';

    // Inject modals into DOM
    document.body.insertAdjacentHTML('beforeend', qrScannerModalHtml);
    document.body.insertAdjacentHTML('beforeend', manualInputModalHtml);

    // Open QR Scanner Modal
    document.getElementById('btn-qr-scanner')?.addEventListener('click', function() {
        $('#qrScannerModal').modal('show');
    });

    // Check camera permission and HTTPS requirement - called when modal is fully shown
    $('#qrScannerModal').on('shown.bs.modal', function() {
        checkCameraPermission();
    });

    // Clean up scanner when modals are hidden
    $('#qrScannerModal').on('hidden.bs.modal', function() {
        stopQrScanner();
    });

    $('#manualInputModal').on('hidden.bs.modal', function() {
        const input = document.getElementById('manual_qr_code');
        const statusDiv = document.getElementById('manual-input-status');
        if (input) input.value = '';
        if (statusDiv) {
            statusDiv.style.display = 'none';
            statusDiv.textContent = '';
        }
    });

    // Explicit cancel button handlers (fallback for data-bs-dismiss)
    document.querySelectorAll('#qrScannerModal .btn-secondary[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            stopQrScanner();
            $('#qrScannerModal').modal('hide');
        });
    });

    document.querySelectorAll('#manualInputModal .btn-secondary[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            $('#manualInputModal').modal('hide');
        });
    });

    // Allow Enter key in manual input
    document.getElementById('manual_qr_code')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchMemberByManualCode();
        }
    });

    // ============================================
    // PAYMENT COLLECTION LOGIC
    // ============================================
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

    // Form submission with AJAX
    $('#paymentForm').on('submit', function(e) {
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
                    // Redirect to receipt page
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        toastr.success(response.message || '{{ __('payment_collected_success') }}');
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    toastr.error(response.message || '{{ __('error_occurred') }}');
                    btn.prop('disabled', false).html(originalText);
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
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // ===============================
    // Refresh member summary (AJAX)
    // ===============================
    window.refreshMemberSummary = function(memberId) {
        const btn = $(`button[onclick="refreshMemberSummary(${memberId})"]`);
        const originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '{{ route("collector.payments.get-member-details") }}',
            method: 'GET',
            data: { member_id: memberId },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    toastr.error(response.message || '{{ __('error_occurred') }}');
                }
            },
            error: function() {
                toastr.error('{{ __('error_occurred') }}');
            },
            complete: function() {
                btn.html(originalHtml).prop('disabled', false);
            }
        });
    };

});
</script>
@endpush