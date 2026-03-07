<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyOffDay extends Model
{
    use HasFactory;

    protected $table = 'weekly_off_days'; // Nama tabel baru

    protected $fillable = [
        'employee_id',
        'day',
        'week_start',
        'week_end',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
    ];

    // Relasi ke employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scope untuk cari berdasarkan minggu
    public function scopeForWeek($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->where('week_start', $startDate)
                         ->where('week_end', $endDate);
        }

        return $query->where('week_start', $startDate);
    }

    // Scope untuk cari berdasarkan karyawan dan minggu
    public function scopeForEmployeeWeek($query, $employeeId, $startDate, $endDate)
    {
        return $query->where('employee_id', $employeeId)
                     ->where('week_start', $startDate)
                     ->where('week_end', $endDate);
    }

    // Helper untuk ambil daftar hari libur karyawan di minggu tertentu
    public static function getOffDaysForWeek($employeeId, $startDate, $endDate)
    {
        return self::where('employee_id', $employeeId)
                   ->where('week_start', $startDate)
                   ->where('week_end', $endDate)
                   ->pluck('day')
                   ->toArray();
    }
}
