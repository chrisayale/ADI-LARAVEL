<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Capital Investment Club')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #146c43;
            --primary-dark: #0d4529;
            --primary-light: #198754;
            --secondary-color: #6c757d;
            --accent-color: #ffc107;
            --light-bg: #f8f9fa;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            --sidebar-width: 280px;
            --header-height: 60px;
            --border-radius: 10px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ========== LAYOUT STRUCTURE ========== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 992px) {
            .main-wrapper {
                margin-left: 0;
            }
        }

        .main-content {
            min-height: calc(100vh - var(--header-height) - 60px);
            margin-top: var(--header-height);
            /* padding: 2.6rem; */
            padding: 2.6rem 0.1rem
        }

        /* ========== OVERLAY ========== */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        .overlay.active {
            display: block;
        }

        /* ========== CARDS ========== */
        .card-custom {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .card-header-custom {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.25rem 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            font-weight: 600;
            color: var(--primary-color);
        }

        .card-body-custom {
            padding: 1.5rem;
        }

        /* ========== BUTTONS ========== */
        .btn-success-custom {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-success-custom:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(20, 108, 67, 0.2);
        }

        .btn-outline-success-custom {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-success-custom:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* ========== STATUS BADGES ========== */
        .status-badge {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.completed {
            background: rgba(25, 135, 84, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(25, 135, 84, 0.2);
        }

        .status-badge.pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--accent-color);
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .status-badge.rejected {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        /* ========== SIDEBAR COUNTER BADGES ========== */
    /* ========== PERFECTLY CIRCULAR SIDEBAR BADGES ========== */
        .sidebar-badge {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 22px !important;
            height: 22px !important;
            padding: 0 !important;
            border-radius: 50% !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            line-height: 22px !important;
            text-align: center !important;
            transition: all 0.2s ease;
            margin-left: auto;
            margin-right: 4px;
            box-sizing: border-box;
            flex-shrink: 0;
        }

        .sidebar-badge.bg-warning {
            background: #ffc107 !important;
            color: #000 !important;
            border: none !important;
        }

        .sidebar-badge.bg-danger {
            background: #dc3545 !important;
            color: #fff !important;
            border: none !important;
        }

        .sidebar-badge.bg-success {
            background: #198754 !important;
            color: #fff !important;
            border: none !important;
        }

        .sidebar-badge.bg-primary {
            background: #0d6efd !important;
            color: #fff !important;
            border: none !important;
        }

/* Ensure the badge stays circular even with longer numbers */
.sidebar-badge:empty {
    display: none !important;
}

/* For double-digit numbers */
.sidebar-badge[style*="width"] {
    width: auto !important;
    min-width: 22px !important;
    padding: 0 4px !important;
}


        /* ========== AVATARS ========== */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
        }

        .avatar-xl {
            width: 80px;
            height: 80px;
            font-size: 1.5rem;
        }

        .avatar-primary {
            background: var(--primary-color);
        }

        /* ========== PAGE TITLES ========== */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: var(--secondary-color);
            margin-bottom: 0;
        }

        /* ========== TABLES ========== */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
        }

        .table-custom thead {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        }

        .table-custom thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-custom tbody tr {
            transition: background-color 0.2s ease;
        }

        .table-custom tbody tr:hover {
            background-color: rgba(20, 108, 67, 0.05);
        }

        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem !important;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .card-body-custom {
                padding: 1rem;
            }
        }

    </style>

    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Header -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Scripts -->
<!-- jQuery MUST come first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js (for charts) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing...');

        // Initialize Bootstrap components
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Get sidebar elements
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const body = document.body;

        if (!sidebarToggle || !sidebar) {
            console.warn('Sidebar elements not found');
            return;
        }

        // Function to open sidebar
        function openSidebar() {
            sidebar.classList.add('show');
            if (overlay) overlay.classList.add('active');
            body.style.overflow = 'hidden';
            console.log('Sidebar opened');
        }

        // Function to close sidebar
        function closeSidebar() {
            sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('active');
            body.style.overflow = '';
            console.log('Sidebar closed');
        }

        // Toggle sidebar function
        function toggleSidebar() {
            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }

        // Sidebar toggle click event
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSidebar();
        });

        // Close sidebar when clicking overlay
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                e.preventDefault();
                closeSidebar();
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isMobile = window.innerWidth < 992;
            if (isMobile && sidebar.classList.contains('show')) {
                // Check if click is outside sidebar and not on toggle button
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    closeSidebar();
                }
            }
        });

        // Handle escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && sidebar.classList.contains('show')) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                if (sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            }
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Fix for modals not closing
        document.addEventListener('hidden.bs.modal', function(event) {
            // Remove any remaining backdrop
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });

            // Remove modal-open class
            body.classList.remove('modal-open');
            body.style.overflow = '';
            body.style.paddingRight = '';
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    const modalInstance = bootstrap.Modal.getInstance(activeModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }
        });

        // Log initial state
        console.log('Sidebar initialized', {
            hasToggle: !!sidebarToggle,
            hasSidebar: !!sidebar,
            hasOverlay: !!overlay,
            isMobile: window.innerWidth < 992
        });
    });

    // Expose closeSidebar function globally (for external calls)
    window.closeSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const body = document.body;

        if (sidebar) sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('active');
        body.style.overflow = '';
    };

    // Debug function to check modal state
    window.debugModals = function() {
        const modals = document.querySelectorAll('.modal');
        console.log('Found ' + modals.length + ' modals');
        modals.forEach((modal, index) => {
            console.log('Modal ' + (index + 1) + ':', {
                id: modal.id,
                isShown: modal.classList.contains('show'),
                display: modal.style.display,
                backdrop: document.querySelector('.modal-backdrop')
            });
        });
    };

    // Add to your main layout or a separate JS file
function loadNotifications() {
    $.ajax({
        url: '/notifications/recent',
        type: 'GET',
        success: function(response) {
            updateNotificationBadge(response.unread_count);
            updateNotificationList(response.notifications);
        }
    });
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    if (badge) {
        badge.textContent = count;
        if (count > 0) {
            badge.style.display = 'inline';
        } else {
            badge.style.display = 'none';
        }
    }
}

function updateNotificationList(notifications) {
    const list = document.getElementById('notificationList');
    if (!list) return;

    let html = '';
    if (notifications.length > 0) {
        notifications.forEach(n => {
            html += `
                <a href="/notifications/${n.id}" class="dropdown-item ${!n.is_read ? 'bg-light' : ''}">
                    <div class="media">
                        <div class="rounded-circle bg-${n.color} text-white d-flex align-items-center justify-content-center mr-2"
                             style="width: 30px; height: 30px;">
                            <i class="fas ${n.icon} fa-sm"></i>
                        </div>
                        <div class="media-body">
                            <strong>${n.title}</strong>
                            <p class="small text-muted mb-0">${n.message.substring(0, 50)}...</p>
                            <small class="text-muted">${n.created_at}</small>
                        </div>
                    </div>
                </a>
            `;
        });
    } else {
        html = '<div class="text-center p-3"><i class="fas fa-bell-slash fa-2x text-muted mb-2"></i><p class="text-muted mb-0">No notifications</p></div>';
    }

    list.innerHTML = html;
}

// Load notifications every 30 seconds
$(document).ready(function() {
    loadNotifications();
    setInterval(loadNotifications, 30000);
});
</script>

<!-- Page Specific Scripts -->
@stack('scripts')
</body>
</html>
