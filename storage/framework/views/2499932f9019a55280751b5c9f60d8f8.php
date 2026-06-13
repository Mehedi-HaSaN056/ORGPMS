<?php $__env->startSection('title','Employees'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-people text-primary me-2"></i>Employees</h1></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create employees')): ?><a href="<?php echo e(route('employees.create')); ?>" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add Employee</a><?php endif; ?>
</div>
<div class="card mb-3"><div class="card-body">
<form method="GET" class="row g-2">
    <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search by name, email, ID..." value="<?php echo e(request('search')); ?>"></div>
    <?php if(auth()->user()->is_management): ?><div class="col-md-3"><select name="department" class="form-select"><option value="">All Departments</option><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>" <?php echo e(request('department')==$d->id?'selected':''); ?>><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><?php endif; ?>
    <div class="col-md-2"><select name="status" class="form-select"><option value="">All Status</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="suspended">Suspended</option></select></div>
    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100">Filter</button></div>
</form>
</div></div>
<div class="row g-3">
<?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="col-md-6 col-lg-4">
<div class="card h-100">
<div class="card-body text-center">
    <img src="<?php echo e($emp->avatar_url); ?>" class="rounded-circle mb-3" width="70" height="70" style="object-fit:cover;border:3px solid var(--border)">
    <h6 class="mb-0"><?php echo e($emp->name); ?></h6>
    <p class="text-muted small mb-2"><?php echo e($emp->designation ?? '—'); ?></p>
    <span class="badge rounded-pill mb-2" style="background:<?php echo e($emp->department?->color ?? '#6b7280'); ?>20;color:<?php echo e($emp->department?->color ?? '#6b7280'); ?>"><?php echo e($emp->department?->name ?? 'No Dept'); ?></span>
    <div class="mb-3">
        <div class="progress" style="height:6px"><div class="progress-bar bg-success" data-value="<?php echo e($emp->task_completion_rate); ?>" style="width:0%"></div></div>
        <small class="text-muted"><?php echo e($emp->task_completion_rate); ?>% Task Completion</small>
    </div>
    <a href="<?php echo e(route('employees.show',$emp)); ?>" class="btn btn-sm btn-outline-primary w-100">View Profile</a>
</div>
</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="col-12 text-center text-muted py-5"><i class="bi bi-people fs-2 d-block mb-2"></i>No employees found.</div>
<?php endif; ?>
</div>
<div class="mt-3"><?php echo e($employees->withQueryString()->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/employees/index.blade.php ENDPATH**/ ?>