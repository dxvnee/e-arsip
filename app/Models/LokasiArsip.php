<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LokasiArsip extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_arsip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_lokasi',
        'gedung',
        'ruang',
        'rak',
        'boks',
        'keterangan',
        'kapasitas',
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
            'kapasitas' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto generate kode_lokasi before creating
        static::creating(function ($model) {
            if (empty($model->kode_lokasi)) {
                $model->kode_lokasi = $model->generateKodeLokasi();
            }
        });
    }

    /**
     * Generate unique kode lokasi
     */
    public function generateKodeLokasi(): string
    {
        $prefix = strtoupper(substr($this->gedung, 0, 1));
        $prefix .= strtoupper(substr($this->ruang, 0, 1));
        $prefix .= '-' . $this->rak;
        $prefix .= '-' . $this->boks;

        return $prefix;
    }

    /**
     * Get full location name
     *
     * @return string
     */
    public function getFullLocationAttribute(): string
    {
        return "{$this->gedung} / {$this->ruang} / Rak {$this->rak} / Boks {$this->boks}";
    }

    /**
     * Get short location name
     *
     * @return string
     */
    public function getShortLocationAttribute(): string
    {
        return "Rak {$this->rak} - Boks {$this->boks}";
    }

    /**
     * Relation to arsip
     */
    public function arsip(): HasMany
    {
        return $this->hasMany(Arsip::class, 'lokasi_arsip_id');
    }

    /**
     * Get arsip count
     */
    public function getArsipCountAttribute(): int
    {
        return $this->arsip()->count();
    }

    /**
     * Check if location is in use
     */
    public function isInUse(): bool
    {
        return $this->arsip()->exists();
    }

    /**
     * Check if location can be deleted
     */
    public function canBeDeleted(): bool
    {
        return !$this->isInUse();
    }

    /**
     * Scope for active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode_lokasi', 'like', "%{$search}%")
                ->orWhere('gedung', 'like', "%{$search}%")
                ->orWhere('ruang', 'like', "%{$search}%")
                ->orWhere('rak', 'like', "%{$search}%")
                ->orWhere('boks', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
        });
    }

    /**
     * Scope filter by gedung
     */
    public function scopeByGedung($query, $gedung)
    {
        return $query->where('gedung', $gedung);
    }

    /**
     * Scope filter by ruang
     */
    public function scopeByRuang($query, $ruang)
    {
        return $query->where('ruang', $ruang);
    }
}
