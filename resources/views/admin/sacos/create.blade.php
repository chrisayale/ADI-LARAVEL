@extends('layouts.app')

@section('title', __('new_sacco'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('new_sacco') }}</h1>
        <a href="{{ route('admin.sacos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- Error Message -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>{{ __('please_correct_errors') }} :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Create Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('sacco_information') }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sacos.store') }}">
                @csrf

                <!-- Informations sur le SACCO -->
                <div class="mb-4">
                    <h6 class="text-muted">{{ __('sacco_information') }}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('sacco_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                {{ __('official_mobile_money') }} <span class="text-danger">*</span>
                                <span class="text-muted fw-normal">({{ __('where_member_money_deposited') }})</span>
                            </label>
                            <input type="tel" name="mobile_money" class="form-control @error('mobile_money') is-invalid @enderror" value="{{ old('mobile_money') }}" required>
                            @error('mobile_money')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('official_email_optional') }}</label>
                        <input type="email" name="official_email" class="form-control @error('official_email') is-invalid @enderror" value="{{ old('official_email') }}">
                        @error('official_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Localisation du SACCO -->
                <div class="mb-4">
                    <h6 class="text-muted">{{ __('sacco_location') }} <span class="text-danger">*</span></h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __('province') }} <span class="text-danger">*</span></label>
                            <select name="province" class="form-select @error('province') is-invalid @enderror" required>
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
                            @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __('territory_commune') }} <span class="text-danger">*</span></label>
                            <input type="text" name="territory" class="form-control @error('territory') is-invalid @enderror" value="{{ old('territory') }}" required>
                            @error('territory')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __('city_village_quartier') }} <span class="text-danger">*</span></label>
                            <input type="text" name="city_quartier" class="form-control @error('city_quartier') is-invalid @enderror" value="{{ old('city_quartier') }}" required>
                            @error('city_quartier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('detailed_physical_address_optional') }}</label>
                        <textarea name="physical_address" class="form-control @error('physical_address') is-invalid @enderror" rows="2">{{ old('physical_address') }}</textarea>
                        @error('physical_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Informations du demandeur / Initiateur -->
                <div class="mb-4">
                    <h6 class="text-muted">{{ __('applicant_initiator_info') }} <span class="text-danger">*</span></h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('applicant_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="applicant_name" class="form-control @error('applicant_name') is-invalid @enderror" value="{{ old('applicant_name') }}" required>
                            @error('applicant_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('applicant_role') }} <span class="text-danger">*</span></label>
                            <input type="text" name="applicant_role" class="form-control @error('applicant_role') is-invalid @enderror" value="{{ old('applicant_role') }}" required>
                            @error('applicant_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('applicant_contact_email_optional') }}</label>
                            <input type="email" name="applicant_email" class="form-control @error('applicant_email') is-invalid @enderror" value="{{ old('applicant_email') }}">
                            @error('applicant_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('applicant_contact_phone') }} <span class="text-danger">*</span></label>
                            <input type="tel" name="applicant_phone" class="form-control @error('applicant_phone') is-invalid @enderror" value="{{ old('applicant_phone') }}" required>
                            @error('applicant_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Motif / Description -->
                <div class="mb-4">
                    <h6 class="text-muted">{{ __('reason_description') }}</h6>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="{{ __('describe_sacco_project') }}">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Paramètres système -->
                <div class="mb-4">
                    <h6 class="text-muted">{{ __('system_settings') }}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('primary_currency') }}</label>
                            <input type="text" name="primary_currency" class="form-control @error('primary_currency') is-invalid @enderror" value="{{ old('primary_currency', 'USD') }}">
                            @error('primary_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('secondary_currency') }}</label>
                            <input type="text" name="secondary_currency" class="form-control @error('secondary_currency') is-invalid @enderror" value="{{ old('secondary_currency', 'CDF') }}">
                            @error('secondary_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('status') }} <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('active') }}</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('inactive') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="reset" class="btn btn-secondary">{{ __('reset') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('save_sacco') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection