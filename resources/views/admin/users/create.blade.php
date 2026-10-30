@extends('layouts.app')

@section('title', __('add_new_user'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('add_new_user') }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- Error Message -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('user_information') }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- User Type Selection -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="font-weight-bold">{{ __('user_type') }} <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_member" value="member"
                                   {{ old('role', 'member') == 'member' ? 'checked' : '' }} onchange="toggleUserType()">
                            <label class="form-check-label" for="role_member">{{ __('member') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_treasurer" value="treasurer"
                                   {{ old('role') == 'treasurer' ? 'checked' : '' }} onchange="toggleUserType()">
                            <label class="form-check-label" for="role_treasurer">{{ __('treasurer') }}</label>
                        </div>
                        @error('role')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Personal Information (Common for both) -->
                <h5 class="border-bottom pb-2 mb-4">{{ __('personal_information') }}</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name">{{ __('full_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email">{{ __('email_address') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone_number">{{ __('phone_number') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                               id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="national_id">{{ __('national_id_passport') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                               id="national_id" name="national_id" value="{{ old('national_id') }}" required>
                        @error('national_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address">{{ __('physical_address') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Next of Kin Information (Required for ALL users) -->
                <h5 class="border-bottom pb-2 mb-4 mt-4">{{ __('next_of_kin_information') }}</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="next_of_kin_name">{{ __('next_of_kin_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('next_of_kin_name') is-invalid @enderror"
                               id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name') }}" required>
                        @error('next_of_kin_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="next_of_kin_phone">{{ __('next_of_kin_phone') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('next_of_kin_phone') is-invalid @enderror"
                               id="next_of_kin_phone" name="next_of_kin_phone" value="{{ old('next_of_kin_phone') }}" required>
                        @error('next_of_kin_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="next_of_kin_relationship">{{ __('relationship') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('next_of_kin_relationship') is-invalid @enderror"
                               id="next_of_kin_relationship" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship') }}" required>
                        @error('next_of_kin_relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Member Specific Fields -->
                <div id="member-fields" style="{{ old('role', 'member') == 'member' ? 'display: block;' : 'display: none;' }}">
                    <h5 class="border-bottom pb-2 mb-4 mt-4">{{ __('membership_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_number">{{ __('membership_number') }}</label>
                            <input type="text" class="form-control @error('membership_number') is-invalid @enderror"
                                   id="membership_number" name="membership_number" value="{{ old('membership_number') }}"
                                   placeholder="{{ __('membership_number_placeholder') }}">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                {{ __('membership_number_help') }}
                            </small>
                            @error('membership_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="membership_date">{{ __('membership_date') }}</label>
                            <input type="date" class="form-control @error('membership_date') is-invalid @enderror"
                                   id="membership_date" name="membership_date" value="{{ old('membership_date', date('Y-m-d')) }}">
                            @error('membership_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Treasurer Specific Fields -->
                <div id="treasurer-fields" style="{{ old('role') == 'treasurer' ? 'display: block;' : 'display: none;' }}">
                    <h5 class="border-bottom pb-2 mb-4 mt-4">{{ __('employment_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id">{{ __('employee_id') }}</label>
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                   id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                                   placeholder="{{ __('employee_id_placeholder') }}">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                {{ __('employee_id_help') }}
                            </small>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employment_date">{{ __('employment_date') }}</label>
                            <input type="date" class="form-control @error('employment_date') is-invalid @enderror"
                                   id="employment_date" name="employment_date" value="{{ old('employment_date', date('Y-m-d')) }}">
                            @error('employment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="position">{{ __('position_title') }}</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                   id="position" name="position" value="{{ old('position') }}"
                                   placeholder="{{ __('position_placeholder') }}">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Information (Common for both) -->
                <h5 class="border-bottom pb-2 mb-4 mt-4">{{ __('account_information') }}</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password">{{ __('password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        <small class="text-muted">{{ __('password_min_chars') }}</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation">{{ __('confirm_password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status">{{ __('account_status') }} <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('active') }}</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __('pending') }}</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('inactive') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="profile_photo">{{ __('profile_photo') }}</label>
                        <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" accept="image/*">
                        <small class="text-muted">{{ __('photo_formats') }}</small>
                        @error('profile_photo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="mt-4">

                <div class="text-right">
                    <button type="reset" class="btn btn-secondary">{{ __('reset') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('create_user') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleUserType() {
        const isMember = document.getElementById('role_member').checked;
        const memberFields = document.getElementById('member-fields');
        const treasurerFields = document.getElementById('treasurer-fields');

        if (isMember) {
            memberFields.style.display = 'block';
            treasurerFields.style.display = 'none';

            // Clear treasurer fields
            document.getElementById('employee_id').value = '';
            document.getElementById('position').value = '';
            document.getElementById('employment_date').value = '{{ date('Y-m-d') }}';
        } else {
            memberFields.style.display = 'none';
            treasurerFields.style.display = 'block';

            // Clear member fields
            document.getElementById('membership_number').value = '';
            document.getElementById('membership_date').value = '{{ date('Y-m-d') }}';
        }
    }

    // Show preview of auto-generated IDs
    document.addEventListener('DOMContentLoaded', function() {
        toggleUserType();

        const year = new Date().getFullYear();

        // Update placeholders with current year
        const memberInput = document.getElementById('membership_number');
        if (memberInput) {
            memberInput.placeholder = '{{ __('membership_number_placeholder_js', ['year' => $year]) }}';
        }

        const empInput = document.getElementById('employee_id');
        if (empInput) {
            empInput.placeholder = '{{ __('employee_id_placeholder_js', ['year' => $year]) }}';
        }

        // Auto-clear validation errors when switching tabs
        document.getElementById('role_member').addEventListener('change', function() {
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        });

        document.getElementById('role_treasurer').addEventListener('change', function() {
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        });
    });
</script>
@endpush