{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information Column -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="mb-4 position-relative d-inline-block">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                 class="rounded-circle img-thumbnail"
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 alt="{{ $user->name }}">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 150px; height: 150px; font-size: 3rem;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif

                        @if($user->hasRole('admin'))
                            <span class="position-absolute bottom-0 end-0 badge bg-danger rounded-circle p-2"
                                  style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="fas fa-crown"></i>
                            </span>
                        @elseif($user->hasRole('treasurer'))
                            <span class="position-absolute bottom-0 end-0 badge bg-info rounded-circle p-2"
                                  style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="fas fa-user-tie"></i>
                            </span>
                        @endif
                    </div>

                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">
                        @foreach($user->roles as $role)
                            <span class="badge badge-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'treasurer' ? 'info' : 'success') }} p-2">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-envelope mr-2"></i> {{ $user->email }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-phone mr-2"></i> {{ $user->phone_number ?? 'N/A' }}
                    </p>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="font-weight-bold text-primary">{{ $stats['member_since'] }}</div>
                            <small class="text-muted">Member Since</small>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-success">CDF {{ number_format($stats['total_savings'], 0) }}</div>
                            <small class="text-muted">Total Savings</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Specific Information -->
            @if($user->hasRole('member'))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Membership Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Membership No:</th>
                                <td><strong>{{ $user->membership_number ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Membership Date:</th>
                                <td>{{ $user->membership_date ? $user->membership_date->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>National ID:</th>
                                <td>{{ $user->national_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $user->address ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @elseif($user->hasRole('treasurer'))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Employment Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Employee ID:</th>
                                <td><strong>{{ $user->employee_id ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Position:</th>
                                <td>{{ $user->position ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Employment Date:</th>
                                <td>{{ $user->employment_date ? $user->employment_date->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>National ID:</th>
                                <td>{{ $user->national_id ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @elseif($user->hasRole('admin'))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Administrator Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>National ID:</th>
                                <td>{{ $user->national_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $user->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email Verified:</th>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">Verified</span>
                                        <br><small>{{ $user->email_verified_at->format('d M Y') }}</small>
                                    @else
                                        <span class="badge badge-warning">Not Verified</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Next of Kin Information (Common for all) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Next of Kin</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Name:</th>
                            <td><strong>{{ $user->next_of_kin_name ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $user->next_of_kin_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Relationship:</th>
                            <td>{{ $user->next_of_kin_relationship ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistics and Activity Column -->
        <div class="col-lg-8">
            <!-- Statistics Cards -->
            <div class="row">
                @if($user->hasRole('member'))
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Active Loans</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_loans'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Loans</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_loans'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Pending Apps</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['loan_applications'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($user->hasRole('treasurer'))
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Assigned Loans</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['assigned_loans'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Verified</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified_loans'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($user->hasRole('admin'))
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Members</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_members'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Treasurers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_treasurers'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Loans</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_loans'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="timeline" id="recent-activity">
                        <!-- Activity will be loaded here via AJAX -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Savings Transactions (for members) -->
            @if($user->hasRole('member') && $user->savingsTransactions->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Savings Transactions</h6>
                        <a href="{{ route('member.savings.transactions') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction #</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->savingsTransactions->take(5) as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                        <td>{{ $transaction->transaction_number }}</td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->type_color }}">
                                                {{ ucfirst($transaction->transaction_type) }}
                                            </span>
                                        </td>
                                        <td class="text-right">CDF {{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-right">CDF {{ number_format($transaction->balance_after, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Loan Applications (for members) -->
            @if($user->hasRole('member') && $user->loanApplications->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Loan Applications</h6>
                        <a href="{{ route('member.loans.my-loans') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Application #</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->loanApplications->take(5) as $loan)
                                    <tr>
                                        <td>{{ $loan->created_at->format('d M Y') }}</td>
                                        <td>{{ $loan->application_number }}</td>
                                        <td class="text-right">CDF {{ number_format($loan->amount_applied, 2) }}</td>
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
                                            <span class="badge badge-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                                {{ str_replace('_', ' ', ucfirst($loan->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Assigned Loans (for treasurers) -->
            @if($user->hasRole('treasurer') && $user->assignedLoans->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Assigned Loans</h6>
                        <a href="{{ route('treasurer.loans.assigned') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Application #</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->assignedLoans->take(5) as $loan)
                                    <tr>
                                        <td>{{ $loan->user->name }}</td>
                                        <td>{{ $loan->application_number }}</td>
                                        <td class="text-right">CDF {{ number_format($loan->amount_applied, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                                {{ str_replace('_', ' ', ucfirst($loan->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .timeline::-webkit-scrollbar {
        width: 6px;
    }

    .timeline::-webkit-scrollbar-track {
        background: #f8f9fc;
        border-radius: 3px;
    }

    .timeline::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 3px;
    }

    .timeline::-webkit-scrollbar-thumb:hover {
        background: #c0c5cc;
    }

    .timeline-item {
        padding: 15px;
        border-left: 3px solid transparent;
        margin-bottom: 10px;
        background: #f8f9fc;
        border-radius: 5px;
        transition: all 0.2s;
    }

    .timeline-item:hover {
        background: #eaecf4;
        transform: translateX(5px);
    }

    .timeline-item.success { border-left-color: #1cc88a; }
    .timeline-item.info { border-left-color: #36b9cc; }
    .timeline-item.warning { border-left-color: #f6c23e; }
    .timeline-item.danger { border-left-color: #e74a3b; }
    .timeline-item.primary { border-left-color: #4e73df; }
    .timeline-item.secondary { border-left-color: #858796; }
</style>
@endpush

@push('scripts')
<script>
    // Load recent activity via AJAX
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/profile/recent-activity', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(activities => {
            updateActivityTimeline(activities);
        })
        .catch(error => {
            console.error('Error loading activities:', error);
            document.getElementById('recent-activity').innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <p>Unable to load recent activity</p>
                </div>
            `;
        });
    });

    function updateActivityTimeline(activities) {
        const container = document.getElementById('recent-activity');

        if (!activities || activities.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-history fa-2x mb-2"></i>
                    <p>No recent activity</p>
                </div>
            `;
            return;
        }

        let html = '';
        activities.forEach(activity => {
            const date = new Date(activity.date);
            const formattedDate = date.toLocaleDateString('en-US', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            html += `
                <div class="timeline-item ${activity.color}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge badge-${activity.color} mb-2">
                                <i class="fas fa-${activity.icon} mr-1"></i>
                                ${activity.type === 'savings' ? activity.description : 'Loan Application'}
                            </span>
                            <h6 class="mb-1">${activity.type === 'savings' ? 'CDF ' + activity.amount.toLocaleString() : activity.description}</h6>
                            <p class="text-muted small mb-0">
                                Ref: ${activity.reference}
                                ${activity.status ? ' • Status: ' + activity.status.replace('_', ' ') : ''}
                            </p>
                        </div>
                        <small class="text-muted">${formattedDate}</small>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }
</script>
@endpush
