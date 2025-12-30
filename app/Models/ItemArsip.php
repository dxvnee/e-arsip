<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemArsip extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'item_arsip';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'berkas_arsip_id',
        'nomor_item',
        'uraian_item',
        'tanggal_arsip',
        'jumlah',
        'satuan',
        'kondisi',
        'keterangan',
        'file_path',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal_arsip' => 'date',
        'jumlah' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Relationship: ItemArsip belongs to BerkasArsip.
     */
    public function berkasArsip(): BelongsTo
    {
        return $this->belongsTo(BerkasArsip::class, 'berkas_arsip_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor: Get formatted tanggal.
     */
    public function getFormattedTanggalAttribute(): string
    {
        return $this->tanggal_arsip ? $this->tanggal_arsip->format('d/m/Y') : '-';
    }

    /**
     * Accessor: Get klasifikasi through berkas.
     */
    public function getKlasifikasiAttribute()
    {
        return $this->berkasArsip?->klasifikasiArsip;
    }

    /**
     * Accessor: Get lokasi through berkas.
     */
    public function getLokasiAttribute()
    {
        return $this->berkasArsip?->lokasiArsip;
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Search by keyword.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nomor_item', 'like', "%{$keyword}%")
                ->orWhere('uraian_item', 'like', "%{$keyword}%")
                ->orWhere('keterangan', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope: Filter by berkas.
     */
    public function scopeByBerkas($query, $berkasId)
    {
        return $query->where('berkas_arsip_id', $berkasId);
    }

    /**
     * Scope: Filter by date range.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_arsip', [$startDate, $endDate]);
    }
}
