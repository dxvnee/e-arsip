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
        'judul_arsip',
        'deskripsi',
        'file_arsip',
        'file_type',
        'file_size',
        'updated_by',
        'change_notes',
        'metadata_changes',
    ];
    
    protected $casts = [
        'metadata_changes' => 'array',
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
     * Relationship with User (updater)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_arsip) {
            return Storage::url($this->file_arsip);
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
