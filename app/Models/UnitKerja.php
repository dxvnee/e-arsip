<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitKerja extends Model
{
    use HasFactory;
    
    protected $table = 'unit_kerja';
    
    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'keterangan',
        'kepala_unit',
        'nip_kepala',
        'email',
        'phone',
        'alamat',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Relationship with Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Relationship with Arsip
     */
    public function arsip()
    {
        return $this->hasMany(Arsip::class);
    }
    
    /**
     * Scope for active unit kerja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
