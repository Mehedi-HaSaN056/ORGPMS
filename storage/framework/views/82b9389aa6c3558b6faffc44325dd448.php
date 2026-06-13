<?php $__env->startSection('title', 'My Dashboard'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title">
            <?php if($user->department): ?>
            <span style="color:<?php echo e($user->department->color); ?>"><i class="<?php echo e($user->department->icon); ?>"></i></span>
            <?php endif; ?>
            Welcome, <?php echo e($user->name); ?>

        </h1>
        <p class="page-subtitle"><?php echo e($user->department?->name ?? 'No Department'); ?> &middot; <?php echo e(now()->format('l, d M Y')); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('plans.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Plan</a>
        <a href="<?php echo e(route('work-logs.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-journal-text me-1"></i>Work Log</a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['myPendingTasks']); ?></div>
            <div class="stat-label">Pending Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['myCompletedTasks']); ?></div>
            <div class="stat-label">Completed Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-exclamation-triangle"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['myOverdueTasks']); ?></div>
            <div class="stat-label">Overdue Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-bullseye"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['myKpiScore']); ?>%</div>
            <div class="stat-label">KPI Score (This Month)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-graph-up"></i></div>
        <div>
            <div class="stat-value"><?php echo e($data['taskCompletionRate']); ?>%</div>
            <div class="stat-label">Task Completion Rate</div>
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
    <!-- Monthly Progress Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart me-2 text-primary"></i>Monthly Task Progress (<?php echo e(now()->year); ?>)</span>
            </div>
            <div class="card-body">
                <div id="monthlyChart"></div>
            </div>
        </div>
    </div>

    <!-- KPI Gauge -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><span><i class="bi bi-bullseye me-2 text-warning"></i>My KPI Score</span></div>
            <div class="card-body text-center">
                <div id="kpiGauge"></div>
                <p class="text-muted small mt-2"><?php echo e(now()->format('F Y')); ?></p>
                <a href="<?php echo e(route('kpi.index')); ?>" class="btn btn-outline-primary btn-sm mt-2">View All KPIs</a>
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-calendar-event me-2 text-danger"></i>Upcoming Deadlines (7 Days)</span>
                <a href="<?php echo e(route('plans.index')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $data['upcomingDeadlines']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="flex-shrink-0">
                        <span class="priority-badge priority-<?php echo e($task->priority); ?>"><?php echo e($task->priority); ?></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small"><?php echo e($task->title); ?></div>
                        <div class="text-muted" style="font-size:.75rem">
                            <i class="bi bi-calendar me-1"></i>Due: <?php echo e($task->due_date?->format('d M Y')); ?>

                            <?php if($task->plan): ?><span class="ms-2"><i class="bi bi-kanban me-1"></i><?php echo e($task->plan->title); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <span class="status-badge status-<?php echo e($task->status); ?>"><?php echo e(str_replace('_',' ',$task->status)); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-muted p-4">
                    <i class="bi bi-calendar-check fs-2 d-block mb-2"></i>No upcoming deadlines. Great job!
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Plans -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-kanban me-2 text-primary"></i>Recent Department Plans</span>
                <a href="<?php echo e(route('plans.index')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $data['recentPlans']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-start justify-content-between mb-1">
                        <a href="<?php echo e(route('plans.show',$plan)); ?>" class="fw-semibold small text-decoration-none text-primary"><?php echo e($plan->title); ?></a>
                        <span class="status-badge status-<?php echo e($plan->approval_status); ?>"><?php echo e($plan->approval_status); ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-muted" style="font-size:.75rem">
                        <span><i class="bi bi-person me-1"></i><?php echo e($plan->creator?->name); ?></span>
                        <?php if($plan->due_date): ?><span><i class="bi bi-calendar me-1"></i><?php echo e($plan->due_date->format('d M')); ?></span><?php endif; ?>
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height:4px">
                            <div class="progress-bar bg-primary" data-value="<?php echo e($plan->progress); ?>" style="width:0%"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-muted p-4">
                    <i class="bi bi-kanban fs-2 d-block mb-2"></i>No plans yet.
                    <br><a href="<?php echo e(route('plans.create')); ?>" class="btn btn-sm btn-primary mt-2">Create First Plan</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Monthly chart
const monthlyData = <?php echo json_encode($data['monthlyProgress'], 15, 512) ?>;
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const cats   = monthlyData.map(d => months[d.month-1]);
OrgCharts.barChart(document.getElementById('monthlyChart'), [
    { name: 'Total Tasks', data: monthlyData.map(d => d.total) },
    { name: 'Completed',   data: monthlyData.map(d => d.completed) }
], cats);

// KPI Gauge
OrgCharts.kpiGauge(document.getElementById('kpiGauge'), <?php echo e($data['myKpiScore']); ?>);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/dashboard/department.blade.php ENDPATH**/ ?>