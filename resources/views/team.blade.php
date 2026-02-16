@extends('layouts.guest')

@section('title', 'Our Team - Capital Investment Club')

@section('content')
    <!-- Particle Background -->
    <div id="particles-js"></div>

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
        style="padding-top: 150px; padding-bottom: 80px; background: linear-gradient(135deg, rgba(20,108,67,0.95) 0%, rgba(13,80,48,0.95) 100%), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover fixed;">
        <div class="container text-center">
            <h1 class="display-3 fw-bold text-white mb-4" data-aos="fade-up">Our Leadership Team</h1>
            <p class="lead text-light opacity-75 col-lg-8 mx-auto" data-aos="fade-up" data-aos-delay="100">
                Meet the dedicated Ugandan professionals guiding our investment club to greater heights
            </p>
        </div>
    </section>

    <!-- Executive Committee -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-crown me-2"></i>Executive Committee
                </span>
                <h2 class="display-5 fw-bold mb-3">Club Leadership</h2>
                <p class="lead text-muted">Experienced Ugandan professionals committed to your financial success</p>
            </div>

            <div class="row g-4">
                @php
                    $executiveTeam = [
                        [
                            'name' => 'Irumira Mukisa Divine',
                            'role' => 'Chairperson',
                            'desc' => 'Over 15 years in investment banking and financial advisory. Leads the club with vision and integrity.',
                            'image' => 'https://images.unsplash.com/photo-1506277886164-e25aa3f4ef7f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'IMD',
                            'email' => 'divine.irumira@capitalclub.org',
                            'phone' => '+256 700 123001',
                            'linkedin' => '#',
                            'responsibilities' => ['Strategic Planning', 'Club Governance', 'Member Relations']
                        ],
                        [
                            'name' => 'Muyama Betha',
                            'role' => 'Vice Chairperson',
                            'desc' => 'Experienced business consultant specializing in SME growth and investment strategies.',
                            'image' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'MB',
                            'email' => 'betha.muyama@capitalclub.org',
                            'phone' => '+256 700 123002',
                            'linkedin' => '#',
                            'responsibilities' => ['Member Recruitment', 'Partnership Development', 'Events']
                        ],
                        [
                            'name' => 'Lutaaya Davis Crescent',
                            'role' => 'Secretary',
                            'desc' => 'Professional administrator with expertise in organizational management and record keeping.',
                            'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'LDC',
                            'email' => 'davis.lutaaya@capitalclub.org',
                            'phone' => '+256 700 123003',
                            'linkedin' => '#',
                            'responsibilities' => ['Meeting Coordination', 'Record Management', 'Communications']
                        ],
                        [
                            'name' => 'Aijuka Joshua',
                            'role' => 'Treasurer',
                            'desc' => 'Certified accountant with expertise in financial management and investment analysis.',
                            'image' => 'https://images.unsplash.com/photo-1504257432389-52343af06ae4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'AJ',
                            'email' => 'joshua.aijuka@capitalclub.org',
                            'phone' => '+256 700 123004',
                            'linkedin' => '#',
                            'responsibilities' => ['Financial Oversight', 'Contribution Management', 'Loan Processing']
                        ]
                    ];
                @endphp

                @foreach ($executiveTeam as $index => $member)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100">
                            <div class="card-body p-4 text-center">
                                <!-- Profile Image with Badge -->
                                <div class="position-relative mb-4" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
                                        class="rounded-circle w-100 h-100 object-fit-cover shadow-sm border border-4 border-success">
                                    @if($index == 0)
                                    <div class="position-absolute bottom-0 end-0 bg-warning text-white rounded-circle p-2 border border-3 border-white">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Name and Role -->
                                <h4 class="mb-1">{{ $member['name'] }}</h4>
                                <div class="badge bg-success text-white px-3 py-1 mb-3">
                                    {{ $member['role'] }}
                                </div>

                                <!-- Description -->
                                <p class="text-muted small mb-4">{{ $member['desc'] }}</p>

                                <!-- Responsibilities -->
                                <div class="mb-4">
                                    @foreach($member['responsibilities'] as $resp)
                                        <span class="badge bg-light text-dark me-1 mb-1">{{ $resp }}</span>
                                    @endforeach
                                </div>

                                <!-- Contact -->
                                <div class="d-flex justify-content-center mb-3">
                                    <a href="mailto:{{ $member['email'] }}" class="btn btn-outline-success btn-sm rounded-circle me-2" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="tel:{{ $member['phone'] }}" class="btn btn-outline-success btn-sm rounded-circle me-2" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    <a href="{{ $member['linkedin'] }}" class="btn btn-outline-success btn-sm rounded-circle" title="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Committee Members - Group 16 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-users me-2"></i>Committee Members - Group 16
                </span>
                <h2 class="display-5 fw-bold mb-3">Supporting Our Mission</h2>
                <p class="lead text-muted">Dedicated members who help run our club operations</p>
            </div>

            <div class="row g-4">
                @php
                    $group16 = [
                        [
                            'name' => 'Namapala Victoria',
                            'role' => 'Investment Committee Lead',
                            'desc' => 'Financial analyst with expertise in portfolio management and market research',
                            'image' => 'https://images.unsplash.com/photo-1551836022-4c4d79e9c8e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'NV'
                        ],
                        [
                            'name' => 'Mutyaba Edwin Muwanguzi',
                            'role' => 'Loan Committee Lead',
                            'desc' => 'Banking professional with 12+ years experience in microfinance',
                            'image' => 'https://images.unsplash.com/photo-1531427188911-c95d7f9d9f9b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'MEM'
                        ],
                        [
                            'name' => 'Nansamba Angel',
                            'role' => 'Welfare Committee Lead',
                            'desc' => 'Community organizer and member support specialist',
                            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'NA'
                        ],
                        [
                            'name' => 'Kakooza Peter',
                            'role' => 'Audit Committee Lead',
                            'desc' => 'Certified public accountant with KPMG experience',
                            'image' => 'https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'KP'
                        ],
                        [
                            'name' => 'Nyangoma Ereth',
                            'role' => 'Membership Committee Lead',
                            'desc' => 'HR professional managing member relations and recruitment',
                            'image' => 'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'NE'
                        ],
                        [
                            'name' => 'Kalyanjo Ssekidde Shafik',
                            'role' => 'IT Committee Lead',
                            'desc' => 'Software engineer managing digital platforms and security',
                            'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'KSS'
                        ]
                    ];
                @endphp

                @foreach ($group16 as $index => $member)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
                                        class="rounded-circle me-3" width="70" height="70" style="object-fit: cover; border: 2px solid #198754;">
                                    <div>
                                        <h5 class="mb-1">{{ $member['name'] }}</h5>
                                        <div class="badge bg-success bg-opacity-10 text-success mb-2">
                                            {{ $member['role'] }}
                                        </div>
                                        <p class="text-muted small mb-0">{{ $member['desc'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Committee Members - Group 18 -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-users me-2"></i>Committee Members - Group 18
                </span>
                <h2 class="display-5 fw-bold mb-3">Driving Innovation Forward</h2>
                <p class="lead text-muted">Young professionals bringing fresh perspectives to our club</p>
            </div>

            <div class="row g-4">
                @php
                    $group18 = [
                        [
                            'name' => 'Samuel Kato',
                            'role' => 'Project Coordinator',
                            'desc' => 'Project manager specializing in community development initiatives',
                            'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'SK'
                        ],
                        [
                            'name' => 'Jalali Lubega',
                            'role' => 'Research Analyst',
                            'desc' => 'Market researcher with expertise in investment trends',
                            'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'JL'
                        ],
                        [
                            'name' => 'Ronald Mwekambe',
                            'role' => 'Communications Officer',
                            'desc' => 'Public relations specialist managing member communications',
                            'image' => 'https://images.unsplash.com/photo-1531427188911-c95d7f9d9f9b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'RM'
                        ],
                        [
                            'name' => 'Innocent Zziwa',
                            'role' => 'Compliance Officer',
                            'desc' => 'Legal professional ensuring regulatory compliance',
                            'image' => 'https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'IZ'
                        ],
                        [
                            'name' => 'James Brian Birungi',
                            'role' => 'Digital Innovation Lead',
                            'desc' => 'Tech entrepreneur driving digital transformation',
                            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'initials' => 'JBB'
                        ]
                    ];
                @endphp

                @foreach ($group18 as $index => $member)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
                                        class="rounded-circle me-3" width="70" height="70" style="object-fit: cover; border: 2px solid #198754;">
                                    <div>
                                        <h5 class="mb-1">{{ $member['name'] }}</h5>
                                        <div class="badge bg-success bg-opacity-10 text-success mb-2">
                                            {{ $member['role'] }}
                                        </div>
                                        <p class="text-muted small mb-0">{{ $member['desc'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Advisory Board -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-star me-2"></i>Advisory Board
                </span>
                <h2 class="display-5 fw-bold mb-3">Our Trusted Ugandan Advisors</h2>
                <p class="lead text-muted">Industry experts providing strategic guidance</p>
            </div>

            <div class="row g-4">
                @php
                    $advisors = [
                        [
                            'name' => 'Prof. Wasswa William',
                            'role' => 'Financial Advisor',
                            'desc' => 'Professor of Finance, Makerere University Business School',
                            'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
                        ],
                        [
                            'name' => 'Ms. Florence Nakyeyune',
                            'role' => 'Legal Advisor',
                            'desc' => 'Senior Partner at Nakyeyune & Associates Advocates',
                            'image' => 'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
                        ],
                        [
                            'name' => 'Mr. Charles Mwanguhya',
                            'role' => 'Investment Advisor',
                            'desc' => 'Former CEO, Uganda Development Bank',
                            'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
                        ]
                    ];
                @endphp

                @foreach ($advisors as $index => $advisor)
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="hover-card card shadow-sm border-0 h-100">
                            <div class="card-body p-4 text-center">
                                <img src="{{ $advisor['image'] }}" alt="{{ $advisor['name'] }}"
                                    class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover; border: 3px solid #198754;">
                                <h4 class="mb-1">{{ $advisor['name'] }}</h4>
                                <div class="badge bg-success text-white px-3 py-1 mb-3">
                                    {{ $advisor['role'] }}
                                </div>
                                <p class="text-muted">{{ $advisor['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Join Our Team Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">Want to Join Our Team?</h2>
                    <p class="lead text-muted mb-5">We're always looking for dedicated Ugandans to join our committees and help shape the future of our investment club.</p>
                    <a href="#contact" class="btn btn-success btn-lg px-5 py-3">
                        <i class="fas fa-hand-paper me-2"></i>Express Interest
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer py-5" style="background: #0d5030;">
        <!-- Footer content remains the same -->
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
                    <p class="text-light opacity-75 mb-4">Empowering Ugandans through transparent digital finance solutions
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
