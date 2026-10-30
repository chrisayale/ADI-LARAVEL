@extends('layouts.app')

@section('title', __('dashboard'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>{{ __('dashboard') }}</h5>
                </div>
                <div class="card-body-custom">
                    @if(auth()->check())
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>{{ __('welcome_back', ['name' => auth()->user()->name]) }}</h6>
                                <p class="text-muted mb-0">
                                    {{ __('role') }}:
                                    @if(auth()->user()->hasRole('admin'))
                                        <span class="badge bg-primary">{{ __('admin') }}</span>
                                    @elseif(auth()->user()->hasRole('treasurer'))
                                        <span class="badge bg-success">{{ __('treasurer') }}</span>
                                    @elseif(auth()->user()->hasRole('member'))
                                        <span class="badge bg-info">{{ __('member') }}</span>
                                    @elseif(auth()->user()->hasRole('super_admin'))
                                        <span class="badge bg-dark">{{ __('super_admin') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('no_role_assigned') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            @if(auth()->user()->hasRole('admin'))
                                <div class="col-md-4">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary w-100 py-3">
                                        <i class="fas fa-tachometer-alt fa-2x d-block mb-2"></i>
                                        {{ __('admin_space') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                        <i class="fas fa-users fa-2x d-block mb-2"></i>
                                        {{ __('user_management') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-success w-100 py-3">
                                        <i class="fas fa-hand-holding-usd fa-2x d-block mb-2"></i>
                                        {{ __('loan_management') }}
                                    </a>
                                </div>
                            @elseif(auth()->user()->hasRole('treasurer'))
                                <div class="col-md-4">
                                    <a href="{{ route('treasurer.dashboard') }}" class="btn btn-outline-success w-100 py-3">
                                        <i class="fas fa-tachometer-alt fa-2x d-block mb-2"></i>
                                        {{ __('treasurer_space') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('treasurer.repayments.collect') }}" class="btn btn-outline-primary w-100 py-3">
                                        <i class="fas fa-money-bill-wave fa-2x d-block mb-2"></i>
                                        {{ __('collect_payment') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-outline-warning w-100 py-3">
                                        <i class="fas fa-search fa-2x d-block mb-2"></i>
                                        {{ __('pending_verifications') }}
                                    </a>
                                </div>
                            @elseif(auth()->user()->hasRole('member'))
                                <div class="col-md-4">
                                    <a href="{{ route('member.dashboard') }}" class="btn btn-outline-info w-100 py-3">
                                        <i class="fas fa-tachometer-alt fa-2x d-block mb-2"></i>
                                        {{ __('member_space') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('member.savings.my-savings') }}" class="btn btn-outline-success w-100 py-3">
                                        <i class="fas fa-piggy-bank fa-2x d-block mb-2"></i>
                                        {{ __('my_savings') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('member.loans.apply') }}" class="btn btn-outline-primary w-100 py-3">
                                        <i class="fas fa-file-invoice fa-2x d-block mb-2"></i>
                                        {{ __('apply_for_loan') }}
                                    </a>
                                </div>
                            @elseif(auth()->user()->hasRole('super_admin'))
                                <div class="col-md-4">
                                    <a href="{{ route('super-admin.sacos.index') }}" class="btn btn-outline-dark w-100 py-3">
                                        <i class="fas fa-university fa-2x d-block mb-2"></i>
                                        {{ __('sacco_management') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.request-sacco.index') }}" class="btn btn-outline-primary w-100 py-3">
                                        <i class="fas fa-file-alt fa-2x d-block mb-2"></i>
                                        {{ __('sacco_requests') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary w-100 py-3">
                                        <i class="fas fa-tachometer-alt fa-2x d-block mb-2"></i>
                                        {{ __('admin_dashboard_short') }}
                                    </a>
                                </div>
                            @else
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h5>{{ __('no_role_assigned') }}</h5>
                                    <p class="text-muted">{{ __('no_role_assigned_desc') }}</p>
                                    <a href="{{ route('profile.show') }}" class="btn btn-primary">{{ __('my_profile') }}</a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-sign-in-alt fa-3x text-primary mb-3"></i>
                            <h5>{{ __('welcome_saving_sacco') }}</h5>
                            <p class="text-muted">{{ __('login_to_access') }}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>{{ __('login') }}</a>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary ms-2"><i class="fas fa-user-plus me-2"></i>{{ __('create_account') }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection