@extends('layouts.partials.app')

@section('title', 'Data Karyawan - ' . ($company->name ?? '-'))
@section('page-title', 'Data Karyawan')

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

    /* ===== CARD STATISTIK ===== */
    .stat-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
    }

    .stat-icon-wrapper {
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

    .stat-content {
        flex: 1;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 13px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        margin-bottom: 2px;
    }

    .stat-desc {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .stat-change {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-change.positif { color: #10b981; }
    .stat-change.neutral { color: var(--text-secondary); }

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
        margin-left: auto;
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
        width: 220px;
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
        text-decoration: none;
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

    .filter-item.active {
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .filter-divider {
        height: 1px;
        background-color: var(--border-color);
        margin: 8px 0;
    }

    /* ===== PER PAGE FILTER ===== */
    .perpage-dropdown {
        position: relative;
        display: inline-block;
    }

    .perpage-btn {
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

    .perpage-btn:hover {
        border-color: var(--accent-color);
        background-color: var(--accent-soft);
    }

    .perpage-menu {
        position: absolute;
        top: calc(100% + 5px);
        right: 0;
        width: 100px;
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

    .perpage-dropdown.active .perpage-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .perpage-item {
        padding: 8px 16px;
        color: var(--text-primary);
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .perpage-item:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .perpage-item.active {
        background-color: var(--accent-color);
        color: white;
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

    /* ===== BADGE LIBUR ===== */
    .badge-libur {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 500;
        background-color: var(--accent-soft);
        color: var(--accent-color);
        border: 1px solid var(--accent-color);
    }

    .badge-full-kerja {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 500;
        background-color: #6b7280;
        color: white;
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
        background-color: rgba(22, 163, 74, 0.2);
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
    .table thead th i {
        color: var(--accent-color);
    }

    /* ===== AVATAR + NAMA ===== */
    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--accent-soft);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
    }

    .employee-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    /* ===== BADGE STATUS ===== */
    .badge-status {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        white-space: nowrap;
        text-align: center;
    }

    .badge-aktif {
        background-color: #10b981;
        color: white;
    }

    .badge-nonaktif {
        background-color: #ef4444;
        color: white;
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

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-info {
        color: var(--text-secondary);
        font-size: 13px;
    }

    .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .page-item {
        display: inline-block;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 6px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-card);
        color: var(--text-primary);
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .page-link:hover {
        border-color: var(--accent-color);
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .page-item.active .page-link {
        background-color: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
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

    /* ===== UPLOAD FOTO ===== */
    .upload-container {
        margin-bottom: 20px;
    }

    .preview-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 2px dashed var(--border-color);
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        background-color: var(--accent-soft);
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-image i {
        font-size: 40px;
        color: var(--text-secondary);
    }

    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .upload-btn {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
    }

    .upload-btn:hover {
        background: #15803d;
    }

    .upload-btn-wrapper input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
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
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
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

    /* ===== RESPONSIVE CARD STATISTIK ===== */
    /* Tablet (768px - 992px) */
    @media (min-width: 769px) and (max-width: 992px) {
        .content-area {
            padding: 20px;
        }

        .stat-card {
            padding: 16px;
            gap: 12px;
        }

        .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .stat-value {
            font-size: 20px;
        }

        .stat-label {
            font-size: 12px;
        }

        .stat-change, .stat-desc {
            font-size: 11px;
        }
    }

    /* Mobile (576px - 768px) */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .row {
            margin-right: -8px;
            margin-left: -8px;
        }

        [class*="col-"] {
            padding-right: 8px;
            padding-left: 8px;
        }

        .stat-card {
            padding: 14px;
            margin-bottom: 8px;
        }

        .stat-icon-wrapper {
            width: 36px;
            height: 36px;
            font-size: 18px;
        }

        .stat-value {
            font-size: 18px;
        }

        .stat-label {
            font-size: 11px;
        }

        .stat-change, .stat-desc {
            font-size: 10px;
        }
    }

    /* Mobile kecil (< 576px) */
    @media (max-width: 576px) {
        .content-area {
            padding: 12px;
        }

        .stat-card {
            padding: 12px;
            gap: 8px;
        }

        .stat-icon-wrapper {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .stat-value {
            font-size: 16px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-change, .stat-desc {
            font-size: 9px;
        }
    }

    /* ===== RESPONSIVE TABLET (768px - 992px) ===== */
    @media (min-width: 769px) and (max-width: 992px) {
        .table-header {
            gap: 12px;
        }

        .table-actions {
            gap: 8px;
        }

        .search-wrapper {
            min-width: 200px;
        }

        .filter-btn, .perpage-btn, .btn-add {
            padding: 8px 14px;
            font-size: 13px;
        }

        /* Table dengan scroll */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -1px;
            width: 100%;
        }

        .table {
            min-width: 1000px;
        }

        .table thead th {
            font-size: 13px;
            padding: 10px 6px;
        }

        .table tbody td {
            font-size: 13px;
            padding: 10px 6px;
        }

        .employee-info {
            gap: 10px;
        }

        .avatar-placeholder, .employee-avatar {
            width: 36px;
            height: 36px;
            font-size: 13px;
        }

        .employee-name {
            font-size: 13px;
        }

        .badge-status {
            padding: 5px 10px;
            font-size: 11px;
            min-width: 70px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
        }

        .pagination-wrapper {
            gap: 15px;
        }

        .pagination-info {
            font-size: 12px;
        }

        .page-link {
            min-width: 34px;
            height: 34px;
            font-size: 12px;
        }

        .modal-content {
            max-width: 450px;
        }

        .modal-header, .modal-body, .modal-footer {
            padding: 18px 22px;
        }

        .modal-title {
            font-size: 17px;
        }

        .form-label {
            font-size: 13px;
        }

        .form-control, .form-select {
            padding: 10px 14px;
            font-size: 13px;
        }

        .btn-primary, .btn-secondary, .btn-danger {
            padding: 10px 22px;
            font-size: 13px;
        }

        .preview-image {
            width: 100px;
            height: 100px;
        }

        .upload-btn {
            padding: 8px 16px;
            font-size: 13px;
        }
    }

    /* ===== RESPONSIVE MOBILE (< 768px) ===== */
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .table-actions {
            flex-direction: column;
            margin-left: 0;
            gap: 8px;
        }

        .search-wrapper {
            min-width: 100%;
        }

        .filter-btn, .perpage-btn {
            width: 100%;
            justify-content: center;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        /* Filter menu */
        .filter-menu {
            width: 100%;
            right: 0;
            left: 0;
        }

        .perpage-menu {
            width: 100%;
            right: 0;
            left: 0;
        }

        /* Table dengan horizontal scroll */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -1px;
            width: 100%;
            border-radius: 8px;
        }

        .table-responsive {
            background: linear-gradient(to right, var(--bg-card) 30%, rgba(255,255,255,0)),
                        linear-gradient(to right, rgba(255,255,255,0), var(--bg-card) 70%) 0 100%,
                        radial-gradient(farthest-side at 0% 50%, rgba(0,0,0,0.2), rgba(0,0,0,0)),
                        radial-gradient(farthest-side at 100% 50%, rgba(0,0,0,0.2), rgba(0,0,0,0)) 0 100%;
            background-repeat: no-repeat;
            background-color: var(--bg-card);
            background-size: 40px 100%, 40px 100%, 14px 100%, 14px 100%;
            background-position: 0 0, 100% 0, 0 0, 100% 0;
            background-attachment: local, local, scroll, scroll;
        }

        .table {
            min-width: 1000px;
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            font-size: 12px;
            padding: 8px 6px;
            white-space: nowrap;
        }

        .table thead th i {
            margin-right: 4px;
        }

        .table tbody td {
            font-size: 12px;
            padding: 8px 6px;
            white-space: nowrap;
        }

        /* Employee info */
        .employee-info {
            gap: 8px;
        }

        .avatar-placeholder, .employee-avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .employee-name {
            font-size: 12px;
            white-space: nowrap;
        }

        /* Badge status */
        .badge-status {
            padding: 4px 8px;
            font-size: 11px;
            min-width: 65px;
            white-space: nowrap;
        }

        .badge-status i {
            margin-right: 4px;
        }

        /* Action buttons */
        .action-btn {
            width: 28px;
            height: 28px;
        }

        /* Pagination */
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .pagination-info {
            font-size: 12px;
            text-align: center;
        }

        .pagination {
            gap: 4px;
        }

        .page-link {
            min-width: 32px;
            height: 32px;
            font-size: 12px;
        }

        /* Modal */
        .modal-content {
            width: 95%;
            max-width: 100%;
            margin: 0 10px;
        }

        .modal-header, .modal-body, .modal-footer {
            padding: 16px 18px;
        }

        .modal-title {
            font-size: 16px;
        }

        .modal-close {
            width: 30px;
            height: 30px;
            font-size: 18px;
        }

        .modal-footer {
            flex-direction: column-reverse;
            gap: 8px;
        }

        .modal-footer button {
            width: 100%;
        }

        /* Form */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .form-label i {
            margin-right: 4px;
        }

        .form-control, .form-select {
            padding: 10px 14px;
            font-size: 13px;
        }

        .btn-primary, .btn-secondary, .btn-danger {
            padding: 10px 20px;
            font-size: 13px;
            width: 100%;
        }

        .btn-primary i, .btn-secondary i, .btn-danger i {
            margin-right: 6px;
        }

        /* Upload foto */
        .upload-container {
            margin-bottom: 16px;
        }

        .preview-image {
            width: 100px;
            height: 100px;
        }

        .preview-image i {
            font-size: 36px;
        }

        .upload-btn {
            padding: 8px 16px;
            font-size: 13px;
        }

        .upload-btn i {
            margin-right: 4px;
        }

        /* Notification & Loading */
        .notification {
            min-width: 220px;
            max-width: 300px;
            padding: 12px 16px;
            gap: 10px;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .notification-title {
            font-size: 13px;
        }

        .notification-message {
            font-size: 12px;
        }

        .loading-spinner {
            padding: 20px 30px;
        }

        .loading-spinner i {
            font-size: 40px;
        }

        .loading-spinner p {
            font-size: 14px;
        }

        .loading-spinner small {
            font-size: 12px;
        }
    }

    /* ===== RESPONSIVE MOBILE KECIL (< 576px) ===== */
    @media (max-width: 576px) {
        .table {
            min-width: 900px;
        }

        .table thead th {
            font-size: 11px;
            padding: 6px 4px;
        }

        .table tbody td {
            font-size: 11px;
            padding: 6px 4px;
        }

        .employee-info {
            gap: 6px;
        }

        .avatar-placeholder, .employee-avatar {
            width: 28px;
            height: 28px;
            font-size: 11px;
        }

        .employee-name {
            font-size: 11px;
        }

        .badge-status {
            padding: 3px 6px;
            font-size: 10px;
            min-width: 55px;
        }

        .action-btn {
            width: 26px;
            height: 26px;
            font-size: 11px;
        }

        .page-link {
            min-width: 28px;
            height: 28px;
            font-size: 11px;
        }

        .modal-title {
            font-size: 15px;
        }

        .modal-header, .modal-body, .modal-footer {
            padding: 14px 16px;
        }

        .form-label {
            font-size: 12px;
        }

        .form-control, .form-select {
            padding: 8px 12px;
            font-size: 12px;
        }

        .btn-primary, .btn-secondary, .btn-danger {
            padding: 8px 16px;
            font-size: 12px;
        }

        .preview-image {
            width: 80px;
            height: 80px;
        }

        .preview-image i {
            font-size: 30px;
        }

        .upload-btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        .notification {
            min-width: 180px;
            max-width: 260px;
            padding: 10px 14px;
        }

        .notification-icon {
            width: 28px;
            height: 28px;
            font-size: 14px;
        }

        .notification-title {
            font-size: 12px;
        }

        .notification-message {
            font-size: 11px;
        }

        .loading-spinner {
            padding: 15px 20px;
        }

        .loading-spinner i {
            font-size: 36px;
        }

        .loading-spinner p {
            font-size: 13px;
        }

        .loading-spinner small {
            font-size: 11px;
        }
    }

    /* ===== RESPONSIVE LANDSCAPE ===== */
    @media (max-height: 500px) and (orientation: landscape) {
        .modal-content {
            max-height: 85vh;
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }

        .loading-spinner {
            padding: 15px 25px;
        }

        .loading-spinner i {
            font-size: 30px;
            margin-bottom: 8px;
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
               {{-- ROW 1: CARD STATISTIK (RESPONSIVE) --}}
<div class="row g-3 g-md-4 mb-3 mb-md-4">
    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Karyawan</div>
                <div class="stat-value">{{ $totalKaryawan }}</div>
                <div class="stat-change positif">
                    <i class="bi bi-arrow-up"></i> +{{ $totalAktif }} aktif
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Karyawan Aktif</div>
                <div class="stat-value">{{ $totalAktif }}</div>
                <div class="stat-change neutral">
                    <i class="bi bi-check-circle"></i>
                    @php
                        $persenAktif = $totalKaryawan > 0 ? round(($totalAktif/$totalKaryawan)*100) : 0;
                    @endphp
                    {{ $persenAktif }}%
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper">
                <i class="bi bi-person-x-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Karyawan Nonaktif</div>
                <div class="stat-value">{{ $totalNonaktif }}</div>
                <div class="stat-desc">
                    @php
                        $persenNonaktif = $totalKaryawan > 0 ? round(($totalNonaktif/$totalKaryawan)*100) : 0;
                    @endphp
                    {{ $persenNonaktif }}%
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper">
                <i class="bi bi-building"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Divisi</div>
                <div class="stat-value">{{ $divisions->count() }}</div>
                <div class="stat-desc">Unit kerja</div>
            </div>
        </div>
    </div>
</div>
                {{-- ROW 2: TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title"><i class="bi bi-list-ul me-2"></i>Data Karyawan</h3>

                        <div class="table-actions">
                            {{-- SEARCH --}}
                            <div class="search-wrapper">
                                <i class="search-icon bi bi-search"></i>
                                <input type="text" id="searchInput" class="search-input"
                                       placeholder="Cari karyawan..." onkeyup="filterEmployees()">
                            </div>

                            {{-- FILTER DIVISI DROPDOWN --}}
                            <div class="filter-dropdown" id="filterDivisiDropdown">
                                <div class="filter-btn">
                                    <i class="bi bi-building"></i>
                                    <span id="selectedDivision">Semua Divisi</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="filter-menu">
                                    <a href="javascript:void(0)" onclick="setFilter('division', '', 'Semua Divisi')"
                                       class="filter-item active" data-value="">
                                        <i class="bi bi-briefcase"></i>
                                        Semua Divisi
                                    </a>
                                    @foreach($divisions as $division)
                                    <a href="javascript:void(0)" onclick="setFilter('division', '{{ $division->id }}', '{{ $division->name }}')"
                                       class="filter-item" data-value="{{ $division->id }}">
                                        <i class="bi bi-building"></i>
                                        {{ $division->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>

                            {{-- FILTER STATUS DROPDOWN --}}
                            <div class="filter-dropdown" id="filterStatusDropdown">
                                <div class="filter-btn">
                                    <i class="bi bi-check-circle"></i>
                                    <span id="selectedStatus">Semua Status</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="filter-menu">
                                    <a href="javascript:void(0)" onclick="setFilter('status', '', 'Semua Status')"
                                       class="filter-item active" data-value="">
                                        <i class="bi bi-list-ul"></i>
                                        Semua Status
                                    </a>
                                    <div class="filter-divider"></div>
                                    <a href="javascript:void(0)" onclick="setFilter('status', 'Aktif', 'Aktif')"
                                       class="filter-item" data-value="Aktif">
                                        <i class="bi bi-check-circle" style="color: #10b981;"></i>
                                        Aktif
                                    </a>
                                    <a href="javascript:void(0)" onclick="setFilter('status', 'Nonaktif', 'Nonaktif')"
                                       class="filter-item" data-value="Nonaktif">
                                        <i class="bi bi-x-circle" style="color: #ef4444;"></i>
                                        Nonaktif
                                    </a>
                                </div>
                            </div>

                            {{-- PER PAGE FILTER --}}
                            <div class="perpage-dropdown" id="perpageDropdown">
                                <div class="perpage-btn">
                                    <i class="bi bi-list"></i>
                                    <span id="selectedPerPage">Tampil: 5</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="perpage-menu">
                                    @foreach([5,10,15,20,50] as $value)
                                    <a href="javascript:void(0)" onclick="setPerPage({{ $value }})"
                                       class="perpage-item {{ $value == 5 ? 'active' : '' }}" data-value="{{ $value }}">
                                        {{ $value }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>

                            {{-- TOMBOL TAMBAH --}}
                            <button class="btn-add" onclick="openModal('tambah')">
                                <i class="bi bi-plus-circle"></i>
                                <span>Tambah Karyawan</span>
                            </button>
                        </div>
                    </div>

                    {{-- TABEL KARYAWAN --}}
                    <div class="table-responsive">
                        <table class="table" id="employeeTable">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>No</th>
                                    <th><i class="bi bi-person me-1"></i>Nama</th>
                                    <th><i class="bi bi-credit-card me-1"></i>No Rekening</th>
                                    <th><i class="bi bi-bank me-1"></i>Bank</th>
                                    <th><i class="bi bi-phone me-1"></i>No HP</th>
                                    <th><i class="bi bi-building me-1"></i>Divisi</th>
                                    <th><i class="bi bi-calendar-week me-1"></i>Hari Libur</th>
                                    <th><i class="bi bi-check-circle me-1"></i>Status</th>
                                    <th><i class="bi bi-tools me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                <tr class="employee-row" 
                                    data-name="{{ strtolower($employee->name) }}" 
                                    data-division-id="{{ $employee->division_id }}" 
                                    data-status="{{ $employee->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="employee-info">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                                     alt="{{ $employee->name }}"
                                                     class="employee-avatar">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="employee-name">{{ $employee->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->account_number ?? '-' }}</td>
                                    <td>{{ $employee->bank->name ?? '-' }}</td>
                                    <td>{{ $employee->phone ?? '-' }}</td>
                                    <td>{{ $employee->division->name ?? '-' }}</td>
                                    <td>
                                        @if($employee->regular_off_day)
                                            @if($employee->regular_off_day == 'Tidak Libur')
                                                <span class="badge-full-kerja">
                                                    <i class="bi bi-briefcase"></i> Full Kerja
                                                </span>
                                            @else
                                                <span class="badge-libur">
                                                    <i class="bi bi-calendar-check"></i> {{ $employee->regular_off_day }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge-full-kerja" style="background-color: #9ca3af;">
                                                <i class="bi bi-dash"></i> Belum diatur
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $employee->status == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                                            <i class="bi bi-{{ $employee->status == 'Aktif' ? 'check' : 'x' }}-circle me-1"></i>
                                            {{ $employee->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <div class="action-btn edit" onclick="openModal('edit', {{ $employee->id }}, '{{ $employee->name }}')">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="action-btn delete" onclick="openModal('hapus', {{ $employee->id }}, '{{ $employee->name }}')">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr id="noDataRow">
                                    <td colspan="9" class="text-center py-5">
                                        <div class="no-data-content">
                                            <i class="bi bi-people" style="font-size: 48px; color: var(--text-secondary); opacity: 0.5;"></i>
                                            <p class="mt-3 text-secondary">Belum ada data karyawan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                                <tr id="emptySearchRow" style="display: none;">
                                    <td colspan="9" class="text-center py-5">
                                        <div class="no-data-content">
                                            <i class="bi bi-search" style="font-size: 48px; color: var(--text-secondary); opacity: 0.5;"></i>
                                            <p class="mt-3 text-secondary">Tidak ada karyawan yang sesuai dengan kriteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    {{-- PAGINATION (Client Side) --}}
                    <div class="pagination-wrapper" id="paginationWrapper">
                        <div class="pagination-info" id="paginationInfo">
                            {{-- Will be filled by JS --}}
                        </div>
                        <ul class="pagination" id="paginationList">
                            {{-- Will be filled by JS --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- FOOTER --}}
        @include('layouts.footer')
    </div>
</div>

{{-- MODAL TAMBAH/EDIT KARYAWAN --}}
<div class="modal" id="karyawanModal">
    <div class="modal-content">
        <form id="karyawanForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-person-plus me-2"></i>Tambah Karyawan
                </h5>
                <button type="button" class="modal-close" onclick="closeModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                {{-- UPLOAD FOTO --}}
                <div class="upload-container">
                    <div class="preview-image" id="imagePreview">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="upload-btn-wrapper">
                        <button class="upload-btn" type="button">
                            <i class="bi bi-camera"></i>
                            Upload Foto
                        </button>
                        <input type="file" name="photo" id="fileUpload" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <small style="color: var(--text-secondary); display: block; text-align: center; margin-top: 8px;">
                        Format: JPG, PNG. Maks 2MB (Opsional)
                    </small>
                </div>

                {{-- NAMA --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person"></i>
                        Nama Lengkap <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>

                {{-- DIVISI --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-building"></i>
                        Divisi <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="division_id" id="division_id" class="form-select" required>
                        <option value="">Pilih Divisi</option>
                        @foreach($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- STATUS --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-check-circle"></i>
                        Status <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                {{-- NO REKENING --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-credit-card"></i>
                        Nomor Rekening
                    </label>
                    <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Masukkan nomor rekening">
                </div>

                {{-- BANK --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-bank"></i>
                        Bank <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="bank_id" id="bank_id" class="form-select" required>
                        <option value="">Pilih Bank</option>
                        @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- NO HP --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-phone"></i>
                        No HP
                    </label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="081234567890">
                </div>

                {{-- HARI LIBUR TETAP --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-calendar-week"></i>
                        Hari Libur Tetap (Setiap Minggu)
                    </label>
                    <select name="regular_off_day" id="regular_off_day" class="form-select">
                        <option value="">-- Pilih Hari Libur --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                        <option value="Tidak Libur">Tidak Libur (Full Kerja)</option>
                    </select>
                    <small style="color: var(--text-secondary); display: block; margin-top: 5px;">
                        <i class="bi bi-info-circle"></i> Pilih 1 hari libur tetap per minggu. Pilih "Tidak Libur" jika full kerja.
                    </small>
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
                <p id="hapusText" style="color: var(--text-secondary); font-size: 14px; margin-bottom: 5px;">
                    Data karyawan akan dihapus permanen.
                </p>
                <p id="hapusNama" style="color: var(--accent-color); font-weight: 600; font-size: 16px;"></p>
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

    // ===== DROPDOWN TOGGLE =====
    $(document).ready(function() {
        // Filter Divisi dropdown toggle
        $('#filterDivisiDropdown .filter-btn').click(function(e) {
            e.stopPropagation();
            $('#filterDivisiDropdown').toggleClass('active');
            $('#filterStatusDropdown').removeClass('active');
            $('#perpageDropdown').removeClass('active');
        });

        // Filter Status dropdown toggle
        $('#filterStatusDropdown .filter-btn').click(function(e) {
            e.stopPropagation();
            $('#filterStatusDropdown').toggleClass('active');
            $('#filterDivisiDropdown').removeClass('active');
            $('#perpageDropdown').removeClass('active');
        });

        // Perpage dropdown toggle
        $('#perpageDropdown .perpage-btn').click(function(e) {
            e.stopPropagation();
            $('#perpageDropdown').toggleClass('active');
            $('#filterDivisiDropdown').removeClass('active');
            $('#filterStatusDropdown').removeClass('active');
        });

        // Click outside to close all dropdowns
        $(document).click(function(e) {
            if (!$(e.target).closest('#filterDivisiDropdown').length) {
                $('#filterDivisiDropdown').removeClass('active');
            }
            if (!$(e.target).closest('#filterStatusDropdown').length) {
                $('#filterStatusDropdown').removeClass('active');
            }
            if (!$(e.target).closest('#perpageDropdown').length) {
                $('#perpageDropdown').removeClass('active');
            }
        });

        // ===== FORM SUBMIT DENGAN LOADING =====
        $('#karyawanForm').on('submit', function(e) {
            showLoading('Menyimpan data karyawan...');
            $('#submitBtn').addClass('btn-loading').prop('disabled', true);
            return true;
        });

        $('#hapusForm').on('submit', function(e) {
            showLoading('Menghapus data karyawan...');
            $('#deleteBtn').addClass('btn-loading').prop('disabled', true);
            return true;
        });

        // Initialize display
        filterEmployees();
    });

    // ===== MODAL FUNCTIONS =====
    function openModal(type, id = null, karyawanName = '') {
        if (type === 'hapus') {
            // Untuk modal hapus
            if (id && karyawanName) {
                document.getElementById('hapusText').innerHTML = `Karyawan <strong>${karyawanName}</strong> akan dihapus permanen.`;
                document.getElementById('hapusForm').action = `{{ url('employees') }}/${id}`;
                document.getElementById('hapusNama').innerHTML = karyawanName;
            }
            document.getElementById('hapusModal').classList.add('active');
        } else {
            // Untuk modal tambah/edit
            const modal = document.getElementById('karyawanModal');
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('karyawanForm');
            const methodField = document.getElementById('methodField');
            const submitBtn = document.getElementById('submitBtn');

            if (type === 'edit' && id) {
                title.innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Karyawan';
                methodField.value = 'PUT';
                form.action = `{{ url('employees') }}/${id}`;

                showLoading('Mengambil data karyawan...');

                fetch(`/employees/${id}`)
                    .then(response => response.json())
                    .then(employee => {
                        document.getElementById('name').value = employee.name;
                        document.getElementById('division_id').value = employee.division_id;
                        document.getElementById('status').value = employee.status;
                        document.getElementById('account_number').value = employee.account_number || '';
                        document.getElementById('bank_id').value = employee.bank_id || '';
                        document.getElementById('phone').value = employee.phone || '';
                        document.getElementById('regular_off_day').value = employee.regular_off_day || '';

                        const preview = document.getElementById('imagePreview');
                        if (employee.photo) {
                            preview.innerHTML = `<img src="{{ asset('storage') }}/${employee.photo}" alt="Preview">`;
                        } else {
                            const initial = employee.name.charAt(0).toUpperCase();
                            preview.innerHTML = `<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background: var(--accent-soft); color: var(--accent-color); font-size: 48px; font-weight: 600;">${initial}</div>`;
                        }

                        hideLoading();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Gagal memuat data karyawan');
                        hideLoading();
                    });
            } else {
                title.innerHTML = '<i class="bi bi-person-plus me-2"></i>Tambah Karyawan';
                methodField.value = 'POST';
                form.action = '{{ route("employees.store") }}';
                form.reset();
                document.getElementById('imagePreview').innerHTML = '<i class="bi bi-person"></i>';
            }

            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
            modal.classList.add('active');
        }
    }

    function closeModal() {
        document.getElementById('karyawanModal').classList.remove('active');
    }

    function closeHapusModal() {
        document.getElementById('hapusModal').classList.remove('active');
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }



    // Close modal with Escape key
    $(document).keydown(function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeHapusModal();
            $('#filterDivisiDropdown').removeClass('active');
            $('#filterStatusDropdown').removeClass('active');
            $('#perpageDropdown').removeClass('active');
        }
    });

    // Close modal when clicking outside
    $(document).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            closeModal();
            closeHapusModal();
        }
    });

    // Hide loading when page loads
    window.addEventListener('pageshow', function() {
        hideLoading();
    });

    // ===== CLIENT-SIDE FILTERING & PAGINATION =====
    let currentFilters = {
        search: '',
        division: '',
        status: ''
    };

    let currentPage = 1;
    let rowsPerPage = 5;

    function setFilter(type, value, text) {
        currentFilters[type] = value;
        currentPage = 1; // Reset to first page on filter change
        
        // Update UI dropdown text
        if (type === 'division') {
            document.getElementById('selectedDivision').textContent = text;
            $('#filterDivisiDropdown .filter-item').removeClass('active');
            $(`#filterDivisiDropdown .filter-item[data-value="${value}"]`).addClass('active');
            $('#filterDivisiDropdown').removeClass('active');
        } else if (type === 'status') {
            document.getElementById('selectedStatus').textContent = text;
            $('#filterStatusDropdown .filter-item').removeClass('active');
            $(`#filterStatusDropdown .filter-item[data-value="${value}"]`).addClass('active');
            $('#filterStatusDropdown').removeClass('active');
        }
        
        filterEmployees();
    }

    function setPerPage(value) {
        rowsPerPage = parseInt(value);
        currentPage = 1;
        
        // Update UI
        document.getElementById('selectedPerPage').textContent = `Tampil: ${value}`;
        $('#perpageDropdown .perpage-item').removeClass('active');
        $(`#perpageDropdown .perpage-item[data-value="${value}"]`).addClass('active');
        $('#perpageDropdown').removeClass('active');
        
        filterEmployees();
    }

    function filterEmployees() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = Array.from(document.querySelectorAll('.employee-row'));
        
        // 1. First, filter all rows based on criteria
        const filteredRows = rows.filter(row => {
            const name = row.getAttribute('data-name');
            const divisionId = row.getAttribute('data-division-id');
            const status = row.getAttribute('data-status');

            const matchSearch = name.includes(searchTerm);
            const matchDivision = currentFilters.division === '' || divisionId === currentFilters.division;
            const matchStatus = currentFilters.status === '' || status === currentFilters.status;

            return matchSearch && matchDivision && matchStatus;
        });

        const totalVisible = filteredRows.length;

        // 2. Hide all rows first
        rows.forEach(row => row.style.display = 'none');

        // 3. Handle Pagination slicing
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const rowsToDisplay = filteredRows.slice(startIndex, endIndex);

        rowsToDisplay.forEach(row => {
            row.style.display = '';
        });

        // 4. Update No Data message
        const emptySearchRow = document.getElementById('emptySearchRow');
        const noDataRow = document.getElementById('noDataRow');
        
        if (totalVisible === 0) {
            if (noDataRow) {
                emptySearchRow.style.display = 'none';
            } else {
                emptySearchRow.style.display = 'table-row';
            }
            document.getElementById('paginationWrapper').style.display = 'none';
        } else {
            emptySearchRow.style.display = 'none';
            document.getElementById('paginationWrapper').style.display = 'flex';
        }

        // 5. Update UI Components
        renderPagination(totalVisible);
        updatePaginationInfo(totalVisible, startIndex + 1, Math.min(endIndex, totalVisible));
    }

    function renderPagination(totalRows) {
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        const paginationList = document.getElementById('paginationList');
        paginationList.innerHTML = '';

        if (totalPages <= 1) {
            return;
        }

        // Previous Button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></a>`;
        paginationList.appendChild(prevLi);

        // Page Numbers
        let startPage = Math.max(1, currentPage - 1);
        let endPage = Math.min(totalPages, startPage + 2);
        
        if (endPage === totalPages) {
            startPage = Math.max(1, endPage - 2);
        }

        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="goToPage(${i})">${i}</a>`;
            paginationList.appendChild(li);
        }

        // Next Button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></a>`;
        paginationList.appendChild(nextLi);
    }

    function goToPage(page) {
        currentPage = page;
        filterEmployees();
        // Scroll to top of table
        document.getElementById('employeeTable').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function updatePaginationInfo(total, start, end) {
        const info = document.getElementById('paginationInfo');
        if (total === 0) {
            info.textContent = 'Tidak ada data untuk ditampilkan';
        } else {
            info.textContent = `Menampilkan ${start} - ${end} dari ${total} data`;
        }
    }
</script>
@endpush
