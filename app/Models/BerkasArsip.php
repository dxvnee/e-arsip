<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BerkasArsip extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'berkas_arsip';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kode_klasifikasi_id',
        'nomor_berkas',
        'uraian_berkas',
        'kurun_waktu',
        'tahun',
        'unit_kerja',
        'status_arsip',
        'lokasi_arsip_id',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tahun' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate nomor_berkas if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_berkas)) {
                $model->nomor_berkas = static::generateNomorBerkas($model->tahun);
            }
        });
    }

    /**
     * Generate unique nomor berkas.
     */
    public static function generateNomorBerkas($tahun = null): string
    {
        $tahun = $tahun ?? date('Y');
        $prefix = 'BKS';

        $lastBerkas = static::withTrashed()
            ->whereYear('created_at', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBerkas && preg_match('/BKS-(\d+)-' . $tahun . '/', $lastBerkas->nomor_berkas, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%04d-%s', $prefix, $nextNumber, $tahun);
    }

    /**
     * Relationship: BerkasArsip belongs to KlasifikasiArsip.
     */
    public function klasifikasiArsip(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'kode_klasifikasi_id');
    }

    /**
     * Relationship: BerkasArsip belongs to LokasiArsip.
     */
    public function lokasiArsip(): BelongsTo
    {
        return $this->belongsTo(LokasiArsip::class, 'lokasi_arsip_id');
    }

    /**
     * Relationship: BerkasArsip has many ItemArsip.
     */
    public function itemArsip(): HasMany
    {
        return $this->hasMany(ItemArsip::class, 'berkas_arsip_id');
    }

    /**
     * Accessor: Get status badge color.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status_arsip) {
            'Aktif' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
            'Inaktif' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
            'Permanen' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-archive'],
            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-question-circle'],
        };
    }

    /**
     * Accessor: Get full berkas info.
     */
    public function getFullInfoAttribute(): string
    {
        $klasifikasi = $this->klasifikasiArsip ? $this->klasifikasiArsip->kode_klasifikasi : '-';
        return "[{$klasifikasi}] {$this->nomor_berkas} - {$this->uraian_berkas}";
    }

    /**
     * Accessor: Count item arsip in this berkas.
     */
    public function getItemCountAttribute(): int
    {
        return $this->itemArsip()->count();
    }

    /**
     * Check if berkas can be deleted (no item arsip).
     */
    public function canBeDeleted(): bool
    {
        return $this->itemArsip()->count() === 0;
    }

    /**
     * Scope: Filter by status arsip.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_arsip', $status);
    }

    /**
     * Scope: Filter by tahun.
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope: Filter by klasifikasi.
     */
    public function scopeByKlasifikasi($query, $klasifikasiId)
    {
        return $query->where('kode_klasifikasi_id', $klasifikasiId);
    }

    /**
     * Scope: Filter by unit kerja.
     */
    public function scopeByUnitKerja($query, $unitKerja)
    {
        return $query->where('unit_kerja', 'like', "%{$unitKerja}%");
    }

    /**
     * Scope: Search by keyword.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nomor_berkas', 'like', "%{$keyword}%")
                ->orWhere('uraian_berkas', 'like', "%{$keyword}%")
                ->orWhere('unit_kerja', 'like', "%{$keyword}%")
                ->orWhere('keterangan', 'like', "%{$keyword}%");
        });
    }
}
