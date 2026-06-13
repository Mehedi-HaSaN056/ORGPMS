<?php $__env->startSection('title','KPI Tracking'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-bullseye text-warning me-2"></i>KPI Tracking</h1></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kpi')): ?><a href="<?php echo e(route('kpi.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add KPI</a><?php endif; ?>
</div>
<div class="card mb-4"><div class="card-body">
<form method="GET" class="row g-2">
    <div class="col-md-2"><input type="number" name="year" class="form-control" placeholder="Year" value="<?php echo e(request('year', now()->year)); ?>"></div>
    <div class="col-md-2"><select name="month" class="form-select"><option value="">All Months</option><?php for($m=1;$m<=12;$m++): ?><option value="<?php echo e($m); ?>" <?php echo e(request('month')==$m?'selected':''); ?>><?php echo e(date('F',mktime(0,0,0,$m,1))); ?></option><?php endfor; ?></select></div>
    <?php if(auth()->user()->is_management): ?>
    <div class="col-md-3"><select name="department" class="form-select"><option value="">All Departments</option><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>" <?php echo e(request('department')==$d->id?'selected':''); ?>><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-md-3"><select name="user_id" class="form-select"><option value="">All Employees</option><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($e->id); ?>" <?php echo e(request('user_id')==$e->id?'selected':''); ?>><?php echo e($e->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <?php endif; ?>
    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100">Filter</button></div>
</form>
</div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Employee</th><th>Department</th><th>KPI Title</th><th>Target</th><th>Achieved</th><th>Score</th><th>Period</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td><?php echo e($kpi->user?->name); ?></td>
    <td><?php echo e($kpi->department?->name); ?></td>
    <td><a href="<?php echo e(route('kpi.show',$kpi)); ?>" class="text-decoration-none"><?php echo e($kpi->title); ?></a></td>
    <td><?php echo e($kpi->target); ?> <?php echo e($kpi->metric_unit); ?></td>
    <td><?php echo e($kpi->achieved); ?> <?php echo e($kpi->metric_unit); ?></td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <div class="progress flex-grow-1"><div class="progress-bar <?php echo e($kpi->score>=75?'bg-success':($kpi->score>=50?'bg-warning':'bg-danger')); ?>" data-value="<?php echo e($kpi->score); ?>" style="width:0%"></div></div>
            <span class="fw-bold small"><?php echo e($kpi->score); ?>%</span>
        </div>
    </td>
    <td><?php echo e(date('M',mktime(0,0,0,$kpi->month,1))); ?> <?php echo e($kpi->year); ?></td>
    <td><span class="status-badge status-<?php echo e($kpi->status); ?>"><?php echo e($kpi->status); ?></span></td>
    <td>
        <a href="<?php echo e(route('kpi.show',$kpi)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
        <?php if(auth()->user()->is_management && $kpi->status !== 'approved'): ?>
        <form method="POST" action="<?php echo e(route('kpi.approve',$kpi)); ?>" class="d-inline"><?php echo csrf_field(); ?><button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check"></i></button></form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-bullseye fs-2 d-block mb-2"></i>No KPIs found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($kpis->withQueryString()->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/kpi/index.blade.php ENDPATH**/ ?>