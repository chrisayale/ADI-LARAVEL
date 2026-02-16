@extends('layouts.app')

@section('title', 'My Loans')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Loans</h1>
        <div>
            <a href="{{ route('member.loans.apply') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> New Application
            </a>
            <a href="{{ route('member.loans.applications') }}" class="btn btn-info">
                <i class="fas fa-file-invoice"></i> All Applications
            </a>
        </div>
    </div>

    <!-- Active Loans Summary -->
    <div class="row mb-4">
        @php
            $totalActiveBalance = 0;
            $totalMonthlyPayment = 0;
            $activeLoanCount = 0;

            foreach($loans as $loan) {
                if(in_array($loan->status, ['approved', 'disbursed'])) {
                    $totalActiveBalance += $loan->balance;
                    $totalMonthlyPayment += $loan->monthly_repayment ?? 0;
                    $activeLoanCount++;
                }
            }
        @endphp

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoanCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Outstanding Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totalActiveBalance, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                Monthly Obligation</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totalMonthlyPayment, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">My Loans</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application #</th>
                            <th>Amount Approved</th>
                            <th>Interest Rate</th>
                            <th>Duration</th>
                            <th>Monthly Payment</th>
                            <th>Balance</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                        <tr>
                            <td>{{ $loans->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $loan->application_number }}</strong>
                            </td>
                            <td class="text-right">
                                <strong>UGX {{ number_format($loan->amount_approved, 2) }}</strong>
                            </td>
                            <td class="text-center">{{ $loan->interest_rate }}%</td>
                            <td class="text-center">{{ $loan->duration_months }} months</td>
                            <td class="text-right">UGX {{ number_format($loan->monthly_repayment, 2) }}</td>
                            <td class="text-right {{ $loan->balance > 0 ? 'text-danger' : 'text-success' }}">
                                <strong>UGX {{ number_format($loan->balance, 2) }}</strong>
                            </td>
                            <td style="min-width: 150px;">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $loan->progress_percentage }}%"></div>
                                </div>
                                <small class="text-muted">{{ $loan->progress_percentage }}% paid</small>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'approved' => 'success',
                                        'disbursed' => 'primary',
                                        'completed' => 'success',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$loan->status] ?? 'secondary' }} p-2">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('member.loans.show', $loan) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('member.loans.repayment-schedule', $loan) }}"
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-calendar"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No loans found</p>
                                <a href="{{ route('member.loans.apply') }}" class="btn btn-primary">
                                    Apply for a Loan
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $loans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
