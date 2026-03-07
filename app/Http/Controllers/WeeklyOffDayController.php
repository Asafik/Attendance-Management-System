<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WeeklyOffDay;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WeeklyOffDayController extends Controller
{
    /**
     * Menampilkan halaman atur libur mingguan
     */
    public function index(Request $request)
    {
        // Ambil semua karyawan untuk dropdown
        $employees = Employee::with('division')->get();

        // Ambil parameter dari request
        $employeeId = $request->employee_id;
        $weekStart = $request->week_start ? Carbon::parse($request->week_start) : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $request->week_end ? Carbon::parse($request->week_end) : Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Format untuk tampilan
        $weekStartFormatted = $weekStart->format('Y-m-d');
        $weekEndFormatted = $weekEnd->format('Y-m-d');
        $weekDisplay = $weekStart->format('d M Y') . ' - ' . $weekEnd->format('d M Y');

        // Ambil data libur untuk karyawan terpilih (jika ada)
        $selectedEmployee = null;
        $offDays = [];

        if ($employeeId) {
            $selectedEmployee = Employee::with('division')->find($employeeId);

            if ($selectedEmployee) {
                $offDays = $selectedEmployee->weeklyOffDays()
                    ->where('week_start', $weekStartFormatted)
                    ->where('week_end', $weekEndFormatted)
                    ->pluck('day')
                    ->toArray();
            }
        }

        // Ambil riwayat libur untuk karyawan terpilih
        $history = [];
        if ($employeeId) {
            $history = WeeklyOffDay::where('employee_id', $employeeId)
                ->orderBy('week_start', 'desc')
                ->get()
                ->groupBy(function($item) {
                    return $item->week_start->format('Y-m-d') . '|' . $item->week_end->format('Y-m-d');
                });
        }

        return view('admin.weekly-off-days', compact(
            'employees',
            'employeeId',
            'weekStartFormatted',
            'weekEndFormatted',
            'weekDisplay',
            'selectedEmployee',
            'offDays',
            'history'
        ));
    }

    /**
     * Menyimpan data libur mingguan
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'week_start' => 'required|date',
            'week_end' => 'required|date|after_or_equal:week_start',
            'off_days' => 'nullable|array',
            'off_days.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // Hapus data lama untuk minggu yang sama
        WeeklyOffDay::where('employee_id', $request->employee_id)
            ->where('week_start', $request->week_start)
            ->where('week_end', $request->week_end)
            ->delete();

        // Simpan data baru
        if ($request->has('off_days') && !empty($request->off_days)) {
            foreach ($request->off_days as $day) {
                WeeklyOffDay::create([
                    'employee_id' => $request->employee_id,
                    'day' => $day,
                    'week_start' => $request->week_start,
                    'week_end' => $request->week_end,
                ]);
            }

            $message = 'Libur mingguan berhasil disimpan.';
        } else {
            $message = 'Tidak ada hari libur untuk minggu ini.';
        }

        return redirect()
            ->route('weekly-off-days.index', [
                'employee_id' => $request->employee_id,
                'week_start' => $request->week_start,
            ])
            ->with('success', $message);
    }

    /**
     * Menghapus data libur untuk minggu tertentu
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'week_start' => 'required|date',
            'week_end' => 'required|date',
        ]);

        WeeklyOffDay::where('employee_id', $request->employee_id)
            ->where('week_start', $request->week_start)
            ->where('week_end', $request->week_end)
            ->delete();

        return redirect()
            ->route('weekly-off-days.index', [
                'employee_id' => $request->employee_id,
            ])
            ->with('success', 'Data libur mingguan berhasil dihapus.');
    }

    /**
     * API: Ambil data libur untuk karyawan di minggu tertentu
     * (untuk AJAX)
     */
    public function getWeekData(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'week_start' => 'required|date',
            'week_end' => 'required|date',
        ]);

        $offDays = WeeklyOffDay::where('employee_id', $request->employee_id)
            ->where('week_start', $request->week_start)
            ->where('week_end', $request->week_end)
            ->pluck('day')
            ->toArray();

        return response()->json([
            'success' => true,
            'off_days' => $offDays,
        ]);
    }

    /**
     * Duplikasi libur dari minggu sebelumnya
     */
    public function copyFromPreviousWeek(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'week_start' => 'required|date',
            'week_end' => 'required|date',
            'prev_week_start' => 'required|date',
            'prev_week_end' => 'required|date',
        ]);

        // Ambil data minggu sebelumnya
        $prevOffDays = WeeklyOffDay::where('employee_id', $request->employee_id)
            ->where('week_start', $request->prev_week_start)
            ->where('week_end', $request->prev_week_end)
            ->pluck('day')
            ->toArray();

        // Hapus data minggu ini
        WeeklyOffDay::where('employee_id', $request->employee_id)
            ->where('week_start', $request->week_start)
            ->where('week_end', $request->week_end)
            ->delete();

        // Simpan data baru
        foreach ($prevOffDays as $day) {
            WeeklyOffDay::create([
                'employee_id' => $request->employee_id,
                'day' => $day,
                'week_start' => $request->week_start,
                'week_end' => $request->week_end,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Libur mingguan berhasil disalin dari minggu sebelumnya',
            'off_days' => $prevOffDays,
        ]);
    }
}
