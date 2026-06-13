<?php $__env->startSection('title','Login'); ?>
<?php $__env->startSection('content'); ?>
<div class="auth-page">
    <div class="auth-card fade-in-up">
        <div class="auth-logo">
            <div class="logo-icon"><i class="bi bi-buildings-fill"></i></div>
            <h1>OrgPMS</h1>
            <p>Enterprise Planning &amp; Performance System</p>
        </div>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i><?php echo e($errors->first()); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.submit')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('email')); ?>" placeholder="your@email.com" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="passwordInput"
                           class="form-control" placeholder="••••••••" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()">
                        <i class="bi bi-eye" id="pwdEyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                <a href="<?php echo e(route('password.request')); ?>" class="small text-primary text-decoration-none">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="bi bi-shield-lock me-1"></i>Secured Enterprise Login
            </small>
        </div>

        <hr class="my-3">
        <div class="text-center">
            <small class="text-muted d-block mb-2 fw-semibold">Demo Credentials</small>
            <div class="d-flex flex-wrap gap-1 justify-content-center">
                <button onclick="fillLogin('superadmin@orgpms.com')" class="btn btn-outline-secondary btn-sm">Super Admin</button>
                <button onclick="fillLogin('management@orgpms.com')" class="btn btn-outline-secondary btn-sm">Management</button>
                <button onclick="fillLogin('it.head@orgpms.com')" class="btn btn-outline-secondary btn-sm">Dept Head</button>
                <button onclick="fillLogin('employee@orgpms.com')" class="btn btn-outline-secondary btn-sm">Employee</button>
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd(){
    const i=document.getElementById('passwordInput');
    const e=document.getElementById('pwdEyeIcon');
    i.type = i.type==='password' ? 'text' : 'password';
    e.className = i.type==='password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
function fillLogin(email){
    document.querySelector('input[name=email]').value=email;
    document.querySelector('input[name=password]').value='password';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\orgpms-app\resources\views/auth/login.blade.php ENDPATH**/ ?>