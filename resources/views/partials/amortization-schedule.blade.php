@if($loan->repayments && $loan->repayments->count() > 0)
    @php
        $periodCount = $loan->repayments->count();
        $isPatoNdogoDaily = ($loan->amortization_method === 'pato_ndogo' && $loan->repayment_frequency === 'daily');

        // Adjusted amount (rounded for Pato Ndogo daily)
        $adjustedAmount = $loan->adjusted_daily_amount ?? ($isPatoNdogoDaily
            ? ceil($loan->monthly_repayment / 100) * 100
            : $loan->monthly_repayment);

        $constantPrincipal = $loan->amount_approved / $periodCount;
        $totalAdjustedDue = $adjustedAmount * $periodCount;
        $totalPaid = $loan->repayments->sum('amount_paid');
        $totalPenalties = $loan->repayments->sum('penalty_amount');
        $globalBalance = $totalAdjustedDue + $totalPenalties - $totalPaid;
        if ($globalBalance < 0) $globalBalance = 0;

        // Theoretical components for Pato Ndogo daily: EXACT simple division
        if ($isPatoNdogoDaily) {
            $theoreticalRate = (($periodCount / 30) * 10) / 100;
            $theoreticalTotalInterest = $loan->amount_approved * $theoreticalRate;
            $constantInterest = $theoreticalTotalInterest / $periodCount;
            $theoreticalAmount = $constantPrincipal + $constantInterest;
        } else {
            $theoreticalAnnualRate = $loan->interest_rate / 100;
            $theoreticalPeriodicRate = $theoreticalAnnualRate /
                ($loan->repayment_frequency === 'daily' ? 365 :
                ($loan->repayment_frequency === 'weekly' ? 52 : 12));
        }
    @endphp

