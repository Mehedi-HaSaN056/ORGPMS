<?php $__env->startSection('title','Messages'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-envelope text-primary me-2"></i>Inbox</h1><p class="page-subtitle"><?php echo e($unreadCount); ?> unread message(s)</p></div>
    <a href="<?php echo e(route('messages.compose')); ?>" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i>Compose</a>
</div>
<div class="card"><div class="card-body p-0">
<?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<a href="<?php echo e(route('messages.show',$msg)); ?>" class="d-flex align-items-start gap-3 p-3 border-bottom text-decoration-none <?php echo e(!$msg->is_read && $msg->receiver_id===auth()->id()?'fw-semibold':'text-body'); ?>" style="<?php echo e(!$msg->is_read && $msg->receiver_id===auth()->id()?'background:var(--primary-light)':''); ?>">
<img src="<?php echo e($msg->sender?->avatar_url); ?>" class="rounded-circle flex-shrink-0" width="40" height="40" style="object-fit:cover">
<div class="flex-grow-1 min-w-0">
<div class="d-flex justify-content-between">
<span><?php echo e($msg->sender?->name); ?></span>
<small class="text-muted"><?php echo e($msg->created_at->diffForHumans()); ?></small>
</div>
<div><?php echo e($msg->subject ?? '(No subject)'); ?></div>
<div class="text-muted small text-truncate"><?php echo e(Str::limit(strip_tags($msg->body), 80)); ?></div>
</div>
<div class="d-flex flex-column align-items-end gap-1">
<span class="badge rounded-pill <?php echo e(['normal'=>'bg-secondary','high'=>'bg-warning','urgent'=>'bg-danger'][$msg->priority]); ?>"><?php echo e($msg->priority); ?></span>
<?php if(!$msg->is_read && $msg->receiver_id===auth()->id()): ?><span class="rounded-circle bg-primary" style="width:8px;height:8px;display:inline-block"></span><?php endif; ?>
</div>
</a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center text-muted py-5"><i class="bi bi-envelope-open fs-2 d-block mb-2"></i>Your inbox is empty.</div>
<?php endif; ?>
</div></div>
<div class="mt-3"><?php echo e($messages->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/communication/inbox.blade.php ENDPATH**/ ?>