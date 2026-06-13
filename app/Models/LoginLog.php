<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LoginLog extends Model {
    use HasFactory;
    protected $fillable = ['user_id','ip_address','user_agent','browser','platform','device','is_successful','failure_reason','logged_in_at','logged_out_at'];
    protected $casts = ['is_successful'=>'boolean','logged_in_at'=>'datetime','logged_out_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
}
