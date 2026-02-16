@if($loan->repayments && $loan->repayments->count() > 0)
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Loan Amortization Schedule</h6>
        <div>
            <span class="badge badge-info mr-2">Total: UGX {{ number_format($loan->total_repayable, 2) }}</span>
            <span class="badge badge-success">Paid: UGX {{ number_format($loan->repayments->sum('amount_paid'), 2) }}</span>
            <span class="badge badge-warning">Balance: UGX {{ number_format($loan->balance, 2) }}</span>
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
                {{ $loan->progress_percentage }}% Paid
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="amortizationTable">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Due Date</th>
                        <th>Amount Due</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $remainingBalance = $loan->amount_approved;
                        $monthlyRate = $loan->interest_rate / 100 / 12;
                    @endphp

                    @foreach($loan->repayments as $repayment)
                        @php
                            // Calculate principal and interest portions
                            $interestPortion = $remainingBalance * $monthlyRate;
                            $principalPortion = $repayment->amount_due - $interestPortion;
                            $remainingBalance -= $principalPortion;

                            $statusColor = match($repayment->status) {
                                'paid' => 'success',
                                'partial' => 'info',
                                'overdue' => 'danger',
                                default => 'warning'
                            };
                        @endphp
                        <tr class="{{ $repayment->status == 'overdue' ? 'table-danger' : ($repayment->status == 'paid' ? 'table-success' : '') }}">
                            <td>{{ $repayment->installment_number }}</td>
                            <td>
                                {{ $repayment->due_date->format('d M Y') }}
                                @if($repayment->is_overdue)
                                    <span class="badge badge-danger ml-1">Overdue</span>
                                @endif
                            </td>
                            <td><strong>UGX {{ number_format($repayment->amount_due, 0) }}</strong></td>
                            <td>UGX {{ number_format($principalPortion, 0) }}</td>
                            <td>UGX {{ number_format($interestPortion, 0) }}</td>
                            <td>UGX {{ number_format($repayment->amount_paid ?? 0, 0) }}</td>
                            <td>UGX {{ number_format($repayment->balance, 0) }}</td>
                            <td>
                                <span class="badge badge-{{ $statusColor }} px-3 py-2">
                                    {{ ucfirst($repayment->status) }}
                                </span>
                            </td>
                            <td>{{ $repayment->paid_at ? $repayment->paid_at->format('d M Y') : '-' }}</td>
                            <td>
                                @if($repayment->receipt_number)
                                    <a href="#" class="btn btn-sm btn-outline-info" title="View Receipt">
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
                    <tr>
                        <th colspan="2" class="text-right">Totals:</th>
                        <th>UGX {{ number_format($loan->repayments->sum('amount_due'), 0) }}</th>
                        <th>UGX {{ number_format($loan->amount_approved, 0) }}</th>
                        <th>UGX {{ number_format($loan->repayments->sum('amount_due') - $loan->amount_approved, 0) }}</th>
                        <th>UGX {{ number_format($loan->repayments->sum('amount_paid'), 0) }}</th>
                        <th>UGX {{ number_format($loan->repayments->sum('balance'), 0) }}</th>
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
                        <small>Paid Installments</small>
                        <h5>{{ $loan->repayments->where('status', 'paid')->count() }}/{{ $loan->repayments->count() }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <small>Pending</small>
                        <h5>{{ $loan->repayments->where('status', 'pending')->count() }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body py-2">
                        <small>Partial</small>
                        <h5>{{ $loan->repayments->where('status', 'partial')->count() }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <small>Overdue</small>
                        <h5>{{ $loan->repayments->where('status', 'overdue')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
