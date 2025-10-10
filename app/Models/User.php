<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'jabatan',
        'unit_kerja_id',
        'phone',
        'address',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    
    /**
     * Relationship with UnitKerja
     */
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }
    
    /**
     * Relationship with Arsip (created)
     */
    public function arsipCreated()
    {
        return $this->hasMany(Arsip::class, 'created_by');
    }
    
    /**
     * Relationship with LogAktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user is operator
     */
    public function isOperator()
    {
        return $this->role === 'operator';
    }
    
    /**
     * Check if user is viewer
     */
    public function isViewer()
    {
        return $this->role === 'viewer';
    }
}
