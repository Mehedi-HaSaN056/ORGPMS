<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Kpi extends Model {
    use HasFactory;
    protected $fillable = ['department_id','user_id','evaluated_by','title','description','target','achieved','score','metric_unit','year','month','period','status','remarks','approved_at'];
    protected $casts = ['approved_at'=>'datetime','target'=>'decimal:2','achieved'=>'decimal:2','score'=>'decimal:2'];
    public function department()  { return $this->belongsTo(Department::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function evaluator()   { return $this->belongsTo(User::class,'evaluated_by'); }
    public function getAchievementPercentageAttribute(): float {
        return $this->target > 0 ? round(($this->achieved / $this->target) * 100, 2) : 0;
    }
    protected static function boot() {
        parent::boot();
        static::saving(function($kpi) {
            if($kpi->target > 0) { $kpi->score = round(($kpi->achieved / $kpi->target) * 100, 2); }
        });
    }
}
