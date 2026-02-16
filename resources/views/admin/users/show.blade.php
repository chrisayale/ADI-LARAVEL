@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal"
                    {{ ($activeLoans > 0) ? 'disabled' : '' }}>
                <i class="fas fa-trash"></i> Delete User
            </button>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            @php
                $statusColors = [
                    'active' => 'success',
                    'inactive' => 'warning',
                    'suspended' => 'danger',
                    'pending' => 'info'
                ];
                $color = $statusColors[$user->status] ?? 'secondary';
            @endphp
            <div class="alert alert-{{ $color }} mb-0 d-flex justify-content-between align-items-center">
                <div>
                    <strong>Account Status:</strong> {{ ucfirst($user->status) }}
                    @if($user->email_verified_at)
                        <span class="ml-3 badge badge-success">Email Verified</span>
                    @else
                        <span class="ml-3 badge badge-warning">Email Not Verified</span>
                    @endif
                </div>
                <div>
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $user->status === 'active' ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }}"></i>
                            {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Savings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totalSavings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
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
                                Active Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
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
                                Total Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
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
                                Member Since</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $user->membership_date ? $user->membership_date->format('d M Y') : $user->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Details Column -->
        <div class="col-lg-8">
            <!-- Personal Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Full Name:</th>
                                    <td><strong>{{ $user->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Email Address:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number:</th>
                                    <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>National ID:</th>
                                    <td>{{ $user->national_id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Address:</th>
                                    <td>{{ $user->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>User Type:</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Login:</th>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Never' }}</td>
                                </tr>
                                <tr>
                                    <th>Registered On:</th>
                                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Specific Information (if user is member) -->
            @if($user->hasRole('member'))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Membership Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Membership Number:</th>
                                    <td><strong>{{ $user->membership_number ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Membership Date:</th>
                                    <td>{{ $user->membership_date ? $user->membership_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Total Savings:</th>
                                    <td><strong>UGX {{ number_format($totalSavings, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Active Loans:</th>
                                    <td>{{ $activeLoans }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Treasurer Specific Information (if user is treasurer) -->
            @if($user->hasRole('treasurer'))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Employee ID:</th>
                                    <td><strong>{{ $user->employee_id ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td>{{ $user->position ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Employment Date:</th>
                                    <td>{{ $user->employment_date ? $user->employment_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Next of Kin Information (for all users) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Next of Kin Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Name:</strong><br>
                            {{ $user->next_of_kin_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Phone:</strong><br>
                            {{ $user->next_of_kin_phone ?? 'N/A' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Relationship:</strong><br>
                            {{ $user->next_of_kin_relationship ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Loan Applications -->
            @if($user->loanApplications && $user->loanApplications->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Loan Applications</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Application #</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->loanApplications->take(5) as $loan)
                                <tr>
                                    <td>{{ $loan->application_number }}</td>
                                    <td>UGX {{ number_format($loan->amount_applied, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'under_verification' => 'info',
                                                'verified' => 'primary',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'disbursed' => 'primary',
                                                'completed' => 'success',
                                                'defaulted' => 'danger'
                                            ];
                                            $color = $statusColors[$loan->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $color }}">{{ ucfirst($loan->status) }}</span>
                                    </td>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->loanApplications->count() > 5)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-link">View All Loans</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Savings Transactions -->
            @if($user->savingsTransactions && $user->savingsTransactions->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Savings Transactions</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->savingsTransactions->take(5) as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->type == 'deposit' ? 'success' : 'info' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>UGX {{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->reference_number ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Profile Photo Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Photo</h6>
                </div>
                <div class="card-body text-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             class="img-fluid rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;"
                             alt="{{ $user->name }}">
                    @else
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle"
                             style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif

                    @if($user->membership_number)
                        <p class="mb-0"><strong>ID:</strong> {{ $user->membership_number }}</p>
                    @elseif($user->employee_id)
                        <p class="mb-0"><strong>ID:</strong> {{ $user->employee_id }}</p>
                    @endif
                </div>
            </div>

            <!-- Account Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-gray-500">Total Savings</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">UGX {{ number_format($totalSavings, 2) }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">Loan Applications</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLoans }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">Active Loans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoans }}</div>
                    </div>
                    <hr>
                    <div>
                        <div class="small text-gray-500">Member Since</div>
                        <div class="h6 mb-0 text-gray-800">
                            {{ $user->membership_date ? $user->membership_date->format('F d, Y') : $user->created_at->format('F d, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-piggy-bank mr-2"></i> View Savings History
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-invoice mr-2"></i> View Loan History
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line mr-2"></i> Generate Statement
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope mr-2"></i> Send Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($activeLoans > 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            This user has {{ $activeLoans }} active loan(s) and cannot be deleted.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Are you sure you want to delete this user? This action cannot be undone.
                        </div>
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        @if($user->membership_number)
                            <p><strong>Membership #:</strong> {{ $user->membership_number }}</p>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    @if($activeLoans == 0)
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .table-borderless th, .table-borderless td {
        border: none !important;
        padding: 0.5rem !important;
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
