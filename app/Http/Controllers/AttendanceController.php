<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Halaman utama absensi
     */
    public function index()
    {
        $employees = Employee::all();

        $month = now()->month;
        $year  = now()->year;

        // Statistik bulan ini (selain Hadir)
        $totalWFH   = Attendance::whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->where('status', 'WFH')
                        ->count();

        $totalIzin  = Attendance::whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->where('status', 'Izin')
                        ->count();

        $totalAlpha = Attendance::whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->where('status', 'Alpha')
                        ->count();

        $totalLibur = Attendance::whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->where('status', 'Libur')
                        ->count();

        return view('admin.attendance', compact(
            'employees',
            'totalWFH',
            'totalIzin',
            'totalAlpha',
            'totalLibur'
        ));
    }


    /**
     * JSON data untuk FullCalendar
     * Mode global & per karyawan
     */
    public function events(Request $request)
    {
        $employeeId = $request->employee_id;

        $query = Attendance::with('employee');

        if ($employeeId) {
            // 🔥 FILTER PER KARYAWAN - TAMPILKAN SEMUA (TERMASUK LIBUR TETAP)
            $query->where('employee_id', $employeeId);
        } else {
            // 🔥 FILTER GLOBAL - HANYA TAMPILKAN YANG BUKAN LIBUR TETAP
            $query->where(function($q) {
                $q->where('note', 'NOT LIKE', '%Libur tetap%')
                  ->orWhereNull('note');
            });
        }

        $attendances = $query->get();

        $events = [];

        foreach ($attendances as $attendance) {

            $color = match ($attendance->status) {
                'WFH'   => '#3b82f6', // Biru
                'Izin'  => '#f59e0b', // Kuning
                'Alpha' => '#ef4444', // Merah
                'Libur' => '#6b7280', // Abu-abu
                default => '#10b981', // Hijau (Hadir)
            };

            $title = $employeeId
                ? $attendance->status
                : $attendance->status . ' - ' . $attendance->employee->name;

            $events[] = [
                'title' => $title,
                'start' => $attendance->date,
                'color' => $color,
                'extendedProps' => [
                    'status' => $attendance->status,
                    'note' => $attendance->note,
                    'employee_name' => $attendance->employee->name,
                ],
            ];
        }

        return response()->json($events);
    }


    /**
     * Simpan / Update / Hapus status
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'status'      => 'required|in:Hadir,WFH,Izin,Alpha,Libur',
            'note'        => 'nullable|string|max:255',
        ]);

        // Jika status = Hadir → hapus record (kembali ke default)
        if ($request->status === 'Hadir') {

            Attendance::where('employee_id', $request->employee_id)
                ->where('date', $request->date)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Status kembali ke Hadir'
            ]);
        }

        // Insert / Update selain Hadir
        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date'        => $request->date,
            ],
            [
                'status' => $request->status,
                'note'   => $request->note,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil disimpan sebagai ' . $request->status,
            'data' => $attendance
        ]);
    }


    /**
     * Hapus data absensi (opsional)
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
        ]);

        Attendance::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dihapus'
        ]);
    }


    /**
     * Ambil detail absensi per tanggal (opsional, untuk modal)
     */
    public function getDetail(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
        ]);

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->first();

        if ($attendance) {
            return response()->json([
                'exists' => true,
                'status' => $attendance->status,
                'note' => $attendance->note,
            ]);
        }

        return response()->json([
            'exists' => false,
            'status' => 'Hadir',
            'note' => null,
        ]);
    }


    /**
     * Sinkronisasi libur tetap karyawan ke database absensi
     * Dipanggil saat memilih karyawan di halaman absensi
     */
    public function syncRegularOffDays(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // 🔥 CEK APAKAH SUDAH ADA DATA LIBUR TETAP SEBELUMNYA
        $existingCount = Attendance::where('employee_id', $employee->id)
            ->where('note', 'LIKE', '%Libur tetap%')
            ->count();

        // HAPUS DULU SEMUA LIBUR TETAP LAMA
        Attendance::where('employee_id', $employee->id)
            ->where('note', 'LIKE', '%Libur tetap%')
            ->delete();

        // Kalau tidak punya libur tetap atau pilih "Tidak Libur"
        if (!$employee->regular_off_day || $employee->regular_off_day === 'Tidak Libur') {
            return response()->json([
                'success' => true,
                'message' => 'Karyawan tidak punya libur tetap',
                'inserted' => 0,
                'deleted' => true,
                'off_day' => null,
                'existing' => false
            ]);
        }

        $year = $request->year;
        $month = $request->month;
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        // Map hari ke angka (1 = Senin, 7 = Minggu) - Carbon dayOfWeekIso
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
        $inserted = 0;

        // INSERT LIBUR TETAP BARU
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);

            // Cek apakah hari ini adalah hari libur tetap
            if ($date->dayOfWeekIso == $targetDayNumber) {
                $dateStr = $date->format('Y-m-d');

                // INSERT LANGSUNG (tanpa cek exists karena sudah dihapus semua)
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $dateStr,
                    'status' => 'Libur',
                    'note' => 'Libur tetap otomatis - ' . $employee->regular_off_day
                ]);
                $inserted++;
            }
        }

        // 🔥 KIRIM INFO APAKAH SEBELUMNYA SUDAH ADA DATA
        return response()->json([
            'success' => true,
            'message' => "Berhasil sinkronisasi libur tetap",
            'inserted' => $inserted,
            'employee' => $employee->name,
            'off_day' => $employee->regular_off_day,
            'deleted' => false,
            'existing' => $existingCount > 0 // TRUE jika sebelumnya sudah ada data
        ]);
    }
}
