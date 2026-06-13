<?php $__env->startSection('title','Pending Approvals'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-check-circle text-warning me-2"></i>Pending Approvals</h1></div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Plan Title</th><th>Department</th><th>Requested By</th><th>Priority</th><th>Due Date</th><th>Actions</th></tr></thead>
<tbody>
<?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td><a href="<?php echo e(route('plans.show',$plan)); ?>" class="fw-semibold text-decoration-none"><?php echo e($plan->title); ?></a></td>
<td><?php echo e($plan->department?->name); ?></td>
<td><?php echo e($plan->creator?->name); ?></td>
<td><span class="priority-badge priority-<?php echo e($plan->priority); ?>"><?php echo e($plan->priority); ?></span></td>
<td><?php echo e($plan->due_date?->format('d M Y') ?? '—'); ?></td>
<td>
<div class="d-flex gap-1">
<form method="POST" action="<?php echo e(route('plans.approve',$plan)); ?>" class="d-inline"><input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"><button class="btn btn-sm btn-success">Approve</button></form>
<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#reject<?php echo e($plan->id); ?>">Reject</button>
<a href="<?php echo e(route('plans.show',$plan)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
</div>
<div class="modal fade" id="reject<?php echo e($plan->id); ?>"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject Plan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="<?php echo e(route('plans.reject',$plan)); ?>"><?php echo csrf_field(); ?><div class="modal-body"><textarea name="comment" class="form-control" rows="3" placeholder="Reason..." required></textarea></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-check-all fs-2 d-block mb-2 text-success"></i>No pending approvals!</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($plans->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/management/approvals.blade.php ENDPATH**/ ?>