<?php $__env->startSection('title','Create Plan'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h1 class="page-title">Create New Plan</h1></div>
    <a href="<?php echo e(route('plans.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
<div class="card-body">
<form method="POST" action="<?php echo e(route('plans.store')); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<div class="mb-3"><label class="form-label">Plan Title *</label><input type="text" name="title" class="form-control" value="<?php echo e(old('title')); ?>" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?php echo e(old('description')); ?></textarea></div>
<div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label">Department *</label><select name="department_id" class="form-select" required><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>" <?php echo e(old('department_id')==$d->id?'selected':''); ?>><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-md-6"><label class="form-label">Assign To</label><select name="assigned_to" class="form-select"><option value="">— Select Employee —</option><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($e->id); ?>"><?php echo e($e->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Priority *</label><select name="priority" class="form-select" required><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="critical">Critical</option></select></div>
    <div class="col-md-4"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="<?php echo e(old('start_date')); ?>"></div>
    <div class="col-md-4"><label class="form-label">Due Date</label><input type="date" name="due_date" class="form-control" value="<?php echo e(old('due_date')); ?>"></div>
</div>
<div class="mb-3"><label class="form-label">Attachments</label><input type="file" name="attachments[]" class="form-control" multiple></div>
<div class="d-flex gap-2 justify-content-end">
    <a href="<?php echo e(route('plans.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Plan</button>
</div>
</form>
</div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/planning/create.blade.php ENDPATH**/ ?>