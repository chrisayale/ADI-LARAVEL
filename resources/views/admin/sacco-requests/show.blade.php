@extends('layouts.app')

@section('title', __('sacco_request_details'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('sacco_request_details') }}</h1>
        <a href="{{ route('admin.request-sacco.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- Request Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('request_information') }}</h6>
                </div>
                <div class="card-body">
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
                            <div class="mb-3">
                                <strong>{{ __('official_email') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->email or __('not_provided') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>{{ __('location') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->province }}, {{ $requestSacco->territory }}, {{ $requestSacco->city_village }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('detailed_address') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->address or __('not_provided') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>{{ __('applicant_name') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_name }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('applicant_role') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_role }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('applicant_email') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_email or __('not_provided') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('applicant_phone') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>{{ __('status') }} :</strong>
                                <p class="mb-1">
                                    @switch($requestSacco->status)
                                        @case('pending')
                                            <span class="badge badge-warning">{{ translate_status('pending') }}</span>
                                        @break
                                        @case('approved')
                                            <span class="badge badge-success">{{ translate_status('approved') }}</span>
                                        @break
                                        @case('rejected')
                                            <span class="badge badge-danger">{{ translate_status('rejected') }}</span>
                                        @break
                                        @default
                                            <span class="badge badge-info">{{ ucfirst($requestSacco->status) }}</span>
                                    @endswitch
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('submission_date') }} :</strong>
                                <p class="mb-1">{{ $requestSacco->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($requestSacco->reviewed_at)
                                <div class="mb-3">
                                    <strong>{{ __('review_date') }} :</strong>
                                    <p class="mb-1">{{ $requestSacco->reviewed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($requestSacco->description)
                        <hr class="my-4">
                        <div class="mb-3">
                            <strong>{{ __('reason_description') }} :</strong>
                            <p class="mb-1">{{ nl2br(e($requestSacco->description)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection