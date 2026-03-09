<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Relasi
        'user_id',

        // Informasi aktivitas
        'action',
        'model',
        'model_id',
        'description',
        'status',

        // Informasi request
        'method',
        'url',
        'payload',

        // Informasi IP & Device
        'ip_address',
        'user_agent',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'device',
        'device_model',

        // Informasi Lokasi (dari IP)
        'city',
        'region',
        'country',
        'country_code',

        // Informasi Lokasi (dari GPS)
        'latitude',
        'longitude',
        'accuracy',
        'location_source',
        'full_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'integer',
    ];

    /**
     * Get the user that owns the activity log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (polymorphic).
     */
    public function relatable()
    {
        return $this->morphTo('model', 'model', 'model_id');
    }

    // ============================================
    // ===== SCOPES UNTUK FILTER =====
    // ============================================

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk filter login activity
     */
    public function scopeLogins($query)
    {
        return $query->where('action', 'login');
    }

    /**
     * Scope untuk filter logout activity
     */
    public function scopeLogouts($query)
    {
        return $query->where('action', 'logout');
    }

    /**
     * Scope untuk filter failed activity
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope untuk filter success activity
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope untuk filter kemarin
     */
    public function scopeYesterday($query)
    {
        return $query->whereDate('created_at', today()->subDay());
    }

    /**
     * Scope untuk filter minggu ini
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope untuk filter bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan IP
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', 'LIKE', "%{$ip}%");
    }

    /**
     * Scope untuk pencarian (search)
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('ip_address', 'LIKE', "%{$search}%")
              ->orWhere('browser', 'LIKE', "%{$search}%")
              ->orWhere('os', 'LIKE', "%{$search}%")
              ->orWhere('device', 'LIKE', "%{$search}%")
              ->orWhere('city', 'LIKE', "%{$search}%")
              ->orWhere('country', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhereHas('user', function($userQuery) use ($search) {
                  $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
              });
        });
    }

    // ============================================
    // ===== ACCESSORS & ATTRIBUTES =====
    // ============================================

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'success' => 'status-success',
            'failed'  => 'status-failed',
            'warning' => 'status-warning',
            default   => 'status-info',
        };
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'success' => 'bi-check-circle-fill',
            'failed'  => 'bi-x-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            default   => 'bi-info-circle-fill',
        };
    }

    /**
     * Get action icon
     */
    public function getActionIconAttribute()
    {
        return match($this->action) {
            'login'  => 'bi-box-arrow-in-right',
            'logout' => 'bi-box-arrow-left',
            'create' => 'bi-plus-circle',
            'update' => 'bi-pencil',
            'delete' => 'bi-trash',
            'view'   => 'bi-eye',
            default  => 'bi-clock-history',
        };
    }

    /**
     * Get flag dari country_code
     */
    public function getFlagAttribute()
    {
        if (!$this->country_code) {
            return '<span class="flag-icon">🌐</span>';
        }

        // Convert country code ke flag emoji
        $code = strtoupper($this->country_code);
        $flag = '';
        for ($i = 0; $i < strlen($code); $i++) {
            $flag .= mb_chr(127397 + ord($code[$i]));
        }
        return '<span class="flag-icon">' . $flag . '</span>';
    }

    /**
     * Get flag HTML dengan Bootstrap Icons (alternatif)
     */
    public function getFlagIconAttribute()
    {
        $countryIcons = [
            'ID' => 'bi-flag',
            'SG' => 'bi-flag',
            'MY' => 'bi-flag',
            'JP' => 'bi-flag',
            'KR' => 'bi-flag',
            'US' => 'bi-flag',
            'GB' => 'bi-flag',
        ];

        $icon = $countryIcons[strtoupper($this->country_code)] ?? 'bi-flag';
        return '<i class="bi ' . $icon . '"></i>';
    }

    /**
     * Format waktu display
     */
    public function getFormattedTimeAttribute()
    {
        return [
            'main' => $this->created_at->format('H:i:s'),
            'sub' => $this->created_at->format('d M Y'),
            'full' => $this->created_at->format('d M Y H:i:s'),
            'diff' => $this->created_at->diffForHumans(),
            'date' => $this->created_at->format('Y-m-d'),
            'time' => $this->created_at->format('H:i:s'),
        ];
    }

    /**
     * Get device display
     */
    public function getDeviceDisplayAttribute()
    {
        return [
            'browser' => trim($this->browser . ' ' . $this->browser_version),
            'os' => trim($this->os . ' ' . $this->os_version),
            'type' => ucfirst($this->device ?? 'unknown'),
            'full' => trim($this->browser . ' ' . $this->browser_version) . ' on ' .
                      trim($this->os . ' ' . $this->os_version),
        ];
    }

    /**
     * Get location display
     */
    public function getLocationDisplayAttribute()
    {
        $parts = [];
        if ($this->city) $parts[] = $this->city;
        if ($this->region) $parts[] = $this->region;
        if ($this->country) $parts[] = $this->country;

        return [
            'city' => $this->city ?? '-',
            'region' => $this->region ?? '-',
            'country' => $this->country ?? '-',
            'full' => implode(', ', $parts) ?: '-',
            'coordinates' => ($this->latitude && $this->longitude)
                ? $this->latitude . ', ' . $this->longitude
                : '-',
        ];
    }

    /**
     * Get GPS location if available
     */
    public function getGpsLocationAttribute()
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'accuracy' => $this->accuracy,
            'source' => $this->location_source,
            'address' => $this->full_address,
        ];
    }

    /**
     * Check if has GPS location
     */
    public function getHasGpsAttribute()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get activity title for display
     */
    public function getActivityTitleAttribute()
    {
        $actionTitles = [
            'login' => 'Login',
            'logout' => 'Logout',
            'create' => 'Menambahkan',
            'update' => 'Mengubah',
            'delete' => 'Menghapus',
            'view' => 'Melihat',
        ];

        $actionText = $actionTitles[$this->action] ?? ucfirst($this->action);

        if ($this->model && $this->action !== 'login' && $this->action !== 'logout') {
            $modelName = class_basename($this->model);
            return $actionText . ' ' . $modelName;
        }

        return $actionText;
    }

    // ============================================
    // ===== HELPER METHODS =====
    // ============================================

    /**
     * Log activity (static method untuk memudahkan pencatatan)
     */
    public static function log($userId, $action, $data = [])
    {
        return self::create(array_merge([
            'user_id' => $userId,
            'action' => $action,
            'created_at' => now(),
        ], $data));
    }

    /**
     * Log login success
     */
    public static function logLogin($user, $request, $status = 'success')
    {
        return self::create([
            'user_id' => $user->id,
            'action' => 'login',
            'status' => $status,
            'description' => $status === 'success' ? 'Login berhasil' : 'Login gagal',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => self::getBrowser($request->userAgent()),
            'os' => self::getOS($request->userAgent()),
            'device' => self::getDevice($request->userAgent()),
        ]);
    }

    /**
     * Log logout
     */
    public static function logLogout($user, $request)
    {
        return self::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'status' => 'success',
            'description' => 'Logout berhasil',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => self::getBrowser($request->userAgent()),
            'os' => self::getOS($request->userAgent()),
            'device' => self::getDevice($request->userAgent()),
        ]);
    }

    // ============================================
    // ===== STATIC HELPER FUNCTIONS =====
    // ============================================

    /**
     * Get browser from user agent
     */
    public static function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        return 'Unknown';
    }

    /**
     * Get OS from user agent
     */
    public static function getOS($userAgent)
    {
        if (strpos($userAgent, 'Windows NT 10.0') !== false) return 'Windows 10';
        if (strpos($userAgent, 'Windows NT 11.0') !== false) return 'Windows 11';
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac OS X') !== false) {
            if (preg_match('/Mac OS X (\d+[._]\d+)/', $userAgent, $matches)) {
                return 'macOS ' . str_replace('_', '.', $matches[1]);
            }
            return 'macOS';
        }
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) {
            if (preg_match('/Android (\d+(\.\d+)?)/', $userAgent, $matches)) {
                return 'Android ' . $matches[1];
            }
            return 'Android';
        }
        if (preg_match('/(iPhone|iPad).*OS (\d+[._]\d+)/', $userAgent, $matches)) {
            return 'iOS ' . str_replace('_', '.', $matches[2]);
        }
        if (strpos($userAgent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }

    /**
     * Get device from user agent
     */
    public static function getDevice($userAgent)
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            return 'tablet';
        }
        if (preg_match('/(mobile|iphone|ipod|android|blackberry)/i', $userAgent)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
