<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Capital Investment Club')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- AOS (Animate on Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Particle.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <style>
        :root {
            --primary-color: #146c43;
            --primary-light: #198754;
            --primary-dark: #0d5030;
            --primary-gradient: linear-gradient(135deg, #146c43 0%, #0d5030 100%);
            --accent-color: #ffc107;
            --accent-light: #ffdb70;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.7;
            overflow-x: hidden;
            background: #ffffff;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            line-height: 1.3;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Custom Selection */
        ::selection {
            background: rgba(20, 108, 67, 0.2);
            color: var(--primary-dark);
        }

        /* Custom Navbar */
        .navbar {
            padding: 1.2rem 0;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-sm);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            padding: 0.8rem 0;
            box-shadow: var(--shadow-md);
            background: rgba(255, 255, 255, 0.98) !important;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--primary-color) !important;
            position: relative;
            display: flex;
            align-items: center;
        }

        .navbar-brand .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover .logo-icon {
            transform: rotate(15deg) scale(1.1);
            box-shadow: 0 5px 15px rgba(20, 108, 67, 0.3);
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            position: relative;
            color: #555 !important;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: 70%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Glowing Button */
        .btn-glow {
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.4s ease;
            border: none;
        }

        .btn-glow::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }

        .btn-glow:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-glow:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        /* Floating Animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        /* Pulse Animation */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(20, 108, 67, 0.4);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(20, 108, 67, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(20, 108, 67, 0);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Wave Background */
        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave-bg svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        /* Gradient Text */
        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card Hover Effect */
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            overflow: hidden;
            position: relative;
        }

        .hover-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s;
        }

        .hover-card:hover::before {
            left: 100%;
        }

        .hover-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-xl) !important;
        }

        /* Feature Icon */
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            background: var(--primary-gradient);
            color: white;
            position: relative;
        }

        .feature-icon::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 25px;
            background: var(--primary-gradient);
            z-index: -1;
            opacity: 0.3;
            animation: pulse 2s infinite;
        }

        /* Particle Background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }

        .loader {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            border: 3px solid;
            border-color: var(--primary-color) var(--primary-color) transparent transparent;
            animation: rotation 1s linear infinite;
        }

        .loader::after,
        .loader::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            border: 3px solid;
            border-color: transparent transparent var(--primary-light) var(--primary-light);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            animation: rotationBack 0.5s linear infinite;
            transform-origin: center center;
        }

        .loader::before {
            width: 20px;
            height: 20px;
            border-color: var(--primary-color) var(--primary-color) transparent transparent;
            animation: rotation 1.5s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes rotationBack {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        /* Typewriter Effect */
        .typewriter {
            overflow: hidden;
            border-right: .15em solid var(--primary-color);
            white-space: nowrap;
            animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
        }

        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 100%
            }
        }

        @keyframes blink-caret {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: var(--primary-color);
            }
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        /* Section Padding */
        section {
            padding: 100px 0;
            position: relative;
        }

        /* Progress bar for navbar */
        .progress-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: transparent;
            z-index: 1;
        }

        .progress-bar {
            height: 100%;
            background: var(--primary-gradient);
            width: 0%;
            transition: width 0.3s;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            section {
                padding: 60px 0;
            }

            .navbar-brand span {
                font-size: 1.2rem;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Shine Effect */
        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        .shine-text {
            background: linear-gradient(90deg, #146c43, #ffc107, #146c43);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }

        /* Parallax Effect */
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Social links */
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-5px);
        }

        /* Footer links */
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    @yield('content')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>

    <script>
        // Global functions
        function animateCounter() {
            $('.counter').each(function() {
                var $this = $(this);
                var countTo = parseFloat($this.attr('data-count'));
                var duration = 2000;
                var increment = countTo / (duration / 60);
                var current = 0;

                var timer = setInterval(function() {
                    if (current >= countTo) {
                        clearInterval(timer);
                        $this.text(countTo);
                    } else {
                        current += increment;
                        $this.text(Math.floor(current));
                    }
                }, 60 / countTo);
            });
        }

        function typeWriter(element, text, speed) {
            let i = 0;

            function type() {
                if (i < text.length) {
                    element.html(text.substring(0, i + 1));
                    i++;
                    setTimeout(type, speed);
                }
            }
            type();
        }

        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        // Main initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });

            // Remove preloader
            window.addEventListener('load', function() {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }

                // Initialize particles
                if (typeof particlesJS !== 'undefined' && document.getElementById('particles-js')) {
                    particlesJS('particles-js', {
                        particles: {
                            number: {
                                value: 80,
                                density: {
                                    enable: true,
                                    value_area: 800
                                }
                            },
                            color: {
                                value: "#146c43"
                            },
                            shape: {
                                type: "circle"
                            },
                            opacity: {
                                value: 0.5,
                                random: true
                            },
                            size: {
                                value: 3,
                                random: true
                            },
                            line_linked: {
                                enable: true,
                                distance: 150,
                                color: "#146c43",
                                opacity: 0.2,
                                width: 1
                            },
                            move: {
                                enable: true,
                                speed: 2,
                                direction: "none",
                                random: true,
                                straight: false,
                                out_mode: "out",
                                bounce: false
                            }
                        },
                        interactivity: {
                            detect_on: "canvas",
                            events: {
                                onhover: {
                                    enable: true,
                                    mode: "repulse"
                                },
                                onclick: {
                                    enable: true,
                                    mode: "push"
                                }
                            }
                        }
                    });
                }
            });
        });

        // jQuery document ready
        $(document).ready(function() {
            // Navbar scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('.navbar').addClass('scrolled');
                } else {
                    $('.navbar').removeClass('scrolled');
                }

                // Back to top button
                if ($(this).scrollTop() > 300) {
                    $('.back-to-top').addClass('show');
                } else {
                    $('.back-to-top').removeClass('show');
                }

                // Update progress bar
                updateProgressBar();
            });

            // Back to top functionality
            $('.back-to-top').click(function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

            // Smooth scrolling for anchor links
            $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
                    location.hostname === this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    if (target.length) {
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top - 80
                        }, 1000, 'swing');
                    }
                }
            });

            // Initialize GSAP animations
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                // Animate elements on scroll
                $('.animate-on-scroll').each(function() {
                    gsap.from(this, {
                        scrollTrigger: {
                            trigger: this,
                            start: 'top 80%',
                            end: 'bottom 20%',
                            toggleActions: 'play none none reverse'
                        },
                        y: 50,
                        opacity: 0,
                        duration: 1,
                        ease: 'power2.out'
                    });
                });

                // Text reveal animation
                $('.reveal-text').each(function() {
                    let text = $(this).text();
                    $(this).html('');
                    for (let i = 0; i < text.length; i++) {
                        $(this).append('<span style="opacity:0">' + text.charAt(i) + '</span>');
                    }

                    $(this).find('span').each(function(index) {
                        gsap.to(this, {
                            scrollTrigger: {
                                trigger: this,
                                start: 'top 80%',
                                toggleActions: 'play none none reverse'
                            },
                            opacity: 1,
                            delay: index * 0.05,
                            duration: 0.5
                        });
                    });
                });
            }

            // Hover card tilt effect
            $('.hover-card').on('mousemove', function(e) {
                const card = $(this);
                const cardWidth = card.width();
                const cardHeight = card.height();
                const centerX = card.offset().left + cardWidth / 2;
                const centerY = card.offset().top + cardHeight / 2;
                const mouseX = e.pageX - centerX;
                const mouseY = e.pageY - centerY;
                const rotateX = (mouseY / cardHeight) * -5;
                const rotateY = (mouseX / cardWidth) * 5;

                card.css({
                    'transform': `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`
                });
            }).on('mouseleave', function() {
                $(this).css({
                    'transform': 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)'
                });
            });

            // Initialize typewriter on hero text
            const heroText = $('.hero-text');
            if (heroText.length > 0 && heroText.text().trim() !== '') {
                const fullText = heroText.text();
                heroText.html('');
                typeWriter(heroText, fullText, 50);
            }

            // Form submission with animation
            $('form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
                submitBtn.prop('disabled', true);

                // Simulate API call
                setTimeout(function() {
                    submitBtn.html('<i class="fas fa-check me-2"></i>Sent Successfully!');
                    submitBtn.removeClass('btn-success').addClass('btn-success');

                    setTimeout(function() {
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                        form[0].reset();
                    }, 2000);
                }, 2000);
            });

            // Add progress bar to navbar if not exists
            if ($('.navbar').length > 0 && !$('.progress-container').length) {
                $('.navbar').append('<div class="progress-container"><div class="progress-bar"></div></div>');
            }
        });

        // Progress bar function
        function updateProgressBar() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            $('.progress-bar').css('width', scrolled + '%');
        }

        // Intersection Observer for counter animation
        document.addEventListener('DOMContentLoaded', function() {
            const counterSection = document.querySelector('.counter-section');
            if (counterSection) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.5
                });

                observer.observe(counterSection);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
