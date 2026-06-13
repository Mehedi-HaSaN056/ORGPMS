<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'department_id', 'name', 'email', 'employee_id', 'phone',
        'designation', 'avatar', 'gender', 'date_of_birth', 'joining_date',
        'address', 'password', 'status', 'last_login_at', 'last_login_ip',
        'two_factor_secret', 'two_factor_enabled',
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'date_of_birth'     => 'date',
        'joining_date'      => 'date',
        'two_factor_enabled' => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class, 'assigned_to');
    }

    public function createdPlans()
    {
        return $this->hasMany(Plan::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function workLogs()
    {
        return $this->hasMany(WorkLog::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    // ─── Accessors ─────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('assets/img/default-avatar.png');
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->hasRole(['super_admin', 'admin']);
    }

    public function getIsManagementAttribute(): bool
    {
        return $this->hasRole(['super_admin', 'admin', 'management']);
    }

    // ─── KPI Helpers ───────────────────────────────────────────

    public function getMonthlyKpiScore(int $year, int $month): float
    {
        $kpi = $this->kpis()
            ->where('year', $year)
            ->where('month', $month)
            ->avg('score');
        return round($kpi ?? 0, 2);
    }

    public function getTaskCompletionRate(): float
    {
        $total     = $this->tasks()->count();
        $completed = $this->tasks()->where('status', 'completed')->count();
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    public function getOverallPerformanceScore(): float
    {
        $taskRate = $this->getTaskCompletionRate();
        $kpiScore = $this->kpis()->avg('score') ?? 0;
        return round(($taskRate + $kpiScore) / 2, 2);
    }
}
