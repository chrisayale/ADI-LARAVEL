@extends('layouts.auth')

@section('title', 'Login - Capital Investment Club')
@section('subtitle', 'Welcome back to your investment dashboard')

@section('content')
    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="form-group-auth">
            <div class="input-group-auth">
                <i class="fas fa-envelope icon"></i>
                <input type="email"
                       class="form-control-auth @error('email') is-invalid @enderror"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       placeholder="Email Address"
                       required
                       autofocus>
            </div>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group-auth">
            <div class="input-group-auth">
                <i class="fas fa-lock icon"></i>
                <input type="password"
                       class="form-control-auth @error('password') is-invalid @enderror"
                       name="password"
                       id="password"
                       placeholder="Password"
                       required>
                <button type="button" class="password-toggle" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       name="remember"
                       id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link small">
                    Forgot Password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-auth mb-3">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login to Dashboard</span>
        </button>

        @if(Route::has('register'))
            <div class="divider">
                <span>or</span>
            </div>

            <a href="{{ route('register') }}" class="btn-outline-auth">
                <i class="fas fa-user-plus"></i>
                <span>Create New Account</span>
            </a>
        @endif
    </form>
        <form method="GET" action="{{ route('request-sacco.create') }}" class="mt-3">
            <button type="submit" class="btn-auth mb-3">
                <i class="fas fa-plus"></i>
                <span>Demander la création d'un SACCO</span>
            </button>
        </form>
@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });})
</script>
@endpush
