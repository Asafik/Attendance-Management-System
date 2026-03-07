@extends('layouts.partials.app')

@section('title', 'Atur Libur Mingguan - Wadul Guse')
@section('page-title', 'Atur Libur Mingguan')

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
        min-width: 200px;
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

    .filter-select, .filter-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .filter-select:focus, .filter-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .filter-btn {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 42px;
        white-space: nowrap;
    }

    .filter-btn:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    .filter-btn.secondary {
        background: transparent;
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .filter-btn.secondary:hover {
        background: var(--accent-soft);
        border-color: var(--accent-color);
        color: var(--accent-color);
        transform: translateY(-2px);
        box-shadow: none;
    }

    /* ===== CARD FORM ===== */
    .form-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }

    .form-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-title i {
        color: var(--accent-color);
    }

    .employee-info {
        background: var(--accent-soft);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-left: 4px solid var(--accent-color);
    }

    .employee-avatar {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: var(--accent-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 600;
    }

    .employee-details {
        flex: 1;
    }

    .employee-name {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .employee-meta {
        color: var(--text-secondary);
        font-size: 14px;
        display: flex;
        gap: 16px;
    }

    .employee-meta i {
        color: var(--accent-color);
        margin-right: 4px;
    }

    .week-info {
        background: var(--bg-body);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .week-badge {
        background: var(--accent-color);
        color: white;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .week-badge i {
        font-size: 16px;
    }

    .week-actions {
        display: flex;
        gap: 10px;
    }

    /* ===== DAYS CHECKBOX ===== */
    .days-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .day-checkbox {
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .day-checkbox:hover {
        background: var(--accent-soft);
        border-color: var(--accent-color);
    }

    .day-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent-color);
        cursor: pointer;
    }

    .day-checkbox .day-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
        flex: 1;
    }

    .day-checkbox .day-icon {
        color: var(--accent-color);
        font-size: 16px;
    }

    .day-checkbox.selected {
        background: var(--accent-soft);
        border-color: var(--accent-color);
    }

    /* ===== HISTORY TABLE ===== */
    .history-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .history-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .history-title i {
        color: var(--accent-color);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: var(--accent-soft);
        color: var(--accent-color);
        border-bottom: 2px solid var(--accent-color);
        padding: 12px 8px;
        font-weight: 600;
        font-size: 14px;
        text-align: left;
    }

    .table tbody td {
        padding: 12px 8px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 14px;
    }

    .table tbody tr:hover td {
        background-color: var(--accent-soft);
    }

    .badge-day {
        background: var(--accent-soft);
        color: var(--accent-color);
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        margin: 2px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        background: transparent;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
        cursor: pointer;
        margin-right: 4px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.edit:hover {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .action-btn.delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .action-btn.copy:hover {
        background: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--border-color);
        margin-bottom: 16px;
    }

    /* ===== NOTIFICATION ===== */
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
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
        margin-bottom: 10px;
        animation: slideIn 0.3s ease;
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
    }

    .notification-message {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .notification-close {
        color: var(--text-secondary);
        cursor: pointer;
        padding: 4px;
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }

        .employee-info {
            flex-direction: column;
            text-align: center;
        }

        .employee-meta {
            flex-direction: column;
            gap: 8px;
        }

        .week-info {
            flex-direction: column;
            text-align: center;
        }

        .days-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            min-width: 600px;
        }
    }

    @media (max-width: 480px) {
        .days-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="wrapper">
    {{-- NOTIFICATION CONTAINER --}}
    <div id="notificationContainer" class="notification-container"></div>

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        {{-- NAVBAR --}}
        @include('layouts.navbar')

        {{-- CONTENT AREA --}}
        <div class="content-area">
            <div class="content-wrapper">
                {{-- FILTER SECTION --}}
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="bi bi-funnel"></i> Filter Karyawan & Minggu
                    </div>

                    <form method="GET" action="{{ route('weekly-off-days.index') }}" class="filter-row">
                        {{-- PILIH KARYAWAN --}}
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-person-badge"></i> Pilih Karyawan
                            </label>
                            <select name="employee_id" class="filter-select" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $employeeId == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }} - {{ $employee->division->name ?? 'Tanpa Divisi' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PILIH MINGGU --}}
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-calendar-week"></i> Pilih Minggu
                            </label>
                            <input type="week" name="week" class="filter-input"
                                   value="{{ \Carbon\Carbon::parse($weekStartFormatted)->format('Y-\WW') }}"
                                   min="2026-W01" max="{{ now()->format('Y-\WW') }}">
                        </div>

                        {{-- TOMBOL FILTER --}}
                        <div class="filter-group" style="flex: 0 0 auto;">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                </div>

                @if($selectedEmployee)
                    {{-- FORM ATUR LIBUR --}}
                    <div class="form-card">
                        <div class="form-title">
                            <i class="bi bi-calendar-plus"></i> Atur Libur Mingguan
                        </div>

                        {{-- INFO KARYAWAN --}}
                        <div class="employee-info">
                            <div class="employee-avatar">
                                {{ strtoupper(substr($selectedEmployee->name, 0, 1)) }}
                            </div>
                            <div class="employee-details">
                                <div class="employee-name">{{ $selectedEmployee->name }}</div>
                                <div class="employee-meta">
                                    <span><i class="bi bi-building"></i> {{ $selectedEmployee->division->name ?? 'Tanpa Divisi' }}</span>
                                    <span><i class="bi bi-check-circle"></i> {{ $selectedEmployee->status }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- INFO MINGGU --}}
                        <div class="week-info">
                            <div class="week-badge">
                                <i class="bi bi-calendar-week"></i> {{ $weekDisplay }}
                            </div>
                            <div class="week-actions">
                                <button type="button" class="filter-btn secondary" onclick="copyFromPreviousWeek()" id="copyBtn">
                                    <i class="bi bi-files"></i> Salin dari Minggu Lalu
                                </button>
                            </div>
                        </div>

                        {{-- FORM INPUT --}}
                        <form method="POST" action="{{ route('weekly-off-days.store') }}" id="offDaysForm">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
                            <input type="hidden" name="week_start" value="{{ $weekStartFormatted }}">
                            <input type="hidden" name="week_end" value="{{ $weekEndFormatted }}">

                            {{-- GRID HARI --}}
                            <div class="days-grid">
                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    $icons = [
                                        'Senin' => 'bi-calendar',
                                        'Selasa' => 'bi-calendar',
                                        'Rabu' => 'bi-calendar',
                                        'Kamis' => 'bi-calendar',
                                        'Jumat' => 'bi-calendar',
                                        'Sabtu' => 'bi-calendar-week',
                                        'Minggu' => 'bi-calendar-week',
                                    ];
                                @endphp

                                @foreach($days as $day)
                                    <label class="day-checkbox {{ in_array($day, $offDays) ? 'selected' : '' }}">
                                        <input type="checkbox" name="off_days[]" value="{{ $day }}"
                                               {{ in_array($day, $offDays) ? 'checked' : '' }}>
                                        <span class="day-name">{{ $day }}</span>
                                        <i class="bi {{ $icons[$day] }} day-icon"></i>
                                    </label>
                                @endforeach
                            </div>

                            {{-- TOMBOL SIMPAN --}}
                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                <button type="submit" class="filter-btn">
                                    <i class="bi bi-save"></i> Simpan Libur Mingguan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- RIWAYAT LIBUR MINGGUAN --}}
                    <div class="history-card">
                        <div class="history-title">
                            <i class="bi bi-clock-history"></i> Riwayat Libur Mingguan - {{ $selectedEmployee->name }}
                        </div>

                        @if($history->isEmpty())
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p>Belum ada riwayat libur mingguan</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Minggu</th>
                                            <th>Hari Libur</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history as $weekKey => $items)
                                            @php
                                                $firstItem = $items->first();
                                                $weekStart = $firstItem->week_start;
                                                $weekEnd = $firstItem->week_end;
                                                $days = $items->pluck('day')->toArray();
                                                $weekRange = \Carbon\Carbon::parse($weekStart)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($weekEnd)->format('d M Y');
                                            @endphp
                                            <tr>
                                                <td>{{ $weekRange }}</td>
                                                <td>
                                                    @foreach($days as $day)
                                                        <span class="badge-day">{{ $day }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div style="display: flex; gap: 4px;">
                                                        <button type="button" class="action-btn edit"
                                                                onclick="editWeek('{{ $weekStart }}', '{{ $weekEnd }}', {{ json_encode($days) }})"
                                                                title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form method="POST" action="{{ route('weekly-off-days.destroy') }}" style="display: inline;"
                                                              onsubmit="return confirm('Hapus data libur minggu ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
                                                            <input type="hidden" name="week_start" value="{{ $weekStart }}">
                                                            <input type="hidden" name="week_end" value="{{ $weekEnd }}">
                                                            <button type="submit" class="action-btn delete" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="action-btn copy"
                                                                onclick="copyWeek('{{ $weekStart }}', '{{ $weekEnd }}')"
                                                                title="Gunakan untuk minggu ini">
                                                            <i class="bi bi-files"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Pilih karyawan dulu --}}
                    <div class="form-card" style="text-align: center; padding: 60px 20px;">
                        <i class="bi bi-person-plus" style="font-size: 64px; color: var(--border-color); margin-bottom: 20px;"></i>
                        <h4 style="color: var(--text-primary); margin-bottom: 10px;">Belum Ada Karyawan Dipilih</h4>
                        <p style="color: var(--text-secondary);">Silakan pilih karyawan terlebih dahulu untuk mengatur libur mingguan.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- FOOTER --}}
        @include('layouts.footer')
    </div>
