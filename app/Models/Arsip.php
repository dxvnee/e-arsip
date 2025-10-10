<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Arsip extends Model
{
    use HasFactory;
    
    protected $table = 'arsip';
    
    protected $fillable = [
        'nomor_arsip',
        'nomor_surat',
        'judul_arsip',
        'deskripsi',
        'kategori_id',
        'unit_kerja_id',
        'jenis_arsip',
        'tanggal_surat',
        'tanggal_diterima',
        'pengirim',
        'penerima',
        'perihal',
        'isi_ringkas',
        'file_arsip',
        'file_type',
        'file_size',
        'lokasi_fisik',
        'status',
        'tanggal_retensi',
        'created_by',
        'updated_by',
        'tags',
        'view_count',
        'download_count',
    ];
    
    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_diterima' => 'date',
        'tanggal_retensi' => 'date',
        'file_size' => 'integer',
        'view_count' => 'integer',
        'download_count' => 'integer',
    ];
    
    /**
     * Boot method to generate nomor arsip
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($arsip) {
            if (empty($arsip->nomor_arsip)) {
                $year = date('Y');
                $month = date('m');
                $lastArsip = static::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $number = $lastArsip ? intval(substr($lastArsip->nomor_arsip, -4)) + 1 : 1;
                $arsip->nomor_arsip = sprintf('ARS/%s/%s/%04d', $year, $month, $number);
            }
        });
    }
    
    /**
     * Relationship with KategoriArsip
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriArsip::class, 'kategori_id');
    }
    
    /**
     * Relationship with UnitKerja
     */
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
    
    /**
     * Relationship with User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Relationship with User (updater)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    /**
     * Scope for active arsip
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nomor_arsip', 'like', "%{$search}%")
              ->orWhere('nomor_surat', 'like', "%{$search}%")
              ->orWhere('judul_arsip', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('perihal', 'like', "%{$search}%")
              ->orWhere('tags', 'like', "%{$search}%");
        });
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
    
    /**
     * Get tags as array
     */
    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }
    
    /**
     * Set tags from array
     */
    public function setTagsArrayAttribute($value)
    {
        $this->attributes['tags'] = is_array($value) ? implode(',', $value) : $value;
    }
}
