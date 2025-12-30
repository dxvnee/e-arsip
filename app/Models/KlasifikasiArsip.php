<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KlasifikasiArsip extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'klasifikasi_arsip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_klasifikasi',
        'nama_klasifikasi',
        'deskripsi',
        'retensi_aktif',
        'retensi_inaktif',
        'nasib_akhir',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'retensi_aktif' => 'integer',
            'retensi_inaktif' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get total masa retensi (aktif + inaktif)
     *
     * @return int
     */
    public function getTotalRetensiAttribute(): int
    {
        return $this->retensi_aktif + $this->retensi_inaktif;
    }

    /**
     * Get formatted nasib akhir
     *
     * @return string
     */
    public function getNasibAkhirLabelAttribute(): string
    {
        return $this->nasib_akhir === 'musnah' ? 'Musnah' : 'Permanen';
    }

    /**
     * Scope untuk klasifikasi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode_klasifikasi', 'like', "%{$search}%")
                ->orWhere('nama_klasifikasi', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }
}
