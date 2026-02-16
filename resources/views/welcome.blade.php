@extends('layouts.guest')

@section('title', 'Capital Investment Club - Empowering Members Through Digital Finance')

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
                <span>Capital Investment</span>
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
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Features</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/team">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/testimonials">Testimonials</a>
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
                                            <h6 class="mb-0">Dashboard</h6>
                                            <small class="text-muted">View your portfolio</small>
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
                            <i class="fas fa-star me-2"></i>Trusted by 250+ Members
                        </span>
                    </div>
                    <h1 class="display-3 fw-bold text-white mb-4 hero-text">
                        Empowering <span class="shine-text">Financial Growth</span> Through Digital Innovation
                    </h1>
                    <p class="lead text-light mb-4 opacity-75">
                        Track contributions, manage loans, and coordinate meetings — all in one secure, intuitive platform
                        designed for modern investment clubs.
                    </p>
                    <div class="mt-5">

                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="fas fa-play-circle me-2"></i>See How It Works
                        </a>
                    </div>
                    <div class="mt-5 d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt fa-2x text-light me-3"></i>
                            <div>
                                <h5 class="text-white mb-0">Bank-Level Security</h5>
                                <small class="text-light opacity-75">256-bit encryption</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bolt fa-2x text-warning me-3"></i>
                            <div>
                                <h5 class="text-white mb-0">Real-time Updates</h5>
                                <small class="text-light opacity-75">Instant notifications</small>
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
                    <p class="text-muted mt-2">Active Members</p>
                </div>
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="5.2">0</div>
                        <i class="fas fa-dollar-sign fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">Million in Contributions</p>
                </div>
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="300">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="150">0</div>
                        <i class="fas fa-check-circle fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">Loans Approved</p>
                </div>
                <div class="col-md-3 col-6 mb-5" data-aos="fade-up" data-aos-delay="400">
                    <div class="position-relative">
                        <div class="stat-number display-4 fw-bold gradient-text counter" data-count="98">0</div>
                        <i class="fas fa-percent fa-2x text-success position-absolute top-0 end-0"></i>
                    </div>
                    <p class="text-muted mt-2">Member Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-bolt me-2"></i>Features
                </span>
                <h2 class="display-5 fw-bold mb-3 reveal-text">Powerful Features for Modern Investment Clubs</h2>
                <p class="lead text-muted">Everything you need to manage your investment club efficiently and transparently
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
                            <h4 class="mb-3">Smart Contributions</h4>
                            <p class="text-muted mb-4">Track your monthly savings with full transparency. Automated
                                reminders and detailed history at your fingertips.</p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Automated tracking
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Real-time updates
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Mobile
                                    notifications</li>
                            </ul>
                            <a href="#" class="btn btn-link text-success text-decoration-none fw-bold">
                                Explore Feature <i class="fas fa-arrow-right ms-1"></i>
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
                            <h4 class="mb-3">Loan Management</h4>
                            <p class="text-muted mb-4">Apply for internal loans and monitor repayment status easily.
                                Real-time updates and flexible payment options.</p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Quick applications
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Automated
                                    approvals</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Flexible repayment
                                </li>
                            </ul>
                            <a href="#" class="btn btn-link text-success text-decoration-none fw-bold">
                                Explore Feature <i class="fas fa-arrow-right ms-1"></i>
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
                            <h4 class="mb-3">Meeting Coordination</h4>
                            <p class="text-muted mb-4">Stay updated on upcoming meetings and access archived minutes. Never
                                miss an important decision.</p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Calendar
                                    integration</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Minutes archive
                                </li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Attendance
                                    tracking</li>
                            </ul>
                            <a href="#" class="btn btn-link text-success text-decoration-none fw-bold">
                                Explore Feature <i class="fas fa-arrow-right ms-1"></i>
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
                    <i class="fas fa-cogs me-2"></i>Our Services
                </span>
                <h2 class="display-5 fw-bold text-white mb-3">Comprehensive Financial Solutions</h2>
                <p class="lead text-light opacity-75">We provide end-to-end solutions for all your investment club needs
                </p>
            </div>

            <div class="row g-4">
                @php
                    $services = [
                        [
                            'icon' => 'fa-chart-pie',
                            'title' => 'Portfolio Management',
                            'desc' => 'Track and optimize your investment portfolio',
                            'color' => 'success',
                        ],
                        [
                            'icon' => 'fa-file-invoice-dollar',
                            'title' => 'Financial Reporting',
                            'desc' => 'Detailed reports and analytics',
                            'color' => 'warning',
                        ],
                        [
                            'icon' => 'fa-shield-alt',
                            'title' => 'Secure Transactions',
                            'desc' => 'Bank-level security for all transactions',
                            'color' => 'info',
                        ],
                        [
                            'icon' => 'fa-mobile-alt',
                            'title' => 'Mobile Access',
                            'desc' => 'Access your account anywhere, anytime',
                            'color' => 'primary',
                        ],
                        [
                            'icon' => 'fa-headset',
                            'title' => '24/7 Support',
                            'desc' => 'Round-the-clock customer support',
                            'color' => 'danger',
                        ],
                        [
                            'icon' => 'fa-sync',
                            'title' => 'Auto-sync',
                            'desc' => 'Automatic data synchronization',
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
                                <h4 class="text-white mb-3">{{ $service['title'] }}</h4>
                                <p class="text-light opacity-75 mb-4">{{ $service['desc'] }}</p>
                                <a href="#" class="btn btn-outline-light btn-sm">
                                    Learn More <i class="fas fa-arrow-right ms-1"></i>
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
                    <i class="fas fa-users me-2"></i>Our Team
                </span>
                <h2 class="display-5 fw-bold mb-3 reveal-text">Meet Our Leadership Team</h2>
                <p class="lead text-muted">Dedicated professionals guiding our investment club to success</p>
            </div>

            <div class="row g-4">
                @php
                    $team = [
                        [
                            'name' => 'Jane Namutebi',
                            'role' => 'Chairperson',
                            'desc' =>
                                'Oversees club governance and strategic direction with over 10 years of investment experience.',
                            'image' =>
                                'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'JN',
                        ],
                        [
                            'name' => 'David Kato',
                            'role' => 'Treasurer',
                            'desc' =>
                                'Manages contributions, loans, and financial reporting with precision and transparency.',
                            'image' =>
                                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'DK',
                        ],
                        [
                            'name' => 'Sarah Nansubuga',
                            'role' => 'Secretary',
                            'desc' =>
                                'Coordinates meetings and maintains member records with exceptional organizational skills.',
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
                                    <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
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
                                <h4 class="mb-1">{{ $member['name'] }}</h4>
                                <div class="badge bg-success bg-opacity-10 text-success px-3 py-1 mb-3">
                                    {{ $member['role'] }}
                                </div>

                                <!-- Description -->
                                <p class="text-muted mb-4">{{ $member['desc'] }}</p>

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
                    <i class="fas fa-star me-2"></i>Testimonials
                </span>
                <h2 class="display-5 fw-bold mb-3 reveal-text">What Our Members Say</h2>
                <p class="lead text-muted">Join thousands of satisfied members who trust our platform</p>
            </div>

            <div class="row g-4">
                @php
                    $testimonials = [
                        [
                            'name' => 'John Musoke',
                            'role' => 'Member since 2023',
                            'text' =>
                                'This platform transformed how our investment club operates. The transparency and ease of use are incredible!',
                            'rating' => 5,
                        ],
                        [
                            'name' => 'Mary Nakato',
                            'role' => 'Business Owner',
                            'text' =>
                                'The loan management system saved us countless hours. Highly recommended for any investment group!',
                            'rating' => 5,
                        ],
                        [
                            'name' => 'Robert Ssemwanga',
                            'role' => 'Club Treasurer',
                            'text' => 'Best decision we made for our club. The reporting features alone are worth it!',
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
                                        <h5 class="mb-1">{{ $testimonial['name'] }}</h5>
                                        <small class="text-muted">{{ $testimonial['role'] }}</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">"{{ $testimonial['text'] }}"</p>
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
                            <i class="fas fa-question-circle me-2"></i>FAQ
                        </span>
                        <h2 class="display-5 fw-bold mb-4">Frequently Asked Questions</h2>
                        <p class="lead text-muted">Find answers to common questions about our investment club and platform.
                        </p>
                        <div class="mt-4">
                            <a href="#contact" class="btn btn-success btn-lg">
                                <i class="fas fa-comments me-2"></i>Ask a Question
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="accordion" id="faqAccordion" data-aos="fade-left">
                        @php
                            $faqs = [
                                [
                                    'q' => 'Who can join the club?',
                                    'a' =>
                                        'Any resident of Kampala with a commitment to monthly contributions and ethical investment practices. We welcome individuals who are serious about financial growth and community development.',
                                ],
                                [
                                    'q' => 'How are loans approved?',
                                    'a' =>
                                        'Loan applications are reviewed by the executive committee and approved based on contribution history, purpose, and repayment capacity. Decisions are typically made within 3-5 business days.',
                                ],
                                [
                                    'q' => 'What are the contribution requirements?',
                                    'a' =>
                                        'Members are required to make monthly contributions as agreed upon during registration. Minimum contribution is UGX 50,000 per month. Contributions can be made through mobile money or bank deposit.',
                                ],
                                [
                                    'q' => 'Is my data secure?',
                                    'a' =>
                                        'Yes! We use bank-level security encryption to protect your data. All transactions are logged and monitored. We never share your personal information with third parties without your consent.',
                                ],
                            ];
                        @endphp

                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                        <i class="fas fa-question-circle text-success me-3"></i>
                                        {{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="faq{{ $index }}" class="accordion-collapse collapse"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['a'] }}
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
                            <h2 class="display-5 fw-bold text-dark mb-4">Ready to Transform Your Investment Club?</h2>
                            <p class="lead text-muted mb-5">Join hundreds of successful members who have already
                                transformed their financial journey.</p>
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">

                                <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-5 py-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Existing Member Login
                                </a>
                            </div>
                            <p class="mt-5 mb-0 text-muted">
                                <i class="fas fa-phone me-2"></i>+256 700 123456 |
                                <i class="fas fa-envelope ms-3 me-2"></i>info@capitalclub.org |
                                <i class="fas fa-map-marker-alt ms-3 me-2"></i>Kampala, Uganda
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
