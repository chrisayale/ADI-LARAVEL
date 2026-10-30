<nav class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header text-center py-3 px-3 border-bottom border-white-10">
        <div class="d-flex align-items-center justify-content-center mb-1">
            <div class="avatar avatar-lg bg-white text-success d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 40px; height: 40px; font-weight: 600; font-size: 15px;">
                {{ auth()->check() ? (substr(auth()->user()->name, 0, 1) . substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) ?? substr(auth()->user()->name, 1, 1)) : 'GS' }}
            </div>
        </div>
        <h6 class="mb-0 fw-bold text-white" style="font-size: 14px;">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</h6>
        @if(auth()->check() && auth()->user()->membership_number)
            <small class="text-white-50 d-block mt-1" style="font-size: 11px;">{{ auth()->user()->membership_number }}</small>
        @endif
        <small class="text-white-50 d-block mt-1" style="font-size: 11px;">
            @if(auth()->check())
                @if(auth()->user()->hasRole('admin'))
                    System Administrator
                @elseif(auth()->user()->hasRole('treasurer'))
                    Treasurer
                @else
                    Member
                @endif
            @else
                Guest
            @endif
        </small>
    </div>

    <!-- Scrollable Navigation Menu -->
    <div class="sidebar-menu py-2">
        <ul class="nav flex-column">
            <!-- DASHBOARD - Common -->
            <li class="nav-item">
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-pie me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Dashboard</span>
                    </a>
                @elseif(auth()->check() && auth()->user()->hasRole('treasurer'))
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.dashboard') ? 'active' : '' }}"
                       href="{{ route('treasurer.dashboard') }}">
                        <i class="fas fa-chart-pie me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Dashboard</span>
                    </a>
                @elseif(auth()->check())
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.dashboard') ? 'active' : '' }}"
                       href="{{ route('member.dashboard') }}">
                        <i class="fas fa-chart-pie me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Dashboard</span>
                    </a>
                @else
                    <!-- No dashboard for guests -->
                @endif
            </li>

            @if(auth()->check() && auth()->user()->hasRole('admin'))
                <!-- ADMIN MENU -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Core Management</small>
                </li>

                <!-- Users -->
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Users</span>
                        @php $pendingUsers = \App\Models\User::where('status', 'pending')->count(); @endphp
                        @if($pendingUsers > 0)
                            <span class="sidebar-badge">{{ $pendingUsers }}</span>
                        @endif
                    </a>
                </li>

                <!-- Loan Management - Flat structure -->
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.index') ? 'active' : '' }}"
                       href="{{ route('admin.loans.index') }}">
                        <i class="fas fa-hand-holding-usd me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">All Loans</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.applications') ? 'active' : '' }}"
                       href="{{ route('admin.loans.applications') }}">
                        <i class="fas fa-file-alt me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Applications</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.pending') ? 'active' : '' }}"
                       href="{{ route('admin.loans.pending') }}">
                        <i class="fas fa-clock me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Pending</span>
                        @php $pendingLoans = \App\Models\LoanApplication::where('status', 'pending')->count(); @endphp
                        @if($pendingLoans > 0)
                            <span class="sidebar-badge">{{ $pendingLoans }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.under-verification') ? 'active' : '' }}"
                       href="{{ route('admin.loans.under-verification') }}">
                        <i class="fas fa-search me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Under Verification</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.verified') ? 'active' : '' }}"
                       href="{{ route('admin.loans.verified') }}">
                        <i class="fas fa-check-circle me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Verified</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.approved') ? 'active' : '' }}"
                       href="{{ route('admin.loans.approved') }}">
                        <i class="fas fa-thumbs-up me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Approved</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.disbursed') ? 'active' : '' }}"
                       href="{{ route('admin.loans.disbursed') }}">
                        <i class="fas fa-money-bill-wave me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Disbursed</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.completed') ? 'active' : '' }}"
                       href="{{ route('admin.loans.completed') }}">
                        <i class="fas fa-check-double me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Completed</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.loans.defaulters') ? 'active' : '' }}"
                       href="{{ route('admin.loans.defaulters') }}">
                        <i class="fas fa-exclamation-triangle me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Defaulters</span>
                    </a>
                </li>

                <!-- Payments - Flat structure -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Payments</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.payments.collect') ? 'active' : '' }}"
                       href="{{ route('admin.payments.collect') }}">
                        <i class="fas fa-hand-holding-usd me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Collect Payment</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.payments.history') ? 'active' : '' }}"
                       href="{{ route('admin.payments.history') }}">
                        <i class="fas fa-history me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Payment History</span>
                    </a>
                </li>

                <!-- Savings - Flat structure -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Savings</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.savings.index') ? 'active' : '' }}"
                       href="{{ route('admin.savings.index') }}">
                        <i class="fas fa-list me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">All Transactions</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.savings.deposits') ? 'active' : '' }}"
                       href="{{ route('admin.savings.deposits') }}">
                        <i class="fas fa-arrow-down me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Deposits</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.savings.withdrawals') ? 'active' : '' }}"
                       href="{{ route('admin.savings.withdrawals') }}">
                        <i class="fas fa-arrow-up me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Withdrawals</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.savings.create') ? 'active' : '' }}"
                       href="{{ route('admin.savings.create') }}">
                        <i class="fas fa-plus-circle me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Add Transaction</span>
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                <!-- SACCO Requests (Super Admin only) -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">SACCO Requests</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.request-sacco.index') ? 'active' : '' }}"
                       href="{{ route('admin.request-sacco.index') }}">
                        <i class="fas fa-file-alt me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Demandes de SACCO</span>
                        @php $pendingSaccoRequests = \App\Models\RequestSacco::where('status', 'pending')->count(); @endphp
                        @if($pendingSaccoRequests > 0)
                            <span class="sidebar-badge">{{ $pendingSaccoRequests }}</span>
                        @endif
                    </a>
                </li>

                <!-- Gestion des SACCOs (Super Admin only) -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Gestion des SACCOs</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.sacos.index') ? 'active' : '' }}"
                       href="{{ route('admin.sacos.index') }}">
                        <i class="fas fa-university me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">SACCOs</span>
                        @php $totalSacos = \App\Models\Sacco::count(); @endphp
                        @php $activeSacos = \App\Models\Sacco::where('status', 'active')->count(); @endphp
                        <span style="font-size: 11px; opacity: 0.8;">{{ $activeSacos }}/{{ $totalSacos }} actifs</span>
                    </a>
                </li>
                @endif

                <!-- Reports -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Reports & Analytics</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.reports.savings') ? 'active' : '' }}"
                       href="{{ route('admin.reports.savings') }}">
                        <i class="fas fa-chart-line me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Savings Report</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.reports.loans') ? 'active' : '' }}"
                       href="{{ route('admin.reports.loans') }}">
                        <i class="fas fa-chart-bar me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Loans Report</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('admin.reports.financial-summary') ? 'active' : '' }}"
                       href="{{ route('admin.reports.financial-summary') }}">
                        <i class="fas fa-file-invoice-dollar me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Financial Summary</span>
                    </a>
                </li>

            @elseif(auth()->check() && auth()->user()->hasRole('treasurer'))
                <!-- TREASURER MENU - Flat structure -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Loan Operations</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.repayments.collect') ? 'active' : '' }}"
                       href="{{ route('treasurer.repayments.collect') }}">
                        <i class="fas fa-hand-holding-usd me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Collect Payment</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.repayments.index') ? 'active' : '' }}"
                       href="{{ route('treasurer.repayments.index') }}">
                        <i class="fas fa-history me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Payment History</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.loans.assigned') ? 'active' : '' }}"
                       href="{{ route('treasurer.loans.assigned') }}">
                        <i class="fas fa-tasks me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">My Assigned Loans</span>
                        @if(auth()->check())
                            @php
                                $assignedLoans = \App\Models\LoanApplication::where('treasurer_id', auth()->id())
                                    ->whereIn('status', ['disbursed', 'approved'])
                                    ->count();
                            @endphp
                        @else
                            @php
                                $assignedLoans = 0;
                            @endphp
                        @endif
                        @if($assignedLoans > 0)
                            <span class="sidebar-badge">{{ $assignedLoans }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-section mt-2 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Verifications</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.verifications.pending') ? 'active' : '' }}"
                       href="{{ route('treasurer.verifications.pending') }}">
                        <i class="fas fa-clock me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Pending</span>
                        @php $pendingVerifications = \App\Models\LoanApplication::where('treasurer_id', auth()->id())->where('status', 'under_verification')->count(); @endphp
                        @if($pendingVerifications > 0)
                            <span class="sidebar-badge">{{ $pendingVerifications }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.verifications.verified') ? 'active' : '' }}"
                       href="{{ route('treasurer.verifications.verified') }}">
                        <i class="fas fa-check me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Verified</span>
                    </a>
                </li>

                <!-- Savings Section -->
                <li class="nav-section mt-2 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Savings</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.savings.transactions') ? 'active' : '' }}"
                       href="{{ route('treasurer.savings.transactions') }}">
                        <i class="fas fa-list me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">All Transactions</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.savings.deposits') ? 'active' : '' }}"
                       href="{{ route('treasurer.savings.deposits') }}">
                        <i class="fas fa-arrow-down me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Deposits</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.savings.withdrawals') ? 'active' : '' }}"
                       href="{{ route('treasurer.savings.withdrawals') }}">
                        <i class="fas fa-arrow-up me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Withdrawals</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('treasurer.savings.create') ? 'active' : '' }}"
                       href="{{ route('treasurer.savings.create') }}">
                        <i class="fas fa-plus-circle me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Record Transaction</span>
                    </a>
                </li>

            @else
                <!-- MEMBER MENU - Flat structure -->
                <li class="nav-section mt-3 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">My Savings</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.savings.my-savings') ? 'active' : '' }}"
                       href="{{ route('member.savings.my-savings') }}">
                        <i class="fas fa-piggy-bank me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">My Savings</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.savings.transactions') ? 'active' : '' }}"
                       href="{{ route('member.savings.transactions') }}">
                        <i class="fas fa-history me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Transaction History</span>
                    </a>
                </li>

                <li class="nav-section mt-2 mb-1 px-3">
                    <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">My Loans</small>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.loans.my-loans') ? 'active' : '' }}"
                       href="{{ route('member.loans.my-loans') }}">
                        <i class="fas fa-hand-holding-usd me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">My Loans</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.loans.apply') ? 'active' : '' }}"
                       href="{{ route('member.loans.apply') }}">
                        <i class="fas fa-file-invoice me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">Apply for Loan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.loans.applications') ? 'active' : '' }}"
                       href="{{ route('member.loans.applications') }}">
                        <i class="fas fa-clipboard-list me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">My Applications</span>
                    </a>
                </li>
            @endif

            <!-- PROFILE - Common for all -->
            <li class="nav-section mt-3 mb-1 px-3">
                <small class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Account</small>
            </li>

            <li class="nav-item">
                @if(auth()->check() && auth()->user()->hasRole('member'))
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('member.profile.edit') ? 'active' : '' }}"
                       href="{{ route('member.profile.edit') }}">
                        <i class="fas fa-user me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">My Profile</span>
                    </a>
                @else
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                       href="{{ route('profile.show') }}">
                        <i class="fas fa-user me-2" style="width: 20px; font-size: 14px;"></i>
                        <span style="font-size: 13px;">My Profile</span>
                    </a>
                @endif
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer mt-auto p-2 border-top border-white-10">
        <form method="POST" action="{{ route('logout') }}" class="w-100">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center py-2"
                    style="font-size: 13px; border-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-sign-out-alt me-2" style="font-size: 12px;"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>

<style>
    /* Sidebar Variables */
    :root {
        --sidebar-width: 280px;
        --sidebar-bg: linear-gradient(180deg, #146c43 0%, #0d5030 100%);
        --sidebar-border: rgba(255, 255, 255, 0.1);
        --sidebar-text: rgba(255, 255, 255, 0.9);
        --sidebar-hover: rgba(255, 255, 255, 0.12);
        --sidebar-active: rgba(255, 255, 255, 0.18);
        --sidebar-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Sidebar Container */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
        z-index: 1050;
        transform: translateX(-100%);
        transition: var(--sidebar-transition);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
    }

    @media (min-width: 992px) {
        .sidebar {
            transform: translateX(0);
        }
    }

    .sidebar.show {
        transform: translateX(0);
    }

    /* Sidebar Header */
    .sidebar-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--sidebar-border) !important;
        padding: 0.5rem 0.75rem !important;
        flex-shrink: 0;
    }

    .avatar {
        background: rgba(255, 255, 255, 0.9) !important;
        color: #146c43 !important;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    /* Navigation Menu */
    .sidebar-menu {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar-menu::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
    }

    .nav-section {
        opacity: 0.7;
    }

    /* Navigation Links */
    .nav-item {
        margin: 0.125rem 0.5rem;
    }

    .nav-link {
        color: var(--sidebar-text);
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        transition: var(--sidebar-transition);
        display: flex;
        align-items: center;
        text-decoration: none;
        border: 1px solid transparent;
        width: 100%;
    }

    .nav-link:hover {
        background: var(--sidebar-hover);
        color: white;
        border-color: rgba(255, 255, 255, 0.15);
    }

    .nav-link.active {
        background: var(--sidebar-active);
        color: white;
        font-weight: 600;
        border-left: 3px solid #ffd700;
    }

    .nav-link i {
        min-width: 20px;
        opacity: 0.8;
    }

    .nav-link:hover i,
    .nav-link.active i {
        opacity: 1;
    }

    /* Sidebar Badge */
    .sidebar-badge {
        margin-left: auto;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
        font-weight: 600;
        background-color: #ffc107;
        color: #000;
    }

    /* Sidebar Footer */
    .sidebar-footer {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(5px);
        border-top: 1px solid var(--sidebar-border) !important;
        flex-shrink: 0;
        padding: 0.5rem !important;
    }

    .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.25);
        color: rgba(255, 255, 255, 0.8);
    }

    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
        color: white;
    }

    /* Text utilities */
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .border-white-10 {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Mobile */
    @media (max-width: 991.98px) {
        .sidebar {
            width: 280px;
            z-index: 1060;
        }
    }
</style>
