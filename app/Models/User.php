<?php
// app/Models/User.php

namespace App\Models;

// 1. IMPORT SANCTUM DI SINI
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // 2. PASANG HasApiTokens DI SINI AGAR BISA BIKIN TOKEN
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        // Field session (1 akun 1 device)
        'session_id',
        'last_login_at',
        'last_ip',
        'last_user_agent',
        // ===== TAMBAHAN UNTUK LOKASI =====
        'location_permission', // 'not_set', 'allowed', 'blocked'
        'last_latitude',
        'last_longitude',
        'last_accuracy',
        'last_location_at',
        // =================================
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        // ===== TAMBAHAN UNTUK LOKASI =====
        'last_latitude' => 'decimal:8',
        'last_longitude' => 'decimal:8',
        'last_accuracy' => 'integer',
        'last_location_at' => 'datetime',
        // =================================
    ];

    /**
     * Relasi ke Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi ke Activity Logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Cek apakah user aktif
     */
    public function isActive()
    {
        return $this->is_active === true;
    }

    /**
     * Update session info saat login
     */
    public function updateSessionInfo($request)
    {
        $this->update([
            'session_id' => session()->getId(),
            'last_login_at' => now(),
            'last_ip' => $request->ip(),
            'last_user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Cek apakah ini session terbaru
     */
    public function isCurrentSession()
    {
        return $this->session_id === session()->getId();
    }

    // ===== METHOD BARU UNTUK LOKASI =====

    /**
     * Cek apakah user sudah pernah memberikan izin lokasi
     */
    public function hasLocationPermission()
    {
        return $this->location_permission === 'allowed';
    }

    /**
     * Cek apakah user pernah menolak izin lokasi
     */
    public function hasBlockedLocation()
    {
        return $this->location_permission === 'blocked';
    }

    /**
     * Update status izin lokasi
     */
    public function updateLocationPermission($status)
    {
        $this->update(['location_permission' => $status]);
    }

    /**
     * Update lokasi terakhir user
     */
    public function updateLastLocation($latitude, $longitude, $accuracy = null)
    {
        $this->update([
            'last_latitude' => $latitude,
            'last_longitude' => $longitude,
            'last_accuracy' => $accuracy,
            'last_location_at' => now(),
        ]);
    }

    /**
     * Dapatkan lokasi terakhir dalam format array
     */
    public function getLastLocationAttribute()
    {
        if (!$this->last_latitude || !$this->last_longitude) {
            return null;
        }

        return [
            'latitude' => $this->last_latitude,
            'longitude' => $this->last_longitude,
            'accuracy' => $this->last_accuracy,
            'time' => $this->last_location_at,
        ];
    }

    /**
     * Dapatkan status izin dalam format teks yang user-friendly
     */
    public function getLocationPermissionTextAttribute()
    {
        return match($this->location_permission) {
            'allowed' => 'Mengizinkan',
            'blocked' => 'Menolak',
            default => 'Belum Ditentukan',
        };
    }
}
