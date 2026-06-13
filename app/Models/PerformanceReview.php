<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PerformanceReview extends Model {
    use HasFactory;
    protected $fillable = ['user_id','reviewed_by','department_id','year','month','task_completion_score','kpi_score','communication_score','punctuality_score','quality_score','overall_score','rating','strengths','improvements','remarks','status'];
    protected $casts = ['task_completion_score'=>'decimal:2','kpi_score'=>'decimal:2','communication_score'=>'decimal:2','punctuality_score'=>'decimal:2','quality_score'=>'decimal:2','overall_score'=>'decimal:2'];
    public function user()       { return $this->belongsTo(User::class); }
    public function reviewer()   { return $this->belongsTo(User::class,'reviewed_by'); }
    public function department() { return $this->belongsTo(Department::class); }
    protected static function boot() {
        parent::boot();
        static::saving(function($r) {
            $r->overall_score = round(($r->task_completion_score+$r->kpi_score+$r->communication_score+$r->punctuality_score+$r->quality_score)/5,2);
            $r->rating = match(true) { $r->overall_score>=90=>'excellent',$r->overall_score>=75=>'good',$r->overall_score>=60=>'average',$r->overall_score>=40=>'below_average',default=>'poor' };
        });
    }
}
