@extends('layouts.app')

@section('title', __('approve_sacco_request'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('approve_sacco_request') }}</h1>
        <a href="{{ route('admin.request-sacco.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- Approval Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('approve_sacco_request') }}</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.request-sacco.storeApproval', $requestSacco->id) }}">
                        @csrf

                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('request_information') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('sacco_name') }} :</strong>
                                        <p class="mb-1">{{ $requestSacco->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('mobile_money_phone') }} :</strong>
                                        <p class="mb-1">{{ $requestSacco->phone_number }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('applicant_name') }} :</strong>
                                        <p class="mb-1">{{ $requestSacco->applicant_name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('applicant_phone') }} :</strong>
                                        <p class="mb-1">{{ $requestSacco->applicant_phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('approval_details') }}</h6>

                            <div class="mb-3">
                                <label class="form-label">{{ __('unique_sacco_code') }} *</label>
                                <input type="text" name="code" class="form-control" required
                                       value="{{ old('code', $requestSacco->code ?: '') }}"
                                       placeholder="{{ __('leave_empty_for_auto_generate') }}">
                                <small class="text-muted">{{ __('auto_generate_code_note') }}</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('gps_coordinates_optional') }}</label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">{{ __('latitude') }}</label>
                                        <input type="number" name="latitude" class="form-control" step="0.000001"
                                               value="{{ old('latitude') }}" placeholder="-4.3250">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">{{ __('longitude') }}</label>
                                        <input type="number" name="longitude" class="form-control" step="0.000001"
                                               value="{{ old('longitude') }}" placeholder="15.3222">
                                    </div>
                                </div>
                                <small class="text-muted">{{ __('gps_coordinates_note') }}</small>
                            </div>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>{{ __('note') }}:</strong> {{ __('approval_creates_auto') }}
                            <ul class="mb-0 mt-2">
                                <li>{{ __('official_email_created') }}</li>
                                <li>{{ __('default_password_account') }}</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i> {{ __('approve_and_create_sacco') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection