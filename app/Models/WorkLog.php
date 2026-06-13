<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkLog extends Model {
    use HasFactory;
    protected $fillable = ['user_id','task_id','department_id','description','hours_spent','log_date','status'];
    protected $casts = ['log_date'=>'date','hours_spent'=>'decimal:2'];
    public function user()       { return $this->belongsTo(User::class); }
    public function task()       { return $this->belongsTo(Task::class); }
    public function department() { return $this->belongsTo(Department::class); }
}
