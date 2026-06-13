<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Plan extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['department_id','created_by','assigned_to','approved_by','title','description','priority','status','approval_status','start_date','due_date','completed_at','progress','management_comment'];
    protected $casts = ['start_date'=>'date','due_date'=>'date','completed_at'=>'date'];
    public function department() { return $this->belongsTo(Department::class); }
    public function creator()    { return $this->belongsTo(User::class,'created_by'); }
    public function assignee()   { return $this->belongsTo(User::class,'assigned_to'); }
    public function approver()   { return $this->belongsTo(User::class,'approved_by'); }
    public function tasks()      { return $this->hasMany(Task::class); }
    public function attachments(){ return $this->morphMany(Attachment::class,'attachable'); }
    public function scopePending($q)    { return $q->where('approval_status','pending'); }
    public function scopeApproved($q)   { return $q->where('approval_status','approved'); }
    public function scopeOverdue($q)    { return $q->where('due_date','<',now())->whereNotIn('status',['completed','cancelled']); }
    public function getIsOverdueAttribute(): bool { return $this->due_date && $this->due_date->isPast() && !in_array($this->status,['completed','cancelled']); }
}
