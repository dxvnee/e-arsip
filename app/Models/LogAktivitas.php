<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitas extends Model
{
    use HasFactory;
    
    protected $table = 'log_aktivitas';
    
    protected $fillable = [
        'user_id',
        'aksi',
        'model_type',
        'model_id',
        'deskripsi',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
    ];
    
    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];
    
    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the related model
     */
    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }
    
    /**
     * Create log entry
     */
    public static function log($aksi, $deskripsi, $model = null, $dataLama = null, $dataBaru = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'deskripsi' => $deskripsi,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
