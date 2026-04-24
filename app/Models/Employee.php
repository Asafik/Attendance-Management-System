<?php

namespace App\Models;

// 1. TAMBAHKAN IMPORT INI
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// 2. GANTI 'Model' MENJADI 'Authenticatable'
class Employee extends Authenticatable
{
    // 3. TAMBAHKAN HasApiTokens dan Notifiable
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'division_id',
        'position_id',
        'bank_id',
        'username',       // ✅ Tambahkan username untuk login
        'password',       // ✅ Tambahkan password untuk login
        'name',
        'phone',
        'account_number',
        'photo',
        'status',
        'regular_off_day',
    ];

    // 4. SEMBUNYIKAN PASSWORD SAAT JADI JSON API
    protected $hidden = [
        'password',
    ];

    /**
     * Relasi ke Division
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relasi ke Position
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Relasi ke Bank
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Relasi ke Attendances (Absensi)
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * ===== HELPER FUNCTION UNTUK LIBUR TETAP =====
     */

    public function isRegularOffDay($dayName)
    {
        return $this->regular_off_day === $dayName;
    }

    public function countRegularOffDaysInMonth($year, $month)
    {
        if (!$this->regular_off_day || $this->regular_off_day === 'Tidak Libur') {
            return 0;
        }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $totalOffDays = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = \Carbon\Carbon::create($year, $month, $day);
            $dayName = $this->getIndonesianDayName($date->dayOfWeekIso);

            if ($dayName === $this->regular_off_day) {
                $totalOffDays++;
            }
        }

        return $totalOffDays;
    }

    public function getRegularOffDayTextAttribute()
    {
        if (!$this->regular_off_day) {
            return 'Belum diatur';
        }

        if ($this->regular_off_day === 'Tidak Libur') {
            return 'Full Kerja (Tidak Ada Libur)';
        }

        return 'Libur setiap ' . $this->regular_off_day;
    }

    private function getIndonesianDayName($dayNumber)
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        return $days[$dayNumber] ?? '';
    }

    /**
     * Accessor untuk URL foto
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : null;
    }
}
