<?php $__env->startSection('title','Reports'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>Reports</h1></div></div>
<div class="row g-4">
<?php $__currentLoopData = [['KPI Report','kpi','bullseye','warning','Generate monthly & yearly KPI performance reports by employee and department.'],['Performance Report','performance','person-check','success','View comprehensive employee performance reviews and ratings.'],['Department Report','department','diagram-3','info','Compare all departments by tasks, plans, and KPI achievement.'],['Employee Report','employee/{employee}','person','primary','Individual employee performance, KPI, and activity report.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title,$route,$icon,$color,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-md-6 col-lg-3">
<div class="card h-100 text-center">
<div class="card-body py-4">
<div class="stat-icon <?php echo e($color); ?> mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem"><i class="bi bi-<?php echo e($icon); ?>"></i></div>
<h5><?php echo e($title); ?></h5>
<p class="text-muted small"><?php echo e($desc); ?></p>
<form method="GET" action="<?php echo e(route('reports.'.(str_contains($route,'{')?'employee':$route), str_contains($route,'{')?[auth()->user()]:[] )); ?>">
<div class="row g-2 mb-2">
<div class="col"><select name="year" class="form-select form-select-sm"><?php for($y=now()->year;$y>=now()->year-3;$y--): ?><option value="<?php echo e($y); ?>"><?php echo e($y); ?></option><?php endfor; ?></select></div>
<div class="col"><select name="month" class="form-select form-select-sm"><option value="">All</option><?php for($m=1;$m<=12;$m++): ?><option value="<?php echo e($m); ?>" <?php echo e(now()->month==$m?'selected':''); ?>><?php echo e(date('M',mktime(0,0,0,$m,1))); ?></option><?php endfor; ?></select></div>
</div>
<div class="d-grid gap-1">
<button type="submit" class="btn btn-<?php echo e($color); ?> btn-sm">View Report</button>
<button type="submit" name="format" value="pdf" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</button>
</div>
</form>
</div>
</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/reports/index.blade.php ENDPATH**/ ?>