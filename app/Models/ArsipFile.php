<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ArsipFile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'arsip_file';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'item_arsip_id',
        'nama_file',
        'path_file',
        'ukuran',
        'tipe_file',
        'hash_file',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'ukuran' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Relationship: ArsipFile belongs to ItemArsip.
     */
    public function itemArsip(): BelongsTo
    {
        return $this->belongsTo(ItemArsip::class, 'item_arsip_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted file size.
     */
    public function getFormattedUkuranAttribute(): string
    {
        $bytes = $this->ukuran;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get file icon based on type.
     */
    public function getFileIconAttribute(): string
    {
        return match ($this->tipe_file) {
            'pdf' => 'fa-file-pdf text-red-500',
            'jpg', 'jpeg', 'png' => 'fa-file-image text-blue-500',
            default => 'fa-file text-gray-500',
        };
    }

    /**
     * Check if file is an image.
     */
    public function isImage(): bool
    {
        return in_array($this->tipe_file, ['jpg', 'jpeg', 'png']);
    }

    /**
     * Check if file is PDF.
     */
    public function isPdf(): bool
    {
        return $this->tipe_file === 'pdf';
    }

    /**
     * Get full storage path.
     */
    public function getFullPathAttribute(): string
    {
        return Storage::disk('local')->path($this->path_file);
    }

    /**
     * Check if file exists in storage.
     */
    public function fileExists(): bool
    {
        return Storage::disk('local')->exists($this->path_file);
    }

    // ==================== METHODS ====================

    /**
     * Delete file from storage.
     */
    public function deleteFromStorage(): bool
    {
        if ($this->fileExists()) {
            return Storage::disk('local')->delete($this->path_file);
        }
        return true;
    }
}
