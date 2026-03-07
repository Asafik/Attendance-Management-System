@extends('layouts.partials.app')

@section('title', 'Dashboard - Alena Mandiri Group')
@section('page-title', 'Dashboard Absensi')

@push('styles')
<style>
    /* ===== RESET & VARIABLE ===== */
    .content-area {
        background-color: var(--bg-body);
        padding: 24px;
    }

    /* ===== FILTER SECTION ===== */
    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .month-selector {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .month-select {
        padding: 10px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        min-width: 180px;
        cursor: pointer;
    }

    .month-select:focus {
        outline: none;
        border-color: var(--accent-color);
    }

    /* ===== CARD STATISTIK DENGAN WARNA PEMBEDA ===== */
    .stat-card {
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color) !important;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
    }

    /* WARNA PEMBEDA UNTUK SETIAP CARD */
    .stat-card.card-total {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(22, 163, 74, 0.05) 100%);
        border-left: 4px solid var(--accent-color) !important;
    }

    .stat-card.card-hadir {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-left: 4px solid #10b981 !important;
    }

    .stat-card.card-izin {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(245, 158, 11, 0.05) 100%);
        border-left: 4px solid #f59e0b !important;
    }

    .stat-card.card-alpha {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(239, 68, 68, 0.05) 100%);
        border-left: 4px solid #ef4444 !important;
    }

    .stat-card.card-libur {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(107, 114, 128, 0.05) 100%);
        border-left: 4px solid #6b7280 !important;
    }

    .stat-card.card-wfh {
        background: linear-gradient(135deg, var(--bg-card) 0%, rgba(59, 130, 246, 0.05) 100%);
        border-left: 4px solid #3b82f6 !important;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .stat-icon.card-total { background: rgba(22, 163, 74, 0.1); color: var(--accent-color); }
    .stat-icon.card-hadir { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.card-izin { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.card-alpha { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-icon.card-libur { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .stat-icon.card-wfh { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    .stat-label {
        color: var(--text-secondary) !important;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary) !important;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .stat-change {
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: auto;
    }

    .stat-change.card-total { color: var(--accent-color) !important; }
    .stat-change.card-hadir { color: #10b981 !important; }
    .stat-change.card-izin { color: #f59e0b !important; }
    .stat-change.card-alpha { color: #ef4444 !important; }
    .stat-change.card-libur { color: #6b7280 !important; }
    .stat-change.card-wfh { color: #3b82f6 !important; }

    /* ===== CHART CARD ===== */
    .chart-card {
        background-color: var(--bg-card) !important;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color) !important;
        height: 100%;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .chart-title {
        font-weight: 600;
        color: var(--text-primary) !important;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-title i {
        color: var(--accent-color);
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* ===== STATISTIK DIVISI ===== */
    .division-stats {
        background-color: var(--bg-card) !important;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color) !important;
        height: 100%;
    }

    .division-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .division-title {
        font-weight: 600;
        color: var(--text-primary) !important;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .division-title i { color: var(--accent-color); }

    .division-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .division-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .division-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--accent-soft);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .division-info {
        flex: 1;
    }

    .division-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }

    .division-name span {
        font-weight: normal;
        color: var(--text-secondary);
        font-size: 12px;
    }

    .division-bar {
        height: 6px;
        background-color: var(--accent-soft);
        border-radius: 30px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .division-bar-fill {
        height: 100%;
        border-radius: 30px;
    }

    .division-meta {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: var(--text-secondary);
    }

    /* ===== AKTIVITAS TERBARU ===== */
    .activity-card {
        background-color: var(--bg-card) !important;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color) !important;
        height: 100%;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .activity-title {
        font-weight: 600;
        color: var(--text-primary) !important;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-title i { color: var(--accent-color); }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        background-color: var(--bg-body);
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        transform: translateX(4px);
        border-color: var(--accent-color);
    }

    .activity-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--accent-soft);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        flex-shrink: 0;
    }

    .activity-info {
        flex: 1;
        min-width: 0;
    }

    .activity-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 14px;
    }

    .activity-name span {
        font-size: 11px;
        color: var(--accent-color);
        margin-left: 4px;
    }

    .activity-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 11px;
        flex-wrap: wrap;
    }

    .activity-badge {
        padding: 4px 8px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-hadir { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-wfh { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-izin { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-alpha { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .badge-libur { background-color: rgba(107, 114, 128, 0.1); color: #6b7280; }

    .activity-time {
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        font-size: 10px;
    }

    .activity-time i {
        font-size: 11px;
    }

    /* ===== TEXT SECONDARY ===== */
    .text-secondary {
        color: var(--text-secondary) !important;
    }

    /* ===== RESPONSIVE BREAKPOINTS ===== */

    /* Desktop Besar (1400px ke atas) */
    @media (min-width: 1400px) {
        .row-cards {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        .col-card {
            flex: 0 0 20%;
            padding: 0 15px;
            margin-bottom: 30px;
        }
        .stat-card { padding: 24px; }
        .stat-icon { width: 56px; height: 56px; font-size: 28px; }
        .stat-value { font-size: 32px; }
    }

    /* Desktop (1200px - 1399px) */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .row-cards {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        .col-card {
            flex: 0 0 20%;
            padding: 0 12px;
            margin-bottom: 24px;
        }
        .stat-card { padding: 20px; }
        .stat-icon { width: 52px; height: 52px; font-size: 26px; }
        .stat-value { font-size: 28px; }
    }

    /* Laptop (992px - 1199px) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .row-cards {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        .col-card {
            flex: 0 0 20%;
            padding: 0 12px;
            margin-bottom: 24px;
        }
        .stat-card { padding: 18px; }
        .stat-icon { width: 48px; height: 48px; font-size: 24px; }
        .stat-value { font-size: 24px; }
        .stat-label { font-size: 13px; }
    }

    /* TABLET (768px - 991px) - 3 CARD PER BARIS */
    @media (min-width: 768px) and (max-width: 991px) {
        .content-area { padding: 20px; }

        .row-cards {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        .col-card {
            flex: 0 0 33.333%;
            padding: 0 12px;
            margin-bottom: 24px;
        }
        .stat-card { padding: 16px; }
        .stat-icon { width: 44px; height: 44px; font-size: 22px; margin-bottom: 12px; }
        .stat-value { font-size: 24px; }
        .stat-label { font-size: 13px; }
        .stat-change { font-size: 12px; }

        .chart-container { height: 250px; }
        .chart-title { font-size: 16px; }

        .division-stats { padding: 16px; }
        .division-title { font-size: 16px; }
        .division-icon { width: 36px; height: 36px; font-size: 16px; }
        .division-name { font-size: 14px; }
        .division-name span { font-size: 12px; }

        .activity-card { padding: 16px; }
        .activity-title { font-size: 16px; }
        .activity-item { padding: 10px; }
        .activity-name { font-size: 13px; }
        .activity-name span { font-size: 10px; }
        .activity-meta { gap: 8px; }
    }

    /* MOBILE (576px - 767px) - 2 CARD PER BARIS */
    @media (min-width: 576px) and (max-width: 767px) {
        .content-area { padding: 16px; }

        .filter-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .month-selector {
            width: 100%;
        }

        .month-select {
            width: 100%;
        }

        .row-cards {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .col-card {
            flex: 0 0 50%;
            padding: 0 10px;
            margin-bottom: 20px;
        }
        .stat-card { padding: 14px; }
        .stat-icon { width: 40px; height: 40px; font-size: 20px; margin-bottom: 10px; }
        .stat-value { font-size: 22px; }
        .stat-label { font-size: 12px; }
        .stat-change { font-size: 11px; }

        .chart-card { padding: 16px; }
        .chart-title { font-size: 16px; }
        .chart-container { height: 220px; }

        .division-stats { padding: 16px; }
        .division-title { font-size: 16px; }
        .division-icon { width: 36px; height: 36px; font-size: 16px; }
        .division-name { font-size: 14px; }
        .division-name span { font-size: 12px; }

        .activity-card { padding: 16px; }
        .activity-title { font-size: 16px; }
        .activity-item { padding: 10px; }
        .activity-avatar { width: 36px; height: 36px; font-size: 15px; }
        .activity-name { font-size: 13px; }
        .activity-name span { font-size: 10px; }
        .activity-time { font-size: 10px; }
        .activity-meta { gap: 8px; }
    }

    /* MOBILE KECIL (< 576px) - 2 CARD PER BARIS */
    @media (max-width: 575px) {
        .content-area { padding: 12px; }

        .filter-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .month-selector {
            width: 100%;
        }

        .month-select {
            width: 100%;
        }

        .row-cards {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -8px;
        }
        .col-card {
            flex: 0 0 50%;
            padding: 0 8px;
            margin-bottom: 16px;
        }
        .stat-card { padding: 12px; }
        .stat-icon { width: 36px; height: 36px; font-size: 18px; margin-bottom: 8px; }
        .stat-value { font-size: 20px; }
        .stat-label { font-size: 11px; }
        .stat-change { font-size: 10px; }

        .chart-card { padding: 12px; }
        .chart-title { font-size: 15px; }
        .chart-container { height: 200px; }

        .division-stats { padding: 12px; }
        .division-title { font-size: 15px; }
        .division-icon { width: 32px; height: 32px; font-size: 14px; }
        .division-name { font-size: 13px; }
        .division-name span { font-size: 11px; }
        .division-meta { font-size: 10px; }

        .activity-card { padding: 12px; }
        .activity-title { font-size: 15px; }
        .activity-item { padding: 8px; gap: 8px; }
        .activity-avatar { width: 32px; height: 32px; font-size: 13px; }
        .activity-name { font-size: 12px; }
        .activity-name span { font-size: 9px; }
        .activity-badge { padding: 3px 6px; font-size: 9px; }
        .activity-time { font-size: 9px; }
        .activity-meta { gap: 6px; }
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
            {{-- FILTER BULAN --}}
            <div class="filter-section">
                <h2 class="page-title">Dashboard Absensi</h2>

                <div class="month-selector">
                    <form method="GET" action="{{ route('dashboard') }}" id="monthForm">
                        <select name="month" class="month-select" onchange="this.form.submit()">
                            @foreach($months as $value => $label)
                                <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- 1️⃣ CARD RINGKASAN HARI INI (5 CARD) --}}
            <div class="row-cards">
                {{-- Total Karyawan --}}
                <div class="col-card">
                    <div class="stat-card card-total">
                        <div class="stat-icon card-total"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-label">Total Karyawan</div>
                        <div class="stat-value">{{ $totalKaryawan }}</div>
                        <div class="stat-change card-total">
                            <i class="bi bi-check-circle-fill"></i> {{ $karyawanAktif }} aktif
                        </div>
                    </div>
                </div>

                {{-- Hadir Hari Ini --}}
                <div class="col-card">
                    <div class="stat-card card-hadir">
                        <div class="stat-icon card-hadir"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="stat-label">Hadir Hari Ini</div>
                        <div class="stat-value">{{ $totalHadirHariIni }}</div>
                        <div class="stat-change card-hadir">
                            <i class="bi bi-percent"></i> {{ $persentaseHadir }}%
                        </div>
                    </div>
                </div>

                {{-- Izin Hari Ini --}}
                <div class="col-card">
                    <div class="stat-card card-izin">
                        <div class="stat-icon card-izin"><i class="bi bi-calendar-check-fill"></i></div>
                        <div class="stat-label">Izin Hari Ini</div>
                        <div class="stat-value">{{ $izinHariIni }}</div>
                        <div class="stat-change card-izin">
                            <i class="bi bi-info-circle"></i> {{ $izinHariIni }} orang
                        </div>
                    </div>
                </div>

                {{-- Alpha Hari Ini --}}
                <div class="col-card">
                    <div class="stat-card card-alpha">
                        <div class="stat-icon card-alpha"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div class="stat-label">Alpha Hari Ini</div>
                        <div class="stat-value">{{ $alphaHariIni }}</div>
                        <div class="stat-change card-alpha">
                            <i class="bi bi-exclamation-circle"></i> {{ $alphaHariIni }} orang
                        </div>
                    </div>
                </div>

                {{-- WFH Hari Ini --}}
                <div class="col-card">
                    <div class="stat-card card-wfh">
                        <div class="stat-icon card-wfh"><i class="bi bi-house-door-fill"></i></div>
                        <div class="stat-label">WFH Hari Ini</div>
                        <div class="stat-value">{{ $wfhHariIni ?? 0 }}</div>
                        <div class="stat-change card-wfh">
                            <i class="bi bi-house-door"></i> Work From Home
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2️⃣ GRAFIK ABSENSI PER MINGGU (BULAN INI) --}}
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="chart-card">
                        <div class="chart-header">
                            <span class="chart-title">
                                <i class="bi bi-graph-up"></i> Grafik Absensi {{ $selectedMonthName }}
                            </span>
                            <span class="text-secondary">Per Minggu</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3️⃣ STATISTIK DIVISI + 4️⃣ AKTIVITAS TERBARU --}}
            <div class="row g-4">
                {{-- KIRI: Statistik Divisi (BULAN INI) --}}
                <div class="col-12 col-lg-6">
                    <div class="division-stats">
                        <div class="division-header">
                            <span class="division-title">
                                <i class="bi bi-building"></i> Statistik Divisi {{ $selectedMonthName }}
                            </span>
                            <span class="text-secondary">{{ count($divisionStats) }} Divisi</span>
                        </div>
                        <div class="division-list">
                            @forelse($divisionStats as $division)
                            <div class="division-item">
                                <div class="division-icon">
                                    @if($division['name'] == 'IT')
                                        <i class="bi bi-laptop"></i>
                                    @elseif($division['name'] == 'Marketing')
                                        <i class="bi bi-megaphone"></i>
                                    @elseif($division['name'] == 'HRD')
                                        <i class="bi bi-people"></i>
                                    @elseif($division['name'] == 'Finance')
                                        <i class="bi bi-calculator"></i>
                                    @else
                                        <i class="bi bi-building"></i>
                                    @endif
                                </div>
                                <div class="division-info">
                                    <div class="division-name">
                                        {{ $division['name'] }}
                                        <span>{{ $division['total_karyawan'] }} karyawan</span>
                                    </div>
                                    <div class="division-bar">
                                        <div class="division-bar-fill" style="width: {{ $division['persentase'] }}%; background-color:
                                            @if($division['persentase'] >= 80) #10b981
                                            @elseif($division['persentase'] >= 60) #f59e0b
                                            @else #ef4444
                                            @endif">
                                        </div>
                                    </div>
                                    <div class="division-meta">
                                        <span>
                                            @if($division['izin'] > 0 || $division['alpha'] > 0)
                                                <i class="bi bi-exclamation-triangle-fill" style="color: #f59e0b;"></i>
                                                {{ $division['izin'] }} izin, {{ $division['alpha'] }} alpha
                                            @else
                                                <i class="bi bi-check-circle-fill" style="color: #10b981;"></i>
                                                Semua hadir
                                            @endif
                                        </span>
                                        <span style="font-weight: 600; color:
                                            @if($division['persentase'] >= 80) #10b981
                                            @elseif($division['persentase'] >= 60) #f59e0b
                                            @else #ef4444
                                            @endif">
                                            {{ $division['persentase'] }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="bi bi-building" style="font-size: 48px; color: var(--text-secondary);"></i>
                                <p class="mt-2">Belum ada data divisi</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KANAN: Aktivitas Terbaru --}}
                <div class="col-12 col-lg-6">
                    <div class="activity-card">
                        <div class="activity-header">
                            <span class="activity-title">
                                <i class="bi bi-clock-history"></i> Aktivitas Terbaru
                            </span>
                            <span class="text-secondary">Non-libur tetap</span>
                        </div>
                        <div class="activity-list">
                            @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-avatar">{{ $activity['initials'] }}</div>
                                <div class="activity-info">
                                    <div class="activity-name">
                                        {{ $activity['name'] }}
                                        <span>- {{ $activity['division'] }}</span>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-badge badge-{{ strtolower($activity['status']) }}">{{ $activity['status'] }}</span>
                                        <span class="activity-time">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $activity['date_formatted'] }}
                                        </span>
                                        <span class="activity-time">
                                            <i class="bi bi-clock"></i>
                                            {{ $activity['time'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="bi bi-clock-history" style="font-size: 48px; color: var(--text-secondary);"></i>
                                <p class="mt-2">Belum ada aktivitas hari ini</p>
                                <small class="text-secondary">(libur tetap tidak ditampilkan)</small>
                            </div>
                            @endforelse
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    $(document).ready(function() {
        const isDarkMode = () => document.body.classList.contains('dark-mode');
        const textColor = () => isDarkMode() ? '#ffffff' : '#1e293b';
        const gridColor = () => isDarkMode() ? '#2a2f3f' : '#e9ecf0';

        // Grafik Absensi Per Minggu (LINE CHART)
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($weeklyData['weeks']) !!},
                datasets: [
                    {
                        label: 'WFH',
                        data: {!! json_encode($weeklyData['wfh']) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.3
                    },
                    {
                        label: 'Izin',
                        data: {!! json_encode($weeklyData['izin']) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.3
                    },
                    {
                        label: 'Alpha',
                        data: {!! json_encode($weeklyData['alpha']) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            color: textColor()
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'var(--bg-card)',
                        titleColor: 'var(--text-primary)',
                        bodyColor: 'var(--text-secondary)',
                        borderColor: 'var(--border-color)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor() },
                        ticks: { color: textColor() }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor() }
                    }
                }
            }
        });

        // Update chart saat dark mode toggle
        $('#darkModeToggle').click(function() {
            setTimeout(() => {
                const color = textColor();
                const grid = gridColor();

                attendanceChart.options.scales.y.ticks.color = color;
                attendanceChart.options.scales.x.ticks.color = color;
                attendanceChart.options.scales.y.grid.color = grid;
                attendanceChart.options.plugins.legend.labels.color = color;
                attendanceChart.update();
            }, 100);
        });
    });



</script>

@endpush
