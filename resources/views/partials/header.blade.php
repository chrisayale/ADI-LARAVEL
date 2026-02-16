<header class="header" id="header">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-2 px-sm-3 px-lg-4">
            <!-- Mobile Menu Toggle & Brand Container -->
            <div class="d-flex align-items-center flex-grow-1">
                <!-- Mobile Menu Toggle -->
                <button class="btn btn-link text-white d-lg-none p-1 me-2" type="button" id="sidebarToggle" aria-label="Toggle menu">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <!-- Brand Logo -->
                <a class="navbar-brand d-flex align-items-center me-auto me-md-0" href="{{
                    auth()->user()->hasRole('admin') ? route('admin.dashboard') :
                    (auth()->user()->hasRole('treasurer') ? route('treasurer.dashboard') : route('member.dashboard'))
                }}">
                    <div class="d-flex align-items-center">
                        <div class="brand-icon bg-white rounded-circle p-2 me-2">
                            <i class="fas fa-handshake text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="brand-text d-none d-md-block">
                            <span class="fw-bold fs-6 lh-1">Capital Investment</span>
                            <small class="d-block text-white-50 opacity-75" style="font-size: 0.7rem;">Club</small>
                        </div>
                    </div>
                </a>

                <!-- Mobile Stats -->
                <div class="d-flex d-md-none align-items-center ms-auto me-2">
                    <div class="text-white text-end me-2">
                        <small class="d-block opacity-75" style="font-size: 0.7rem;">Total Sav.</small>
                        <strong class="d-block fw-semibold" style="font-size: 0.85rem;">UGX {{ number_format(auth()->user()->total_savings ?? 0, 0) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Desktop Stats -->
            <div class="d-none d-md-flex align-items-center ms-auto me-3 me-lg-4">
                @if(auth()->user()->hasRole('admin'))
                <div class="text-white text-center me-3 me-xl-4">
                    <small class="d-block opacity-75" style="font-size: 0.75rem;">Total Members</small>
                    <strong class="d-block fs-6 fw-semibold lh-1">{{ \App\Models\User::count() }}</strong>
                </div>
                <div class="vr text-white opacity-25 mx-2 d-none d-xl-block" style="height: 1.5rem;"></div>
                <div class="text-white text-center">
                    <small class="d-block opacity-75" style="font-size: 0.75rem;">Pending Loans</small>
                    <strong class="d-block fs-6 fw-semibold lh-1">{{ \App\Models\LoanApplication::where('status', 'pending')->count() }}</strong>
                </div>
                @elseif(auth()->user()->hasRole('treasurer'))
                <div class="text-white text-center me-3 me-xl-4">
                    <small class="d-block opacity-75" style="font-size: 0.75rem;">Assigned Loans</small>
                    <strong class="d-block fs-6 fw-semibold lh-1">{{ \App\Models\LoanApplication::where('treasurer_id', auth()->id())->whereIn('status', ['pending', 'under_verification'])->count() }}</strong>
                </div>
                <div class="vr text-white opacity-25 mx-2 d-none d-xl-block" style="height: 1.5rem;"></div>
                <div class="text-white text-center">
                    <small class="d-block opacity-75" style="font-size: 0.75rem;">Today's Trans.</small>
                    <strong class="d-block fs-6 fw-semibold lh-1">UGX {{ number_format(\App\Models\SavingsTransaction::whereDate('created_at', today())->sum('amount'), 0) }}</strong>
                </div>
                @endif
            </div>

            <!-- Right Side Menu -->
            <div class="navbar-nav align-items-center flex-row">
                <!-- Notifications Dropdown - Fixed with Bootstrap 5 syntax -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="notificationsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <span class="badge badge-danger badge-counter" id="notificationCount">0</span>
                    </a>
                    <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in"
                         aria-labelledby="notificationsDropdown">
                        <h6 class="dropdown-header bg-primary text-white">Notifications</h6>
                        <div id="notificationList">
                            <!-- Notifications will be loaded here via AJAX -->
                            <div class="text-center p-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">
                            View All Notifications
                        </a>
                    </div>
                </li>

                <!-- User Menu - Fixed with Bootstrap 5 syntax -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center p-1 p-md-2" href="#"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-white text-success d-flex align-items-center justify-content-center rounded-circle me-1 me-md-2"
                                 style="width: 32px; height: 32px; font-weight: 600;">
                                {{ substr(auth()->user()->name, 0, 1) }}{{ substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) ?? substr(auth()->user()->name, 1, 1) }}
                            </div>
                            <div class="d-none d-sm-block text-start">
                                <span class="d-block fw-medium" style="font-size: 0.875rem;">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 250px;">
                        <li>
                            <div class="dropdown-header px-3 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-success text-white d-flex align-items-center justify-content-center rounded-circle me-3"
                                         style="width: 48px; height: 48px; font-size: 1.25rem; font-weight: 600;">
                                        {{ substr(auth()->user()->name, 0, 1) }}{{ substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) ?? substr(auth()->user()->name, 1, 1) }}
                                    </div>
                                    <div style="min-width: 0;">
                                        <strong class="d-block text-truncate">{{ auth()->user()->name }}</strong>
                                        <small class="text-muted d-block text-truncate">{{ auth()->user()->email }}</small>
                                        @if(auth()->user()->membership_number)
                                        <small class="text-success fw-medium">{{ auth()->user()->membership_number }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
    <i class="fas fa-user me-2 text-muted"></i>My Profile
</a>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger w-100 text-start px-3 py-2">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </div>
        </div>
    </nav>
</header>

<!-- Add this JavaScript at the bottom of your layout or in a script section -->
<script>
    // Function to load notifications
    function loadNotifications() {
        fetch('/notifications/recent', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
            updateNotificationList(data.notifications);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            // Fallback to static content if AJAX fails
            document.getElementById('notificationList').innerHTML = `
                <div class="text-center p-3">
                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Unable to load notifications</p>
                </div>
            `;
        });
    }

    function updateNotificationBadge(count) {
        const badge = document.getElementById('notificationCount');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    function updateNotificationList(notifications) {
        const list = document.getElementById('notificationList');
        if (!list) return;

        let html = '';
        if (notifications && notifications.length > 0) {
            notifications.forEach(n => {
                const bgClass = !n.is_read ? 'bg-light' : '';
                html += `
                    <a href="/notifications/${n.id}" class="dropdown-item ${bgClass}">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-${n.color} text-white d-flex align-items-center justify-content-center me-2"
                                 style="width: 32px; height: 32px; flex-shrink: 0;">
                                <i class="fas ${n.icon} fa-sm"></i>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <strong class="d-block text-truncate">${n.title}</strong>
                                <p class="small text-muted mb-1 text-truncate">${n.message.substring(0, 50)}...</p>
                                <small class="text-muted">${n.created_at}</small>
                            </div>
                        </div>
                    </a>
                `;
            });
        } else {
            html = `
                <div class="text-center p-3">
                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No notifications</p>
                </div>
            `;
        }

        list.innerHTML = html;
    }

    // Load notifications when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
    });
