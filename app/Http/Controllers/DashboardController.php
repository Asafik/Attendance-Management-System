<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Division;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ===== FILTER BULAN (default bulan ini) =====
        $selectedMonth = $request->month ?? now()->format('Y-m');
        $selectedYear = Carbon::parse($selectedMonth)->year;
        $selectedMonthNumber = Carbon::parse($selectedMonth)->month;
        $selectedMonthName = Carbon::parse($selectedMonth)->format('F Y');

        // ===== DATA UNTUK FILTER (pilihan bulan) =====
        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths($i);
            $months[$date->format('Y-m')] = $date->format('F Y');
        }

        // ===== DATA KARYAWAN =====
        $totalKaryawan = Employee::count();
        $karyawanAktif = Employee::where('status', 'Aktif')->count();
        $karyawanNonaktif = Employee::where('status', 'Nonaktif')->count();

        // ===== DATA HARI INI =====
        $today = now()->format('Y-m-d');

        // Hitung yang tidak hadir (Izin + Alpha)
        $tidakHadirHariIni = Attendance::whereDate('date', $today)
                                ->whereIn('status', ['Izin', 'Alpha'])
                                ->count();

        // Hadir = Total Karyawan Aktif - (Izin + Alpha)
        $totalHadirHariIni = $karyawanAktif - $tidakHadirHariIni;
        $persentaseHadir = $karyawanAktif > 0
            ? round(($totalHadirHariIni / $karyawanAktif) * 100)
            : 0;

        // Detail ketidakhadiran
        $izinHariIni = Attendance::whereDate('date', $today)
                        ->where('status', 'Izin')
                        ->count();

        $alphaHariIni = Attendance::whereDate('date', $today)
                        ->where('status', 'Alpha')
                        ->count();

        $liburHariIni = Attendance::whereDate('date', $today)
                        ->where('status', 'Libur')
                        ->where('note', 'LIKE', '%Libur tetap%')
                        ->count();

        $wfhHariIni = Attendance::whereDate('date', $today)
                      ->where('status', 'WFH')
                      ->count();

        // ===== DATA UNTUK GRAFIK (PER MINGGU DALAM BULAN INI) =====
        $weeklyData = $this->getWeeklyAttendance($selectedYear, $selectedMonthNumber);

        // ===== STATISTIK PER DIVISI (BULAN INI) =====
        $divisions = Division::withCount('employees')->get();
        $divisionStats = [];

        foreach ($divisions as $division) {
            // Hitung total karyawan aktif di divisi ini
            $totalKaryawanDivisi = Employee::where('division_id', $division->id)
                                    ->where('status', 'Aktif')
                                    ->count();

            if ($totalKaryawanDivisi == 0) {
                continue;
            }

            // Hitung total hari dalam bulan
            $totalHariBulan = Carbon::create($selectedYear, $selectedMonthNumber)->daysInMonth;

            // Hitung ketidakhadiran (Izin + Alpha) di bulan ini
            $tidakHadirBulanIni = Attendance::whereYear('date', $selectedYear)
                                    ->whereMonth('date', $selectedMonthNumber)
                                    ->whereIn('status', ['Izin', 'Alpha'])
                                    ->whereHas('employee', function($q) use ($division) {
                                        $q->where('division_id', $division->id);
                                    })
                                    ->count();

            // Total potensi kehadiran = jumlah karyawan * jumlah hari
            $totalPotensiKehadiran = $totalKaryawanDivisi * $totalHariBulan;

            // Total kehadiran aktual = potensi - ketidakhadiran
            $totalHadirBulanIni = $totalPotensiKehadiran - $tidakHadirBulanIni;

            // Persentase kehadiran
            $persentase = $totalPotensiKehadiran > 0
                ? round(($totalHadirBulanIni / $totalPotensiKehadiran) * 100)
                : 0;

            // Detail ketidakhadiran di bulan ini
            $izinBulanIni = Attendance::whereYear('date', $selectedYear)
                            ->whereMonth('date', $selectedMonthNumber)
                            ->where('status', 'Izin')
                            ->whereHas('employee', function($q) use ($division) {
                                $q->where('division_id', $division->id);
                            })
                            ->count();

            $alphaBulanIni = Attendance::whereYear('date', $selectedYear)
                            ->whereMonth('date', $selectedMonthNumber)
                            ->where('status', 'Alpha')
                            ->whereHas('employee', function($q) use ($division) {
                                $q->where('division_id', $division->id);
                            })
                            ->count();

            $wfhBulanIni = Attendance::whereYear('date', $selectedYear)
                            ->whereMonth('date', $selectedMonthNumber)
                            ->where('status', 'WFH')
                            ->whereHas('employee', function($q) use ($division) {
                                $q->where('division_id', $division->id);
                            })
                            ->count();

            $divisionStats[] = [
                'name' => $division->name,
                'total_karyawan' => $totalKaryawanDivisi,
                'total_hari' => $totalHariBulan,
                'hadir' => $totalHadirBulanIni,
                'tidak_hadir' => $tidakHadirBulanIni,
                'persentase' => $persentase,
                'izin' => $izinBulanIni,
                'alpha' => $alphaBulanIni,
                'wfh' => $wfhBulanIni,
            ];
        }

        // Urutkan berdasarkan persentase tertinggi
        usort($divisionStats, function($a, $b) {
            return $b['persentase'] <=> $a['persentase'];
        });

        // ===== AKTIVITAS TERBARU =====
        $recentActivities = Attendance::with('employee.division')
                            ->where(function($q) {
                                $q->where('note', 'NOT LIKE', '%Libur tetap%')
                                  ->orWhereNull('note');
                            })
                            ->whereIn('status', ['WFH', 'Izin', 'Alpha', 'Libur'])
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(function($attendance) {
                                $date = Carbon::parse($attendance->date);
                                return [
                                    'name' => $attendance->employee->name,
                                    'division' => $attendance->employee->division->name ?? 'Tanpa Divisi',
                                    'status' => $attendance->status,
                                    'time' => $attendance->created_at->format('H:i'),
                                    'date_formatted' => $date->format('d M Y'),
                                    'initials' => $this->getInitials($attendance->employee->name)
                                ];
                            });

        return view('admin.dashboard', compact(
            'totalKaryawan',
            'karyawanAktif',
            'karyawanNonaktif',
            'totalHadirHariIni',
            'persentaseHadir',
            'izinHariIni',
            'alphaHariIni',
            'liburHariIni',
            'wfhHariIni',
            'weeklyData',
            'divisionStats',
            'recentActivities',
            'months',
            'selectedMonth',
            'selectedMonthName'
        ));
    }

    /**
     * Ambil data kehadiran per minggu dalam bulan tertentu
     */
    private function getWeeklyAttendance($year, $month)
    {
        $weeks = [];
        $wfh = [];
        $izin = [];
        $alpha = [];

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = Carbon::create($year, $month, $startOfMonth->daysInMonth);

        $currentWeek = 1;
        $weekStart = $startOfMonth->copy();

        while ($weekStart <= $endOfMonth) {
            $weekEnd = $weekStart->copy()->addDays(6);
            if ($weekEnd > $endOfMonth) {
                $weekEnd = $endOfMonth->copy();
            }

            $wfhCount = Attendance::whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                          ->where('status', 'WFH')
                          ->count();

            $izinCount = Attendance::whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                         ->where('status', 'Izin')
                         ->count();

            $alphaCount = Attendance::whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                          ->where('status', 'Alpha')
                          ->count();

            $weeks[] = "Minggu " . $currentWeek;
            $wfh[] = $wfhCount;
            $izin[] = $izinCount;
            $alpha[] = $alphaCount;

            $currentWeek++;
            $weekStart = $weekStart->copy()->addDays(7);
        }

        return [
            'weeks' => $weeks,
            'wfh' => $wfh,
            'izin' => $izin,
            'alpha' => $alpha
        ];
    }

    /**
     * Ambil inisial dari nama
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }
}
