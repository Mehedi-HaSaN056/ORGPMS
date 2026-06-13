<?php $__env->startSection('title','Work Logs'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-journal-text text-primary me-2"></i>Work Logs</h1></div></div>
<div class="card mb-4"><div class="card-header">Log Today's Work</div><div class="card-body">
<form method="POST" action="<?php echo e(route('work-logs.store')); ?>"><?php echo csrf_field(); ?>
<div class="row g-2">
<div class="col-md-5"><textarea name="description" class="form-control" rows="2" placeholder="What did you work on today?" required></textarea></div>
<div class="col-md-2"><input type="number" name="hours_spent" class="form-control" placeholder="Hours" min="0" max="24" step="0.5"></div>
<div class="col-md-2"><input type="date" name="log_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required></div>
<div class="col-md-1"><button type="submit" class="btn btn-primary h-100 w-100">Log</button></div>
</div>
</form>
</div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Date</th><th>Description</th><th>Hours</th></tr></thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td><?php echo e($log->log_date->format('d M Y')); ?></td><td><?php echo e($log->description); ?></td><td><?php echo e($log->hours_spent ?? '—'); ?>h</td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr><td colspan="3" class="text-center py-5 text-muted">No work logs yet.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($logs->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/work-logs/index.blade.php ENDPATH**/ ?>