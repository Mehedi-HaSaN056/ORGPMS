<?php $__env->startSection('title','Plans'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-kanban text-primary me-2"></i>Plans</h1></div>
    <a href="<?php echo e(route('plans.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Plan</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search plans..." value="<?php echo e(request('search')); ?>"></div>
            <div class="col-md-2"><select name="status" class="form-select"><option value="">All Status</option><?php $__currentLoopData = ['pending','in_progress','completed','delayed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php echo e(request('status')==$s?'selected':''); ?>><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div class="col-md-2"><select name="priority" class="form-select"><option value="">All Priority</option><?php $__currentLoopData = ['low','medium','high','critical']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p); ?>" <?php echo e(request('priority')==$p?'selected':''); ?>><?php echo e(ucfirst($p)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <?php if(auth()->user()->is_management): ?>
            <div class="col-md-2"><select name="department" class="form-select"><option value="">All Depts</option><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>" <?php echo e(request('department')==$d->id?'selected':''); ?>><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <?php endif; ?>
            <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filter</button></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table data-table mb-0">
            <thead><tr><th>Title</th><th>Department</th><th>Priority</th><th>Status</th><th>Approval</th><th>Progress</th><th>Due Date</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><a href="<?php echo e(route('plans.show',$plan)); ?>" class="text-decoration-none fw-semibold"><?php echo e($plan->title); ?></a><br><small class="text-muted">By <?php echo e($plan->creator?->name); ?></small></td>
                <td><span style="color:<?php echo e($plan->department?->color); ?>"><i class="<?php echo e($plan->department?->icon); ?>"></i></span> <?php echo e($plan->department?->name); ?></td>
                <td><span class="priority-badge priority-<?php echo e($plan->priority); ?>"><?php echo e($plan->priority); ?></span></td>
                <td><span class="status-badge status-<?php echo e($plan->status); ?>"><?php echo e(str_replace('_',' ',$plan->status)); ?></span></td>
                <td><span class="status-badge status-<?php echo e($plan->approval_status); ?>"><?php echo e($plan->approval_status); ?></span></td>
                <td style="min-width:100px"><div class="progress"><div class="progress-bar bg-primary" data-value="<?php echo e($plan->progress); ?>" style="width:0%"></div></div><small><?php echo e($plan->progress); ?>%</small></td>
                <td><small><?php echo e($plan->due_date?->format('d M Y') ?? '—'); ?></small></td>
                <td>
                    <a href="<?php echo e(route('plans.show',$plan)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    <a href="<?php echo e(route('plans.edit',$plan)); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-kanban fs-2 d-block mb-2"></i>No plans found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?php echo e($plans->withQueryString()->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/planning/index.blade.php ENDPATH**/ ?>