<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Achievement extends Model {
    use HasFactory;
    protected $fillable = ['user_id','title','description','badge_icon','badge_color','type'];
    public function user() { return $this->belongsTo(User::class); }
}