</div>
@endsection

@push('scripts')
<script>
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
            <div class="notification-icon" style="background-color: ${type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : (type === 'warning' ? '#f59e0b' : '#3b82f6'))}">
                <i class="bi ${icons[type]}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${title || titles[type]}</div>
                <div class="notification-message">${message}</div>
            </div>
            <div class="notification-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </div>
        `;

        notificationContainer.appendChild(notification);

        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);

        notification.querySelector('.notification-close').addEventListener('click', function() {
            notification.remove();
        });
    }

    // ===== SHOW NOTIFICATIONS FROM SESSION =====
    @if(session('success'))
        showNotification('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showNotification('error', '{{ session('error') }}');
    @endif

    // ===== HIGHLIGHT CHECKBOX =====
    document.querySelectorAll('.day-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.closest('.day-checkbox').classList.add('selected');
            } else {
                this.closest('.day-checkbox').classList.remove('selected');
            }
        });
    });

    // ===== COPY FROM PREVIOUS WEEK =====
    function copyFromPreviousWeek() {
        const employeeId = {{ $selectedEmployee->id ?? 'null' }};
        const weekStart = '{{ $weekStartFormatted }}';
        const weekEnd = '{{ $weekEndFormatted }}';

        if (!employeeId) return;

        // Hitung minggu sebelumnya (7 hari sebelumnya)
        const prevStart = new Date(weekStart);
        prevStart.setDate(prevStart.getDate() - 7);
        const prevEnd = new Date(weekEnd);
        prevEnd.setDate(prevEnd.getDate() - 7);

        const prevStartStr = prevStart.toISOString().split('T')[0];
        const prevEndStr = prevEnd.toISOString().split('T')[0];

        fetch(`{{ route('weekly-off-days.get-week-data') }}?employee_id=${employeeId}&week_start=${prevStartStr}&week_end=${prevEndStr}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.off_days.length > 0) {
                    // Uncheck semua
                    document.querySelectorAll('.day-checkbox input[type="checkbox"]').forEach(cb => {
                        cb.checked = false;
                        cb.closest('.day-checkbox').classList.remove('selected');
                    });

                    // Check sesuai data
                    data.off_days.forEach(day => {
                        const checkbox = document.querySelector(`.day-checkbox input[value="${day}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            checkbox.closest('.day-checkbox').classList.add('selected');
                        }
                    });

                    showNotification('success', 'Berhasil menyalin libur dari minggu sebelumnya');
                } else {
                    showNotification('info', 'Tidak ada data libur di minggu sebelumnya');
                }
            })
            .catch(error => {
                showNotification('error', 'Gagal mengambil data minggu sebelumnya');
            });
    }

    // ===== EDIT WEEK =====
    function editWeek(weekStart, weekEnd, days) {
        // Redirect ke halaman dengan parameter minggu tersebut
        const url = new URL(window.location.href);
        url.searchParams.set('week_start', weekStart);
        url.searchParams.set('week_end', weekEnd);
        window.location.href = url.toString();
    }

    // ===== COPY WEEK =====
    function copyWeek(weekStart, weekEnd) {
        const employeeId = {{ $selectedEmployee->id ?? 'null' }};
        const currentWeekStart = '{{ $weekStartFormatted }}';
        const currentWeekEnd = '{{ $weekEndFormatted }}';

        fetch(`{{ route('weekly-off-days.get-week-data') }}?employee_id=${employeeId}&week_start=${weekStart}&week_end=${weekEnd}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.off_days.length > 0) {
                    // Uncheck semua
                    document.querySelectorAll('.day-checkbox input[type="checkbox"]').forEach(cb => {
                        cb.checked = false;
                        cb.closest('.day-checkbox').classList.remove('selected');
                    });

                    // Check sesuai data
                    data.off_days.forEach(day => {
                        const checkbox = document.querySelector(`.day-checkbox input[value="${day}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            checkbox.closest('.day-checkbox').classList.add('selected');
                        }
                    });

                    showNotification('success', 'Data libur disalin ke minggu ini');
                } else {
                    showNotification('info', 'Tidak ada data libur di minggu tersebut');
                }
            });
    }

    // ===== CONVERT WEEK INPUT =====
    document.querySelector('input[type="week"]')?.addEventListener('change', function() {
        if (this.value) {
            const [year, week] = this.value.split('-W');
            // Hitung tanggal mulai minggu (Senin)
            const firstDayOfYear = new Date(year, 0, 1);
            const daysOffset = (week - 1) * 7;
            const firstDayOfWeek = new Date(firstDayOfYear.setDate(firstDayOfYear.getDate() + daysOffset));

            // Cari hari Senin
            const day = firstDayOfWeek.getDay(); // 0 Minggu, 1 Senin, ...
            const mondayOffset = day === 0 ? -6 : 1 - day;
            const monday = new Date(firstDayOfWeek.setDate(firstDayOfWeek.getDate() + mondayOffset));

            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);

            const weekStart = monday.toISOString().split('T')[0];
            const weekEnd = sunday.toISOString().split('T')[0];

            // Update hidden inputs
            const form = document.getElementById('offDaysForm');
            if (form) {
                form.querySelector('input[name="week_start"]').value = weekStart;
                form.querySelector('input[name="week_end"]').value = weekEnd;
            }
        }
    });
</script>
@endpush