<div class="card shadow mb-4 border-0 rounded-xl">
    <div class="card-header bg-white border-bottom py-4 px-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="m-0 font-weight-bold text-gray-800 d-flex align-items-center">
                <i class="fas fa-table text-primary me-2"></i> {{ __('amortization_schedule') }}
            </h5>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                    {{ __('total_due_adjusted') }}: <strong class="monospace">{{ number_format($totalAdjustedDue, 2, ',', ' ') }} {{ __('CDF') }}</strong>
                </span>
                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                    {{ __('penalties') }}: <strong class="monospace">{{ number_format($totalPenalties, 2, ',', ' ') }} {{ __('CDF') }}</strong>
                </span>
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                    {{ __('total_paid') }}: <strong class="monospace">{{ number_format($totalPaid, 2, ',', ' ') }} {{ __('CDF') }}</strong>
                </span>
                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                    {{ __('global_balance') }}: <strong class="monospace">{{ number_format($globalBalance, 2, ',', ' ') }} {{ __('CDF') }}</strong>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <!-- Progress Bar -->
        <div class="px-5 py-4 border-bottom bg-light">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="m-0 font-weight-semibold text-gray-700">{{ __('repayment_progress') }}</h6>
                <span class="text-gray-600 fw-medium">{{ $loan->progress_percentage }}% {{ __('paid') }}</span>
            </div>
            <div class="progress" style="height: 10px;" role="progressbar"
                 aria-valuenow="{{ $loan->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar bg-success rounded-pill"
                     style="width: {{ $loan->progress_percentage }}%;"></div>
            </div>
        </div>

        <!-- Amortization Table -->
        <div class="table-responsive p-0">
            <table class="table table-hover mb-0 amortization-table" id="amortizationTable">
                <thead class="thead-light bg-gray-50">
                    <tr>
                        <th class="text-center px-3 py-3" style="min-width: 50px; white-space: nowrap;">N°</th>
                        <th class="text-center px-3 py-3" style="min-width: 110px; white-space: nowrap;">{{ __('due_date') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 130px; white-space: nowrap;">{{ __('adjusted_due_amount') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 120px; white-space: nowrap;">{{ __('principal_constant') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 120px; white-space: nowrap;">{{ __('interest_constant') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 140px; white-space: nowrap;">{{ __('theoretical_amount_exact') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 110px; white-space: nowrap;">{{ __('gap') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 110px; white-space: nowrap;">{{ __('amount_paid') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 100px; white-space: nowrap;">{{ __('penalty') }}</th>
                        <th class="text-end px-3 py-3" style="min-width: 120px; white-space: nowrap;">{{ __('global_balance') }}</th>
                        <th class="text-center px-3 py-3" style="min-width: 100px; white-space: nowrap;">{{ __('status') }}</th>
                        <th class="text-center px-3 py-3" style="min-width: 110px; white-space: nowrap;">{{ __('payment_date') }}</th>
                        <th class="text-center px-3 py-3" style="min-width: 60px; white-space: nowrap;">{{ __('receipt') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cumulativePaid = 0;
                        $cumulativePenalties = 0;
                        $remainingBalance = null;
                    @endphp
                    @foreach($loan->repayments as $repayment)
                        @php
                            if ($isPatoNdogoDaily) {
                                // EXACT theoretical (constant per day)
                                $theoreticalInterest = $constantInterest;
                                $theoreticalAmt = $theoreticalAmount;
                            } else {
                                // Standard declining balance for other methods
                                if (!isset($remainingBalance)) {
                                    $remainingBalance = $loan->amount_approved;
                                }
                                $theoreticalInterest = $remainingBalance * $theoreticalPeriodicRate;
                                $theoreticalAmt = $constantPrincipal + $theoreticalInterest;
                                $remainingBalance -= $constantPrincipal;
                            }

                            // Gap = Adjusted - Theoretical (exact)
                            $gap = $adjustedAmount - $theoreticalAmt;

                            // Update cumulative paid and penalties
                            $cumulativePaid += $repayment->amount_paid ?? 0;
                            $cumulativePenalties += $repayment->penalty_amount ?? 0;

                            // NEW BALANCE LOGIC: Solde global = Total Adjusted Due + Total Penalties - Cumulative Paid
                            $rowGlobalBalance = $totalAdjustedDue + $cumulativePenalties - $cumulativePaid;
                            if ($rowGlobalBalance < 0) $rowGlobalBalance = 0;

                            $statusColor = match($repayment->status) {
                                'paid' => 'success',
                                'partial' => 'info',
                                'overdue' => 'danger',
                                default => 'warning'
                            };

                            $statusLabel = match($repayment->status) {
                                'paid' => __('paid'),
                                'partial' => __('partial'),
                                'overdue' => __('overdue'),
                                default => __('pending')
                            };
                        @endphp
                        <tr class="{{ $repayment->status === 'overdue' ? 'table-danger-light' : ($repayment->status === 'paid' ? 'table-success-light' : '') }}">
                            <td class="text-center px-3 py-3 align-middle">{{ $repayment->installment_number }}</td>
                            <td class="text-center px-3 py-3 align-middle monospace">{{ $repayment->due_date->format('d M Y') }}</td>
                            <td class="text-end px-3 py-3 align-middle monospace fw-semibold text-primary">
                                {{ number_format($adjustedAmount, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace">
                                {{ number_format($constantPrincipal, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace">
                                {{ number_format($theoreticalInterest, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace text-muted small">
                                {{ number_format($theoreticalAmt, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace fw-bold
                                {{ $gap >= 0 ? 'text-danger' : 'text-success' }}">
                                {{ $gap >= 0 ? '+' : '' }}{{ number_format($gap, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace
                                {{ ($repayment->amount_paid ?? 0) > 0 ? 'fw-semibold text-success' : 'text-muted' }}">
                                {{ number_format($repayment->amount_paid ?? 0, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace
                                {{ ($repayment->penalty_amount ?? 0) > 0 ? 'fw-bold text-danger' : 'text-muted' }}">
                                {{ number_format($repayment->penalty_amount ?? 0, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-end px-3 py-3 align-middle monospace fw-bold text-dark">
                                {{ number_format($rowGlobalBalance, 2, ',', ' ') }} {{ __('CDF') }}
                            </td>
                            <td class="text-center px-3 py-3 align-middle">
                                <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} px-3 py-2 rounded-pill fw-medium">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="text-center px-3 py-3 align-middle text-muted monospace">
                                {{ $repayment->paid_date ? $repayment->paid_date->format('d M Y') : '—' }}
                            </td>
                            <td class="text-center px-3 py-3 align-middle">
                                @if($repayment->receipt_number)
                                    <a href="#" class="btn btn-sm btn-outline-primary rounded-circle p-1" title="{{ __('view_receipt') }}" data-bs-toggle="tooltip">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="thead-light bg-gray-50 fw-bold">
                    @php
                        // Calculate totals for footer
                        $totalPaidFooter = $loan->repayments->sum('amount_paid');
                        $totalPenaltiesFooter = $loan->repayments->sum('penalty_amount');
                        $finalGlobalBalance = $totalAdjustedDue + $totalPenaltiesFooter - $totalPaidFooter;
                        if ($finalGlobalBalance < 0) $finalGlobalBalance = 0;

                        if ($isPatoNdogoDaily) {
                            $totalTheoreticalInterest = $constantInterest * $periodCount;
                            $totalTheoreticalAmount = $theoreticalAmount * $periodCount;
                            $totalGap = ($adjustedAmount * $periodCount) - $totalTheoreticalAmount;
                        } else {
                            $totalTheoreticalInterest = 0;
                            $totalTheoreticalAmount = 0;
                            $balanceForFooter = $loan->amount_approved;
                            for ($i = 0; $i < $periodCount; $i++) {
                                $int = $balanceForFooter * $theoreticalPeriodicRate;
                                $totalTheoreticalInterest += $int;
                                $totalTheoreticalAmount += $constantPrincipal + $int;
                                $balanceForFooter -= $constantPrincipal;
                            }
                            $totalGap = ($adjustedAmount * $periodCount) - $totalTheoreticalAmount;
                        }
                    @endphp
                    <tr class="border-top-2">
                        <th colspan="2" class="text-end px-3 py-3 align-middle">{{ __('total') }}</th>
                        <th class="text-end px-3 py-3 align-middle monospace">
                            {{ number_format($totalAdjustedDue, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace">
                            {{ number_format($loan->amount_approved, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace">
                            {{ number_format($totalTheoreticalInterest, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace text-muted small">
                            {{ number_format($totalTheoreticalAmount, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace fw-bold
                            {{ $totalGap >= 0 ? 'text-danger' : 'text-success' }}">
                            {{ $totalGap >= 0 ? '+' : '' }}{{ number_format($totalGap, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace">
                            {{ number_format($totalPaidFooter, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace fw-bold text-danger">
                            {{ number_format($totalPenaltiesFooter, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th class="text-end px-3 py-3 align-middle monospace text-dark">
                            {{ number_format($finalGlobalBalance, 2, ',', ' ') }} {{ __('CDF') }}
                        </th>
                        <th colspan="3" class="px-3 py-3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Summary Cards -->
        <div class="p-4 border-top bg-white">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card bg-success-subtle border-0 text-success h-100">
                        <div class="card-body py-3 text-center">
                            <small class="text-muted d-block">{{ __('installments_paid') }}</small>
                            <h4 class="mb-0">{{ $loan->repayments->where('status', 'paid')->count() }}/{{ $loan->repayments->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning-subtle border-0 text-warning h-100">
                        <div class="card-body py-3 text-center">
                            <small class="text-muted d-block">{{ __('pending') }}</small>
                            <h4 class="mb-0">{{ $loan->repayments->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info-subtle border-0 text-info h-100">
                        <div class="card-body py-3 text-center">
                            <small class="text-muted d-block">{{ __('partial') }}</small>
                            <h4 class="mb-0">{{ $loan->repayments->where('status', 'partial')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger-subtle border-0 text-danger h-100">
                        <div class="card-body py-3 text-center">
                            <small class="text-muted d-block">{{ __('overdue') }}</small>
                            <h4 class="mb-0">{{ $loan->repayments->where('status', 'overdue')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Amortization table specific styles */
    .amortization-table {
        font-size: 0.85rem;
    }

    .amortization-table th {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
        background-color: #f8f9fa;
    }

    .amortization-table td {
        vertical-align: middle;
        border-color: #e9ecef;
    }

    .amortization-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .amortization-table tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    .amortization-table .table-danger-light {
        background-color: #fff5f5 !important;
    }

    .amortization-table .table-success-light {
        background-color: #f0fff4 !important;
    }

    .amortization-table .table-danger-light:hover {
        background-color: #ffe0e0 !important;
    }

    .amortization-table .table-success-light:hover {
        background-color: #d4f6dc !important;
    }

    .amortization-table tfoot tr {
        background-color: #f8f9fa;
    }

    .amortization-table .monospace {
        font-family: 'SF Mono', 'Fira Code', 'Monospace', monospace;
        font-variant-numeric: tabular-nums;
    }

    /* Badge styles */
    .badge {
        font-weight: 500;
        letter-spacing: 0.01em;
    }

    .bg-success-subtle { background-color: #d1f2eb !important; }
    .bg-warning-subtle { background-color: #fef3c7 !important; }
    .bg-info-subtle { background-color: #d6eaf8 !important; }
    .bg-danger-subtle { background-color: #fadbd8 !important; }
    .text-success { color: #1e8a49 !important; }
    .text-warning { color: #b7950b !important; }
    .text-info { color: #2e86c1 !important; }
    .text-danger { color: #c0392b !important; }

    /* Progress bar */
    .progress {
        border-radius: 5px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 5px;
        transition: width 0.3s ease;
    }

    /* Scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips for receipt buttons
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endif