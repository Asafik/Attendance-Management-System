<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Exports\RekapBulananExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportAttendanceController extends Controller
{
    /**
     * Menampilkan halaman laporan absensi
     */
    public function index(Request $request)
    {
        // Ambil bulan dari filter atau default bulan sekarang
        $month = $request->month ?? now()->format('Y-m');

        $year = Carbon::parse($month)->year;
        $monthNumber = Carbon::parse($month)->month;

        // Hitung total hari dalam bulan
        $totalDaysInMonth = Carbon::create($year, $monthNumber)->daysInMonth;

        // Ambil employee + attendance di bulan tersebut
        $employees = Employee::with(['attendances' => function ($query) use ($year, $monthNumber) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $monthNumber);
        }, 'division'])->get();

        return view('admin.reportAttendance', compact(
            'employees',
            'month',
            'totalDaysInMonth'
        ));
    }

    /**
     * Export rekap absensi ke Excel
     */
    public function export(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $year = Carbon::parse($month)->year;
        $monthNumber = Carbon::parse($month)->month;

        $employees = Employee::with(['attendances' => function ($query) use ($year, $monthNumber) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $monthNumber);
        }, 'division'])->get();

        $fileName = 'rekap_absensi_' . str_replace('-', '_', $month) . '.xlsx';

        return Excel::download(new RekapBulananExport($employees, $month), $fileName);
    }
}
