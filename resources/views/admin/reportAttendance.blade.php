@extends('layouts.partials.app')

@section('title', 'Rekap Bulanan - ' . ($company->name ?? '-'))
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
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
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
        text-decoration: none;
    }

    .filter-btn:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    .filter-btn.export {
        background: #10b981;
    }

    .filter-btn.export:hover {
        background: #0e9f6e;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .filter-btn i {
        font-size: 16px;
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

    /* ===== ICON DI THEAD ===== */
    .table thead th i {
        color: var(--accent-color);
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

    /* ===== NOTIFICATION CUSTOM ===== */
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }

    .notification {
        background-color: var(--bg-card);
        border-left: 4px solid;
        border-radius: 8px;
        padding: 16px 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        max-width: 400px;
        pointer-events: auto;
        animation: slideIn 0.3s ease forwards;
        position: relative;
        overflow: hidden;
    }

    .notification.success {
        border-left-color: #10b981;
    }

    .notification.success .notification-icon {
        background-color: #10b981;
        color: white;
    }

    .notification.error {
        border-left-color: #ef4444;
    }

    .notification.error .notification-icon {
        background-color: #ef4444;
        color: white;
    }

    .notification.warning {
        border-left-color: #f59e0b;
    }

    .notification.warning .notification-icon {
        background-color: #f59e0b;
        color: white;
    }

    .notification.info {
        border-left-color: #3b82f6;
    }

    .notification.info .notification-icon {
        background-color: #3b82f6;
        color: white;
    }

    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .notification-message {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .notification-close {
        color: var(--text-secondary);
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .notification-close:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background-color: var(--accent-color);
        width: 100%;
        animation: progress 3s linear forwards;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes progress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    /* ===== LOADING OVERLAY ===== */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 100000;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 30px 40px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: zoomIn 0.3s ease;
    }

    .loading-spinner i {
        font-size: 50px;
        color: var(--accent-color);
        margin-bottom: 15px;
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    .loading-spinner p {
        color: var(--text-primary);
        font-size: 16px;
        font-weight: 500;
        margin: 0;
    }

    .loading-spinner small {
        color: var(--text-secondary);
        font-size: 13px;
        display: block;
        margin-top: 5px;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
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

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            min-width: 1100px;
        }

        .table thead th {
            font-size: 12px;
            padding: 8px 4px;
        }

        .table tbody td {
            font-size: 12px;
            padding: 8px 4px;
        }

        .summary-card {
            padding: 12px;
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .summary-value {
            font-size: 18px;
        }

        .row {
            margin-right: -8px;
            margin-left: -8px;
        }

        [class*="col-"] {
            padding-right: 8px;
            padding-left: 8px;
        }
    }

    @media (max-width: 576px) {
        .content-area {
            padding: 12px;
        }

        .summary-card {
            padding: 10px;
            gap: 8px;
        }

        .summary-icon {
            width: 36px;
            height: 36px;
            font-size: 18px;
        }

        .summary-value {
            font-size: 16px;
        }

        .summary-label {
            font-size: 11px;
        }

        .notification {
            min-width: 250px;
            padding: 12px 16px;
        }

        .table {
            min-width: 1000px;
        }
    }
</style>
@endpush

@section('content')
<div class="wrapper">
    {{-- NOTIFICATION CONTAINER --}}
    <div id="notificationContainer" class="notification-container"></div>

    {{-- LOADING OVERLAY --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner">
            <i class="bi bi-arrow-repeat"></i>
            <p>Sedang Memproses...</p>
            <small>Mohon tunggu sebentar</small>
        </div>
    </div>

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
                    <form method="GET" action="{{ route('report.attendance') }}" class="filter-row" id="filterForm">
                        {{-- PILIH BULAN --}}
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-calendar-month"></i> Pilih Bulan
                            </label>
                            <input type="month" name="month" class="filter-input-month" value="{{ $month }}" max="{{ now()->format('Y-m') }}">
                        </div>

                        {{-- TOMBOL FILTER --}}
                        <div class="filter-group" style="flex: 0 0 auto;">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel"></i> Terapkan
                            </button>
                        </div>

                        {{-- TOMBOL EXPORT --}}
                       <div class="filter-group" style="flex: 0 0 auto;">
                            <button type="button" class="filter-btn export" onclick="exportData()">
                                <i class="bi bi-download"></i> Export Excel
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SUMMARY CARD --}}
                @php
                    $totalKaryawan = $employees->count();
                    $totalIzin = 0;
                    $totalAlpha = 0;
                    $totalLiburTambahan = 0;
                    $totalLiburTetap = 0;
                    $totalWFH = 0;

                    foreach($employees as $employee) {
                        $totalIzin += $employee->attendances->where('status', 'Izin')->count();
                        $totalAlpha += $employee->attendances->where('status', 'Alpha')->count();

                        // Pisahkan libur tetap dan libur tambahan berdasarkan note
                        $totalLiburTetap += $employee->attendances
                            ->where('status', 'Libur')
                            ->filter(function($att) {
                                return str_contains($att->note, 'Libur tetap');
                            })->count();

                        $totalLiburTambahan += $employee->attendances
                            ->where('status', 'Libur')
                            ->filter(function($att) {
                                return !str_contains($att->note, 'Libur tetap');
                            })->count();

                        $totalWFH += $employee->attendances->where('status', 'WFH')->count();
                    }

                    // Yang mengurangi kehadiran adalah IZIN + ALPHA + LIBUR TETAP + LIBUR TAMBAHAN
                    $totalTidakHadir = $totalIzin + $totalAlpha + $totalLiburTetap + $totalLiburTambahan;
                    $totalKehadiran = ($totalKaryawan * $totalDaysInMonth) - $totalTidakHadir;
                @endphp

                <div class="row g-3 g-md-4 mb-3 mb-md-4">
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Karyawan</div>
                                <div class="summary-value">{{ $totalKaryawan }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Hari Kerja</div>
                                <div class="summary-value">{{ $totalDaysInMonth }} Hari</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Kehadiran</div>
                                <div class="summary-value">{{ $totalKehadiran }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Total Tidak Hadir</div>
                                <div class="summary-value">{{ $totalTidakHadir }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">
                            <i class="bi bi-table"></i> Rekap Absensi {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                        </h3>
                        <div class="table-actions">
                            <span style="color: var(--text-secondary); font-size: 13px;">
                                <i class="bi bi-info-circle"></i> Total hari: {{ $totalDaysInMonth }} hari
                            </span>
                        </div>
                    </div>

                    {{-- TABEL REKAP DENGAN KOLOM LIBUR TETAP & LIBUR TAMBAHAN --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>No</th>
                                    <th><i class="bi bi-person me-1"></i>Nama</th>
                                    <th><i class="bi bi-building me-1"></i>Divisi</th>
                                    <th><i class="bi bi-check-circle me-1"></i>Hadir</th>
                                    <th><i class="bi bi-house-door me-1"></i>WFH</th>
                                    <th><i class="bi bi-file-text me-1"></i>Izin</th>
                                    <th><i class="bi bi-x-circle me-1"></i>Alpha</th>
                                    <th><i class="bi bi-calendar-check me-1"></i>Libur Tetap</th>
                                    <th><i class="bi bi-moon me-1"></i>Libur Tambahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                    @php
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

                                        // Yang mengurangi kehadiran adalah IZIN + ALPHA + LIBUR TETAP + LIBUR TAMBAHAN
                                        $totalTidakHadirKaryawan = $izin + $alpha + $liburTetap + $liburTambahan;
                                        $hadir = $totalDaysInMonth - $totalTidakHadirKaryawan;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->division->name ?? '-' }}</td>
                                        <td><strong>{{ $hadir }}</strong></td>
                                        <td>{{ $wfh }}</td>
                                        <td>{{ $izin }}</td>
                                        <td>{{ $alpha }}</td>
                                        <td>{{ $liburTetap }}</td>
                                        <td>{{ $liburTambahan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="bi bi-emoji-frown" style="font-size: 24px; color: var(--text-secondary);"></i>
                                            <p class="mt-2">Belum ada data karyawan</p>
                                        </td>
                                    </tr>
                                @endforelse
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
    // ===== LOADING OVERLAY =====
    const loadingOverlay = document.getElementById('loadingOverlay');

    function showLoading(message = 'Sedang Memproses...') {
        const spinnerText = loadingOverlay.querySelector('p');
        spinnerText.textContent = message;
        loadingOverlay.classList.add('active');
    }

    function hideLoading() {
        loadingOverlay.classList.remove('active');
    }

    // ===== NOTIFICATION SYSTEM =====
    const notificationContainer = document.getElementById('notificationContainer');

    function showNotification(type, message, title = null) {
        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        const titles = {
            success: 'Berhasil!',
            error: 'Gagal!',
            warning: 'Peringatan!',
            info: 'Informasi'
        };

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="bi ${icons[type]}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${title || titles[type]}</div>
                <div class="notification-message">${message}</div>
            </div>
            <div class="notification-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </div>
            <div class="notification-progress"></div>
        `;

        notificationContainer.appendChild(notification);

        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 3000);

        notification.querySelector('.notification-close').addEventListener('click', function() {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        });
    }

    // ===== AUTO SUBMIT FILTER =====
    $(document).ready(function() {
        $('.filter-input-month').on('change', function() {
            $(this).closest('form').submit();
        });
    });

    // ===== EXPORT DATA DENGAN AJAX =====
    function exportData() {
        const month = '{{ $month }}';
        const url = '{{ route("report.attendance.export") }}?month=' + month;

        showLoading('Menyiapkan file export...');

        // Fetch dengan AJAX
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob();
        })
        .then(blob => {
            // Buat URL untuk blob
            const blobUrl = window.URL.createObjectURL(blob);

            // Buat link download
            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = 'rekap_absensi_' + month.replace('-', '_') + '.xlsx';
            document.body.appendChild(link);

            // Trigger download
            link.click();

            // Cleanup
            document.body.removeChild(link);
            window.URL.revokeObjectURL(blobUrl);

            // Hide loading dan kasih notifikasi
            hideLoading();
            showNotification('success', 'File export berhasil didownload');
        })
        .catch(error => {
            console.error('Export error:', error);
            hideLoading();
            showNotification('error', 'Gagal export file. Silakan coba lagi.');
        });
    }

    // Hide loading when page loads
    window.addEventListener('pageshow', function() {
        hideLoading();
    });

    // Show notification from session
    @if(session('success'))
        showNotification('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showNotification('error', '{{ session('error') }}');
    @endif
</script>
@endpush
