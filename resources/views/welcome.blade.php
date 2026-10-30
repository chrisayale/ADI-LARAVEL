@extends('layouts.guest')

@section('title', __('welcome_saving_sacco') . ' - ' . __('login_to_access'))

@section('content')
    <!-- Particle Background -->
    <div id="particles-js"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <!-- Logo and Brand -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <div class="logo-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <span>{{ __('welcome_saving_sacco') }}</span>
            </a>

            <!-- Toggler for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Standard Links -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">{{ __('home') }}</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Features</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="/services">{{ __('services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/team">{{ __('team') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/testimonials">{{ __('testimonials') }}</a>
                    </li>

                    <!-- Member Area Dropdown -->
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle btn btn-outline-success px-4" href="#" id="memberMenu"
                            role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>{{ __('member_area') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn">
                            <li>
                                <a class="dropdown-item" href="{{ route('login') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-sign-in-alt text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ __('login') }}</h6>
                                            <small class="text-muted">{{ __('access_your_dashboard') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-tachometer-alt text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ __('dashboard') }}</h6>
                                            <small class="text-muted">{{ __('view_your_portfolio') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section"
        style="padding-top: 150px; min-height: 100vh; display: flex; align-items: center; background: linear-gradient(135deg, rgba(20,108,67,0.9) 0%, rgba(13,80,48,0.9) 100%), url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover no-repeat fixed;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="mb-4">
                        <span
                            class="badge bg-light text-success px-4 py-2 rounded-pill mb-3 animate__animated animate__pulse animate__infinite">
                            <i class="fas fa-star me-2"></i>{{ __('trusted_by_members', ['count' => 250]) }}
                        </span>
                    </div>
                    <h1 class="display-3 fw-bold text-white mb-4 hero-text">
                        {!! __('empowering_financial_growth') !!}
                    </h1>
                    <p class="lead text-light mb-4 opacity-75">
                        {{ __('track_contributions') }}
                    </p>
                    <div class="mt-5">

                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="fas fa-play-circle me-2"></i>{{ __('see_how_it_works') }}
                        </a>
                    </div>
                    <div class="mt-5 d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt fa-2x text-light me-3"></i>
                            <div>
                                <h5 class="text-white mb-0">{{ __('bank_level_security') }}</h5>
                                <small class="text-light opacity-75">{{ __('256_bit_encryption') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bolt fa-2x text-warning me-3"></i>
                            <div>
                                <h5 class="text-white mb-0">{{ __('real_time_updates') }}</h5>
                                <small class="text-light opacity-75">{{ __('instant_notifications') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="position-relative">
                        <div class="floating">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                alt="Dashboard Preview" class="img-fluid rounded-4 shadow-lg">
                        </div>
                        <!-- Floating Elements -->
                        <div class="position-absolute top-0 start-0 bg-success rounded-circle p-3 shadow-lg floating"
                            style="animation-delay: 0.5s; z-index: 1;">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                        <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-3 shadow-lg floating"
                            style="animation-delay: 1s; z-index: 1;">
                            <i class="fas fa-coins fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="wave-bg">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                    opacity=".25" fill="#fff"></path>
                <path
                    d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                    opacity=".5" fill="#fff"></path>
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                    fill="#fff"></path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section bg-light">
        <div class="container">
            <div class="row text-center counter-section">
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="250">0</div>
                        <i class="fas fa-plus fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">{{ __('active_members') }}</p>
                </div>
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="5.2">0</div>
                        <i class="fas fa-dollar-sign fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">{{ __('million_contributions') }}</p>
                </div>
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="300">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="150">0</div>
                        <i class="fas fa-check-circle fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">{{ __('loans_approved') }}</p>
                </div>
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="400">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="98">0</div>
                        <i class="fas fa-percent fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">{{ __('member_satisfaction') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-bolt me-2"></i>{{ __('features') }}
                </span>
                <h2 class="display-5 fw-bold mb-3 reveal-text">{{ __('powerful_features') }}</h2>
                <p class="lead text-muted">{{ __('everything_you_need') }}
                </p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="hover-card card shadow-sm border-0 h-100 animate-on-scroll">
                        <div class="card-body p-4 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <h4 class="mb-3">{{ __('smart_contributions') }}</h4>
                            <p class="text-muted mb-4">{{ __('track_monthly_savings') }}</p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('automated_tracking') }}
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('real_time_updates_short') }}
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('mobile_notifications') }}</li>
                            </ul>
                            <a href="#" class="btn btn-link text-success text-decoration-none fw-bold">
                                {{ __('explore_feature') }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="hover-card card shadow-sm border-0 h-100 animate-on-scroll">
                        <div class="card-body p-4 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <h4 class="mb-3">{{ __('loan_management') }}</h4>
                            <p class="text-muted mb-4">{{ __('apply_internal_loans') }}</p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('quick_applications') }}
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('automated_approvals') }}
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('flexible_repayment') }}
                                </li>
                            </ul>
                            <a href="#" class="btn btn-link text-success text-decoration-none fw-bold">
                                {{ __('explore_feature') }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="hover-card card shadow-sm border-0 h-100 animate-on-scroll">
                        <div class="card-body p-4 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="mb-3">{{ __('meeting_coordination') }}</h4>
                            <p class="text-muted mb-4">{{ __('stay_updated_meetings') }}</p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('calendar_integration') }}
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('minutes_archive') }}
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('attendance_tracking') }}
                                </li>
                            </ul>
                            <a href="#" class="btn btn-link text-success text-decoration-none fw-bold">
                                {{ __('explore_feature') }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 parallax"
        style="background: linear-gradient(rgba(20,108,67,0.9), rgba(13,80,48,0.9)), url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover fixed;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-light text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-cogs me-2"></i>{{ __('our_services') }}
                </span>
                <h2 class="display-5 fw-bold text-white mb-3">{{ __('comprehensive_financial') }}</h2>
                <p class="lead text-light opacity-75">{{ __('end_to_end_solutions') }}
                </p>
            </div>

            <div class="row g-4">
                @php
                    $services = [
                        [
                            'icon' => 'fa-chart-pie',
                            'title' => 'portfolio_management',
                            'desc' => 'track_optimize_portfolio',
                            'color' => 'success',
                        ],
                        [
                            'icon' => 'fa-file-invoice-dollar',
                            'title' => 'financial_reporting',
                            'desc' => 'detailed_reports_analytics',
                            'color' => 'warning',
                        ],
                        [
                            'icon' => 'fa-shield-alt',
                            'title' => 'secure_transactions',
                            'desc' => 'bank_level_security_desc',
                            'color' => 'info',
                        ],
                        [
                            'icon' => 'fa-mobile-alt',
                            'title' => 'mobile_access',
                            'desc' => 'access_anywhere',
                            'color' => 'primary',
                        ],
                        [
                            'icon' => 'fa-headset',
                            'title' => 'support_24_7',
                            'desc' => 'round_clock_support',
                            'color' => 'danger',
                        ],
                        [
                            'icon' => 'fa-sync',
                            'title' => 'auto_sync',
                            'desc' => 'auto_data_sync',
                            'color' => 'purple',
                        ],
                    ];
                @endphp

                @foreach ($services as $index => $service)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="glass-effect hover-card card border-0 h-100 animate-on-scroll">
                            <div class="card-body p-4 text-center">
                                <div
                                    class="icon-wrapper bg-{{ $service['color'] }} bg-opacity-10 rounded-circle p-4 d-inline-block mb-4">
                                    <i class="fas {{ $service['icon'] }} fa-2x text-{{ $service['color'] }}"></i>
                                </div>
                                <h4 class="text-white mb-3">{{ __($service['title']) }}</h4>
                                <p class="text-light opacity-75 mb-4">{{ __($service['desc']) }}</p>
                                <a href="#" class="btn btn-outline-light btn-sm">
                                    {{ __('learn_more') }} <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Team Section - Circular Images Version -->
    <section id="team" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-users me-2"></i>{{ __('our_team') }}
                </span>
                <h2 class="display-5 fw-bold mb-3 reveal-text">{{ __('meet_leadership') }}</h2>
                <p class="lead text-muted">{{ __('dedicated_professionals') }}</p>
            </div>

            <div class="row g-4">
                @php
                    $team = [
                        [
                            'name' => 'jane_namutebi',
                            'role' => 'chairperson',
                            'desc' => 'jane_desc',
                            'image' =>
                                'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'JN',
                        ],
                        [
                            'name' => 'david_kato',
                            'role' => 'treasurer',
                            'desc' => 'david_desc',
                            'image' =>
                                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'DK',
                        ],
                        [
                            'name' => 'sarah_nansubuga',
                            'role' => 'secretary',
                            'desc' => 'sarah_desc',
                            'image' =>
                                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'SN',
                        ],
                    ];
                @endphp

                @foreach ($team as $index => $member)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100 animate-on-scroll">
                            <div class="card-body p-4 text-center">
                                <!-- Circular Profile Image -->
                                <div class="position-relative mb-4" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <img src="{{ $member['image'] }}" alt="{{ __($member['name']) }}"
                                        class="rounded-circle w-100 h-100 object-fit-cover shadow-sm border border-4 border-white">
                                    {{-- <div class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 border border-3 border-white">
                                @if ($member['role'] == 'Chairperson')
                                <i class="fas fa-crown"></i>
                                @elseif($member['role'] == 'Treasurer')
                                <i class="fas fa-coins"></i>
                                @else
                                <i class="fas fa-clipboard-list"></i>
                                @endif
                            </div> --}}
                                </div>

                                <!-- Name and Role -->
                                <h4 class="mb-1">{{ __($member['name']) }}</h4>
                                <div class="badge bg-success bg-opacity-10 text-success px-3 py-1 mb-3">
                                    {{ __($member['role']) }}
                                </div>

                                <!-- Description -->
                                <p class="text-muted mb-4">{{ __($member['desc']) }}</p>

                                <!-- Social Links -->
                                <div class="d-flex justify-content-center">
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-circle me-2"
                                        data-bs-toggle="tooltip" title="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-circle me-2"
                                        data-bs-toggle="tooltip" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-circle"
                                        data-bs-toggle="tooltip" title="Phone">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Optional: Add more team members button -->
            {{-- <div class="text-center mt-5" data-aos="fade-up">
            <a href="#contact" class="btn btn-outline-success px-4 py-2">
                <i class="fas fa-users me-2"></i>Meet Full Team
            </a>
        </div> --}}
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            });
        </script>
    @endpush

    <!-- Testimonials -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-star me-2"></i>{{ __('testimonials') }}
                </span>
                <h2 class="display-5 fw-bold mb-3 reveal-text">{{ __('what_members_say') }}</h2>
                <p class="lead text-muted">{{ __('join_satisfied_members') }}</p>
            </div>

            <div class="row g-4">
                @php
                    $testimonials = [
                        [
                            'name' => 'john_musoke',
                            'role' => 'member_since_2023',
                            'text' => 'john_testimonial',
                            'rating' => 5,
                        ],
                        [
                            'name' => 'mary_nakato',
                            'role' => 'business_owner',
                            'text' => 'mary_testimonial',
                            'rating' => 5,
                        ],
                        [
                            'name' => 'robert_ssemwanga',
                            'role' => 'club_treasurer',
                            'text' => 'robert_testimonial',
                            'rating' => 5,
                        ],
                    ];
                @endphp

                @foreach ($testimonials as $index => $testimonial)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100 animate-on-scroll">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="avatar bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-user text-success fa-2x"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">{{ __($testimonial['name']) }}</h5>
                                        <small class="text-muted">{{ __($testimonial['role']) }}</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">"{{ __($testimonial['text']) }}"</p>
                                <div class="star-rating">
                                    @for ($i = 0; $i < $testimonial['rating']; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-5" data-aos="fade-right">
                        <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                            <i class="fas fa-question-circle me-2"></i>{{ __('faq') }}
                        </span>
                        <h2 class="display-5 fw-bold mb-4">{{ __('frequently_asked') }}</h2>
                        <p class="lead text-muted">{{ __('find_answers') }}
                        </p>
                        <div class="mt-4">
                            <a href="#contact" class="btn btn-success btn-lg">
                                <i class="fas fa-comments me-2"></i>{{ __('ask_question') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="accordion" id="faqAccordion" data-aos="fade-left">
                        @php
                            $faqs = [
                                [
                                    'q' => 'who_can_join',
                                    'a' => 'who_can_join_a',
                                ],
                                [
                                    'q' => 'how_loans_approved',
                                    'a' => 'how_loans_approved_a',
                                ],
                                [
                                    'q' => 'contribution_requirements',
                                    'a' => 'contribution_requirements_a',
                                ],
                                [
                                    'q' => 'is_data_secure',
                                    'a' => 'is_data_secure_a',
                                ],
                            ];
                        @endphp

                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                        <i class="fas fa-question-circle text-success me-3"></i>
                                        {{ __($faq['q']) }}
                                    </button>
                                </h2>
                                <div id="faq{{ $index }}" class="accordion-collapse collapse"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ __($faq['a']) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #146c43 0%, #0d5030 100%);">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="zoom-in">
                    <div class="card border-0 shadow-lg hover-card" style="background: rgba(255, 255, 255, 0.95);">
                        <div class="card-body p-5">
                            <h2 class="display-5 fw-bold text-dark mb-4">{{ __('ready_to_transform') }}</h2>
                            <p class="lead text-muted mb-5">{{ __('join_hundreds') }}</p>
<div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
        <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-5 py-3">
            <i class="fas fa-sign-in-alt me-2"></i>{{ __('existing_member_login') }}
        </a>
        <a href="{{ route('request-sacco.create') }}" class="btn btn-success btn-lg px-5 py-3">
            <i class="fas fa-plus me-2"></i>{{ __('request_new_sacco') }}
        </a>
    </div>
                            <p class="mt-5 mb-0 text-muted">
                                {!! __('contact_info') !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer py-5" style="background: #0d5030;">
        <div class="container">
            <div class="row">
                <!-- About Column -->
                <div class="col-lg-4 mb-4 mb-lg-0" data-aos="fade-up">
                    <div class="d-flex align-items-center mb-4">
                        <div class="logo-icon text-warning">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="text-white mb-0 ms-2">{{ __('welcome_saving_sacco') }}</h4>
                    </div>
                    <p class="text-light opacity-75 mb-4">{{ __('empowering_members') }}</p>
                    <div class="social-links">
                        <a href="#" class="floating" style="animation-delay: 0s;"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="floating" style="animation-delay: 0.2s;"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="floating" style="animation-delay: 0.4s;"><i
                                class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="floating" style="animation-delay: 0.6s;"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100">
                    <h6 class="text-white mb-3">{{ __('quick_links') }}</h6>
                    <div class="footer-links">
                        <div class="mb-2"><a href="{{ url('/') }}">{{ __('home') }}</a></div>
                        <div class="mb-2"><a href="#features">{{ __('features') }}</a></div>
                        <div class="mb-2"><a href="#services">{{ __('services') }}</a></div>
                        <div class="mb-2"><a href="#team">{{ __('team') }}</a></div>
                    </div>
                </div>

                <!-- Member Area -->
                <div class="col-lg-3 col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                    <h6 class="text-white mb-3">{{ __('member_area_footer') }}</h6>
                    <div class="footer-links">
                        <div class="mb-2"><a href="{{ route('login') }}">{{ __('member_login') }}</a></div>

                        <div class="mb-2"><a href="#">{{ __('dashboard') }}</a></div>
                        <div class="mb-2"><a href="#">{{ __('help_center') }}</a></div>
                        <div class="mb-2"><a href="#">{{ __('contact_support') }}</a></div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <h6 class="text-white mb-3">{{ __('contact_info_footer') }}</h6>
                    <div class="footer-links">
                        <div class="mb-3 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt mt-1 me-3"></i>
                            <span class="text-white">{{ __('address_footer') }}</span>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <i class="fas fa-phone me-3"></i>
                            <span class="text-white">{{ __('phone_footer') }}</span>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope me-3"></i>
                            <span class="text-white">{{ __('email_footer') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-5 opacity-35 text-white">

            <!-- Copyright -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light opacity-75">{{ __('copyright') }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#" class="me-3">{{ __('privacy_policy') }}</a>
                        <a href="#" class="me-3">{{ __('terms_of_service') }}</a>
                        <a href="#">{{ __('cookie_policy') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>




@endsection

@push('styles')
    <style>
        /* Additional styles for animations */
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Team section background fix */
        .team-image {
            background-size: cover;
            background-position: center;
        }

        /* Star rating */
        .star-rating i {
            margin-right: 2px;
        }

        /* Icon wrapper */
        .icon-wrapper {
            transition: all 0.3s ease;
        }

        .icon-wrapper:hover {
            transform: scale(1.1);
        }
    </style>
@endpush
