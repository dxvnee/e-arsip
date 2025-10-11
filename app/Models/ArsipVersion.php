<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ArsipVersion extends Model
{
    use HasFactory;
    
    protected $table = 'arsip_versions';
    
    protected $fillable = [
        'arsip_id',
        'version_number',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
        'change_note',
        'metadata',
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'file_size' => 'integer',
    ];
    
    /**
     * Relationship with Arsip
     */
    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
    
    /**
     * Relationship with User (creator of this version)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }
    
    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $size = $this->file_size;
        if ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' B';
        }
    }
}
