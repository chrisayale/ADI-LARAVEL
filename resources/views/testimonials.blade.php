@extends('layouts.guest')

@section('title', 'Testimonials - Capital Investment Club')

@section('content')
    <!-- Particle Background -->
    <div id="particles-js"></div>

    <!-- Navigation (same as services page) -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <!-- ... copy navigation from services page ... -->
    </nav>

    <!-- Page Header -->
    <section class="page-header"
        style="padding-top: 150px; padding-bottom: 80px; background: linear-gradient(135deg, rgba(20,108,67,0.95) 0%, rgba(13,80,48,0.95) 100%), url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover fixed;">
        <div class="container text-center">
            <h1 class="display-3 fw-bold text-white mb-4" data-aos="fade-up">Member Testimonials</h1>
            <p class="lead text-light opacity-75 col-lg-8 mx-auto" data-aos="fade-up" data-aos-delay="100">
                Real stories from our valued members about their experience with Capital Investment Club
            </p>
        </div>
    </section>

    <!-- Featured Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @php
                    $featuredTestimonials = [
                        [
                            'name' => 'Dr. Sarah Nabatanzi',
                            'role' => 'Medical Doctor & Member since 2022',
                            'text' => 'Being part of Capital Investment Club has transformed my approach to savings. The transparency and professionalism are unmatched. I\'ve been able to access two loans for my clinic expansion, and the process was seamless.',
                            'rating' => 5,
                            'image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'featured' => true
                        ],
                        [
                            'name' => 'Joseph Mutebi',
                            'role' => 'Business Owner & Member since 2021',
                            'text' => 'The platform\'s loan management system helped me grow my retail business. I started with small contributions and now manage a portfolio of over CDF 50 million. The support team is always responsive.',
                            'rating' => 5,
                            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'featured' => true
                        ],
                        [
                            'name' => 'Grace Akello',
                            'role' => 'Teacher & Member since 2023',
                            'text' => 'As a first-time investor, I was nervous about joining an investment club. But the educational resources and mentor support made all the difference. My savings have grown 30% in just one year!',
                            'rating' => 5,
                            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                            'featured' => true
                        ]
                    ];
                @endphp

                @foreach ($featuredTestimonials as $testimonial)
                    <div class="col-lg-4" data-aos="fade-up">
                        <div class="hover-card card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}"
                                        class="rounded-circle me-3" width="70" height="70" style="object-fit: cover;">
                                    <div>
                                        <h5 class="mb-1">{{ $testimonial['name'] }}</h5>
                                        <small class="text-muted">{{ $testimonial['role'] }}</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    @for ($i = 0; $i < $testimonial['rating']; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                </div>
                                <p class="text-muted fst-italic mb-0">"{{ $testimonial['text'] }}"</p>
                                @if($testimonial['featured'])
                                    <div class="mt-3">
                                        <span class="badge bg-success">Featured Story</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Grid -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-comments me-2"></i>More Stories
                </span>
                <h2 class="display-5 fw-bold mb-3">What Our Community Says</h2>
                <p class="lead text-muted">Join hundreds of satisfied members who trust our platform</p>
            </div>

            <div class="row g-4">
                @php
                    $moreTestimonials = [
                        [
                            'name' => 'Robert Ssemwanga',
                            'role' => 'Club Treasurer',
                            'text' => 'The reporting features have simplified my work tremendously. I can generate monthly statements in minutes instead of hours.',
                            'rating' => 5,
                            'date' => 'March 2025'
                        ],
                        [
                            'name' => 'Jennifer Namutebi',
                            'role' => 'Entrepreneur',
                            'text' => 'Best decision I made for my financial future. The community support and financial literacy resources are invaluable.',
                            'rating' => 5,
                            'date' => 'February 2025'
                        ],
                        [
                            'name' => 'Peter Kaggwa',
                            'role' => 'Civil Servant',
                            'text' => 'I love the transparency. Every transaction is recorded and visible. No more guessing where my money goes.',
                            'rating' => 4,
                            'date' => 'January 2025'
                        ],
                        [
                            'name' => 'Mary Nakato',
                            'role' => 'Small Business Owner',
                            'text' => 'The loan application process was so smooth. I received funds within 3 days to stock my shop before the festive season.',
                            'rating' => 5,
                            'date' => 'December 2024'
                        ],
                        [
                            'name' => 'Charles Lwanga',
                            'role' => 'IT Professional',
                            'text' => 'The mobile app is fantastic. I can track my contributions and loan repayments on the go.',
                            'rating' => 5,
                            'date' => 'November 2024'
                        ],
                        [
                            'name' => 'Esther Mbabazi',
                            'role' => 'Nurse',
                            'text' => 'Finally found an investment club that I can trust. The monthly meetings keep me accountable.',
                            'rating' => 4,
                            'date' => 'October 2024'
                        ],
                        [
                            'name' => 'Fred Mukasa',
                            'role' => 'Engineer',
                            'text' => 'The returns on my investment have exceeded expectations. Highly recommend to serious investors.',
                            'rating' => 5,
                            'date' => 'September 2024'
                        ],
                        [
                            'name' => 'Alice Nabwire',
                            'role' => 'Accountant',
                            'text' => 'As an accountant, I appreciate the detailed financial records and audit trail. Very professional.',
                            'rating' => 5,
                            'date' => 'August 2024'
                        ],
                        [
                            'name' => 'James Ochieng',
                            'role' => 'Sales Manager',
                            'text' => 'The flexibility to choose my contribution amount works perfectly with my commission-based income.',
                            'rating' => 4,
                            'date' => 'July 2024'
                        ]
                    ];
                @endphp

                @foreach ($moreTestimonials as $index => $testimonial)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 50 }}">
                        <div class="hover-card card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="star-rating">
                                        @for ($i = 0; $i < $testimonial['rating']; $i++)
                                            <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($i = $testimonial['rating']; $i < 5; $i++)
                                            <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ $testimonial['date'] }}</small>
                                </div>
                                <p class="text-muted fst-italic mb-4">"{{ $testimonial['text'] }}"</p>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-success fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $testimonial['name'] }}</h6>
                                        <small class="text-muted">{{ $testimonial['role'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Video Testimonials Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill mb-3">
                    <i class="fas fa-video me-2"></i>Video Stories
                </span>
                <h2 class="display-5 fw-bold mb-3">Hear From Our Members</h2>
                <p class="lead text-muted">Watch real members share their success stories</p>
            </div>

            <div class="row g-4">
                @php
                    $videos = [
                        ['title' => 'How I Funded My Business', 'member' => 'John Musoke', 'duration' => '3:45'],
                        ['title' => 'My Journey to Financial Freedom', 'member' => 'Sarah Kirabo', 'duration' => '4:20'],
                        ['title' => 'Why I Chose Capital Investment', 'member' => 'David Okello', 'duration' => '2:55']
                    ];
                @endphp

                @foreach ($videos as $index => $video)
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="hover-card card shadow-sm border-0">
                            <div class="position-relative">
                                <div class="video-thumbnail bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <i class="fas fa-play-circle fa-4x text-success opacity-50"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 bg-dark text-white px-2 py-1 m-2 rounded small">
                                    {{ $video['duration'] }}
                                </span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="mb-1">{{ $video['title'] }}</h5>
                                <p class="text-muted small mb-0">{{ $video['member'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Submit Testimonial Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-pen-fancy fa-3x text-success mb-3"></i>
                                <h3 class="h2 mb-3">Share Your Experience</h3>
                                <p class="text-muted">We'd love to hear about your journey with Capital Investment Club</p>
                            </div>

                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" placeholder="Your Email" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="Your Role (e.g., Business Owner)">
                                    </div>
                                    <div class="col-12">
                                        <select class="form-select">
                                            <option selected>Select Rating</option>
                                            <option>5 Stars - Excellent</option>
                                            <option>4 Stars - Very Good</option>
                                            <option>3 Stars - Good</option>
                                            <option>2 Stars - Fair</option>
                                            <option>1 Star - Needs Improvement</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control" rows="5" placeholder="Your Testimonial" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="agreeTerms">
                                            <label class="form-check-label" for="agreeTerms">
                                                I agree to share my testimonial publicly
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                            Submit Testimonial <i class="fas fa-paper-plane ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
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
        .video-thumbnail {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .video-thumbnail:hover {
            opacity: 0.9;
        }
        .video-thumbnail:hover i {
            transform: scale(1.1);
            opacity: 1 !important;
        }
    </style>
@endpush
