@extends('layouts.partials.app')

@section('title', 'Data Jabatan - ' . ($company->name ?? '-'))
@section('page-title', 'Data Jabatan')

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

    .table-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
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

    /* ===== ACTION BUTTONS ===== */
    .action-btns {
        display: flex;
        gap: 4px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.edit:hover {
        background-color: #10b981;
        color: white;
        border-color: #10b981;
    }

    .action-btn.delete:hover {
        background-color: #ef4444;
        color: white;
        border-color: #ef4444;
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
        max-width: 450px;
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
    .form-group {
        margin-bottom: 20px;
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

    .btn-primary {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #ff4d88;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 103, 154, 0.3);
    }

    .btn-secondary {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-actions {
            flex-direction: column;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
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
                {{-- TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title"><i class="bi bi-briefcase me-2"></i>Data Jabatan</h3>

                        <div class="table-actions">
                            {{-- TOMBOL TAMBAH --}}
                            <button class="btn-add" onclick="openModal('tambah')">
                                <i class="bi bi-plus-circle"></i>
                                <span>Tambah Jabatan</span>
                            </button>
                        </div>
                    </div>

                    {{-- TABEL JABATAN --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>No</th>
                                    <th><i class="bi bi-briefcase me-1"></i>Nama Jabatan</th>
                                    <th><i class="bi bi-tools me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- CONTOH DATA 1 --}}
                                <tr>
                                    <td>1</td>
                                    <td>Manager</td>
                                    <td>
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', 'Manager')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', 'Manager')">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- CONTOH DATA 2 --}}
                                <tr>
                                    <td>2</td>
                                    <td>Supervisor</td>
                                    <td>
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', 'Supervisor')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', 'Supervisor')">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- CONTOH DATA 3 --}}
                                <tr>
                                    <td>3</td>
                                    <td>Staff</td>
                                    <td>
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', 'Staff')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', 'Staff')">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- CONTOH DATA 4 --}}
                                <tr>
                                    <td>4</td>
                                    <td>Programmer</td>
                                    <td>
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', 'Programmer')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', 'Programmer')">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- CONTOH DATA 5 --}}
                                <tr>
                                    <td>5</td>
                                    <td>Admin</td>
                                    <td>
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', 'Admin')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', 'Admin')">
                                                <i class="bi bi-trash"></i>
                                            </div>
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

{{-- MODAL TAMBAH/EDIT JABATAN --}}
<div class="modal" id="jabatanModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">
                <i class="bi bi-plus-circle me-2"></i>Tambah Jabatan
            </h5>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-body">
            <form id="jabatanForm">
                {{-- NAMA JABATAN --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-briefcase"></i>
                        Nama Jabatan
                    </label>
                    <input type="text" class="form-control" id="namaJabatan" placeholder="Masukkan nama jabatan" value="Manager">
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal()">
                <i class="bi bi-x me-2"></i>Batal
            </button>
            <button class="btn-primary" onclick="closeModal()">
                <i class="bi bi-save me-2"></i>Simpan
            </button>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal" id="hapusModal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="bi bi-trash me-2"></i>Konfirmasi Hapus
            </h5>
            <button class="modal-close" onclick="closeHapusModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center; padding: 30px 24px;">
            <div style="font-size: 60px; color: var(--accent-color); margin-bottom: 20px;">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h4 style="color: var(--text-primary); margin-bottom: 10px;">Yakin ingin menghapus?</h4>
            <p style="color: var(--text-secondary); font-size: 14px;" id="hapusText">
                Jabatan <strong>Manager</strong> akan dihapus permanen.
            </p>
        </div>

        <div class="modal-footer" style="justify-content: center;">
            <button class="btn-secondary" onclick="closeHapusModal()">
                <i class="bi bi-x me-2"></i>Batal
            </button>
            <button class="btn-danger" onclick="closeHapusModal()">
                <i class="bi bi-trash me-2"></i>Hapus
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentJabatan = '';

    // Modal functions
    function openModal(type, jabatanName = '') {
        if (type === 'hapus') {
            if (jabatanName) {
                document.getElementById('hapusText').innerHTML = `Jabatan <strong>${jabatanName}</strong> akan dihapus permanen.`;
            }
            document.getElementById('hapusModal').classList.add('active');
        } else {
            const modal = document.getElementById('jabatanModal');
            const title = document.getElementById('modalTitle');
            const inputNama = document.getElementById('namaJabatan');

            if (type === 'edit') {
                title.innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Jabatan';
                if (jabatanName) {
                    inputNama.value = jabatanName;
                }
            } else {
                title.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Jabatan';
                inputNama.value = '';
            }

            modal.classList.add('active');
        }
    }

    function closeModal() {
        document.getElementById('jabatanModal').classList.remove('active');
    }

    function closeHapusModal() {
        document.getElementById('hapusModal').classList.remove('active');
    }

    // Close modal with Escape key
    $(document).keydown(function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeHapusModal();
        }
    });

    // Close modal when clicking outside
    $(document).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            closeModal();
            closeHapusModal();
        }
    });
</script>
@endpush
