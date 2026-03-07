@extends('layouts.partials.app')

@section('title', 'Rekap Bulanan - Wadul Guse')
@section('page-title', 'Rekap Bulanan')

@push('styles')
<style>
    /* ===== CONTENT AREA ===== */
    .content-area {
        padding: 24px;
        min-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
    }

    .content-wrapper {
        flex: 1;
    }

    /* ===== FILTER SECTION ===== */
    .filter-section {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 15px;
    }

    .filter-title i {
        color: var(--accent-color);
        margin-right: 8px;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 250px;
    }

    .filter-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
    }

    .filter-label i {
        color: var(--accent-color);
        margin-right: 6px;
    }

    .filter-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
    }

    .filter-input-month {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .filter-input-month:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
    }

    .filter-btn {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 48px;
    }

    .filter-btn:hover {
        background: #ff4d88;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 103, 154, 0.3);
    }

    .filter-btn.export {
        background: #10b981;
    }

    .filter-btn.export:hover {
        background: #0e9f6e;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* ===== CARD TABLE ===== */
    .table-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .table-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .table-title i {
        color: var(--accent-color);
        margin-right: 8px;
    }

    .table-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* ===== TABLE ===== */
    .table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-primary);
        border-collapse: collapse;
        background-color: transparent;
    }

    .table thead th {
        background-color: var(--accent-soft);
        color: var(--accent-color);
        border-bottom: 2px solid var(--accent-color);
        padding: 12px 8px;
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 12px 8px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 14px;
        background-color: transparent;
        vertical-align: middle;
    }

    .table tbody tr:hover td {
        background-color: var(--accent-soft);
    }

    /* ===== ICON PINK DI THEAD ===== */
    .table thead th i {
        color: var(--accent-color);
    }

    /* ===== BADGE PERSENTASE ===== */
    .badge-persen {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        white-space: nowrap;
        text-align: center;
    }

    .badge-bagus {
        background-color: #10b981;
        color: white;
    }

    .badge-cukup {
        background-color: #f59e0b;
        color: white;
    }

    .badge-kurang {
        background-color: #ef4444;
        color: white;
    }

    /* ===== SUMMARY CARD ===== */
    .summary-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 15px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--accent-soft);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .summary-content {
        flex: 1;
    }

    .summary-label {
        color: var(--text-secondary);
        font-size: 12px;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-actions {
            flex-direction: column;
        }

        .table thead th {
            font-size: 12px;
            padding: 8px 4px;
        }

        .table tbody td {
            font-size: 12px;
            padding: 8px 4px;
        }
    }
</style>
@endpush

