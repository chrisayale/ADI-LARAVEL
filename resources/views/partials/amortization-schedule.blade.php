@if($loan->repayments && $loan->repayments->count() > 0)
    @php
        $periodCount = $loan->repayments->count();
        $isPatoNdogoDaily = ($loan->amortization_method === 'pato_ndogo' && $loan->repayment_frequency === 'daily');
        $adjustedAmount = $loan->adjusted_daily_amount ?? ($isPatoNdogoDaily ? ceil($loan->monthly_repayment / 100) * 100 : $loan->monthly_repayment);
        $constantPrincipal = $loan->amount_approved / $periodCount;
        $totalAdjustedDue = $adjustedAmount * $periodCount;
        $totalPaid = $loan->repayments->sum('amount_paid');
        $globalBalance = $totalAdjustedDue - $totalPaid;
        if ($globalBalance < 0) $globalBalance = 0;

        // Theoretical components for Pato Ndogo daily: EXACT simple division
        if ($isPatoNdogoDaily) {
            $theoreticalRate = (($periodCount / 30) * 10) / 100; // e.g., 0.10
            $theoreticalTotalInterest = $loan->amount_approved * $theoreticalRate;
            $constantInterest = $theoreticalTotalInterest / $periodCount; // Constant per day
            $theoreticalAmount = $constantPrincipal + $constantInterest; // Exact: 3666.6666...
        } else {
            $theoreticalAnnualRate = $loan->interest_rate / 100;
            $theoreticalPeriodicRate = $theoreticalAnnualRate /
                ($loan->repayment_frequency === 'daily' ? 365 :
                ($loan->repayment_frequency === 'weekly' ? 52 : 12));
        }
    @endphp
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Tableau d'amortissement du prêt</h6>
        <div>
            <span class="badge badge-info mr-2">Total dû (Ajusté) : CDF {{ number_format($totalAdjustedDue, 2) }}</span>
            <span class="badge badge-success">Total payé : CDF {{ number_format($totalPaid, 2) }}</span>
            <span class="badge badge-warning">Solde global : CDF {{ number_format($globalBalance, 2) }}</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Progress Bar -->
        <div class="progress mb-4" style="height: 25px;">
            <div class="progress-bar bg-success" role="progressbar"
                 style="width: {{ $loan->progress_percentage }}%;"
                 aria-valuenow="{{ $loan->progress_percentage }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
                {{ $loan->progress_percentage }}% Payé
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="amortizationTable">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 40px;" class="text-center">#</th>
                        <th style="width: 100px;" class="text-center">Date d'échéance</th>
                        <th style="width: 110px;" class="text-end">Montant dû (Ajusté)</th>
                        <th style="width: 100px;" class="text-end">Capital (Constant)</th>
                        <th style="width: 100px;" class="text-end">Intérêt (Constant)</th>
                        <th style="width: 140px;" class="text-end">Montant théorique (Exact)</th>
                        <th style="width: 100px;" class="text-end">Écart</th>
                        <th style="width: 100px;" class="text-end">Montant payé</th>
                        <th style="width: 90px;" class="text-end">Solde global</th>
                        <th style="width: 100px;" class="text-center">Statut</th>
                        <th style="width: 110px;" class="text-center">Date de paiement</th>
                        <th style="width: 80px;" class="text-center">Reçu</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // For new balance logic: cumulative paid tracking
                        $cumulativePaid = 0;
                    @endphp

                    @foreach($loan->repayments as $repayment)
                        @php
                            if ($isPatoNdogoDaily) {
                                // EXACT theoretical (constant per day)
                                $theoreticalInterest = $constantInterest; // 333.333333...
                                $theoreticalAmt = $theoreticalAmount; // 3666.666666...
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

                            // Update cumulative paid
                            $cumulativePaid += $repayment->amount_paid ?? 0;

                            // NEW BALANCE LOGIC: Solde global = Total Adjusted Due - Cumulative Paid
                            $rowGlobalBalance = $totalAdjustedDue - $cumulativePaid;
                            if ($rowGlobalBalance < 0) $rowGlobalBalance = 0;

                            $statusColor = match($repayment->status) {
                                'paid' => 'success',
                                'partial' => 'info',
                                'overdue' => 'danger',
                                default => 'warning'
                            };
                        @endphp
                        <tr class="{{ $repayment->status == 'overdue' ? 'table-danger' : ($repayment->status == 'paid' ? 'table-success' : '') }}">
                            <td class="text-center">{{ $repayment->installment_number }}</td>
                            <td class="text-center">{{ $repayment->due_date->format('d M Y') }}</td>
                            <td class="text-end"><strong>CDF {{ number_format($adjustedAmount, 2, ',', ' ') }}</strong></td>
                            <td class="text-end">CDF {{ number_format($constantPrincipal, 2, ',', ' ') }}</td>
                            <td class="text-end">CDF {{ number_format($theoreticalInterest, 6, ',', ' ') }}</td>
                            <td class="text-end"><small>CDF {{ number_format($theoreticalAmt, 6, ',', ' ') }}</small></td>
                            <td class="text-end">
                                @php
                                    $gapClass = $gap >= 0 ? 'text-danger fw-bold' : 'text-success fw-bold';
                                    $gapSign = $gap >= 0 ? '+' : '';
                                @endphp
                                <span class="{{ $gapClass }}">{{ $gapSign }}CDF {{ number_format($gap, 6, ',', ' ') }}</span>
                            </td>
                            <td class="text-end">CDF {{ number_format($repayment->amount_paid ?? 0, 2, ',', ' ') }}</td>
                            <td class="text-end"><strong>CDF {{ number_format($rowGlobalBalance, 2, ',', ' ') }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-{{ $statusColor }} px-3 py-2">
                                    {{ $repayment->status === 'paid' ? 'Payé' : ($repayment->status === 'partial' ? 'Partiel' : ($repayment->status === 'overdue' ? 'En retard' : 'En attente')) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $repayment->paid_date ? $repayment->paid_date->format('d M Y') : '-' }}</td>
                            <td class="text-center">
                                @if($repayment->receipt_number)
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Voir le reçu">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="thead-light">
                    @php
                        // Calculate totals for footer
                        $totalPaidFooter = $loan->repayments->sum('amount_paid');
                        $finalGlobalBalance = $totalAdjustedDue - $totalPaidFooter;
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
                    <tr>
                        <th colspan="2" class="text-right">Total :</th>
                        <th class="text-end"><strong>CDF {{ number_format($totalAdjustedDue, 2, ',', ' ') }}</strong></th>
                        <th class="text-end"><strong>CDF {{ number_format($loan->amount_approved, 2, ',', ' ') }}</strong></th>
                        <th class="text-end"><strong>CDF {{ number_format($totalTheoreticalInterest, 6, ',', ' ') }}</strong></th>
                        <th class="text-end"><small>CDF {{ number_format($totalTheoreticalAmount, 6, ',', ' ') }}</small></th>
                        <th class="text-end">
                            @php
                                $gapClass = $totalGap >= 0 ? 'text-danger fw-bold' : 'text-success fw-bold';
                                $gapSign = $totalGap >= 0 ? '+' : '';
                            @endphp
                            <span class="{{ $gapClass }}">{{ $gapSign }}CDF {{ number_format($totalGap, 6, ',', ' ') }}</span>
                        </th>
                        <th class="text-end">CDF {{ number_format($totalPaidFooter, 2, ',', ' ') }}</th>
                        <th class="text-end"><strong>CDF {{ number_format($finalGlobalBalance, 2, ',', ' ') }}</strong></th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Summary Cards -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <small>Échéances payées</small>
                        <h5>{{ $loan->repayments->where('status', 'paid')->count() }}/{{ $loan->repayments->count() }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <small>En attente</small>
                        <h5>{{ $loan->repayments->where('status', 'pending')->count() }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body py-2">
                        <small>Partiel</small>
                        <h5>{{ $loan->repayments->where('status', 'partial')->count() }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <small>En retard</small>
                        <h5>{{ $loan->repayments->where('status', 'overdue')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif