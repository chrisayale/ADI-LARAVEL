@extends('layouts.app')

@section('title', 'My Loan Applications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Loan Applications</h1>
        <div>
            <a href="{{ route('member.loans.apply') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> New Application
            </a>
            <a href="{{ route('member.loans.my-loans') }}" class="btn btn-info">
                <i class="fas fa-hand-holding-usd"></i> My Loans
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Disbursed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['disbursed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Applications</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('member.loans.applications') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_verification" {{ request('status') == 'under_verification' ? 'selected' : '' }}>Under Verification</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('member.loans.applications') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loan Applications</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application #</th>
                            <th>Amount</th>
                            <th>Purpose</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $application)
                        <tr>
                            <td>{{ $applications->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $application->application_number }}</strong>
                            </td>
                            <td class="text-right">
                                <strong>UGX {{ number_format($application->amount_applied, 2) }}</strong>
                            </td>
                            <td>{{ Str::limit($application->purpose, 30) }}</td>
                            <td>{{ $application->created_at->format('d M Y') }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'secondary',
                                        'under_verification' => 'info',
                                        'verified' => 'primary',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'disbursed' => 'primary',
                                        'completed' => 'success',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$application->status] ?? 'secondary' }} p-2">
                                    {{ str_replace('_', ' ', ucfirst($application->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('member.loans.show', $application) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                                @if(in_array($application->status, ['approved', 'disbursed']))
                                    <a href="{{ route('member.loans.repayment-schedule', $application) }}"
                                       class="btn btn-sm btn-success">
                                        <i class="fas fa-calendar"></i> Schedule
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No loan applications found</p>
                                <a href="{{ route('member.loans.apply') }}" class="btn btn-primary">
                                    Apply for Loan
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $applications->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
