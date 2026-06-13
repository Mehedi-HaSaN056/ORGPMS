<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'code', 'description', 'color', 'icon', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function users()       { return $this->hasMany(User::class); }
    public function plans()       { return $this->hasMany(Plan::class); }
    public function tasks()       { return $this->hasMany(Task::class); }
    public function kpis()        { return $this->hasMany(Kpi::class); }
    public function messages()    { return $this->hasMany(Message::class); }
    public function devRequests() { return $this->hasMany(DevelopmentRequest::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }

    public function getCompletionRateAttribute(): float
    {
        $total     = $this->tasks()->count();
        $completed = $this->tasks()->where('status', 'completed')->count();
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    public function getAverageKpiScoreAttribute(): float
    {
        return round($this->kpis()->avg('score') ?? 0, 2);
    }
}
