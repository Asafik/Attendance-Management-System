<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RekapBulananExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $employees;
    protected $month;
    protected $year;
    protected $monthName;
    protected $totalDaysInMonth;

    public function __construct($employees, $month)
    {
        $this->employees = $employees;
        $this->month = $month;
        $this->year = Carbon::parse($month)->year;
        $this->monthNumber = Carbon::parse($month)->month;
        $this->monthName = Carbon::parse($month)->format('F Y');
        $this->totalDaysInMonth = Carbon::create($this->year, $this->monthNumber)->daysInMonth;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->employees;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            ['REKAP ABSENSI ' . strtoupper($this->monthName)],
            ['Total Hari Kerja: ' . $this->totalDaysInMonth . ' hari'],
            [],
            [
                'No',
                'Nama Karyawan',
                'Divisi',
                'Hadir',
                'WFH',
                'Izin',
                'Alpha',
                'Libur Tetap',
                'Libur Tambahan',
            ]
        ];
    }

    /**
    * @param mixed $employee
    */
    public function map($employee): array
    {
        static $no = 0;
        $no++;

        // Hitung status
        $wfh = $employee->attendances->where('status', 'WFH')->count();
        $izin = $employee->attendances->where('status', 'Izin')->count();
        $alpha = $employee->attendances->where('status', 'Alpha')->count();

        // Pisahkan libur tetap dan libur tambahan
        $liburTetap = $employee->attendances
            ->where('status', 'Libur')
            ->filter(function($att) {
                return str_contains($att->note, 'Libur tetap');
            })->count();

        $liburTambahan = $employee->attendances
            ->where('status', 'Libur')
            ->filter(function($att) {
                return !str_contains($att->note, 'Libur tetap');
            })->count();

        // Hitung total tidak hadir dan hadir
        $totalTidakHadir = $izin + $alpha + $liburTetap + $liburTambahan;
        $hadir = $this->totalDaysInMonth - $totalTidakHadir;

        return [
            $no,
            $employee->name,
            $employee->division->name ?? '-',
            $hadir,
            $wfh,
            $izin,
            $alpha,
            $liburTetap,
            $liburTambahan,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Merge cell untuk judul
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');

        // Style judul
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Style sub judul
        $sheet->getStyle('A2:I2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Style header tabel
        $sheet->getStyle('A4:I4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '16A34A'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F5E9'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Style seluruh tabel
        $sheet->getStyle('A4:I' . ($this->employees->count() + 4))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Rata tengah untuk kolom angka
        $sheet->getStyle('D4:I' . ($this->employees->count() + 4))->getAlignment()
              ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama
            'C' => 15,  // Divisi
            'D' => 8,   // Hadir
            'E' => 8,   // WFH
            'F' => 8,   // Izin
            'G' => 8,   // Alpha
            'H' => 10,  // Libur Tetap
            'I' => 12,  // Libur Tambahan
        ];
    }
}
