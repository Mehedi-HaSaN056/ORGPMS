<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Message extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['sender_id','receiver_id','department_id','subject','body','type','priority','is_read','read_at','attachment','parent_id'];
    protected $casts = ['is_read'=>'boolean','read_at'=>'datetime'];
    public function sender()     { return $this->belongsTo(User::class,'sender_id'); }
    public function receiver()   { return $this->belongsTo(User::class,'receiver_id'); }
    public function department() { return $this->belongsTo(Department::class); }
    public function parent()     { return $this->belongsTo(Message::class,'parent_id'); }
    public function replies()    { return $this->hasMany(Message::class,'parent_id'); }
    public function scopeUnread($q){ return $q->where('is_read',false); }
}
