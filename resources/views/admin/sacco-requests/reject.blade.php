@extends('layouts.app')

@section('title', __('reject_sacco_request'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('reject_sacco_request') }}</h1>
        <a href="{{ route('admin.request-sacco.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- Rejection Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('reject_sacco_request') }}</h6>
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

                    <form method="POST" action="{{ route('admin.request-sacco.storeRejection', $requestSacco->id) }}">
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
                            <h6 class="text-muted">{{ __('rejection_reason_title') }}</h6>

                            <div class="mb-3">
                                <label class="form-label">{{ __('rejection_reason') }} *</label>
                                <textarea name="rejection_reason" class="form-control" rows="4" required
                                          placeholder="{{ __('explain_why_rejected') }}">{{ old('rejection_reason') }}</textarea>
                                <div class="invalid-feedback">
                                    {{ __('provide_rejection_reason') }}
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-times me-2"></i> {{ __('confirm_rejection') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection