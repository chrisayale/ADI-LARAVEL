@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">{{ __('request_sacco_title') }}</h3>
                </div>
                <div class="card-body">
                    @if (isset($errors) && $errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>{{ __('please_correct_errors') }}</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('close') }}"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('request-sacco.store') }}">
                        @csrf

                        <!-- Informations sur le SACCO -->
                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('sacco_information') }}</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('sacco_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="{{ __('sacco_name_placeholder') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        {{ __('official_mobile_money_number') }} <span class="text-danger">*</span>
                                        <span class="text-muted fw-normal">({{ __('mobile_money_description') }})</span>
                                    </label>
                                    <input type="tel" name="phone_number" class="form-control" required value="{{ old('phone_number') }}" placeholder="{{ __('mobile_money_placeholder') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('official_email_optional') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('email_placeholder') }}">
                            </div>
                        </div>

                        <!-- Localisation du SACCO -->
                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('sacco_location') }} <span class="text-danger">*</span></h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('province') }} <span class="text-danger">*</span></label>
                                    <select name="province" class="form-select" required>
                                        <option value="">{{ __('select_province') }}</option>
                                        <option value="Kinshasa" {{ old('province') == 'Kinshasa' ? 'selected' : '' }}>Kinshasa</option>
                                        <option value="Kongo Central" {{ old('province') == 'Kongo Central' ? 'selected' : '' }}>Kongo Central</option>
                                        <option value="Kwango" {{ old('province') == 'Kwango' ? 'selected' : '' }}>Kwango</option>
                                        <option value="Kwilu" {{ old('province') == 'Kwilu' ? 'selected' : '' }}>Kwilu</option>
                                        <option value="Kasai" {{ old('province') == 'Kasai' ? 'selected' : '' }}>Kasai</option>
                                        <option value="Kasai Central" {{ old('province') == 'Kasai Central' ? 'selected' : '' }}>Kasai Central</option>
                                        <option value="Kasai Oriental" {{ old('province') == 'Kasai Oriental' ? 'selected' : '' }}>Kasai Oriental</option>
                                        <option value="Lomami" {{ old('province') == 'Lomami' ? 'selected' : '' }}>Lomami</option>
                                        <option value="Sankuru" {{ old('province') == 'Sankuru' ? 'selected' : '' }}>Sankuru</option>
                                        <option value="Maniema" {{ old('province') == 'Maniema' ? 'selected' : '' }}>Maniema</option>
                                        <option value="Sud-Kivu" {{ old('province') == 'Sud-Kivu' ? 'selected' : '' }}>Sud-Kivu</option>
                                        <option value="Nord-Kivu" {{ old('province') == 'Nord-Kivu' ? 'selected' : '' }}>Nord-Kivu</option>
                                        <option value="Ituri" {{ old('province') == 'Ituri' ? 'selected' : '' }}>Ituri</option>
                                        <option value="Haut-Uele" {{ old('province') == 'Haut-Uele' ? 'selected' : '' }}>Haut-Uele</option>
                                        <option value="Bas-Uele" {{ old('province') == 'Bas-Uele' ? 'selected' : '' }}>Bas-Uele</option>
                                        <option value="Nord-Ubangi" {{ old('province') == 'Nord-Ubangi' ? 'selected' : '' }}>Nord-Ubangi</option>
                                        <option value="Sud-Ubangi" {{ old('province') == 'Sud-Ubangi' ? 'selected' : '' }}>Sud-Ubangi</option>
                                        <option value="Équateur" {{ old('province') == 'Équateur' ? 'selected' : '' }}>Équateur</option>
                                        <option value="Mongala" {{ old('province') == 'Mongala' ? 'selected' : '' }}>Mongala</option>
                                        <option value="Tshuapa" {{ old('province') == 'Tshuapa' ? 'selected' : '' }}>Tshuapa</option>
                                        <option value="Tshopo" {{ old('province') == 'Tshopo' ? 'selected' : '' }}>Tshopo</option>
                                        <option value="Haut-Lomami" {{ old('province') == 'Haut-Lomami' ? 'selected' : '' }}>Haut-Lomami</option>
                                        <option value="Lualaba" {{ old('province') == 'Lualaba' ? 'selected' : '' }}>Lualaba</option>
                                        <option value="Haut-Katanga" {{ old('province') == 'Haut-Katanga' ? 'selected' : '' }}>Haut-Katanga</option>
                                        <option value="Tanganyika" {{ old('province') == 'Tanganyika' ? 'selected' : '' }}>Tanganyika</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('territory_commune') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="territory" class="form-control" required value="{{ old('territory') }}" placeholder="{{ __('territory_placeholder') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('city_village_quarter') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="city_village" class="form-control" required value="{{ old('city_village') }}" placeholder="{{ __('city_village_placeholder') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('detailed_address_optional') }}</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="{{ __('address_placeholder') }}">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <!-- Informations du demandeur -->
                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('applicant_information') }} <span class="text-danger">*</span></h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('applicant_full_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="applicant_name" class="form-control" required value="{{ old('applicant_name') }}" placeholder="{{ __('applicant_name_placeholder') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('applicant_role') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="applicant_role" class="form-control" required value="{{ old('applicant_role') }}" placeholder="{{ __('applicant_role_placeholder') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('applicant_email_optional') }}</label>
                                    <input type="email" name="applicant_email" class="form-control" value="{{ old('applicant_email') }}" placeholder="{{ __('email_placeholder') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('applicant_phone') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="applicant_phone" class="form-control" required value="{{ old('applicant_phone') }}" placeholder="{{ __('phone_placeholder') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Motif / Description -->
                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('reason_description') }}</h6>
                            <textarea name="description" class="form-control" rows="4" placeholder="{{ __('description_placeholder') }}">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">{{ __('submit_request') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection