@extends('layouts.app')

@section('title', __('user_details', ['name' => $user->name]))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> {{ __('edit_user') }}
            </a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal"
                    {{ ($activeLoans > 0) ? 'disabled' : '' }}>
                <i class="fas fa-trash"></i> {{ __('delete_user') }}
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
                    <strong>{{ __('account_status') }}:</strong> {{ translate_status($user->status) }}
                    @if($user->email_verified_at)
                        <span class="ml-3 badge badge-success">{{ __('email_verified') }}</span>
                    @else
                        <span class="ml-3 badge badge-warning">{{ __('email_not_verified') }}</span>
                    @endif
                </div>
                <div>
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $user->status === 'active' ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }}"></i>
                            {{ $user->status === 'active' ? __('deactivate') : __('activate') }}
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
                                {{ __('total_savings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totalSavings, 2) }}</div>
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
                                {{ __('active_loans') }}</div>
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
                                {{ __('total_loans') }}</div>
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
                                {{ __('member_since') }}</div>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('personal_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('full_name') }}:</th>
                                    <td><strong>{{ $user->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('email_address') }}:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('phone_number') }}:</th>
                                    <td>{{ $user->phone_number ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('national_id_passport') }}:</th>
                                    <td>{{ $user->national_id ?? __('n_a') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('address') }}:</th>
                                    <td>{{ $user->address ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('user_type') }}:</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ translate_status($role->name) }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('last_login') }}:</th>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : __('never') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('registered_on') }}:</th>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('membership_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('membership_number') }}:</th>
                                    <td><strong>{{ $user->membership_number ?? __('n_a') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('membership_date') }}:</th>
                                    <td>{{ $user->membership_date ? $user->membership_date->format('d M Y') : __('n_a') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('total_savings') }}:</th>
                                    <td><strong>CDF {{ number_format($totalSavings, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('active_loans') }}:</th>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('employment_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('employee_id') }}:</th>
                                    <td><strong>{{ $user->employee_id ?? __('n_a') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('position') }}:</th>
                                    <td>{{ $user->position ?? __('n_a') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('employment_date') }}:</th>
                                    <td>{{ $user->employment_date ? $user->employment_date->format('d M Y') : __('n_a') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('next_of_kin_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('next_of_kin_name') }}:</strong><br>
                            {{ $user->next_of_kin_name ?? __('n_a') }}
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('next_of_kin_phone') }}:</strong><br>
                            {{ $user->next_of_kin_phone ?? __('n_a') }}
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('relationship') }}:</strong><br>
                            {{ $user->next_of_kin_relationship ?? __('n_a') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Loan Applications -->
            @if($user->loanApplications && $user->loanApplications->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_loan_applications') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('application_number') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('applied_date') }}</th>
                                    <th>{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->loanApplications->take(5) as $loan)
                                <tr>
                                    <td>{{ $loan->application_number }}</td>
                                    <td>CDF {{ number_format($loan->amount_applied, 2) }}</td>
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
                                        <span class="badge badge-{{ $color }}">{{ translate_status($loan->status) }}</span>
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
                        <a href="#" class="btn btn-link">{{ __('view_all_loans') }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Savings Transactions -->
            @if($user->savingsTransactions && $user->savingsTransactions->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_savings_transactions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('reference') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->savingsTransactions->take(5) as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->type == 'deposit' ? 'success' : 'info' }}">
                                            {{ translate_status($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>CDF {{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->reference_number ?? __('n_a') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('profile_photo') }}</h6>
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
                        <p class="mb-0"><strong>{{ __('id') }}:</strong> {{ $user->membership_number }}</p>
                    @elseif($user->employee_id)
                        <p class="mb-0"><strong>{{ __('id') }}:</strong> {{ $user->employee_id }}</p>
                    @endif
                </div>
            </div>

            <!-- Account Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('account_statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-gray-500">{{ __('total_savings') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">CDF {{ number_format($totalSavings, 2) }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">{{ __('loan_applications') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLoans }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">{{ __('active_loans') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoans }}</div>
                    </div>
                    <hr>
                    <div>
                        <div class="small text-gray-500">{{ __('member_since') }}</div>
                        <div class="h6 mb-0 text-gray-800">
                            {{ $user->membership_date ? $user->membership_date->format('F d, Y') : $user->created_at->format('F d, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('quick_actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-piggy-bank mr-2"></i> {{ __('view_savings_history') }}
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-invoice mr-2"></i> {{ __('view_loan_history') }}
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line mr-2"></i> {{ __('generate_statement') }}
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope mr-2"></i> {{ __('send_message') }}
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
                    <h5 class="modal-title">{{ __('delete_user') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($activeLoans > 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('user_has_active_loans_cannot_delete', ['count' => $activeLoans]) }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('confirm_delete_user_warning') }}
                        </div>
                        <p><strong>{{ __('full_name') }}:</strong> {{ $user->name }}</p>
                        <p><strong>{{ __('email_address') }}:</strong> {{ $user->email }}</p>
                        @if($user->membership_number)
                            <p><strong>{{ __('membership_number') }}:</strong> {{ $user->membership_number }}</p>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    @if($activeLoans == 0)
                        <button type="submit" class="btn btn-danger">{{ __('delete_user') }}</button>
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