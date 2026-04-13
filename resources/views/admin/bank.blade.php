@extends('layouts.partials.app')

@section('title', 'Data Bank - ' . ($company->name ?? '-'))
@section('page-title', 'Data Bank')

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

    /* ===== CUSTOM NOTIFICATION ===== */
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

    /* Loading button state */
    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.8;
    }

    .btn-loading i {
        animation: spin 1s linear infinite;
    }

    /* ===== CARD TABLE ===== */
    .table-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
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

    /* ===== SEARCH ===== */
    .search-wrapper {
        position: relative;
        min-width: 250px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 14px;
    }

    .search-input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .search-input::placeholder {
        color: var(--text-secondary);
    }

    /* ===== FILTER DROPDOWN ===== */
    .filter-dropdown {
        position: relative;
        display: inline-block;
    }

    .filter-btn {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 16px;
        color: var(--text-primary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        border-color: var(--accent-color);
        background-color: var(--accent-soft);
    }

    .filter-btn i {
        color: var(--accent-color);
    }

    .filter-menu {
        position: absolute;
        top: calc(100% + 5px);
        right: 0;
        width: 150px;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: var(--shadow);
        padding: 8px 0;
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
    }

    .filter-dropdown.active .filter-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .filter-item {
        padding: 8px 16px;
        color: var(--text-primary);
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-item:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .filter-item i {
        width: 18px;
        color: var(--text-secondary);
    }

    .filter-item:hover i {
        color: var(--accent-color);
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
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
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
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
    }

    .table thead th:hover {
        background-color: rgba(22, 163, 74, 0.1);
    }

    .table thead th i.sort-icon {
        margin-left: 5px;
        font-size: 12px;
        opacity: 0.5;
    }

    .table thead th.active-sort i.sort-icon {
        opacity: 1;
        color: var(--accent-color);
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

    /* Kolom No lebih kecil */
    .table th:first-child,
    .table td:first-child {
        width: 60px;
        text-align: center;
    }

    /* ===== ICON DI THEAD ===== */
    .table thead th i:not(.sort-icon) {
        color: var(--accent-color);
    }

    /* ===== ACTION BUTTONS ===== */
    .action-btns {
        display: flex;
        gap: 4px;
        justify-content: center;
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

    /* ===== PAGINATION INFO ===== */
    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    .info-text {
        color: var(--text-secondary);
        font-size: 14px;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .page-link:hover {
        background-color: var(--accent-soft);
        border-color: var(--accent-color);
        color: var(--accent-color);
    }

    .page-item.active .page-link {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        color: white;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
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
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
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
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
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

    /* ===== NO DATA STYLE ===== */
    .no-data {
        text-align: center;
        padding: 40px;
    }

    .no-data i {
        font-size: 48px;
        color: var(--text-secondary);
        margin-bottom: 16px;
    }

    .no-data p {
        color: var(--text-secondary);
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

        .search-wrapper {
            min-width: 100%;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .pagination-info {
            flex-direction: column;
            align-items: center;
            text-align: center;
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

        .notification {
            min-width: 250px;
            max-width: 300px;
            padding: 12px 16px;
        }

        .loading-spinner {
            padding: 20px 30px;
        }

        .loading-spinner i {
            font-size: 40px;
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
                {{-- TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title"><i class="bi bi-bank me-2"></i>Data Bank</h3>

                        <div class="table-actions">
                            {{-- SEARCH --}}
                            <div class="search-wrapper">
                                <i class="search-icon bi bi-search"></i>
                                <input type="text" class="search-input" id="searchInput" placeholder="Cari bank...">
                            </div>

                            {{-- FILTER DROPDOWN (untuk tampil per page) --}}
                            <div class="filter-dropdown" id="filterDropdown">
                                <div class="filter-btn">
                                    <i class="bi bi-sort-numeric-down"></i>
                                    <span>Tampil <span id="selectedPerPage">5</span></span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="filter-menu">
                                    <div class="filter-item {{ 5 == 5 ? 'active' : '' }}" data-perpage="5">5 data</div>
                                    <div class="filter-item" data-perpage="10">10 data</div>
                                    <div class="filter-item" data-perpage="15">15 data</div>
                                    <div class="filter-item" data-perpage="20">20 data</div>
                                </div>
                            </div>

                            {{-- TOMBOL TAMBAH --}}
                            <button class="btn-add" onclick="openModal('tambah')">
                                <i class="bi bi-plus-circle"></i>
                                <span>Tambah Bank</span>
                            </button>
                        </div>
                    </div>

                    {{-- TABEL BANK --}}
                    <div class="table-responsive">
                        <table class="table" id="bankTable">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <i class="bi bi-hash me-1"></i>No
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th class="text-left">
                                        <i class="bi bi-bank me-1"></i>Nama Bank
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th class="text-center">
                                        <i class="bi bi-tools me-1"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banks as $index => $bank)
                                <tr data-id="{{ $bank->id }}" data-name="{{ $bank->name }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-left">{{ $bank->name }}</td>
                                    <td class="text-center">
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', {{ $bank->id }}, '{{ $bank->name }}')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', {{ $bank->id }}, '{{ $bank->name }}')">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="no-data-row">
                                    <td colspan="3" class="text-center no-data">
                                        <i class="bi bi-bank"></i>
                                        <p>Belum ada data bank</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION INFO --}}
                    <div class="pagination-info">
                        <div class="info-text" id="paginationText">
                            Menampilkan 0 - 0 dari 0 data
                        </div>

                        <ul class="pagination" id="pagination"></ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- FOOTER --}}
        @include('layouts.footer')
    </div>
</div>

{{-- MODAL TAMBAH/EDIT BANK --}}
<div class="modal" id="bankModal">
    <div class="modal-content">
        <form id="bankForm" method="POST" action="">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Bank
                </h5>
                <button type="button" class="modal-close" onclick="closeModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                {{-- NAMA BANK --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-bank"></i>
                        Nama Bank
                    </label>
                    <input type="text" class="form-control" name="name" id="namaBank" placeholder="Masukkan nama bank" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">
                    <i class="bi bi-x me-2"></i>Batal
                </button>
                <button type="submit" class="btn-primary" id="submitBtn">
                    <i class="bi bi-save me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal" id="hapusModal">
    <div class="modal-content" style="max-width: 400px;">
        <form id="hapusForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-trash me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="modal-close" onclick="closeHapusModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body" style="text-align: center; padding: 30px 24px;">
                <div style="font-size: 60px; color: #ef4444; margin-bottom: 20px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h4 style="color: var(--text-primary); margin-bottom: 10px;">Yakin ingin menghapus?</h4>
                <p style="color: var(--text-secondary); font-size: 14px;" id="hapusText">
                    Bank <strong></strong> akan dihapus permanen.
                </p>
            </div>

            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn-secondary" onclick="closeHapusModal()">
                    <i class="bi bi-x me-2"></i>Batal
                </button>
                <button type="submit" class="btn-danger" id="deleteBtn">
                    <i class="bi bi-trash me-2"></i>Hapus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Include table-handler.js --}}
<script src="{{ asset('js/table-handler.js') }}"></script>

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

        // Auto remove after 3 seconds
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

        // Close on click close button
        notification.querySelector('.notification-close').addEventListener('click', function() {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        });
    }

    // ===== SHOW NOTIFICATIONS FROM SESSION =====
    @if(session('success'))
        showNotification('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showNotification('error', '{{ session('error') }}');
    @endif

    @if($errors->any())
        showNotification('error', '{{ $errors->first() }}', 'Validasi Gagal');
    @endif

    $(document).ready(function() {
        // ===== VARIABLES =====
        let currentPage = 1;
        let perPage = 5;
        let allRows = [];
        let filteredRows = [];

        // ===== LOAD ALL ROWS =====
        function loadAllRows() {
            allRows = [];
            $('#bankTable tbody tr').each(function() {
                const $row = $(this);
                if (!$row.hasClass('no-data-row')) {
                    allRows.push({
                        id: $row.data('id'),
                        name: $row.data('name'),
                        element: $row,
                        index: allRows.length,
                        no: parseInt($row.find('td:eq(0)').text())
                    });
                }
            });
            filteredRows = [...allRows];
        }

        // ===== RENDER TABLE =====
        function renderTable() {
            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, filteredRows.length);
            const pageRows = filteredRows.slice(start, end);

            // Sembunyikan semua row
            $('#bankTable tbody tr').hide();

            if (pageRows.length === 0) {
                if ($('#bankTable tbody .no-data-row').length === 0) {
                    $('#bankTable tbody').html('<tr class="no-data-row"><td colspan="3" class="text-center no-data"><i class="bi bi-bank"></i><p>Tidak ada data ditemukan</p></td></tr>');
                } else {
                    $('#bankTable tbody .no-data-row').show();
                }
            } else {
                $('#bankTable tbody .no-data-row').remove();

                pageRows.forEach((row, index) => {
                    const $row = row.element;
                    const rowNumber = start + index + 1;

                    // Update nomor urut
                    $row.find('td:eq(0)').text(rowNumber);
                    $row.show();
                });
            }

            // Update info text
            const total = filteredRows.length;
            const startDisplay = total === 0 ? 0 : start + 1;
            const endDisplay = Math.min(end, total);
            $('#paginationText').text(`Menampilkan ${startDisplay} - ${endDisplay} dari ${total} data`);

            renderPagination();
        }

        // ===== RENDER PAGINATION =====
        function renderPagination() {
            const totalPages = Math.ceil(filteredRows.length / perPage);
            let html = '';

            if (totalPages <= 1) {
                html = `
                    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
                `;
            } else {
                // Previous button
                if (currentPage === 1) {
                    html += '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>';
                } else {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></span></li>`;
                }

                // Page numbers (maksimal 5)
                const maxVisible = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                let endPage = Math.min(totalPages, startPage + maxVisible - 1);

                if (endPage - startPage + 1 < maxVisible) {
                    startPage = Math.max(1, endPage - maxVisible + 1);
                }

                // First page + dots
                if (startPage > 1) {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(1)">1</span></li>`;
                    if (startPage > 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                // Page numbers
                for (let i = startPage; i <= endPage; i++) {
                    if (i === currentPage) {
                        html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else {
                        html += `<li class="page-item"><span class="page-link" onclick="changePage(${i})">${i}</span></li>`;
                    }
                }

                // Last page + dots
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${totalPages})">${totalPages}</span></li>`;
                }

                // Next button
                if (currentPage === totalPages) {
                    html += '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>';
                } else {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></span></li>`;
                }
            }

            $('#pagination').html(html);
        }

        // ===== CHANGE PAGE =====
        window.changePage = function(page) {
            currentPage = page;
            renderTable();
        };

        // ===== INIT TABLE HANDLER =====
        if (typeof TableHandler !== 'undefined') {
            TableHandler.init('bankTable', {
                searchInputId: 'searchInput',
                sortableColumns: [0, 1],
                defaultSortColumn: 0,
                defaultSortOrder: 'asc',
                onDataChange: function(filteredData) {
                    // Update filtered rows
                    filteredRows = filteredData.map(item => ({
                        id: item.id,
                        name: item.name,
                        element: item.element,
                        index: item.index,
                        no: item.index + 1
                    }));

                    // Reset ke halaman 1 dan render ulang
                    currentPage = 1;
                    renderTable();
                }
            });
        }

        // ===== LOAD INITIAL DATA =====
        loadAllRows();
        filteredRows = [...allRows];
        renderTable();

        // ===== FILTER DROPDOWN =====
        $('#filterDropdown .filter-btn').click(function(e) {
            e.stopPropagation();
            $('#filterDropdown').toggleClass('active');
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('#filterDropdown').length) {
                $('#filterDropdown').removeClass('active');
            }
        });

        // Per page change
        $('.filter-item').click(function() {
            const newPerPage = $(this).data('perpage');
            $('#selectedPerPage').text(newPerPage);
            perPage = newPerPage;
            currentPage = 1;
            $('#filterDropdown').removeClass('active');
            renderTable();
        });

        // ===== FORM SUBMIT WITH LOADING =====
        $('#bankForm').on('submit', function(e) {
            // Tampilkan loading
            showLoading('Menyimpan data...');

            // Disable submit button
            $('#submitBtn').addClass('btn-loading').prop('disabled', true);

            // Biarkan form submit normally
            return true;
        });

        $('#hapusForm').on('submit', function(e) {
            // Tampilkan loading
            showLoading('Menghapus data...');

            // Disable delete button
            $('#deleteBtn').addClass('btn-loading').prop('disabled', true);

            // Biarkan form submit normally
            return true;
        });
    });

    // ===== MODAL FUNCTIONS =====
    function openModal(type, id = null, bankName = '') {
        if (type === 'hapus') {
            if (id && bankName) {
                document.getElementById('hapusText').innerHTML = `Bank <strong>${bankName}</strong> akan dihapus permanen.`;
                document.getElementById('hapusForm').action = `{{ url('banks') }}/${id}`;
            }
            document.getElementById('hapusModal').classList.add('active');
        } else {
            const modal = document.getElementById('bankModal');
            const title = document.getElementById('modalTitle');
            const inputNama = document.getElementById('namaBank');
            const form = document.getElementById('bankForm');
            const methodField = document.getElementById('methodField');
            const submitBtn = document.getElementById('submitBtn');

            if (type === 'edit' && id) {
                title.innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Bank';
                form.action = `{{ url('banks') }}/${id}`;
                methodField.value = 'PUT';
                if (bankName) {
                    inputNama.value = bankName;
                }
            } else {
                title.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Bank';
                form.action = '{{ route("banks.store") }}';
                methodField.value = 'POST';
                inputNama.value = '';
            }

            // Reset submit button
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;

            modal.classList.add('active');
        }
    }

    function closeModal() {
        document.getElementById('bankModal').classList.remove('active');
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

    // Hide loading when page loads (in case of back button)
    window.addEventListener('pageshow', function() {
        hideLoading();
    });
</script>
@endpush
