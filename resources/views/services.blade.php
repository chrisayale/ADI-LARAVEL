@extends('layouts.guest')

@section('title', 'Our Services - Capital Investment Club')

@section('content')
    <!-- Particle Background -->
    <div id="particles-js"></div>

    <!-- Navigation  -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <!-- Logo and Brand -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <div class="logo-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <span>Capital Investment</span>
            </a>

            <!-- Toggler for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('services') ? 'active' : '' }}" href="{{ route('services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('team') ? 'active' : '' }}" href="{{ route('team') }}">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('testimonials') ? 'active' : '' }}" href="{{ route('testimonials') }}">Testimonials</a>
                    </li>

                    <!-- Member Area Dropdown -->
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle btn btn-outline-success px-4" href="#" id="memberMenu"
                            role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>Member Area
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn">
                            <li>
                                <a class="dropdown-item" href="{{ route('login') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-sign-in-alt text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Login</h6>
                                            <small class="text-muted">Access your dashboard</small>
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

    <!-- Page Header -->
    <section class="page-header"
        style="padding-top: 150px; padding-bottom: 80px; background: linear-gradient(135deg, rgba(20,108,67,0.95) 0%, rgba(13,80,48,0.95) 100%), url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover fixed;">
        <div class="container text-center">
            <h1 class="display-3 fw-bold text-white mb-4" data-aos="fade-up">Our Services</h1>
            <p class="lead text-light opacity-75 col-lg-8 mx-auto" data-aos="fade-up" data-aos-delay="100">
                Comprehensive financial solutions tailored to empower your investment journey
            </p>
        </div>
    </section>

    <!-- Main Services Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @php
                    $allServices = [
                        [
                            'icon' => 'fa-chart-pie',
                            'title' => 'Portfolio Management',
                            'desc' => 'Comprehensive portfolio tracking and optimization tools to maximize your returns.',
                            'features' => [
                                'Real-time portfolio valuation',
                                'Performance analytics',
                                'Risk assessment tools',
                                'Diversification insights'
                            ],
                            'color' => 'success'
                        ],
                        [
                            'icon' => 'fa-hand-holding-usd',
                            'title' => 'Loan Management',
                            'desc' => 'Streamlined internal loan processing with flexible repayment options.',
                            'features' => [
                                'Quick application process',
                                'Automated approval workflow',
                                'Flexible repayment schedules',
                                'Interest calculation'
                            ],
                            'color' => 'warning'
                        ],
                        [
                            'icon' => 'fa-piggy-bank',
                            'title' => 'Savings & Contributions',
                            'desc' => 'Automated contribution tracking with smart reminders and history.',
                            'features' => [
                                'Automated monthly contributions',
                                'Contribution history',
                                'SMS/Email reminders',
                                'Mobile money integration'
                            ],
                            'color' => 'info'
                        ],
                        [
                            'icon' => 'fa-file-invoice-dollar',
                            'title' => 'Financial Reporting',
                            'desc' => 'Detailed financial reports and analytics for informed decision-making.',
                            'features' => [
                                'Monthly statements',
                                'Annual reports',
                                'Tax documentation',
                                'Custom report generation'
                            ],
                            'color' => 'primary'
                        ],
                        [
                            'icon' => 'fa-calendar-alt',
                            'title' => 'Meeting Coordination',
                            'desc' => 'Efficient meeting scheduling with automated reminders and minutes archive.',
                            'features' => [
                                'Meeting scheduling',
                                'Automated reminders',
                                'Minutes archive',
                                'Attendance tracking'
                            ],
                            'color' => 'danger'
                        ],
                        [
                            'icon' => 'fa-shield-alt',
                            'title' => 'Secure Transactions',
                            'desc' => 'Bank-level security for all your financial transactions.',
                            'features' => [
                                '256-bit encryption',
                                'Two-factor authentication',
                                'Transaction monitoring',
                                'Audit trails'
                            ],
                            'color' => 'purple'
                        ],
                        [
                            'icon' => 'fa-mobile-alt',
                            'title' => 'Mobile Access',
                            'desc' => 'Access your club information anytime, anywhere with our mobile platform.',
                            'features' => [
                                'Responsive design',
                                'Mobile app interface',
                                'Push notifications',
                                'Offline access'
                            ],
                            'color' => 'success'
                        ],
                        [
                            'icon' => 'fa-headset',
                            'title' => '24/7 Support',
                            'desc' => 'Round-the-clock customer support for all your queries.',
                            'features' => [
                                'Live chat support',
                                'Email assistance',
                                'Phone support',
                                'Knowledge base'
                            ],
                            'color' => 'warning'
                        ],
                        [
                            'icon' => 'fa-sync',
                            'title' => 'Auto-sync',
                            'desc' => 'Automatic synchronization across all devices and platforms.',
                            'features' => [
                                'Real-time updates',
                                'Multi-device sync',
                                'Data backup',
                                'Cloud storage'
                            ],
                            'color' => 'info'
                        ]
                    ];
                @endphp

                @foreach ($allServices as $index => $service)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100 animate-on-scroll">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-wrapper bg-{{ $service['color'] }} bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas {{ $service['icon'] }} fa-2x text-{{ $service['color'] }}"></i>
                                    </div>
                                    <h3 class="h4 mb-0">{{ $service['title'] }}</h3>
                                </div>
                                <p class="text-muted mb-4">{{ $service['desc'] }}</p>
                                <ul class="list-unstyled mb-4">
                                    @foreach ($service['features'] as $feature)
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="#contact" class="btn btn-outline-success w-100">
                                    Learn More <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Service Process Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-cogs me-2"></i>How It Works
                </span>
                <h2 class="display-5 fw-bold mb-3">Our Service Delivery Process</h2>
                <p class="lead text-muted">Simple, transparent, and efficient process for all our services</p>
            </div>

            <div class="row">
                @php
                    $steps = [
                        ['icon' => 'fa-user-plus', 'title' => '1. Member Registration', 'desc' => 'Register and verify your membership to access all services'],
                        ['icon' => 'fa-hand-pointer', 'title' => '2. Select Service', 'desc' => 'Choose the service you need from our comprehensive list'],
                        ['icon' => 'fa-check-circle', 'title' => '3. Application', 'desc' => 'Submit your application with required documentation'],
                        ['icon' => 'fa-clock', 'title' => '4. Processing', 'desc' => 'Our team processes your request within 24-48 hours'],
                        ['icon' => 'fa-bell', 'title' => '5. Notification', 'desc' => 'Receive instant notification about your application status'],
                        ['icon' => 'fa-star', 'title' => '6. Service Delivery', 'desc' => 'Access your service and provide feedback']
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                    <div class="col-lg-2 col-md-4 col-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <div class="text-center">
                            <div class="step-circle bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                <i class="fas {{ $step['icon'] }} fa-2x"></i>
                            </div>
                            <h5 class="mb-2">{{ $step['title'] }}</h5>
                            <small class="text-muted">{{ $step['desc'] }}</small>
                        </div>
                    </div>
                @endforeach
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
                            <h2 class="display-5 fw-bold text-dark mb-4">Ready to Get Started?</h2>
                            <p class="lead text-muted mb-5">Access all our services by becoming a member today.</p>
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">

                                <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-5 py-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Member Login
                                </a>
                            </div>
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
                        <h4 class="text-white mb-0 ms-2">Capital Investment Club</h4>
                    </div>
                    <p class="text-light opacity-75 mb-4">Empowering members through transparent digital finance solutions
                        and community-driven investments.</p>
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
                    <h6 class="text-white mb-3">Quick Links</h6>
                    <div class="footer-links">
                        <div class="mb-2"><a href="{{ url('/') }}">Home</a></div>
                        <div class="mb-2"><a href="#features">Features</a></div>
                        <div class="mb-2"><a href="#services">Services</a></div>
                        <div class="mb-2"><a href="#team">Our Team</a></div>
                    </div>
                </div>

                <!-- Member Area -->
                <div class="col-lg-3 col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                    <h6 class="text-white mb-3">Member Area</h6>
                    <div class="footer-links">
                        <div class="mb-2"><a href="{{ route('login') }}">Member Login</a></div>

                        <div class="mb-2"><a href="#">Dashboard</a></div>
                        <div class="mb-2"><a href="#">Help Center</a></div>
                        <div class="mb-2"><a href="#">Contact Support</a></div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <h6 class="text-white mb-3">Contact Info</h6>
                    <div class="footer-links">
                        <div class="mb-3 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt mt-1 me-3"></i>
                            <span class="text-white">Plot 123, Kampala Road<br>Kampala, Uganda</span>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <i class="fas fa-phone me-3"></i>
                            <span class="text-white">+256 700 123456</span>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope me-3"></i>
                            <span class="text-white">info@capitalclub.org</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-5 opacity-35 text-white">

            <!-- Copyright -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light opacity-75">&copy; 2025 Capital Investment Club. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#" class="me-3">Privacy Policy</a>
                        <a href="#" class="me-3">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection

@push('styles')
    <style>
        .step-circle {
            transition: all 0.3s ease;
        }
        .step-circle:hover {
            transform: scale(1.1);
            background: #0d5030 !important;
        }
    </style>
@endpush