@section('content')
<div class="wrapper">
    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content" id="mainContent">
        {{-- NAVBAR --}}
        @include('layouts.navbar')

        {{-- CONTENT AREA --}}
        <div class="content-area" id="contentArea">
            <div class="content-wrapper">
                {{-- FILTER SECTION --}}
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="bi bi-funnel"></i> Filter Rekap Bulanan
                    </div>
                    <div class="filter-row">
                        {{-- PILIH BULAN --}}
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-calendar-month"></i> Pilih Bulan
                            </label>
                            <select class="filter-input-month" id="bulanFilter">
                                <option value="2026-01">Januari 2026</option>
                                <option value="2026-02">Februari 2026</option>
                                <option value="2026-03" selected>Maret 2026</option>
                                <option value="2026-04">April 2026</option>
                                <option value="2026-05">Mei 2026</option>
                                <option value="2026-06">Juni 2026</option>
                                <option value="2026-07">Juli 2026</option>
                                <option value="2026-08">Agustus 2026</option>
                                <option value="2026-09">September 2026</option>
                                <option value="2026-10">Oktober 2026</option>
                                <option value="2026-11">November 2026</option>
                                <option value="2026-12">Desember 2026</option>
                            </select>
                        </div>

                        {{-- TOMBOL FILTER --}}
                        <div class="filter-group" style="flex: 0 0 auto;">
                            <button class="filter-btn" onclick="terapkanFilter()">
                                <i class="bi bi-funnel"></i> Terapkan
                            </button>
                        </div>

                        {{-- TOMBOL EXPORT --}}
                        <div class="filter-group" style="flex: 0 0 auto;">
                            <button class="filter-btn export" onclick="exportExcel()">
                                <i class="bi bi-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                {{-- SUMMARY CARDS --}}
                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Karyawan</div>
                                <div class="summary-value" id="totalKaryawan">24</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Hari Kerja</div>
                                <div class="summary-value" id="totalHariKerja">22 Hari</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Rata-rata Kehadiran</div>
                                <div class="summary-value" id="rataKehadiran">89.5%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Alpha</div>
                                <div class="summary-value" id="totalAlpha">12</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">
                            <i class="bi bi-table"></i> Rekap Absensi <span id="bulanText">Maret 2026</span>
                        </h3>
                    </div>

                    {{-- TABEL REKAP --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>No</th>
                                    <th><i class="bi bi-person me-1"></i>Nama</th>
                                    <th><i class="bi bi-building me-1"></i>Divisi</th>
                                    <th><i class="bi bi-check-circle me-1"></i>Hadir</th>
                                    <th><i class="bi bi-file-text me-1"></i>Izin</th>
                                    <th><i class="bi bi-x-circle me-1"></i>Alpha</th>
                                    <th><i class="bi bi-exclamation-triangle me-1"></i>Terlambat</th>
                                    <th><i class="bi bi-pie-chart me-1"></i>Persentase</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                {{-- DATA AKAN DIISI VIA JAVASCRIPT --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- FOOTER --}}
        @include('layouts.footer')
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data rekap per bulan
    const dataRekap = {
        '2026-01': { // Januari 2026
            totalKaryawan: 24,
            totalHariKerja: '21 Hari',
            rataKehadiran: '87.2%',
            totalAlpha: 15,
            bulan: 'Januari 2026',
            karyawan: [
                { nama: 'Budi Santoso', divisi: 'IT', hadir: 18, izin: 2, alpha: 1, terlambat: 0, persen: 85.7, kategori: 'bagus' },
                { nama: 'Siti Rahma', divisi: 'HRD', hadir: 20, izin: 1, alpha: 0, terlambat: 0, persen: 95.2, kategori: 'bagus' },
                { nama: 'Andi Wijaya', divisi: 'Keuangan', hadir: 16, izin: 2, alpha: 2, terlambat: 1, persen: 76.2, kategori: 'cukup' },
                { nama: 'Dewi Lestari', divisi: 'Marketing', hadir: 17, izin: 3, alpha: 1, terlambat: 0, persen: 81.0, kategori: 'cukup' },
                { nama: 'Rudi Hermawan', divisi: 'IT', hadir: 15, izin: 2, alpha: 3, terlambat: 1, persen: 71.4, kategori: 'cukup' },
                { nama: 'Maya Sari', divisi: 'HRD', hadir: 19, izin: 1, alpha: 1, terlambat: 0, persen: 90.5, kategori: 'bagus' },
                { nama: 'Agus Prasetyo', divisi: 'Keuangan', hadir: 14, izin: 3, alpha: 3, terlambat: 1, persen: 66.7, kategori: 'kurang' },
                { nama: 'Ratna Sari', divisi: 'Marketing', hadir: 20, izin: 1, alpha: 0, terlambat: 0, persen: 95.2, kategori: 'bagus' }
            ]
        },
        '2026-02': { // Februari 2026
            totalKaryawan: 24,
            totalHariKerja: '20 Hari',
            rataKehadiran: '88.3%',
            totalAlpha: 13,
            bulan: 'Februari 2026',
            karyawan: [
                { nama: 'Budi Santoso', divisi: 'IT', hadir: 18, izin: 1, alpha: 1, terlambat: 0, persen: 90.0, kategori: 'bagus' },
                { nama: 'Siti Rahma', divisi: 'HRD', hadir: 19, izin: 1, alpha: 0, terlambat: 0, persen: 95.0, kategori: 'bagus' },
                { nama: 'Andi Wijaya', divisi: 'Keuangan', hadir: 15, izin: 2, alpha: 2, terlambat: 1, persen: 75.0, kategori: 'cukup' },
                { nama: 'Dewi Lestari', divisi: 'Marketing', hadir: 17, izin: 2, alpha: 1, terlambat: 0, persen: 85.0, kategori: 'bagus' },
                { nama: 'Rudi Hermawan', divisi: 'IT', hadir: 14, izin: 2, alpha: 3, terlambat: 1, persen: 70.0, kategori: 'cukup' },
                { nama: 'Maya Sari', divisi: 'HRD', hadir: 18, izin: 1, alpha: 1, terlambat: 0, persen: 90.0, kategori: 'bagus' },
                { nama: 'Agus Prasetyo', divisi: 'Keuangan', hadir: 13, izin: 3, alpha: 3, terlambat: 1, persen: 65.0, kategori: 'kurang' },
                { nama: 'Ratna Sari', divisi: 'Marketing', hadir: 19, izin: 1, alpha: 0, terlambat: 0, persen: 95.0, kategori: 'bagus' }
            ]
        },
        '2026-03': { // Maret 2026
            totalKaryawan: 24,
            totalHariKerja: '22 Hari',
            rataKehadiran: '89.5%',
            totalAlpha: 12,
            bulan: 'Maret 2026',
            karyawan: [
                { nama: 'Budi Santoso', divisi: 'IT', hadir: 20, izin: 1, alpha: 0, terlambat: 1, persen: 95.5, kategori: 'bagus' },
                { nama: 'Siti Rahma', divisi: 'HRD', hadir: 21, izin: 1, alpha: 0, terlambat: 0, persen: 100, kategori: 'bagus' },
                { nama: 'Andi Wijaya', divisi: 'Keuangan', hadir: 18, izin: 2, alpha: 1, terlambat: 1, persen: 81.8, kategori: 'cukup' },
                { nama: 'Dewi Lestari', divisi: 'Marketing', hadir: 19, izin: 2, alpha: 0, terlambat: 1, persen: 86.4, kategori: 'bagus' },
                { nama: 'Rudi Hermawan', divisi: 'IT', hadir: 17, izin: 1, alpha: 2, terlambat: 2, persen: 77.3, kategori: 'cukup' },
                { nama: 'Maya Sari', divisi: 'HRD', hadir: 20, izin: 1, alpha: 0, terlambat: 1, persen: 90.9, kategori: 'bagus' },
                { nama: 'Agus Prasetyo', divisi: 'Keuangan', hadir: 16, izin: 3, alpha: 1, terlambat: 2, persen: 72.7, kategori: 'cukup' },
                { nama: 'Ratna Sari', divisi: 'Marketing', hadir: 22, izin: 0, alpha: 0, terlambat: 0, persen: 100, kategori: 'bagus' }
            ]
        }
    };

    // Fungsi untuk merender tabel
    function renderTable(bulan) {
        const data = dataRekap[bulan];
        if (!data) return;

        // Update summary cards
        document.getElementById('totalKaryawan').textContent = data.totalKaryawan;
        document.getElementById('totalHariKerja').textContent = data.totalHariKerja;
        document.getElementById('rataKehadiran').textContent = data.rataKehadiran;
        document.getElementById('totalAlpha').textContent = data.totalAlpha;
        document.getElementById('bulanText').textContent = data.bulan;

        // Render tabel
        let html = '';
        data.karyawan.forEach((item, index) => {
            let badgeClass = '';
            if (item.kategori === 'bagus') badgeClass = 'badge-bagus';
            else if (item.kategori === 'cukup') badgeClass = 'badge-cukup';
            else badgeClass = 'badge-kurang';

            html += `<tr>
                <td>${index + 1}</td>
                <td>${item.nama}</td>
                <td>${item.divisi}</td>
                <td>${item.hadir}</td>
                <td>${item.izin}</td>
                <td>${item.alpha}</td>
                <td>${item.terlambat}</td>
                <td>
                    <span class="badge-persen ${badgeClass}">
                        <i class="bi ${item.kategori === 'bagus' ? 'bi-check-circle' : (item.kategori === 'cukup' ? 'bi-exclamation-triangle' : 'bi-x-circle')} me-1"></i>${item.persen}%
                    </span>
                </td>
            </tr>`;
        });
        document.getElementById('tableBody').innerHTML = html;
    }

    // Fungsi terapkan filter
    function terapkanFilter() {
        const bulan = document.getElementById('bulanFilter').value;
        renderTable(bulan);
    }

    // Fungsi export excel
    function exportExcel() {
        const bulan = document.getElementById('bulanFilter').value;
        const data = dataRekap[bulan];
        alert(`Export Excel rekap bulan ${data.bulan}`);
    }

    // Inisialisasi dengan data Maret 2026
    document.addEventListener('DOMContentLoaded', function() {
        renderTable('2026-03');
    });
</script>
@endpush
