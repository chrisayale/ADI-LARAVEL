<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Capital Investment Club')</title>

    <!-- External CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary-color: #146c43;
            --primary-light: #198754;
            --primary-dark: #0d5030;
            --primary-gradient: linear-gradient(135deg, #146c43 0%, #0d5030 100%);
            --accent-color: #ffc107;
            --text-color: #333;
            --text-light: #666;
            --border-color: #e1e5e9;
            --bg-light: #ffffff;
            --shadow-light: 0 5px 20px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 10px 30px rgba(20, 108, 67, 0.2);
            --shadow-hover: 0 15px 35px rgba(20, 108, 67, 0.25);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: var(--text-color);
            line-height: 1.7;
            height: 100%;
            width: 100%;
            position: fixed; /* Prevent scroll by fixing body */
        }

        /* ===== LAYOUT STRUCTURE ===== */
        .auth-layout {
            display: flex;
            height: 100vh;
            width: 100vw;
            position: relative;
            max-height: 100vh; /* Prevent vertical overflow */
            max-width: 100vw; /* Prevent horizontal overflow */
        }

        /* ===== LEFT PANEL - WELCOME CONTENT ===== */
        .auth-left {
            flex: 1.1;
            background: linear-gradient(135deg, rgba(20,108,67,0.95) 0%, rgba(13,80,48,0.95) 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            min-width: 0; /* Prevent flex item overflow */
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            opacity: 0.15;
            animation: zoomIn 20s ease-in-out infinite alternate;
        }

        .auth-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            max-width: 580px;
            width: 100%;
            padding: 1rem;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        /* ===== RIGHT PANEL - FORM ===== */
        .auth-right {
            flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0; /* Remove padding here */
    background: var(--bg-light);
    position: relative;
    overflow-y: auto;
    overflow-x: hidden;
    min-width: 0;
    max-height: 100vh;
    width: 100%;
        }

        .auth-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(20, 108, 67, 0.03) 0%, rgba(13, 80, 48, 0.03) 100%);
            z-index: -1;
        }

        /* ===== BACK TO HOME LINK ===== */
        .back-home {
            position: absolute;
            top: 2.8rem;
            left: -40px;
            z-index: 1000;

            animation: fadeInDown 0.8s ease-out;
        }

        .back-home a {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            background: white;
            border-radius: 50px;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 2px solid rgba(20, 108, 67, 0.1);
            font-size: 0.95rem;
            white-space: nowrap;
            will-change: transform;
            transform-origin: left center;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .back-home a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        .back-home a:active {
            transform: translateX(-5px) scale(0.98);
        }

        /* ===== AUTH CONTAINER ===== */
        .auth-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            padding: 0.5rem 0;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        /* ===== HEADER STYLES ===== */
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease-out 0.4s both;
            padding: 0 1rem;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            background: var(--primary-gradient);
            border-radius: 20px;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.8rem;
            box-shadow: var(--shadow-medium);
            transition: var(--transition-slow);
            animation: pulse 2s ease-in-out infinite;
        }

        .auth-logo:hover {
            transform: rotate(360deg) scale(1.1);
            animation: none;
        }

        .auth-header h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
            font-size: 2rem;
            line-height: 1.2;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .auth-header p {
            color: var(--text-light);
            font-size: 1rem;
            margin: 0 auto;
            max-width: 320px;
            animation: fadeIn 1s ease-out 0.6s both;
            line-height: 1.5;
        }

        /* ===== CONTENT HEADER ===== */
        .auth-content h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .auth-content p {
            opacity: 0.9;
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* ===== FORM COMPONENTS ===== */
        .auth-form {
            width: 100%;
            padding: 0 1rem;
        }

        .form-group-auth {
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease-out var(--delay) both;
        }

        .input-group-auth {
            position: relative;
        }

        .input-group-auth .icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 2;
            transition: var(--transition);
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .input-group-auth:focus-within .icon {
            color: var(--primary-dark);
            transform: translateY(-50%) scale(1.2);
        }

        .form-control-auth {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
            background: white;
            height: 52px;
            will-change: transform;
            max-width: 100%;
            box-sizing: border-box;
        }

        .form-control-auth:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(20, 108, 67, 0.1);
            outline: none;
            transform: translateY(-2px);
        }

        .form-control-auth.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
            animation: shake 0.5s ease-in-out;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 2;
            transition: var(--transition);
            padding: 0.4rem;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            will-change: transform;
        }

        .password-toggle:hover {
            background: rgba(20, 108, 67, 0.1);
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        /* ===== BUTTON STYLES ===== */
        .btn-auth {
            width: 100%;
            padding: 1rem;
            background: var(--primary-gradient);
            border: none;
            border-radius: var(--border-radius);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            min-height: 54px;
            margin-top: 1rem;
            overflow: hidden;
            will-change: transform;
            transform-origin: center;
            box-shadow: 0 4px 15px rgba(20, 108, 67, 0.3);
            max-width: 100%;
            box-sizing: border-box;
        }

        .btn-auth::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s ease;
        }

        .btn-auth:hover::before {
            left: 100%;
        }

        .btn-auth:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .btn-auth:active {
            transform: translateY(-1px);
            transition: var(--transition);
        }

        .btn-outline-auth {
            width: 100%;
            padding: 1rem;
            background: transparent;
            border: 2px solid var(--primary-color);
            border-radius: var(--border-radius);
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            min-height: 54px;
            margin-top: 1rem;
            will-change: transform;
            transform-origin: center;
            max-width: 100%;
            box-sizing: border-box;
        }

        .btn-outline-auth:hover {
            background: rgba(20, 108, 67, 0.08);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(20, 108, 67, 0.15);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.1);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: var(--shadow-medium);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 15px 40px rgba(20, 108, 67, 0.3);
            }
        }

        @keyshake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        /* ===== FEATURES LIST ===== */
        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .features-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            animation: fadeInRight 0.8s ease-out var(--delay) both;
        }

        .features-list .icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .features-list li:hover .icon {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
        }

        .features-list div {
            flex: 1;
            min-width: 0; /* Prevent text overflow */
        }

        .features-list strong {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 1rem;
            font-weight: 600;
            word-wrap: break-word;
        }

        .features-list .small {
            font-size: 0.85rem;
            opacity: 0.85;
            line-height: 1.4;
            word-wrap: break-word;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ===== FOOTER ===== */
        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            width: 100%;
            padding: 0 1rem;
        }

        .auth-footer small {
            color: var(--text-light);
            font-size: 0.875rem;
            line-height: 1.5;
            display: block;
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
        }

        /* ===== ALERT STYLES ===== */
        .alert-auth {
            border-radius: var(--border-radius);
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.3s ease;
            font-size: 0.9rem;
            word-wrap: break-word;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1200px) {
            .auth-content h3 {
                font-size: 1.8rem;
            }

            .features-list li {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 992px) {
            .auth-layout {
                flex-direction: column;
                height: 100vh;
            }

            .auth-left {
                display: none !important; /* Hide welcome panel on mobile */
            }

            .auth-right {
                width: 100%;
                /* padding: 3rem 1.5rem 1.5rem; */
                overflow-y: auto;
                max-height: 100vh;
            }

            .auth-container {
                max-width: 500px;
            }

            .back-home {
                top: 1rem;
                left: 1rem;
            }
        }

        @media (max-width: 768px) {
            .auth-right {
                /* padding: 2.5rem 1rem 1rem; */
            }

            .auth-header h2 {
                font-size: 1.6rem;
            }

            .auth-logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .back-home a {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .form-control-auth {
                height: 48px;
                padding: 0.75rem 0.875rem 0.75rem 2.75rem;
            }

            .btn-auth, .btn-outline-auth {
                min-height: 50px;
                padding: 0.875rem;
            }
        }

        @media (max-width: 576px) {
            .auth-right {
                padding: 2rem 0.75rem 0.75rem;
                justify-content: flex-start;
            }

            .auth-header h2 {
                font-size: 1.4rem;
            }

            .auth-header p {
                font-size: 0.9rem;
            }

            .back-home {
                top: 0.75rem;
                left: 0.75rem;
            }

            .back-home a {
                padding: 0.5rem;
                width: 40px;
                height: 40px;
                justify-content: center;
            }

            .back-home a span {
                display: none;
            }

            .auth-logo {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
                margin-bottom: 1rem;
            }

            .form-control-auth {
                height: 46px;
                font-size: 0.9rem;
            }

            .btn-auth, .btn-outline-auth {
                min-height: 48px;
                font-size: 0.95rem;
            }

            .auth-form {
                padding: 0 0.5rem;
            }
        }

        @media (max-width: 375px) {
            .auth-right {
                padding: 1.5rem 0.5rem 0.5rem;
            }

            .auth-header h2 {
                font-size: 1.3rem;
            }

            .back-home a {
                width: 36px;
                height: 36px;
                padding: 0.4rem;
            }

            .form-control-auth {
                height: 44px;
                padding: 0.7rem 0.75rem 0.7rem 2.5rem;
            }

            .auth-footer small {
                font-size: 0.8rem;
            }
        }

        /* Prevent text selection on interactive elements */
        .btn-auth, .btn-outline-auth, .password-toggle, .back-home a {
            user-select: none;
            -webkit-user-select: none;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-layout">
        <!-- Left Panel - Welcome Content -->
        <div class="auth-left">
            <div class="auth-content">
                <h3>Empowering Financial Growth Through Digital Innovation</h3>
                <p>Join thousands of satisfied members who trust our platform</p>

                <ul class="features-list">
                    <li style="--delay: 0.5s">
                        <div class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <strong>Bank-Level Security</strong>
                            <div class="small opacity-75">256-bit encryption for all transactions</div>
                        </div>
                    </li>
                    <li style="--delay: 0.7s">
                        <div class="icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <strong>Real-time Updates</strong>
                            <div class="small opacity-75">Instant notifications & dashboard updates</div>
                        </div>
                    </li>
                    <li style="--delay: 0.9s">
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <strong>Smart Analytics</strong>
                            <div class="small opacity-75">Track contributions & growth insights</div>
                        </div>
                    </li>
                    <li style="--delay: 1.1s">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <strong>Community Driven</strong>
                            <div class="small opacity-75">250+ active members growing together</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="auth-right">
            <!-- Back to Home Link -->
            <div class="back-home">
                <a href="{{ url('/') }}">
                    <i class="fas fa-arrow-left"></i>
                    <span> Home</span>
                </a>
            </div>

            <!-- Auth Container -->
            <div class="auth-container">
                <!-- Logo & Header -->
                <div class="auth-header mt-4">

                    <h2>Capital Investment Club</h2>
                    <p>@yield('subtitle', 'Member Management System')</p>
                </div>

                <!-- Messages -->
                @if(session('status'))
                    <div class="alert alert-success alert-auth animate__animated animate__fadeIn">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-auth animate__animated animate__shakeX">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Content -->
                <div class="auth-form">
                    @yield('content')
                </div>

                <!-- Footer Links -->
                <div class="auth-footer mt-3">
                    <small>
                        &copy; {{ date('Y') }} Capital Investment Club
                        @if(Route::has('register') && Route::currentRouteName() === 'login')
                            · <a href="{{ route('register') }}">Create Account</a>
                        @endif
                        @if(Route::has('login') && Route::currentRouteName() === 'register')
                            · <a href="{{ route('login') }}">Already have an account?</a>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- External JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent scroll on body
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';

            // Password toggle functionality
            // document.querySelectorAll('.password-toggle').forEach(button => {
            //     button.addEventListener('click', function(e) {
            //         e.preventDefault();
            //         const input = this.parentElement.querySelector('input');
            //         const icon = this.querySelector('i');

            //         if (input.type === 'password') {
            //             input.type = 'text';
            //             icon.classList.remove('fa-eye');
            //             icon.classList.add('fa-eye-slash');
            //         } else {
            //             input.type = 'password';
            //             icon.classList.remove('fa-eye-slash');
            //             icon.classList.add('fa-eye');
            //         }
            //     });
            // });

            // Form submission animation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        const originalHTML = submitBtn.innerHTML;

                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        submitBtn.disabled = true;

                        // Reset after timeout (fallback)
                        setTimeout(() => {
                            if (submitBtn.disabled) {
                                submitBtn.innerHTML = originalHTML;
                                submitBtn.disabled = false;
                            }
                        }, 10000);
                    }
                });
            });

            // Prevent form resubmission on page refresh
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            // Button hover effects without width changes
            document.querySelectorAll('.btn-auth, .btn-outline-auth, .back-home a').forEach(button => {
                // Store original dimensions
                const originalWidth = button.offsetWidth;
                const originalHeight = button.offsetHeight;

                button.addEventListener('mouseenter', function() {
                    // Prevent layout shift by using transform only
                    if (this.classList.contains('back-home')) {
                        this.style.transform = 'translateX(-5px)';
                    } else {
                        this.style.transform = 'translateY(-3px)';
                    }
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) translateX(0)';
                });

                button.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-1px) scale(0.98)';
                });

                button.addEventListener('mouseup', function() {
                    if (this.classList.contains('back-home')) {
                        this.style.transform = 'translateX(-5px)';
                    } else {
                        this.style.transform = 'translateY(-3px)';
                    }
                });
            });

            // Responsive adjustments
            function handleResponsive() {
                const isMobile = window.innerWidth <= 992;
                const backHomeBtn = document.querySelector('.back-home a');
                const welcomePanel = document.querySelector('.auth-left');

                // Handle welcome panel
                if (welcomePanel) {
                    if (isMobile) {
                        welcomePanel.style.display = 'none';
                    } else {
                        welcomePanel.style.display = 'flex';
                    }
                }

                // Handle back button on mobile
                if (backHomeBtn && window.innerWidth <= 576) {
                    backHomeBtn.querySelector('span').style.display = 'none';
                    backHomeBtn.style.padding = '0.5rem';
                    backHomeBtn.style.width = '40px';
                    backHomeBtn.style.justifyContent = 'center';
                } else if (backHomeBtn) {
                    backHomeBtn.querySelector('span').style.display = 'inline';
                    backHomeBtn.style.padding = '0.75rem 1.5rem';
                    backHomeBtn.style.width = '';
                    backHomeBtn.style.justifyContent = 'flex-start';
                }

                // Ensure no horizontal scroll
                document.querySelectorAll('.form-control-auth, .btn-auth, .btn-outline-auth').forEach(el => {
                    el.style.maxWidth = '100%';
                    el.style.boxSizing = 'border-box';
                });
            }

            // Initialize
            handleResponsive();
            window.addEventListener('resize', handleResponsive);

            // Prevent scrolling on touch devices
            document.addEventListener('touchmove', function(e) {
                if (e.target.closest('.auth-right')) {
                    // Allow scrolling only in form area on mobile
                    return;
                }
                e.preventDefault();
            }, { passive: false });

            // Add animation to form elements on load
            setTimeout(() => {
                document.querySelectorAll('.form-group-auth').forEach((el, index) => {
                    el.style.setProperty('--delay', `${0.1 * index}s`);
                });
            }, 100);

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
