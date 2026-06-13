<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['plan_id','department_id','created_by','assigned_to','title','description','priority','status','due_date','completed_at','progress','notes'];
    protected $casts = ['due_date'=>'date','completed_at'=>'datetime'];
    public function plan()        { return $this->belongsTo(Plan::class); }
    public function department()  { return $this->belongsTo(Department::class); }
    public function creator()     { return $this->belongsTo(User::class,'created_by'); }
    public function assignee()    { return $this->belongsTo(User::class,'assigned_to'); }
    public function workLogs()    { return $this->hasMany(WorkLog::class); }
    public function attachments() { return $this->morphMany(Attachment::class,'attachable'); }
    public function scopeOverdue($q){ return $q->where('due_date','<',now())->whereNotIn('status',['completed','cancelled']); }
}
