<?php $__env->startSection('title','Admin Dashboard'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Home</a></li>
<li class="breadcrumb-item active">Admin Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="bi bi-speedometer2 text-primary me-2"></i>Admin Dashboard</h1>
        <p class="page-subtitle">Organization-wide overview &middot; <?php echo e(now()->format('l, d M Y')); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-primary"><i class="bi bi-file-earmark-bar-graph me-1"></i>Reports</a>
        <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add Employee</a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['totalUsers']); ?></div>
            <div class="stat-label">Active Employees</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-diagram-3"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['totalDepartments']); ?></div>
            <div class="stat-label">Departments</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-kanban"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['totalPlans']); ?></div>
            <div class="stat-label">Total Plans</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['overdueTasks']); ?></div>
            <div class="stat-label">Overdue Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['pendingApprovals']); ?></div>
            <div class="stat-label">Pending Approvals</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-envelope"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['unreadMessages']); ?></div>
            <div class="stat-label">Unread Messages</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Department Performance -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-line me-2 text-primary"></i>Department Performance Overview</span>
                <a href="<?php echo e(route('reports.department')); ?>" class="btn btn-sm btn-outline-primary">Full Report</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php $__currentLoopData = $data['departmentStats']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-xl-3">
                        <div class="p-3 rounded-3 border">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="rounded-circle" style="width:10px;height:10px;background:<?php echo e($dept['color']); ?>"></div>
                                <span class="fw-semibold small"><?php echo e($dept['name']); ?></span>
                                <span class="ms-auto text-muted small"><?php echo e($dept['userCount']); ?> staff</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between small text-muted">
                                <span>Task Completion</span>
                                <span class="fw-semibold text-dark"><?php echo e($dept['completionRate']); ?>%</span>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar" data-value="<?php echo e($dept['completionRate']); ?>" style="background:<?php echo e($dept['color']); ?>;width:0%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>KPI Score</span>
                                <span class="fw-bold" style="color:<?php echo e($dept['color']); ?>"><?php echo e($dept['kpiScore']); ?>%</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart + Top Performers -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><span><i class="bi bi-graph-up me-2 text-primary"></i>Monthly Task Statistics (<?php echo e(now()->year); ?>)</span></div>
            <div class="card-body"><div id="adminMonthlyChart"></div></div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span><i class="bi bi-trophy me-2 text-warning"></i>Top Performers</span>
                <a href="<?php echo e(route('employees.leaderboard')); ?>" class="btn btn-sm btn-outline-warning">Leaderboard</a>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $data['topPerformers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="fw-bold" style="width:24px;color:<?php echo e(['#f59e0b','#94a3b8','#cd7c2f'][$i] ?? '#64748b'); ?>">
                        #<?php echo e($i+1); ?>

                    </div>
                    <img src="<?php echo e($emp->avatar_url); ?>" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                    <div class="flex-grow-1">
                        <div class="fw-semibold small"><?php echo e($emp->name); ?></div>
                        <div class="text-muted" style="font-size:.72rem"><?php echo e($emp->department?->name); ?></div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary" style="font-size:.85rem"><?php echo e($emp->overall_performance_score); ?>%</div>
                        <div class="text-muted" style="font-size:.68rem">Score</div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Recent Activity + Logins -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-activity me-2 text-success"></i>Recent Activity</span>
                <a href="<?php echo e(route('admin.activity-logs')); ?>" class="btn btn-sm btn-outline-secondary">All Logs</a>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $data['recentActivities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <img src="<?php echo e($log->user?->avatar_url); ?>" class="rounded-circle flex-shrink-0" width="32" height="32" style="object-fit:cover">
                    <div class="flex-grow-1">
                        <div class="small fw-semibold"><?php echo e($log->user?->name ?? 'System'); ?></div>
                        <div class="text-muted" style="font-size:.73rem"><?php echo e($log->description); ?></div>
                    </div>
                    <div class="text-muted" style="font-size:.7rem;white-space:nowrap"><?php echo e($log->created_at->diffForHumans()); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-shield-check me-2 text-info"></i>Recent Logins</span>
                <a href="<?php echo e(route('admin.login-logs')); ?>" class="btn btn-sm btn-outline-secondary">All Logs</a>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $data['recentLogins']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="stat-icon <?php echo e($log->is_successful ? 'success' : 'danger'); ?>" style="width:36px;height:36px;font-size:.9rem">
                        <i class="bi <?php echo e($log->is_successful ? 'bi-check-circle' : 'bi-x-circle'); ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold"><?php echo e($log->user?->name); ?></div>
                        <div class="text-muted" style="font-size:.72rem"><?php echo e($log->ip_address); ?> &middot; <?php echo e($log->browser); ?></div>
                    </div>
                    <div class="text-muted" style="font-size:.7rem"><?php echo e($log->logged_in_at?->diffForHumans()); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const monthly = <?php echo json_encode($data['monthlyTaskStats'], 15, 512) ?>;
const months  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
OrgCharts.lineChart(document.getElementById('adminMonthlyChart'), [
    { name: 'Total',     data: monthly.map(d=>d.total) },
    { name: 'Completed', data: monthly.map(d=>d.completed) }
], monthly.map(d=>months[d.month-1]));
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>