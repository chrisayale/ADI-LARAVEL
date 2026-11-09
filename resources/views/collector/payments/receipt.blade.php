@extends('layouts.app')

@section('title', __('receipt') . ' - ' . $transaction->receipt_number)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-receipt"></i> {{ __('payment_receipt') }}
                        </h6>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> {{ __('print') }}
                            </button>
                            <a href="{{ route('collector.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('back_to_collection') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Receipt Header -->
                    <div class="text-center mb-4">
                        <h4 class="text-primary mb-1">{{ $sacco->name ?? 'SACCO' }}</h4>
                        <p class="text-muted mb-1">{{ $sacco->official_email ?? '' }}</p>
                        <p class="text-muted mb-0">{{ $sacco->physical_address ?? '' }}</p>
                        <hr class="my-3">
                        <h5 class="mb-0">{{ __('receipt_number') }}: <strong>{{ $transaction->receipt_number }}</strong></h5>
                        <small class="text-muted">{{ __('transaction_number') }}: {{ $transaction->transaction_number }}</small>
                    </div>

                    <!-- Member Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">{{ __('member_info') }}</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold">{{ __('name') }}:</td>
                                    <td>{{ $transaction->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('membership_number') }}:</td>
                                    <td>{{ $transaction->user->membership_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('phone') }}:</td>
                                    <td>{{ $transaction->user->phone_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('zone') }}:</td>
                                    <td>{{ $transaction->user->collection_zone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">{{ __('payment_details') }}</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold">{{ __('date') }}:</td>
                                    <td>{{ $transaction->payment_date->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('payment_method') }}:</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->payment_method === 'cash' ? 'success' : ($transaction->payment_method === 'mpesa' || $transaction->payment_method === 'mobile_money' ? 'info' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('reference') }}:</td>
                                    <td>{{ $transaction->reference_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('collected_by') }}:</td>
                                    <td>{{ $transaction->recorder->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Loan Details -->
                    @if($transaction->loanRepayment && $transaction->loanRepayment->loanApplication)
                    <div class="card bg-light mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">{{ __('loan_details') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>{{ __('loan_reference') }}:</strong> {{ $transaction->loanRepayment->loanApplication->loan_reference }}</p>
                                    <p class="mb-1"><strong>{{ __('installment') }}:</strong> #{{ $transaction->loanRepayment->installment_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>{{ __('due_date') }}:</strong> {{ $transaction->loanRepayment->due_date->format('d/m/Y') }}</p>
                                    <p class="mb-1"><strong>{{ __('status') }}:</strong>
                                        <span class="badge badge-{{ $transaction->loanRepayment->status === 'paid' ? 'success' : ($transaction->loanRepayment->status === 'partial' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($transaction->loanRepayment->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Amount Breakdown -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">{{ __('amount_breakdown') }} ({{ $sacco->primary_currency ?? 'CDF' }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>{{ __('principal_paid') }}</td>
                                            <td class="text-right font-weight-bold">{{ number_format($transaction->amount, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h5 class="text-primary">
                                        {{ __('total_paid') }}: <strong>{{ number_format($transaction->amount, 2) }} {{ $sacco->primary_currency ?? 'CDF' }}</strong>
                                    </h5>
                                </div>
                            </div>
                            @if($transaction->notes)
                            <hr>
                            <p class="mb-0"><strong>{{ __('notes') }}:</strong> {{ $transaction->notes }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Signature Area -->
                    <div class="row mt-5">
                        <div class="col-md-6 text-center">
                            <div class="border-top pt-2">
                                <small class="text-muted">{{ __('collector_signature') }}</small>
                                <br>
                                <span class="font-weight-bold">{{ $transaction->recorder->name ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="border-top pt-2">
                                <small class="text-muted">{{ __('member_signature') }}</small>
                                <br>
                                <span class="font-weight-bold">{{ $transaction->user->name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4 text-muted small">
                        <p class="mb-1">{{ __('thank_you_for_payment') }}</p>
                        <p class="mb-0">{{ __('keep_this_receipt') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-print if URL has ?print=true
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            window.print();
        }
    });
</script>
@endpush