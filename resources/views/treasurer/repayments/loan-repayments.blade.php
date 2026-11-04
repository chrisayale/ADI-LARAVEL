{{-- resources/views/treasurer/repayments/loan-repayments.blade.php --}}
@extends('layouts.app')

@section('title', 'Loan Repayment Schedule - ' . $loan->application_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Repayment Schedule</h1>
        <div>
            <a href="{{ route('treasurer.repayments.assigned') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Assigned Loans
            </a>
            <a href="{{ route('treasurer.repayments.collect') }}?search={{ $loan->user->membership_number }}"
               class="btn btn-success">
                <i class="fas fa-hand-holding-usd"></i> Collect Payment
            </a>
        </div>
    </div>

    <!-- Loan Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loan Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Application #:</th>
                            <td><strong>{{ $loan->application_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Member:</th>
                            <td>{{ $loan->user->name }} ({{ $loan->user->membership_number }})</td>
                        </tr>
                        <tr>
                            <th>Amount Approved:</th>
                            <td><strong>CDF {{ number_format($loan->amount_approved, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('interest_rate') }}:</th>
                            <td>
                                @if($loan->interest_rate_label)
                                    {{ $loan->interest_rate_label }}
                                @else
                                    {{ number_format($loan->interest_rate, 2) }}%
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Duration:</th>
                            <td>{{ $loan->duration_months }} months</td>
                        </tr>
                        <tr>
                            <th>Monthly Payment:</th>
                            <td>CDF {{ number_format($loan->monthly_repayment, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Repayable:</th>
                            <td>CDF {{ number_format($loan->total_repayable, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $loan->status == 'disbursed' ? 'primary' : 'warning' }} p-2">
                                    {{ translate_status($loan->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
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

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Outstanding Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($loan->balance, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Next Payment</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($nextPayment)
                                    CDF {{ number_format($nextPayment->amount_due - $nextPayment->amount_paid, 2) }}
                                    <br><small class="text-muted">Due: {{ $nextPayment->due_date->format('d M Y') }}</small>
                                @else
                                    No pending payments
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Repayment Schedule Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Repayment Schedule</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th>Theoretical Amount</th>
                            <th>Adjusted Amount</th>
                            <th>Amount Paid</th>
                            <th>Balance</th>
                            <th>Difference</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loan->repayments as $repayment)
                            @php
                                $effectiveDue = $repayment->effective_amount_due;
                                $balance = $effectiveDue - $repayment->amount_paid;
                                $difference = $repayment->difference_amount;
                                $differenceClass = $repayment->difference_type === 'surplus' ? 'text-success' : ($repayment->difference_type === 'shortfall' ? 'text-danger' : '');
                                $statusColors = [
                                    'pending' => 'secondary',
                                    'partial' => 'info',
                                    'paid' => 'success',
                                    'overdue' => 'danger'
                                ];
                            @endphp
                        <tr class="{{ $repayment->due_date < now() && $repayment->status != 'paid' ? 'table-danger' : '' }}">
                            <td>{{ $repayment->installment_number }}</td>
                            <td>
                                {{ $repayment->due_date->format('d M Y') }}
                                @if($repayment->due_date < now() && $repayment->status != 'paid')
                                    <br><span class="badge badge-danger">Overdue</span>
                                @endif
                            </td>
                            <td class="text-right">CDF {{ number_format($repayment->amount_due, 2) }}</td>
                            <td class="text-right">
                                @if($repayment->has_adjustment)
                                    <span class="font-weight-bold">CDF {{ number_format($repayment->adjusted_amount, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-right">CDF {{ number_format($repayment->amount_paid, 2) }}</td>
                            <td class="text-right">
                                <strong>CDF {{ number_format($balance, 2) }}</strong>
                            </td>
                            <td class="{{ $differenceClass }} text-right font-weight-bold">
                                @if($repayment->has_adjustment)
                                    @if($repayment->difference_type === 'surplus')
                                        <i class="fas fa-arrow-up"></i> CDF {{ number_format($difference, 2) }}
                                    @elseif($repayment->difference_type === 'shortfall')
                                        <i class="fas fa-arrow-down"></i> CDF {{ number_format($difference, 2) }}
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $statusColors[$repayment->status] ?? 'secondary' }} p-2">
                                    {{ translate_status($repayment->status) }}
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
                </table>
            </div>
        </div>
    </div>

    <!-- Cumulative Surplus Summary -->
    @php
        $totalSurplus = $loan->repayments->sum('difference_amount');
    @endphp
    @if($totalSurplus != 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-{{ $totalSurplus > 0 ? 'success' : 'warning' }} text-white">
            <h6 class="m-0 font-weight-bold">Surplus / Shortfall Summary</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Cumulative Difference: <span class="text-{{ $totalSurplus > 0 ? 'success' : 'danger' }} font-weight-bold">CDF {{ number_format($totalSurplus, 2) }}</span></h5>
                    <p class="text-muted">
                        @if($totalSurplus > 0)
                            Total surplus (overpayment) to be refunded or transferred to savings at loan closure.
                        @else
                            Total shortfall (underpayment) to be collected.
                        @endif
                    </p>
                </div>
                <div class="col-md-6 text-md-right">
                    @if($totalSurplus > 0)
                        <span class="badge badge-success p-2">Surplus</span>
                    @else
                        <span class="badge badge-danger p-2">Shortfall</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
