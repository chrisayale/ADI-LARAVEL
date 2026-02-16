{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Validation Errors:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Edit Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                       id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="national_id">National ID/Passport <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                                       id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}" required>
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address">Physical Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="2" required>{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Specific Fields -->
                        @if($user->hasRole('member'))
                            <h5 class="border-bottom pb-2 mb-4 mt-4">Membership Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="membership_number">Membership Number</label>
                                    <input type="text" class="form-control @error('membership_number') is-invalid @enderror"
                                           id="membership_number" name="membership_number"
                                           value="{{ old('membership_number', $user->membership_number) }}" readonly>
                                    <small class="text-muted">Membership number cannot be changed</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="membership_date">Membership Date</label>
                                    <input type="date" class="form-control @error('membership_date') is-invalid @enderror"
                                           id="membership_date" name="membership_date"
                                           value="{{ old('membership_date', $user->membership_date ? $user->membership_date->format('Y-m-d') : '') }}">
                                    @error('membership_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @elseif($user->hasRole('treasurer'))
                            <h5 class="border-bottom pb-2 mb-4 mt-4">Employment Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="employee_id">Employee ID</label>
                                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                           id="employee_id" name="employee_id"
                                           value="{{ old('employee_id', $user->employee_id) }}" readonly>
                                    <small class="text-muted">Employee ID cannot be changed</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror"
                                           id="position" name="position" value="{{ old('position', $user->position) }}">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="employment_date">Employment Date</label>
                                    <input type="date" class="form-control @error('employment_date') is-invalid @enderror"
                                           id="employment_date" name="employment_date"
                                           value="{{ old('employment_date', $user->employment_date ? $user->employment_date->format('Y-m-d') : '') }}">
                                    @error('employment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Next of Kin Information (Common for all) -->
                        <h5 class="border-bottom pb-2 mb-4 mt-4">Next of Kin Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="next_of_kin_name">Next of Kin Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('next_of_kin_name') is-invalid @enderror"
                                       id="next_of_kin_name" name="next_of_kin_name"
                                       value="{{ old('next_of_kin_name', $user->next_of_kin_name) }}" required>
                                @error('next_of_kin_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="next_of_kin_phone">Next of Kin Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('next_of_kin_phone') is-invalid @enderror"
                                       id="next_of_kin_phone" name="next_of_kin_phone"
                                       value="{{ old('next_of_kin_phone', $user->next_of_kin_phone) }}" required>
                                @error('next_of_kin_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="next_of_kin_relationship">Relationship <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('next_of_kin_relationship') is-invalid @enderror"
                                       id="next_of_kin_relationship" name="next_of_kin_relationship"
                                       value="{{ old('next_of_kin_relationship', $user->next_of_kin_relationship) }}" required>
                                @error('next_of_kin_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Profile Photo -->
                        <h5 class="border-bottom pb-2 mb-4 mt-4">Profile Photo</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                 class="rounded-circle"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 alt="{{ $user->name }}" id="photoPreview">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px; font-size: 2rem;" id="photoPreview">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="profile_photo" name="profile_photo" accept="image/*">
                                            <label class="custom-file-label" for="profile_photo">Choose new photo</label>
                                        </div>
                                        <small class="text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <h5 class="border-bottom pb-2 mb-4 mt-4">Change Password</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <small class="text-muted">Leave empty to keep current password</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Profile Summary Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Summary</h6>
                </div>
                <div class="card-body text-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             class="rounded-circle img-fluid mb-3"
                             style="width: 120px; height: 120px; object-fit: cover;"
                             alt="{{ $user->name }}">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif

                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">
                        @foreach($user->roles as $role)
                            <span class="badge badge-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'treasurer' ? 'info' : 'success') }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </p>

                    <hr>

                    <div class="text-left">
                        <p><i class="fas fa-envelope mr-2"></i> {{ $user->email }}</p>
                        <p><i class="fas fa-phone mr-2"></i> {{ $user->phone_number ?? 'N/A' }}</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i> {{ $user->address ?? 'N/A' }}</p>
                        @if($user->hasRole('member'))
                            <p><i class="fas fa-id-card mr-2"></i> {{ $user->membership_number ?? 'N/A' }}</p>
                        @elseif($user->hasRole('treasurer'))
                            <p><i class="fas fa-id-badge mr-2"></i> {{ $user->employee_id ?? 'N/A' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card shadow mb-4 bg-info text-white">
                <div class="card-body">
                    <h6 class="font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Profile Tips</h6>
                    <ul class="mb-0 small">
                        <li>Keep your contact information up to date</li>
                        <li>Use a professional profile photo</li>
                        <li>Update your next of kin information regularly</li>
                        <li>Change your password periodically for security</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }

            // Check file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (JPG, PNG, GIF)');
                this.value = '';
                return;
            }

            // Update file input label
            const label = this.nextElementSibling;
            if (label) {
                label.textContent = file.name;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with image
                    const container = preview.parentNode;
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded-circle';
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';
                    img.id = 'photoPreview';
                    container.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(file);
        } else {
            // Reset label
            const label = this.nextElementSibling;
            if (label) {
                label.textContent = 'Choose new photo';
            }
        }
    });

    // Confirm before leaving with unsaved changes
    let formModified = false;
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('input:not([type="password"]), select, textarea');

    inputs.forEach(input => {
        input.addEventListener('change', () => {
            formModified = true;
        });
        input.addEventListener('keyup', () => {
            formModified = true;
        });
    });

    // Password fields
    document.getElementById('current_password').addEventListener('input', () => {
        formModified = true;
    });
    document.getElementById('new_password').addEventListener('input', () => {
        formModified = true;
    });

    window.addEventListener('beforeunload', function(e) {
        if (formModified) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    form.addEventListener('submit', function() {
        formModified = false;
    });

    function resetForm() {
        if (confirm('Reset all changes?')) {
            form.reset();
            formModified = false;

            // Reset file input label
            const fileInput = document.getElementById('profile_photo');
            const label = fileInput.nextElementSibling;
            if (label) {
                label.textContent = 'Choose new photo';
            }

            // Reload the page to get original values
            location.reload();
        }
    }
</script>
@endpush
