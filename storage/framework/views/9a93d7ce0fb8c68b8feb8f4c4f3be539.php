<?php $__env->startSection('title', $plan->title); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><?php echo e($plan->title); ?></h1>
        <p class="page-subtitle"><?php echo e($plan->department?->name); ?> &middot; Created by <?php echo e($plan->creator?->name); ?></p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo e(route('plans.edit',$plan)); ?>" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <?php if(auth()->user()->is_management && $plan->approval_status === 'pending'): ?>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal"><i class="bi bi-check-circle me-1"></i>Approve</button>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle me-1"></i>Reject</button>
        <?php endif; ?>
        <a href="<?php echo e(route('plans.index')); ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-4">
<div class="col-lg-8">
<div class="card mb-4">
<div class="card-body">
<div class="row g-3 mb-4">
    <div class="col-sm-4 text-center"><div class="text-muted small mb-1">Priority</div><span class="priority-badge priority-<?php echo e($plan->priority); ?> fs-6"><?php echo e($plan->priority); ?></span></div>
    <div class="col-sm-4 text-center"><div class="text-muted small mb-1">Status</div><span class="status-badge status-<?php echo e($plan->status); ?> fs-6"><?php echo e(str_replace('_',' ',$plan->status)); ?></span></div>
    <div class="col-sm-4 text-center"><div class="text-muted small mb-1">Approval</div><span class="status-badge status-<?php echo e($plan->approval_status); ?> fs-6"><?php echo e($plan->approval_status); ?></span></div>
</div>
<div class="mb-3"><strong>Description</strong><p class="mt-1 text-muted"><?php echo e($plan->description ?? 'No description provided.'); ?></p></div>
<div class="mb-3">
    <div class="d-flex justify-content-between mb-1"><span class="fw-semibold">Progress</span><span class="fw-bold text-primary"><?php echo e($plan->progress); ?>%</span></div>
    <div class="progress" style="height:12px"><div class="progress-bar bg-primary" data-value="<?php echo e($plan->progress); ?>" style="width:0%"></div></div>
</div>
<?php if($plan->management_comment): ?><div class="alert alert-info mt-3"><i class="bi bi-chat-quote me-2"></i><strong>Management Comment:</strong> <?php echo e($plan->management_comment); ?></div><?php endif; ?>
</div>
</div>

<?php if($plan->tasks->count()): ?>
<div class="card">
<div class="card-header"><span><i class="bi bi-list-task me-2"></i>Tasks (<?php echo e($plan->tasks->count()); ?>)</span></div>
<div class="card-body p-0">
<?php $__currentLoopData = $plan->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="d-flex align-items-center gap-3 p-3 border-bottom">
    <span class="status-badge status-<?php echo e($task->status); ?>"><?php echo e(str_replace('_',' ',$task->status)); ?></span>
    <span class="flex-grow-1"><?php echo e($task->title); ?></span>
    <span class="text-muted small"><?php echo e($task->due_date?->format('d M')); ?></span>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
</div>
<?php endif; ?>
</div>

<div class="col-lg-4">
<div class="card mb-3">
<div class="card-header">Details</div>
<div class="card-body">
<table class="table table-sm mb-0">
<tr><td class="text-muted">Assigned To</td><td><?php echo e($plan->assignee?->name ?? '—'); ?></td></tr>
<tr><td class="text-muted">Approved By</td><td><?php echo e($plan->approver?->name ?? '—'); ?></td></tr>
<tr><td class="text-muted">Start Date</td><td><?php echo e($plan->start_date?->format('d M Y') ?? '—'); ?></td></tr>
<tr><td class="text-muted">Due Date</td><td><?php echo e($plan->due_date?->format('d M Y') ?? '—'); ?></td></tr>
<tr><td class="text-muted">Completed</td><td><?php echo e($plan->completed_at?->format('d M Y') ?? '—'); ?></td></tr>
</table>
</div>
</div>

<?php if($plan->attachments->count()): ?>
<div class="card">
<div class="card-header">Attachments</div>
<div class="card-body p-0">
<?php $__currentLoopData = $plan->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<a href="<?php echo e($att->url); ?>" class="d-flex align-items-center gap-2 p-3 border-bottom text-decoration-none" target="_blank">
    <i class="bi bi-file-earmark text-primary fs-5"></i>
    <div><div class="small fw-semibold"><?php echo e($att->original_name); ?></div><div class="text-muted" style="font-size:.7rem"><?php echo e($att->formatted_size); ?></div></div>
</a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
</div>
<?php endif; ?>
</div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Approve Plan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="<?php echo e(route('plans.approve',$plan)); ?>"><?php echo csrf_field(); ?>
<div class="modal-body"><div class="mb-3"><label class="form-label">Comment (optional)</label><textarea name="comment" class="form-control" rows="3"></textarea></div></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Approve</button></div>
</form></div></div></div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title text-danger">Reject Plan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="<?php echo e(route('plans.reject',$plan)); ?>"><?php echo csrf_field(); ?>
<div class="modal-body"><div class="mb-3"><label class="form-label">Reason for Rejection *</label><textarea name="comment" class="form-control" rows="3" required></textarea></div></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div>
</form></div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/planning/show.blade.php ENDPATH**/ ?>