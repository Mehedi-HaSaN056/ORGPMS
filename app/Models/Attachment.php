<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Attachment extends Model {
    use HasFactory;
    protected $fillable = ['uploaded_by','original_name','file_path','file_type','file_size'];
    public function attachable() { return $this->morphTo(); }
    public function uploader()   { return $this->belongsTo(User::class,'uploaded_by'); }
    public function getUrlAttribute(): string { return asset('storage/'.$this->file_path); }
    public function getFormattedSizeAttribute(): string {
        $bytes = $this->file_size;
        if($bytes>=1048576) return round($bytes/1048576,2).' MB';
        if($bytes>=1024) return round($bytes/1024,2).' KB';
        return $bytes.' B';
    }
}
