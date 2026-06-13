<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DevelopmentRequest extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['department_id','requested_by','reviewed_by','title','description','type','priority','status','estimated_budget','budget_comment','management_response','expected_date'];
    protected $casts = ['estimated_budget'=>'decimal:2','expected_date'=>'date'];
    public function department() { return $this->belongsTo(Department::class); }
    public function requester()  { return $this->belongsTo(User::class,'requested_by'); }
    public function reviewer()   { return $this->belongsTo(User::class,'reviewed_by'); }
}
