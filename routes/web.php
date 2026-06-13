<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Department\DashboardController as DeptDashboard;
use App\Http\Controllers\Planning\PlanController;
use App\Http\Controllers\KPI\KpiController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Communication\MessageController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Management\ManagementController;

// ─── Auth Routes ───────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/',          [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login',     [AuthController::class, 'showLogin']);
    Route::post('/login',    [AuthController::class, 'login'])->name('login.submit');
    Route::get('/forgot-password',         [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password',        [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password',         [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated Routes ──────────────────────────────────────────────────────

Route::middleware(['auth', 'active_user', 'track_activity'])->group(function () {

    // Department Dashboard (all employees)
    Route::get('/dashboard', [DeptDashboard::class, 'index'])->name('dashboard');

    // ── Plans ─────────────────────────────────────────────────────────────────
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/',                      [PlanController::class, 'index'])->name('index');
        Route::get('/create',                [PlanController::class, 'create'])->name('create');
        Route::post('/',                     [PlanController::class, 'store'])->name('store');
        Route::get('/{plan}',                [PlanController::class, 'show'])->name('show');
        Route::get('/{plan}/edit',           [PlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}',                [PlanController::class, 'update'])->name('update');
        Route::delete('/{plan}',             [PlanController::class, 'destroy'])->name('destroy');
        Route::post('/{plan}/approve',       [PlanController::class, 'approve'])->name('approve')->middleware('role:management|admin|super_admin');
        Route::post('/{plan}/reject',        [PlanController::class, 'reject'])->name('reject')->middleware('role:management|admin|super_admin');
        Route::patch('/{plan}/progress',     [PlanController::class, 'updateProgress'])->name('progress');
    });

    // ── KPIs ─────────────────────────────────────────────────────────────────
    Route::prefix('kpi')->name('kpi.')->group(function () {
        Route::get('/',                      [KpiController::class, 'index'])->name('index');
        Route::get('/create',                [KpiController::class, 'create'])->name('create')->middleware('role:department_head|management|admin|super_admin');
        Route::post('/',                     [KpiController::class, 'store'])->name('store')->middleware('role:department_head|management|admin|super_admin');
        Route::get('/{kpi}',                 [KpiController::class, 'show'])->name('show');
        Route::put('/{kpi}',                 [KpiController::class, 'update'])->name('update');
        Route::post('/{kpi}/approve',        [KpiController::class, 'approve'])->name('approve')->middleware('role:management|admin|super_admin');
        Route::get('/reports/department',    [KpiController::class, 'departmentReport'])->name('dept-report');
        Route::get('/chart/{user}',          [KpiController::class, 'employeeKpiChart'])->name('chart');
    });

    // ── Employees ─────────────────────────────────────────────────────────────
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/',                      [EmployeeController::class, 'index'])->name('index');
        Route::get('/leaderboard',           [EmployeeController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/create',                [EmployeeController::class, 'create'])->name('create')->middleware('role:admin|super_admin');
        Route::post('/',                     [EmployeeController::class, 'store'])->name('store')->middleware('role:admin|super_admin');
        Route::get('/{employee}',            [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit',       [EmployeeController::class, 'edit'])->name('edit')->middleware('role:admin|super_admin');
        Route::put('/{employee}',            [EmployeeController::class, 'update'])->name('update')->middleware('role:admin|super_admin');
        Route::post('/{employee}/achievement',[EmployeeController::class,'addAchievement'])->name('achievement')->middleware('role:management|admin|super_admin');
    });

    // ── Messages ──────────────────────────────────────────────────────────────
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/',                      [MessageController::class, 'inbox'])->name('inbox');
        Route::get('/compose',               [MessageController::class, 'compose'])->name('compose');
        Route::post('/',                     [MessageController::class, 'send'])->name('send');
        Route::get('/{message}',             [MessageController::class, 'show'])->name('show');
        Route::delete('/{message}',          [MessageController::class, 'destroy'])->name('destroy');
        Route::get('/unread/count',          [MessageController::class, 'unreadCount'])->name('unread');
        Route::post('/broadcast',            [MessageController::class, 'broadcast'])->name('broadcast')->middleware('role:management|admin|super_admin');
    });

    // ── Reports ───────────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                      [ReportController::class, 'index'])->name('index');
        Route::get('/kpi',                   [ReportController::class, 'kpiReport'])->name('kpi');
        Route::get('/performance',           [ReportController::class, 'performanceReport'])->name('performance');
        Route::get('/department',            [ReportController::class, 'departmentReport'])->name('department');
        Route::get('/employee/{user}',       [ReportController::class, 'employeeReport'])->name('employee');
    });

    // ── Admin Routes ──────────────────────────────────────────────────────────
    Route::middleware('role:admin|super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard',             [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/analytics',             [AdminDashboard::class, 'analytics'])->name('analytics');
        Route::get('/login-logs',            [ManagementController::class, 'loginLogs'])->name('login-logs');
        Route::get('/activity-logs',         [ManagementController::class, 'activityLogs'])->name('activity-logs');
        Route::resource('departments',       \App\Http\Controllers\Admin\DepartmentController::class);
        Route::resource('roles',             \App\Http\Controllers\Admin\RoleController::class);
    });

    // ── Management Routes ─────────────────────────────────────────────────────
    Route::middleware('role:management|admin|super_admin')->prefix('management')->name('management.')->group(function () {
        Route::get('/dashboard',             [ManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/overview',              [ManagementController::class, 'overview'])->name('overview');
        Route::get('/approvals',             [ManagementController::class, 'approvals'])->name('approvals');
        Route::get('/dev-requests',          [ManagementController::class, 'devRequests'])->name('dev-requests');
        Route::patch('/dev-requests/{req}/status', [ManagementController::class, 'updateDevRequest'])->name('dev-request.update');
    });

    // ── Development Requests ──────────────────────────────────────────────────
    Route::resource('dev-requests', \App\Http\Controllers\Employee\DevRequestController::class)->except(['edit','update']);

    // ── Work Logs ─────────────────────────────────────────────────────────────
    Route::resource('work-logs', \App\Http\Controllers\Employee\WorkLogController::class)->only(['index','store','show']);

    // ── AJAX / API endpoints ──────────────────────────────────────────────────
    Route::prefix('api')->group(function () {
        Route::get('/notifications',         fn() => auth()->user()->unreadNotifications)->name('api.notifications');
        Route::post('/notifications/read',   fn() => auth()->user()->unreadNotifications->markAsRead())->name('api.notifications.read');
        Route::get('/dashboard/stats',       [AdminDashboard::class, 'analytics'])->name('api.stats');
    });
});
