@extends('layouts.auth')

@section('title', 'Register - Capital Investment Club')
@section('subtitle', 'Create Your Account')

@section('content')
    <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" id="registerForm">
        @csrf

        <!-- Personal Information -->
        <div class="form-section">
            <h6 class="section-title mb-3">Personal Information</h6>

            <div class="row g-1">
                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">First Name</label>
                        <div class="input-group-auth">
                            <i class="fas fa-user icon"></i>
                            <input type="text"
                                   class="form-control-auth @error('first_name') is-invalid @enderror"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   placeholder="Enter first name"
                                   required>
                        </div>
                        @error('first_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Last Name</label>
                        <div class="input-group-auth">
                            <i class="fas fa-user icon"></i>
                            <input type="text"
                                   class="form-control-auth @error('last_name') is-invalid @enderror"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   placeholder="Enter last name"
                                   required>
                        </div>
                        @error('last_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Email Address</label>
                        <div class="input-group-auth">
                            <i class="fas fa-envelope icon"></i>
                            <input type="email"
                                   class="form-control-auth @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="you@example.com"
                                   required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Phone Number</label>
                        <div class="input-group-auth">
                            <i class="fas fa-phone icon"></i>
                            <input type="tel"
                                   class="form-control-auth @error('phone') is-invalid @enderror"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="+1 234 567 8900"
                                   required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="form-section mt-4">
            <h6 class="section-title mb-3">Account Security</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Password</label>
                        <div class="input-group-auth">
                            <i class="fas fa-lock icon"></i>
                            <input type="password"
                                   class="form-control-auth @error('password') is-invalid @enderror"
                                   name="password"
                                   id="password"
                                   placeholder="Create a strong password"
                                   required>
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength mt-2">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted d-block mt-1">Password strength: <span class="strength-text">Very Weak</span></small>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Confirm Password</label>
                        <div class="input-group-auth">
                            <i class="fas fa-lock icon"></i>
                            <input type="password"
                                   class="form-control-auth @error('password_confirmation') is-invalid @enderror"
                                   name="password_confirmation"
                                   id="confirmPassword"
                                   placeholder="Re-enter your password"
                                   required>
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility" data-target="confirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match mt-2">
                            <small class="text-muted">
                                <i class="fas fa-check-circle text-success d-none"></i>
                                <i class="fas fa-times-circle text-danger d-none"></i>
                                <span class="match-text">Passwords must match</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="form-section mt-4">
            <h6 class="section-title mb-3">Employment Details</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Job Type</label>
                        <div class="input-group-auth">
                            <i class="fas fa-briefcase icon"></i>
                            <select class="form-control-auth @error('job_type') is-invalid @enderror"
                                    name="job_type"
                                    required>
                                <option value="" disabled selected>Select job type</option>
                                <option value="Full-time" {{ old('job_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Part-time" {{ old('job_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                <option value="Contract" {{ old('job_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Freelance" {{ old('job_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="Self-employed" {{ old('job_type') == 'Self-employed' ? 'selected' : '' }}>Self-employed</option>
                                <option value="Unemployed" {{ old('job_type') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                <option value="Student" {{ old('job_type') == 'Student' ? 'selected' : '' }}>Student</option>
                            </select>
                        </div>
                        @error('job_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Employment Status</label>
                        <div class="input-group-auth">
                            <i class="fas fa-chart-line icon"></i>
                            <select class="form-control-auth @error('employment_status') is-invalid @enderror"
                                    name="employment_status"
                                    required>
                                <option value="" disabled selected>Select status</option>
                                <option value="Employed" {{ old('employment_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                <option value="Self-employed" {{ old('employment_status') == 'Self-employed' ? 'selected' : '' }}>Self-employed</option>
                                <option value="Unemployed" {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                <option value="Student" {{ old('employment_status') == 'Student' ? 'selected' : '' }}>Student</option>
                                <option value="Retired" {{ old('employment_status') == 'Retired' ? 'selected' : '' }}>Retired</option>
                            </select>
                        </div>
                        @error('employment_status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Next of Kin Information -->
        <div class="form-section mt-4">
            <h6 class="section-title mb-3">Next of Kin Details</h6>

            <div class="form-group-auth mb-3">
                <label class="form-label small mb-1">Full Name</label>
                <div class="input-group-auth">
                    <i class="fas fa-users icon"></i>
                    <input type="text"
                           class="form-control-auth @error('next_of_kin_name') is-invalid @enderror"
                           name="next_of_kin_name"
                           value="{{ old('next_of_kin_name') }}"
                           placeholder="Enter kin's full name"
                           required>
                </div>
                @error('next_of_kin_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Relationship</label>
                        <div class="input-group-auth">
                            <i class="fas fa-heart icon"></i>
                            <select class="form-control-auth @error('next_of_kin_relationship') is-invalid @enderror"
                                    name="next_of_kin_relationship"
                                    required>
                                <option value="" disabled selected>Select relationship</option>
                                <option value="Spouse" {{ old('next_of_kin_relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                <option value="Parent" {{ old('next_of_kin_relationship') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                <option value="Sibling" {{ old('next_of_kin_relationship') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                <option value="Child" {{ old('next_of_kin_relationship') == 'Child' ? 'selected' : '' }}>Child</option>
                                <option value="Friend" {{ old('next_of_kin_relationship') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                <option value="Other" {{ old('next_of_kin_relationship') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        @error('next_of_kin_relationship')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-auth">
                        <label class="form-label small mb-1">Contact Number</label>
                        <div class="input-group-auth">
                            <i class="fas fa-phone-alt icon"></i>
                            <input type="tel"
                                   class="form-control-auth @error('next_of_kin_contact') is-invalid @enderror"
                                   name="next_of_kin_contact"
                                   value="{{ old('next_of_kin_contact') }}"
                                   placeholder="+1 234 567 8900"
                                   required>
                        </div>
                        @error('next_of_kin_contact')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address & Documents -->
        <div class="form-section mt-4">
            <h6 class="section-title mb-3">Address & Verification</h6>

            <div class="form-group-auth mb-3">
                <label class="form-label small mb-1">Full Address</label>
                <div class="input-group-auth">
                    <i class="fas fa-map-marker-alt icon"></i>
                    <textarea class="form-control-auth @error('address') is-invalid @enderror"
                              name="address"
                              placeholder="Enter your complete address"
                              rows="2"
                              required>{{ old('address') }}</textarea>
                </div>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group-auth">
                <label class="form-label small mb-1">National ID / Document</label>
                <div class="file-upload-wrapper">
                    <div class="input-group-auth">
                        <i class="fas fa-id-card icon"></i>
                        <input type="file"
                               class="form-control-auth @error('national_id') is-invalid @enderror"
                               name="national_id"
                               id="nationalId"
                               accept=".pdf,.jpg,.jpeg,.png"
                               style="padding-left: 3rem;">
                    </div>
                    <div class="file-info mt-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload National ID, Driver's License or Passport (PDF, JPG, PNG - Max 2MB)
                        </small>
                        <div class="file-preview mt-2 d-none">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file text-primary me-2"></i>
                                <span class="file-name small"></span>
                                <button type="button" class="btn-remove-file ms-2 btn btn-sm btn-link text-danger p-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('national_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="form-section mt-4">
            <div class="form-check">
                <input class="form-check-input @error('agree_terms') is-invalid @enderror"
                       type="checkbox"
                       name="agree_terms"
                       id="agree_terms"
                       required>
                <label class="form-check-label small" for="agree_terms">
                    I agree to the <a href="#" class="text-decoration-none fw-semibold">Terms & Conditions</a> and
                    <a href="#" class="text-decoration-none fw-semibold">Privacy Policy</a>
                </label>
                @error('agree_terms')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
            <button type="submit" class="btn-auth" id="submitBtn">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </div>
    </form>

    <div class="text-center mt-4 pt-3">
        <small class="text-muted">
            Already have an account?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                Login here
            </a>
        </small>
    </div>

    @push('styles')
    <style>
        /* Form Section Styling */
        .form-section {
            background: rgba(20, 108, 67, 0.02);
            border-radius: var(--border-radius);
            padding: 10px;
            border: 1px solid rgba(20, 108, 67, 0.05);
            transition: var(--transition);
        }

        .form-section:hover {
            border-color: rgba(20, 108, 67, 0.1);
            background: rgba(20, 108, 67, 0.03);
        }

        .section-title {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(20, 108, 67, 0.1);
        }

        /* Form Labels */
        .form-label {
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Select Styling */
        .form-control-auth[type="file"],
        .form-control-auth[type="select"] {
            cursor: pointer;
        }

        select.form-control-auth {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23146c43' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            padding-right: 2.5rem !important;
        }

        /* Textarea Styling */
        textarea.form-control-auth {
            min-height: 80px;
            resize: vertical;
            padding-top: 0.875rem !important;
            line-height: 1.5;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
        }

        .file-info {
            padding-left: 3rem;
        }

        .file-preview {
            background: rgba(20, 108, 67, 0.05);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            border: 1px dashed rgba(20, 108, 67, 0.2);
        }

        .btn-remove-file {
            font-size: 0.8rem;
        }

        /* Password Strength Indicator */
        .password-strength .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 2px;
        }

        .password-strength .progress-bar {
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .strength-text {
            font-weight: 600;
        }

        /* Password Match Indicator */
        .password-match .text-success,
        .password-match .text-danger {
            font-size: 0.8rem;
        }

        /* Row Spacing */
        .row.g-3 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        /* Error Styling */
        .is-invalid {
            border-color: #dc3545 !important;
            animation: shake 0.5s ease-in-out;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }

            .row.g-3 {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 0.75rem;
            }

            .row.g-3 {
                --bs-gutter-x: 0.5rem;
                --bs-gutter-y: 0.5rem;
            }

            .section-title {
                font-size: 0.85rem;
            }

            .form-label {
                font-size: 0.8rem;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Improved Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (!input) return;

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                        this.setAttribute('aria-label', 'Hide password');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                        this.setAttribute('aria-label', 'Show password');
                    }

                    this.setAttribute('aria-pressed', input.type === 'text');
                });

                // Initialize
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input) {
                    button.setAttribute('aria-label', 'Show password');
                    button.setAttribute('aria-pressed', 'false');
                }
            });

            // Password strength checker
            const passwordInput = document.getElementById('password');
            const progressBar = document.querySelector('.password-strength .progress-bar');
            const strengthText = document.querySelector('.strength-text');

            if (passwordInput && progressBar && strengthText) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;

                    // Length check
                    if (password.length >= 8) strength += 25;
                    if (password.length >= 12) strength += 15;

                    // Complexity checks
                    if (/[A-Z]/.test(password)) strength += 20;
                    if (/[a-z]/.test(password)) strength += 20;
                    if (/[0-9]/.test(password)) strength += 20;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 20;

                    // Cap at 100
                    strength = Math.min(strength, 100);

                    // Update progress bar
                    progressBar.style.width = strength + '%';

                    // Update colors and text
                    let color = '#dc3545'; // red
                    let text = 'Very Weak';

                    if (strength >= 40) {
                        color = '#ffc107'; // yellow
                        text = 'Weak';
                    }
                    if (strength >= 60) {
                        color = '#0dcaf0'; // teal
                        text = 'Fair';
                    }
                    if (strength >= 80) {
                        color = '#20c997'; // green
                        text = 'Good';
                    }
                    if (strength >= 95) {
                        color = '#198754'; // dark green
                        text = 'Strong';
                    }

                    progressBar.style.backgroundColor = color;
                    strengthText.textContent = text;
                    strengthText.style.color = color;
                });
            }

            // Password confirmation check
            const confirmInput = document.getElementById('confirmPassword');
            const checkIcon = document.querySelector('.password-match .fa-check-circle');
            const timesIcon = document.querySelector('.password-match .fa-times-circle');
            const matchText = document.querySelector('.match-text');

            function checkPasswordMatch() {
                const password = passwordInput ? passwordInput.value : '';
                const confirm = confirmInput ? confirmInput.value : '';

                if (!checkIcon || !timesIcon || !matchText) return;

                if (!confirm) {
                    checkIcon.classList.add('d-none');
                    timesIcon.classList.add('d-none');
                    matchText.textContent = 'Passwords must match';
                    matchText.style.color = '';
                    return;
                }

                if (password === confirm) {
                    checkIcon.classList.remove('d-none');
                    timesIcon.classList.add('d-none');
                    matchText.textContent = 'Passwords match';
                    matchText.style.color = '#198754';
                } else {
                    checkIcon.classList.add('d-none');
                    timesIcon.classList.remove('d-none');
                    matchText.textContent = 'Passwords do not match';
                    matchText.style.color = '#dc3545';
                }
            }

            if (passwordInput) {
                passwordInput.addEventListener('input', checkPasswordMatch);
            }
            if (confirmInput) {
                confirmInput.addEventListener('input', checkPasswordMatch);
            }

            // File upload preview
            const fileInput = document.getElementById('nationalId');
            const filePreview = document.querySelector('.file-preview');
            const fileName = document.querySelector('.file-name');
            const removeBtn = document.querySelector('.btn-remove-file');

            if (fileInput && filePreview && fileName && removeBtn) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];

                        // Check file size (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size must be less than 2MB');
                            this.value = '';
                            filePreview.classList.add('d-none');
                            return;
                        }

                        // Check file type
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                        if (!validTypes.includes(file.type)) {
                            alert('Only JPG, PNG, and PDF files are allowed');
                            this.value = '';
                            filePreview.classList.add('d-none');
                            return;
                        }

                        fileName.textContent = file.name;
                        filePreview.classList.remove('d-none');
                    }
                });

                removeBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    filePreview.classList.add('d-none');
                });
            }

            // Form validation
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    const termsCheckbox = document.getElementById('agree_terms');

                    if (termsCheckbox && !termsCheckbox.checked) {
                        e.preventDefault();
                        termsCheckbox.focus();
                        termsCheckbox.classList.add('is-invalid');
                        return;
                    }

                    // Check password match
                    if (passwordInput && confirmInput && passwordInput.value !== confirmInput.value) {
                        e.preventDefault();
                        if (confirmInput) {
                            confirmInput.focus();
                            confirmInput.classList.add('is-invalid');
                        }
                        return;
                    }

                    // Show loading state
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
                    submitBtn.disabled = true;

                    // Re-enable after 10 seconds (fallback)
                    setTimeout(() => {
                        if (submitBtn.disabled) {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    }, 10000);
                });

                // Real-time validation
                form.addEventListener('input', function(e) {
                    const target = e.target;

                    if (target.classList.contains('is-invalid')) {
                        // Remove invalid class when user starts typing
                        target.classList.remove('is-invalid');

                        // Update related error display if exists
                        const errorDiv = target.parentElement.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.textContent = '';
                        }
                    }

                    // Special handling for terms checkbox
                    if (target.id === 'agree_terms') {
                        target.classList.remove('is-invalid');
                    }
                });

                // Focus management for better UX
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach((input, index) => {
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && index < inputs.length - 1) {
                            e.preventDefault();
                            inputs[index + 1].focus();
                        }
                    });

                    // Add focus styling
                    input.addEventListener('focus', function() {
                        this.parentElement.parentElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    });
                });
            }
        });
    </script>
    @endpush
@endsection
