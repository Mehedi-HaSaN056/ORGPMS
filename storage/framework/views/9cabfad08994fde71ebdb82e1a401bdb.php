<?php $__env->startSection('title','Edit Plan'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header"><div><h1 class="page-title">Edit Plan</h1></div><a href="<?php echo e(route('plans.show',$plan)); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
<form method="POST" action="<?php echo e(route('plans.update',$plan)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
<div class="mb-3"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="<?php echo e($plan->title); ?>" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?php echo e($plan->description); ?></textarea></div>
<div class="row g-3 mb-3">
<div class="col-md-6"><label class="form-label">Assigned To</label><select name="assigned_to" class="form-select"><option value="">—</option><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($e->id); ?>" <?php echo e($plan->assigned_to==$e->id?'selected':''); ?>><?php echo e($e->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<div class="col-md-6"><label class="form-label">Priority</label><select name="priority" class="form-select"><?php $__currentLoopData = ['low','medium','high','critical']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p); ?>" <?php echo e($plan->priority==$p?'selected':''); ?>><?php echo e(ucfirst($p)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><?php $__currentLoopData = ['pending','in_progress','completed','delayed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php echo e($plan->status==$s?'selected':''); ?>><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<div class="col-md-4"><label class="form-label">Progress (%)</label><input type="range" name="progress" class="form-range mt-2" min="0" max="100" value="<?php echo e($plan->progress); ?>" id="progressRange" oninput="document.getElementById('progressVal').textContent=this.value+'%'"><span id="progressVal" class="fw-bold text-primary"><?php echo e($plan->progress); ?>%</span></div>
<div class="col-md-4"><label class="form-label">Due Date</label><input type="date" name="due_date" class="form-control" value="<?php echo e($plan->due_date?->format('Y-m-d')); ?>"></div>
</div>
<div class="d-flex gap-2 justify-content-end"><a href="<?php echo e(route('plans.show',$plan)); ?>" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Update Plan</button></div>
</form>
</div></div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/planning/edit.blade.php ENDPATH**/ ?>