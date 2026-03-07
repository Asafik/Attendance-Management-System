<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua karyawan
        $employees = Employee::all();

        // Tentukan bulan dan tahun (bulan saat ini)
        $year = now()->year;
        $month = now()->month;

        // Hitung jumlah hari dalam bulan
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        foreach ($employees as $employee) {

            // Hanya insert libur tetap jika karyawan punya regular_off_day
            if ($employee->regular_off_day && $employee->regular_off_day !== 'Tidak Libur') {

                // Map hari ke angka (1 = Senin, 7 = Minggu)
                $dayMap = [
                    'Senin' => 1,
                    'Selasa' => 2,
                    'Rabu' => 3,
                    'Kamis' => 4,
                    'Jumat' => 5,
                    'Sabtu' => 6,
                    'Minggu' => 7,
                ];

                $targetDayNumber = $dayMap[$employee->regular_off_day];

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($year, $month, $day);

                    // Cek apakah hari ini adalah hari libur tetap
                    if ($date->dayOfWeekIso == $targetDayNumber) {

                        // Cek apakah sudah ada data di tanggal ini
                        $exists = Attendance::where('employee_id', $employee->id)
                            ->where('date', $date->format('Y-m-d'))
                            ->exists();

                        // Jika belum ada, insert
                        if (!$exists) {
                            Attendance::create([
                                'employee_id' => $employee->id,
                                'date' => $date->format('Y-m-d'),
                                'status' => 'Libur',
                                'note' => 'Libur tetap otomatis - ' . $employee->regular_off_day
                            ]);
                        }
                    }
                }
            }
        }
    }
}
