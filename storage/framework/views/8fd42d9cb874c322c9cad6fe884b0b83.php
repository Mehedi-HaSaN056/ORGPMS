<?php $__env->startSection('title','Management Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Management Dashboard</h1><p class="page-subtitle">Organization-wide performance overview</p></div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('management.approvals')); ?>" class="btn btn-warning"><i class="bi bi-check-circle me-1"></i>Pending Approvals <span class="badge bg-white text-warning ms-1"><?php echo e($data['pendingApprovals']->count()); ?></span></a>
        <a href="<?php echo e(route('reports.department')); ?>" class="btn btn-outline-primary">Reports</a>
    </div>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon primary"><i class="bi bi-people"></i></div><div><div class="stat-value"><?php echo e($data['orgStats']['users']); ?></div><div class="stat-label">Total Employees</div></div></div>
    <div class="stat-card"><div class="stat-icon success"><i class="bi bi-check-circle"></i></div><div><div class="stat-value"><?php echo e($data['orgStats']['tasksCompleted']); ?></div><div class="stat-label">Tasks Completed</div></div></div>
    <div class="stat-card"><div class="stat-icon warning"><i class="bi bi-bullseye"></i></div><div><div class="stat-value"><?php echo e($data['orgStats']['avgKpi']); ?>%</div><div class="stat-label">Average KPI Score</div></div></div>
    <div class="stat-card"><div class="stat-icon info"><i class="bi bi-kanban"></i></div><div><div class="stat-value"><?php echo e($data['orgStats']['plans']); ?></div><div class="stat-label">Total Plans</div></div></div>
</div>
<div class="row g-4">
<div class="col-lg-8">
<div class="card"><div class="card-header"><span>Department Performance</span></div><div class="card-body">
<div id="deptChart"></div>
</div></div>
</div>
<div class="col-lg-4">
<div class="card"><div class="card-header"><span><i class="bi bi-clock-history me-2 text-danger"></i>Overdue Items</span><a href="<?php echo e(route('plans.index')); ?>" class="btn btn-sm btn-outline-danger">View All</a></div>
<div class="card-body p-0">
<?php $__empty_1 = true; $__currentLoopData = $data['overdueItems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="p-3 border-bottom">
<div class="d-flex justify-content-between">
<span class="small fw-semibold"><?php echo e($task->title); ?></span>
<span class="status-badge status-delayed">Overdue</span>
</div>
<div class="text-muted small"><?php echo e($task->assignee?->name); ?> &middot; <?php echo e($task->department?->name); ?></div>
<div class="text-danger small"><i class="bi bi-calendar me-1"></i>Was due: <?php echo e($task->due_date?->format('d M Y')); ?></div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center text-muted p-4"><i class="bi bi-check-circle-fill text-success fs-2"></i><p>No overdue items!</p></div>
<?php endif; ?>
</div></div>
</div>
<div class="col-12">
<div class="card"><div class="card-header"><span>Pending Plan Approvals</span></div>
<div class="card-body p-0">
<table class="table mb-0">
<thead><tr><th>Plan</th><th>Department</th><th>Created By</th><th>Due Date</th><th>Actions</th></tr></thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $data['pendingApprovals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td><a href="<?php echo e(route('plans.show',$plan)); ?>" class="text-decoration-none fw-semibold"><?php echo e($plan->title); ?></a></td>
<td><?php echo e($plan->department?->name); ?></td>
<td><?php echo e($plan->creator?->name); ?></td>
<td><?php echo e($plan->due_date?->format('d M Y') ?? '—'); ?></td>
<td>
<form method="POST" action="<?php echo e(route('plans.approve',$plan)); ?>" class="d-inline"><?php echo csrf_field(); ?><button class="btn btn-sm btn-success">Approve</button></form>
<a href="<?php echo e(route('plans.show',$plan)); ?>" class="btn btn-sm btn-outline-primary">Review</a>
</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr><td colspan="5" class="text-center text-muted py-3">No pending approvals.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
const deptData = <?php echo json_encode($data['deptPerformance'], 15, 512) ?>;
OrgCharts.barChart(document.getElementById('deptChart'),[{name:'Task Completion',data:deptData.map(d=>d.completion)},{name:'KPI Score',data:deptData.map(d=>d.kpi)}],deptData.map(d=>d.name));
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/management/dashboard.blade.php ENDPATH**/ ?>