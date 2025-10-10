<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriArsip extends Model
{
    use HasFactory;
    
    protected $table = 'kategori_arsip';
    
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'deskripsi',
        'masa_retensi',
        'tingkat_keamanan',
        'warna_label',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'masa_retensi' => 'integer',
    ];
    
    /**
     * Relationship with Arsip
     */
    public function arsip()
    {
        return $this->hasMany(Arsip::class, 'kategori_id');
    }
    
    /**
     * Scope for active kategori
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Get badge color based on security level
     */
    public function getSecurityBadgeAttribute()
    {
        $badges = [
            'publik' => ['color' => 'bg-green-100 text-green-800', 'label' => 'Publik'],
            'internal' => ['color' => 'bg-blue-100 text-blue-800', 'label' => 'Internal'],
            'rahasia' => ['color' => 'bg-yellow-100 text-yellow-800', 'label' => 'Rahasia'],
            'sangat_rahasia' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Sangat Rahasia'],
        ];
        
        return $badges[$this->tingkat_keamanan] ?? $badges['internal'];
    }
}
