@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View Details
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Error Message -->
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

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User Information</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- User Type Display (Read-only) -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="font-weight-bold">User Type</label>
                        <div class="form-control bg-light">
                            @foreach($user->roles as $role)
                                <span class="badge badge-info p-2">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </div>
                        <small class="text-muted">User type cannot be changed after creation</small>
                    </div>
                </div>

                <!-- Personal Information (Common for both) -->
                <h5 class="border-bottom pb-2 mb-4">Personal Information</h5>
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

                <!-- Next of Kin Information (Required for ALL users) -->
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

                <!-- Member Specific Fields -->
                @if($user->hasRole('member'))
                <div id="member-fields">
                    <h5 class="border-bottom pb-2 mb-4 mt-4">Membership Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_number">Membership Number</label>
                            <input type="text" class="form-control @error('membership_number') is-invalid @enderror"
                                   id="membership_number" name="membership_number"
                                   value="{{ old('membership_number', $user->membership_number) }}"
                                   placeholder="MEM-{{ date('Y') }}-00001">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Format: MEM-[YEAR]-[SEQUENCE] (e.g., MEM-{{ date('Y') }}-00001)
                            </small>
                            @error('membership_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="membership_date">Membership Date</label>
                            <input type="date" class="form-control @error('membership_date') is-invalid @enderror"
                                   id="membership_date" name="membership_date"
                                   value="{{ old('membership_date', $user->membership_date ? $user->membership_date->format('Y-m-d') : date('Y-m-d')) }}">
                            @error('membership_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Treasurer Specific Fields -->
                @if($user->hasRole('treasurer'))
                <div id="treasurer-fields">
                    <h5 class="border-bottom pb-2 mb-4 mt-4">Employment Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                   id="employee_id" name="employee_id"
                                   value="{{ old('employee_id', $user->employee_id) }}"
                                   placeholder="EMP-{{ date('Y') }}-001">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Format: EMP-[YEAR]-[SEQUENCE] (e.g., EMP-{{ date('Y') }}-001)
                            </small>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employment_date">Employment Date</label>
                            <input type="date" class="form-control @error('employment_date') is-invalid @enderror"
                                   id="employment_date" name="employment_date"
                                   value="{{ old('employment_date', $user->employment_date ? $user->employment_date->format('Y-m-d') : date('Y-m-d')) }}">
                            @error('employment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="position">Position/Title</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                   id="position" name="position" value="{{ old('position', $user->position) }}"
                                   placeholder="e.g., Senior Treasurer, Assistant Treasurer">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Account Information -->
                <h5 class="border-bottom pb-2 mb-4 mt-4">Account Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status">Account Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="profile_photo">Profile Photo</label>
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                         class="rounded-circle"
                                         style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="{{ $user->name }}">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" accept="image/*">
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                        </div>
                        @error('profile_photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Change Section (Optional) -->
                <div class="card mt-4 mb-4 border-warning">
                    <div class="card-header bg-warning text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-key mr-2"></i> Change Password (Optional)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password">
                                <small class="text-muted">Leave empty to keep current password. Minimum 8 characters if changing.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-4">

                <div class="text-right">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danger Zone - Delete User (with active loan check) -->
    @if(isset($activeLoans) && $activeLoans == 0)
    <div class="card shadow mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            <h6 class="m-0 font-weight-bold">Danger Zone</h6>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="text-danger">Delete User Account</h5>
                    <p class="mb-0">Once you delete this user, all of their data will be permanently removed. This action cannot be undone.</p>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>

                    <p>Are you sure you want to delete this user?</p>

                    <div class="card bg-light">
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            @if($user->membership_number)
                                <p><strong>Membership #:</strong> {{ $user->membership_number }}</p>
                            @endif
                            @if($user->employee_id)
                                <p><strong>Employee ID:</strong> {{ $user->employee_id }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
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

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.rounded-circle');
                if (preview && preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // If it's a div with initials, replace it with an image
                    const container = document.querySelector('.d-flex.align-items-center .mr-3');
                    if (container) {
                        container.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" alt="Preview">`;
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Confirm before leaving with unsaved changes
    let formModified = false;
    const form = document.querySelector('form');
    const inputs = form?.querySelectorAll('input, select, textarea') || [];

    inputs.forEach(input => {
        input.addEventListener('change', () => {
            formModified = true;
        });
        input.addEventListener('keyup', () => {
            formModified = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formModified) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    form?.addEventListener('submit', function() {
        formModified = false;
    });
</script>
@endpush
