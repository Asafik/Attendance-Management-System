@extends('layouts.partials.app')

@section('title', 'Izin & Cuti - Wadul Guse')
@section('page-title', 'Izin & Cuti')

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

    /* ===== CARD ===== */
    .card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .card-title i {
        color: var(--accent-color);
        margin-right: 8px;
    }

    /* ===== BUTTON TAMBAH ===== */
    .btn-add {
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
    }

    .btn-add:hover {
        background: #ff4d88;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 103, 154, 0.3);
    }

    .btn-success {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-success:hover {
        background: #0e9f6e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-secondary {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
        border-color: var(--accent-color);
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

    /* ===== BADGE STATUS ===== */
    .badge-status {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
        white-space: nowrap;
        text-align: center;
    }

    .badge-pending {
        background-color: #f59e0b;
        color: white;
    }

    .badge-approved {
        background-color: #10b981;
        color: white;
    }

    .badge-rejected {
        background-color: #ef4444;
        color: white;
    }

    /* ===== JENIS BADGE ===== */
    .jenis-izin {
        background-color: #8b5cf6;
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .jenis-sakit {
        background-color: #f97316;
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .jenis-cuti {
        background-color: #06b6d4;
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .jenis-wfh {
        background-color: #14b8a6;
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-btns {
        display: flex;
        gap: 4px;
    }

    /* ===== MODAL ===== */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background-color: var(--bg-card);
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background-color: var(--bg-card);
        z-index: 10;
        border-radius: 20px 20px 0 0;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .modal-title i {
        color: var(--accent-color);
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-size: 20px;
        cursor: pointer;
        padding: 4px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background-color: var(--bg-card);
        border-radius: 0 0 20px 20px;
    }

    /* ===== FORM ===== */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
    }

    .form-label i {
        color: var(--accent-color);
        margin-right: 6px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .btn-primary {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #ff4d88;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 103, 154, 0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .card-header {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .form-group {
            min-width: 100%;
        }

        .table thead th {
            font-size: 12px;
            padding: 8px 4px;
        }

        .table tbody td {
            font-size: 12px;
            padding: 8px 4px;
        }

        .modal-content {
            width: 95%;
            margin: 10px;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 16px;
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
                {{-- CARD TABLE --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-calendar-check"></i> Riwayat Pengajuan Izin & Cuti
                        </h3>

                        {{-- TOMBOL TAMBAH PENGAJUAN --}}
                        <button class="btn-add" onclick="openTambahModal()">
                            <i class="bi bi-plus-circle"></i>
                            <span>Tambah Pengajuan</span>
                        </button>
                    </div>

                    {{-- TABEL PENGAJUAN --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>No</th>
                                    <th><i class="bi bi-person me-1"></i>Nama</th>
                                    <th><i class="bi bi-tag me-1"></i>Jenis</th>
                                    <th><i class="bi bi-calendar me-1"></i>Tanggal</th>
                                    <th><i class="bi bi-clock me-1"></i>Lama</th>
                                    <th><i class="bi bi-chat me-1"></i>Alasan</th>
                                    <th><i class="bi bi-check-circle me-1"></i>Status</th>
                                    <th><i class="bi bi-tools me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DATA 1 --}}
                                <tr>
                                    <td>1</td>
                                    <td>Budi Santoso</td>
                                    <td><span class="jenis-cuti">Cuti</span></td>
                                    <td>10-12 Mar 2026</td>
                                    <td>3 hari</td>
                                    <td>Cuti tahunan</td>
                                    <td><span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-success" onclick="openApproveModal('Budi Santoso')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn-danger" onclick="openRejectModal('Budi Santoso')">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{-- DATA 2 --}}
                                <tr>
                                    <td>2</td>
                                    <td>Siti Rahma</td>
                                    <td><span class="jenis-sakit">Sakit</span></td>
                                    <td>12 Mar 2026</td>
                                    <td>1 hari</td>
                                    <td>Demam</td>
                                    <td><span class="badge-status badge-approved"><i class="bi bi-check-circle me-1"></i>Approved</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-success" onclick="openApproveModal('Siti Rahma')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn-danger" onclick="openRejectModal('Siti Rahma')">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{-- DATA 3 --}}
                                <tr>
                                    <td>3</td>
                                    <td>Andi Wijaya</td>
                                    <td><span class="jenis-izin">Izin</span></td>
                                    <td>15 Mar 2026</td>
                                    <td>1 hari</td>
                                    <td>Keperluan keluarga</td>
                                    <td><span class="badge-status badge-rejected"><i class="bi bi-x-circle me-1"></i>Rejected</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-success" onclick="openApproveModal('Andi Wijaya')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn-danger" onclick="openRejectModal('Andi Wijaya')">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{-- DATA 4 --}}
                                <tr>
                                    <td>4</td>
                                    <td>Dewi Lestari</td>
                                    <td><span class="jenis-wfh">WFH</span></td>
                                    <td>18-20 Mar 2026</td>
                                    <td>3 hari</td>
                                    <td>WFH</td>
                                    <td><span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-success" onclick="openApproveModal('Dewi Lestari')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn-danger" onclick="openRejectModal('Dewi Lestari')">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{-- DATA 5 --}}
                                <tr>
                                    <td>5</td>
                                    <td>Rudi Hermawan</td>
                                    <td><span class="jenis-sakit">Sakit</span></td>
                                    <td>20 Mar 2026</td>
                                    <td>1 hari</td>
                                    <td>Sakit gigi</td>
                                    <td><span class="badge-status badge-approved"><i class="bi bi-check-circle me-1"></i>Approved</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-success" onclick="openApproveModal('Rudi Hermawan')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn-danger" onclick="openRejectModal('Rudi Hermawan')">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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

{{-- MODAL TAMBAH PENGAJUAN --}}
<div class="modal" id="tambahModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="bi bi-plus-circle me-2"></i>Tambah Pengajuan Izin / Cuti
            </h5>
            <button class="modal-close" onclick="closeModal('tambah')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-body">
            <form id="tambahForm">
                {{-- NAMA --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person"></i> Nama Karyawan
                    </label>
                    <select class="form-select" id="namaKaryawan">
                        <option value="">Pilih Karyawan</option>
                        <option value="Budi Santoso" selected>Budi Santoso</option>
                        <option value="Siti Rahma">Siti Rahma</option>
                        <option value="Andi Wijaya">Andi Wijaya</option>
                        <option value="Dewi Lestari">Dewi Lestari</option>
                        <option value="Rudi Hermawan">Rudi Hermawan</option>
                    </select>
                </div>

                {{-- JENIS --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-tag"></i> Jenis
                    </label>
                    <select class="form-select" id="jenis">
                        <option value="Cuti" selected>Cuti</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="WFH">WFH</option>
                    </select>
                </div>

                <div class="form-row">
                    {{-- TANGGAL MULAI --}}
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar"></i> Tanggal Mulai
                        </label>
                        <input type="date" class="form-control" id="tanggalMulai" value="2026-03-10" onchange="hitungLamaHari()">
                    </div>

                    {{-- TANGGAL SELESAI --}}
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar"></i> Tanggal Selesai
                        </label>
                        <input type="date" class="form-control" id="tanggalSelesai" value="2026-03-12" onchange="hitungLamaHari()">
                    </div>
                </div>

                {{-- LAMA HARI --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-clock"></i> Lama Hari
                    </label>
                    <input type="text" class="form-control" id="lamaHari" value="3 hari" readonly>
                </div>

                {{-- ALASAN --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-chat"></i> Alasan / Keterangan
                    </label>
                    <textarea class="form-control" id="alasan" placeholder="Masukkan alasan...">Cuti tahunan</textarea>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('tambah')">
                <i class="bi bi-x me-2"></i>Batal
            </button>
            <button class="btn-primary" onclick="simpanPengajuan()">
                <i class="bi bi-save me-2"></i>Ajukan
            </button>
        </div>
    </div>
</div>

{{-- MODAL APPROVE --}}
<div class="modal" id="approveModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="bi bi-check-circle me-2"></i>Konfirmasi Approve
            </h5>
            <button class="modal-close" onclick="closeModal('approve')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-body">
            <div style="font-size: 60px; color: #10b981; text-align: center; margin-bottom: 20px;">
                <i class="bi bi-check-circle"></i>
            </div>
            <h4 style="color: var(--text-primary); text-align: center; margin-bottom: 10px;">Setujui Pengajuan?</h4>
            <p style="color: var(--text-secondary); text-align: center; font-size: 14px;" id="approveText">
                Pengajuan izin/cuti dari <strong>Budi Santoso</strong> akan disetujui.
            </p>
        </div>

        <div class="modal-footer" style="justify-content: center;">
            <button class="btn-secondary" onclick="closeModal('approve')">
                <i class="bi bi-x me-2"></i>Batal
            </button>
            <button class="btn-success" onclick="closeModal('approve')">
                <i class="bi bi-check-lg me-2"></i>Approve
            </button>
        </div>
    </div>
</div>

{{-- MODAL REJECT --}}
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="bi bi-x-circle me-2"></i>Konfirmasi Reject
            </h5>
            <button class="modal-close" onclick="closeModal('reject')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-body">
            <div style="font-size: 60px; color: #ef4444; text-align: center; margin-bottom: 20px;">
                <i class="bi bi-x-circle"></i>
            </div>
            <h4 style="color: var(--text-primary); text-align: center; margin-bottom: 10px;">Tolak Pengajuan?</h4>
            <p style="color: var(--text-secondary); text-align: center; font-size: 14px;" id="rejectText">
                Pengajuan izin/cuti dari <strong>Budi Santoso</strong> akan ditolak.
            </p>
        </div>

        <div class="modal-footer" style="justify-content: center;">
            <button class="btn-secondary" onclick="closeModal('reject')">
                <i class="bi bi-x me-2"></i>Batal
            </button>
            <button class="btn-danger" onclick="closeModal('reject')">
                <i class="bi bi-x-lg me-2"></i>Reject
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi hitung lama hari
    function hitungLamaHari() {
        const tglMulai = document.getElementById('tanggalMulai').value;
        const tglSelesai = document.getElementById('tanggalSelesai').value;

        if (tglMulai && tglSelesai) {
            const mulai = new Date(tglMulai);
            const selesai = new Date(tglSelesai);
            const selisih = Math.ceil((selesai - mulai) / (1000 * 60 * 60 * 24)) + 1;

            if (selisih > 0) {
                document.getElementById('lamaHari').value = selisih + ' hari';
            } else {
                document.getElementById('lamaHari').value = '0 hari';
            }
        }
    }

    // Modal functions
    function openTambahModal() {
        document.getElementById('tambahModal').classList.add('active');
    }

    function openApproveModal(employeeName) {
        document.getElementById('approveText').innerHTML = `Pengajuan izin/cuti dari <strong>${employeeName}</strong> akan disetujui.`;
        document.getElementById('approveModal').classList.add('active');
    }

    function openRejectModal(employeeName) {
        document.getElementById('rejectText').innerHTML = `Pengajuan izin/cuti dari <strong>${employeeName}</strong> akan ditolak.`;
        document.getElementById('rejectModal').classList.add('active');
    }

    function closeModal(type) {
        if (type === 'tambah') {
            document.getElementById('tambahModal').classList.remove('active');
        } else if (type === 'approve') {
            document.getElementById('approveModal').classList.remove('active');
        } else if (type === 'reject') {
            document.getElementById('rejectModal').classList.remove('active');
        }
    }

    function simpanPengajuan() {
        alert('Pengajuan berhasil disimpan');
        closeModal('tambah');
    }

    // Close modal with Escape key
    $(document).keydown(function(e) {
        if (e.key === 'Escape') {
            document.getElementById('tambahModal').classList.remove('active');
            document.getElementById('approveModal').classList.remove('active');
            document.getElementById('rejectModal').classList.remove('active');
        }
    });

    // Close modal when clicking outside
    $(document).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            document.getElementById('tambahModal').classList.remove('active');
            document.getElementById('approveModal').classList.remove('active');
            document.getElementById('rejectModal').classList.remove('active');
        }
    });
</script>
@endpush
