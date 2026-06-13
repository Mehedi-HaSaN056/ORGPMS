<!DOCTYPE html>
<html lang="en" data-theme="<?php echo e(session('theme', 'light')); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'OrgPMS'); ?> — Enterprise Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- ApexCharts -->
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo e(asset('assets/css/app.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="<?php echo $__env->yieldContent('body-class', ''); ?>">

<?php if(auth()->guard()->check()): ?>
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
    <?php if(auth()->user()->department): ?>
    <div class="dept-badge" style="--dept-color: <?php echo e(auth()->user()->department->color); ?>">
        <i class="<?php echo e(auth()->user()->department->icon); ?>"></i>
        <span><?php echo e(auth()->user()->department->name); ?></span>
    </div>
    <?php endif; ?>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        <?php if(auth()->user()->hasRole(['super_admin','admin'])): ?>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i><span>Admin Dashboard</span>
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->hasRole(['management','admin','super_admin'])): ?>
        <a href="<?php echo e(route('management.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('management.*') ? 'active' : ''); ?>">
            <i class="bi bi-graph-up-arrow"></i><span>Management</span>
        </a>
        <?php endif; ?>

        <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-house-door"></i><span>My Dashboard</span>
        </a>

        <div class="nav-section-label">Planning</div>

        <a href="<?php echo e(route('plans.index')); ?>" class="nav-item <?php echo e(request()->routeIs('plans.*') ? 'active' : ''); ?>">
            <i class="bi bi-kanban"></i><span>Plans</span>
            <?php $pendingPlans = \App\Models\Plan::where('approval_status','pending')->count() ?>
            <?php if($pendingPlans > 0 && auth()->user()->is_management): ?>
            <span class="nav-badge"><?php echo e($pendingPlans); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('kpi.index')); ?>" class="nav-item <?php echo e(request()->routeIs('kpi.*') ? 'active' : ''); ?>">
            <i class="bi bi-bullseye"></i><span>KPI Tracking</span>
        </a>

        <div class="nav-section-label">People</div>

        <a href="<?php echo e(route('employees.index')); ?>" class="nav-item <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>">
            <i class="bi bi-people"></i><span>Employees</span>
        </a>

        <a href="<?php echo e(route('employees.leaderboard')); ?>" class="nav-item <?php echo e(request()->routeIs('employees.leaderboard') ? 'active' : ''); ?>">
            <i class="bi bi-trophy"></i><span>Leaderboard</span>
        </a>

        <div class="nav-section-label">Communication</div>

        <a href="<?php echo e(route('messages.inbox')); ?>" class="nav-item <?php echo e(request()->routeIs('messages.*') ? 'active' : ''); ?>">
            <i class="bi bi-envelope"></i><span>Messages</span>
            <span class="nav-badge msg-count" style="display:none">0</span>
        </a>

        <div class="nav-section-label">Reports</div>

        <a href="<?php echo e(route('reports.index')); ?>" class="nav-item <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
            <i class="bi bi-file-earmark-bar-graph"></i><span>Reports</span>
        </a>

        <a href="<?php echo e(route('dev-requests.index')); ?>" class="nav-item <?php echo e(request()->routeIs('dev-requests.*') ? 'active' : ''); ?>">
            <i class="bi bi-lightbulb"></i><span>Dev Requests</span>
        </a>

        <?php if(auth()->user()->hasRole(['admin','super_admin'])): ?>
        <div class="nav-section-label">Admin</div>
        <a href="<?php echo e(route('admin.login-logs')); ?>" class="nav-item">
            <i class="bi bi-shield-check"></i><span>Security Logs</span>
        </a>
        <a href="<?php echo e(route('admin.departments.index')); ?>" class="nav-item">
            <i class="bi bi-diagram-3"></i><span>Departments</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-mini">
            <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="Avatar" class="user-mini-avatar">
            <div class="user-mini-info">
                <span class="user-mini-name"><?php echo e(auth()->user()->name); ?></span>
                <span class="user-mini-role"><?php echo e(auth()->user()->roles->first()?->name ?? 'employee'); ?></span>
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
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
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
                    <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="Avatar">
                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold lh-1"><?php echo e(auth()->user()->name); ?></div>
                        <small class="text-muted"><?php echo e(auth()->user()->department?->name); ?></small>
                    </div>
                    <i class="bi bi-chevron-down ms-1 small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo e(route('employees.show', auth()->user())); ?>"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('kpi.index')); ?>"><i class="bi bi-bullseye me-2"></i>My KPIs</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('work-logs.index')); ?>"><i class="bi bi-journal-text me-2"></i>Work Logs</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

</div><!-- end main-wrapper -->
<?php endif; ?>

<?php if(auth()->guard()->guest()): ?>
    <?php echo $__env->yieldContent('content'); ?>
<?php endif; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>