</script>

<style>
    /* =========================
       BASE HEADER STYLES
       ========================= */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 90px;
        z-index: 1030;
        background: linear-gradient(135deg, #146c43 0%, #0d5030 100%);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    @media (min-width: 992px) {
        .header {
            left: var(--sidebar-width, 250px);
        }
    }

    .navbar {
        padding: 0;
        height: 90px;
        min-height: 90px;
        background: transparent !important;
    }

    .container-fluid {
        align-items: center;
    }

    /* =========================
       DROPDOWN FIXES - CORRECT POSITIONING
       ========================= */
    .dropdown-menu {
        margin-top: 0.5rem !important;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        animation: slideDown 0.2s ease;
        padding: 0.5rem 0;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Dropdown header */
    .dropdown-header {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        border-radius: 10px 10px 0 0;
        padding: 0.75rem 1rem;
    }

    /* Mobile dropdown positioning */
    @media (max-width: 991.98px) {
        .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            max-width: calc(100vw - 2rem);
            transform: none !important;
        }

        .navbar-nav .dropdown-menu {
            position: absolute !important;
        }
    }

    /* Desktop dropdown positioning */
    @media (min-width: 992px) {
        .dropdown-menu {
            position: absolute !important;
        }

        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }
    }

    /* =========================
       RESPONSIVE BREAKPOINTS
       ========================= */
    /* Extra small devices (phones, less than 576px) */
    @media (max-width: 575.98px) {
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .navbar-brand {
            max-width: 140px;
        }

        .brand-icon {
            padding: 0.5rem !important;
        }

        .brand-icon i {
            font-size: 1.25rem !important;
        }

        .avatar-sm {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.75rem !important;
        }

        .dropdown-menu {
            min-width: 280px !important;
            max-width: calc(100vw - 1.5rem);
            right: 0.75rem !important;
        }
    }

    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .brand-text {
            display: none !important;
        }

        .dropdown-menu {
            min-width: 320px !important;
        }
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .container-fluid {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        .dropdown-menu {
            min-width: 320px !important;
        }
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) {
        .container-fluid {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        .dropdown-menu {
            min-width: 350px !important;
        }
    }

    /* =========================
       COMPONENT STYLES
       ========================= */
    /* Brand Logo */
    .navbar-brand {
        padding: 0;
        min-width: 0;
        flex-shrink: 1;
    }

    /* Notification Bell */
    .badge.badge-danger {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(50%, -50%);
        background-color: #dc3545 !important;
        color: white;
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: translate(50%, -50%) scale(1); }
        50% { transform: translate(50%, -50%) scale(1.1); }
        100% { transform: translate(50%, -50%) scale(1); }
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        border-radius: 0;
        margin: 0;
        transition: all 0.2s ease;
        white-space: normal;
        word-wrap: break-word;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: rgba(20, 108, 67, 0.05);
    }

    .dropdown-item.bg-light {
        background-color: #f8f9fc !important;
    }

    /* Notification dropdown scroll */
    #notificationList {
        max-height: 350px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #dee2e6 #f8f9fa;
    }

    #notificationList::-webkit-scrollbar {
        width: 6px;
    }

    #notificationList::-webkit-scrollbar-track {
        background: #f8f9fa;
    }

    #notificationList::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 3px;
    }

    #notificationList::-webkit-scrollbar-thumb:hover {
        background: #c0c5cc;
    }

    /* Avatar */
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .avatar-md {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }

    /* =========================
       VERY SMALL DEVICES FIX
       ========================= */
    @media (max-width: 399.98px) {
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .navbar-brand {
            max-width: 120px;
        }

        .brand-icon {
            padding: 0.375rem !important;
            margin-right: 0.5rem !important;
        }

        .brand-icon i {
            font-size: 1.1rem !important;
        }

        .fa-bell.fa-fw {
            font-size: 1.1rem !important;
        }

        .avatar-sm {
            width: 26px !important;
            height: 26px !important;
            font-size: 0.7rem !important;
        }

        .dropdown-menu {
            min-width: 260px !important;
            max-width: calc(100vw - 1rem);
            right: 0.5rem !important;
        }
    }

    /* =========================
       PREVENT HEADER EXPANSION
       ========================= */
    .navbar-nav {
        flex-direction: row !important;
        align-items: center;
        margin-left: auto;
    }

    .nav-item {
        position: relative;
        display: flex;
        align-items: center;
        height: 100%;
        margin-left: 0.5rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        height: 100%;
        white-space: nowrap;
        padding: 0.5rem !important;
    }

    .dropdown-menu.show {
        display: block;
    }

    /* Text Colors */
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .border-white-10 {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    .bg-white-10 {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Avatar background colors */
    .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .bg-success.bg-opacity-10 {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }

    .bg-primary.bg-opacity-10 {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }

    /* Badge styles */
    .badge.badge-danger {
        background-color: #dc3545;
        color: white;
    }

    .badge.badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge.badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge.badge-info {
        background-color: #17a2b8;
        color: white;
    }
</style>
