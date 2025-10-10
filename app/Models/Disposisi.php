<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disposisi extends Model
{
    use HasFactory;
    
    protected $table = 'disposisi';
    
    protected $fillable = [
        'arsip_id',
        'dari_user_id',
        'kepada_user_id',
        'isi_disposisi',
        'prioritas',
        'sifat',
        'catatan',
        'status',
        'dibaca_pada',
        'diproses_pada',
        'selesai_pada',
        'tindak_lanjut',
    ];
    
    protected $casts = [
        'dibaca_pada' => 'datetime',
        'diproses_pada' => 'datetime',
        'selesai_pada' => 'datetime',
    ];
    
    /**
     * Relationship with Arsip
     */
    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
    
    /**
     * Relationship with User (dari)
     */
    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }
    
    /**
     * Relationship with User (kepada)
     */
    public function kepadaUser()
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }
    
    /**
     * Scope untuk disposisi yang belum dibaca
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('status', 'baru');
    }
    
    /**
     * Scope untuk disposisi user tertentu
     */
    public function scopeUntukUser($query, $userId)
    {
        return $query->where('kepada_user_id', $userId);
    }
    
    /**
     * Mark disposisi as dibaca
     */
    public function markAsDibaca()
    {
        $this->update([
            'status' => 'dibaca',
            'dibaca_pada' => now(),
        ]);
    }
    
    /**
     * Mark disposisi as diproses
     */
    public function markAsDiproses()
    {
        $this->update([
            'status' => 'diproses',
            'diproses_pada' => now(),
        ]);
    }
    
    /**
     * Mark disposisi as selesai
     */
    public function markAsSelesai($tindakLanjut = null)
    {
        $this->update([
            'status' => 'selesai',
            'selesai_pada' => now(),
            'tindak_lanjut' => $tindakLanjut,
        ]);
    }
}
