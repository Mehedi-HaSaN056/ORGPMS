<!DOCTYPE html>
<html lang="en" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OrgPMS') — Enterprise Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- ApexCharts -->
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="@yield('body-class', '')">

@auth
<!-- ═══════════════════════════════════════════════════════════ SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <i class="bi bi-buildings-fill"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">OrgPMS</span>
            <span class="brand-tagline">Enterprise Suite</span>
        </div>
        <button class="sidebar-toggle-btn d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Department badge -->
    @if(auth()->user()->department)
    <div class="dept-badge" style="--dept-color: {{ auth()->user()->department->color }}">
        <i class="{{ auth()->user()->department->icon }}"></i>
        <span>{{ auth()->user()->department->name }}</span>
    </div>
    @endif

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        @if(auth()->user()->hasRole(['super_admin','admin']))
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Admin Dashboard</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['management','admin','super_admin']))
        <a href="{{ route('management.dashboard') }}" class="nav-item {{ request()->routeIs('management.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i><span>Management</span>
        </a>
        @endif

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i><span>My Dashboard</span>
        </a>

        <div class="nav-section-label">Planning</div>

        <a href="{{ route('plans.index') }}" class="nav-item {{ request()->routeIs('plans.*') ? 'active' : '' }}">
            <i class="bi bi-kanban"></i><span>Plans</span>
            @php $pendingPlans = \App\Models\Plan::where('approval_status','pending')->count() @endphp
            @if($pendingPlans > 0 && auth()->user()->is_management)
            <span class="nav-badge">{{ $pendingPlans }}</span>
            @endif
        </a>

        <a href="{{ route('kpi.index') }}" class="nav-item {{ request()->routeIs('kpi.*') ? 'active' : '' }}">
            <i class="bi bi-bullseye"></i><span>KPI Tracking</span>
        </a>

        <div class="nav-section-label">People</div>

        <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i><span>Employees</span>
        </a>

        <a href="{{ route('employees.leaderboard') }}" class="nav-item {{ request()->routeIs('employees.leaderboard') ? 'active' : '' }}">
            <i class="bi bi-trophy"></i><span>Leaderboard</span>
        </a>

        <div class="nav-section-label">Communication</div>

        <a href="{{ route('messages.inbox') }}" class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i><span>Messages</span>
            <span class="nav-badge msg-count" style="display:none">0</span>
        </a>

        <div class="nav-section-label">Reports</div>

        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i><span>Reports</span>
        </a>

        <a href="{{ route('dev-requests.index') }}" class="nav-item {{ request()->routeIs('dev-requests.*') ? 'active' : '' }}">
            <i class="bi bi-lightbulb"></i><span>Dev Requests</span>
        </a>

        @if(auth()->user()->hasRole(['admin','super_admin']))
        <div class="nav-section-label">Admin</div>
        <a href="{{ route('admin.login-logs') }}" class="nav-item">
            <i class="bi bi-shield-check"></i><span>Security Logs</span>
        </a>
        <a href="{{ route('admin.departments.index') }}" class="nav-item">
            <i class="bi bi-diagram-3"></i><span>Departments</span>
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-mini">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="user-mini-avatar">
            <div class="user-mini-info">
                <span class="user-mini-name">{{ auth()->user()->name }}</span>
                <span class="user-mini-role">{{ auth()->user()->roles->first()?->name ?? 'employee' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ MAIN -->
<div class="main-wrapper" id="mainWrapper">

    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="navbar-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb mb-0">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="navbar-right">
            <!-- Search -->
            <div class="global-search d-none d-md-flex">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search plans, employees, KPI..." id="globalSearch">
            </div>

            <!-- Notifications -->
            <div class="dropdown">
                <button class="icon-btn position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot" id="notifDot" style="display:none"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-panel" style="width:360px">
                    <div class="notif-header">
                        <span>Notifications</span>
                        <button class="btn btn-sm" id="markAllRead">Mark all read</button>
                    </div>
                    <div id="notifList" class="notif-list">
                        <div class="text-center text-muted p-4"><i class="bi bi-bell-slash fs-3 d-block mb-2"></i>No new notifications</div>
                    </div>
                </div>
            </div>

            <!-- Theme Toggle -->
            <button class="icon-btn" id="themeToggle" title="Toggle Dark/Light Mode">
                <i class="bi bi-moon-stars" id="themeIcon"></i>
            </button>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="user-avatar-btn" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold lh-1">{{ auth()->user()->name }}</div>
                        <small class="text-muted">{{ auth()->user()->department?->name }}</small>
                    </div>
                    <i class="bi bi-chevron-down ms-1 small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('employees.show', auth()->user()) }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('kpi.index') }}"><i class="bi bi-bullseye me-2"></i>My KPIs</a></li>
                    <li><a class="dropdown-item" href="{{ route('work-logs.index') }}"><i class="bi bi-journal-text me-2"></i>Work Logs</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

</div><!-- end main-wrapper -->
@endauth

@guest
    @yield('content')
@endguest

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

@stack('scripts')
</body>
</html>
