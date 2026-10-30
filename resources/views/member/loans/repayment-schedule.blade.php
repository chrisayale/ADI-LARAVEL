@extends('layouts.app')

@section('title', 'Repayment Schedule - ' . $loan->application_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Repayment Schedule</h1>
        <div>
            <a href="{{ route('member.loans.show', $loan) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Loan Details
            </a>
            <a href="{{ route('member.loans.my-loans') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to My Loans
            </a>
        </div>
    </div>

    <!-- Loan Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Loan Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($loan->amount_approved, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Paid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totalPaid, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Outstanding</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($loan->balance, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Monthly Payment</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($loan->monthly_repayment, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h6 class="mb-0 font-weight-bold">Repayment Progress</h6>
                </div>
                <div class="col-md-7">
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $loan->progress_percentage }}%;"
                             aria-valuenow="{{ $loan->progress_percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ $loan->progress_percentage }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <span class="badge badge-success p-2">{{ $loan->repayments->where('status', 'paid')->count() }}/{{ $loan->repayments->count() }} payments</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Payment Alert -->
    @if($nextPayment)
    <div class="alert alert-{{ $nextPayment->due_date < now() ? 'danger' : 'info' }} mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-{{ $nextPayment->due_date < now() ? 'exclamation-circle' : 'info-circle' }} mr-2"></i>
                <strong>Next Payment:</strong>
                CDF {{ number_format($nextPayment->amount_due - $nextPayment->amount_paid, 2) }}
                due on {{ $nextPayment->due_date->format('d F Y') }}
                @if($nextPayment->due_date < now())
                    <span class="badge badge-danger ml-2">OVERDUE</span>
                @endif
            </div>
            <small class="text-muted">Installment #{{ $nextPayment->installment_number }}</small>
        </div>
    </div>
    @endif

    <!-- Repayment Schedule Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Complete Repayment Schedule</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th>Amount Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loan->repayments as $repayment)
                        @php
                            $balance = $repayment->amount_due - $repayment->amount_paid;
                            $isOverdue = $repayment->due_date < now() && $repayment->status != 'paid';
                            $rowClass = $isOverdue ? 'table-danger' : ($repayment->status == 'paid' ? 'table-success' : '');
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $repayment->installment_number }}</td>
                            <td>
                                {{ $repayment->due_date->format('d M Y') }}
                                @if($isOverdue)
                                    <br><span class="badge badge-danger">Overdue</span>
                                @endif
                            </td>
                            <td class="text-right">CDF {{ number_format($repayment->amount_due, 2) }}</td>
                            <td class="text-right">CDF {{ number_format($repayment->amount_paid, 2) }}</td>
                            <td class="text-right"><strong>CDF {{ number_format($balance, 2) }}</strong></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'secondary',
                                        'partial' => 'info',
                                        'paid' => 'success',
                                        'overdue' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$repayment->status] ?? 'secondary' }} p-2">
                                    {{ ucfirst($repayment->status) }}
                                </span>
                            </td>
                            <td>{{ $repayment->paid_date ? $repayment->paid_date->format('d M Y') : '-' }}</td>
                            <td>
                                @if($repayment->receipt_number)
                                    {{ $repayment->receipt_number }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="2" class="text-right">Totals:</th>
                            <th class="text-right">CDF {{ number_format($loan->repayments->sum('amount_due'), 2) }}</th>
                            <th class="text-right">CDF {{ number_format($loan->repayments->sum('amount_paid'), 2) }}</th>
                            <th class="text-right">CDF {{ number_format($loan->repayments->sum('balance'), 2) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <small>Paid Installments</small>
                    <h4>{{ $loan->repayments->where('status', 'paid')->count() }}/{{ $loan->repayments->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <small>Pending</small>
                    <h4>{{ $loan->repayments->where('status', 'pending')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <small>Partial</small>
                    <h4>{{ $loan->repayments->where('status', 'partial')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <small>Overdue</small>
                    <h4>{{ $loan->repayments->where('due_date', '<', now())->where('status', '!=', 'paid')->count() }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
