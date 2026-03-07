<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'bank_id',
        'name',
        'phone',
        'account_number',
        'photo',
        'status',
        'regular_off_day', // ✅ TAMBAHKAN INI
    ];

    /**
     * Relasi ke Division
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
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

    /**
     * Cek apakah hari ini adalah libur tetap
     * @param string $dayName (Senin, Selasa, dll)
     */
    public function isRegularOffDay($dayName)
    {
        return $this->regular_off_day === $dayName;
    }

    /**
     * Hitung jumlah hari libur tetap dalam 1 bulan
     * @param int $year
     * @param int $month
     */
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

    /**
     * Dapatkan teks hari libur tetap untuk ditampilkan
     */
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

    /**
     * Helper untuk konversi angka hari ke nama hari Indonesia
     * 1 = Senin, 2 = Selasa, ..., 7 = Minggu
     */
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
