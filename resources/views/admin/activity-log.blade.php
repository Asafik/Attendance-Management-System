@extends('layouts.partials.app')

@section('title', 'Log Aktivitas - ' . ($company->name ?? '-'))
@section('page-title', 'Log Aktivitas')

@php
    // Ambil nilai filter dari request
    $selectedRole = request('role', 'all');
    $selectedStatus = request('status', 'all');
    $selectedPerPage = request('per_page', 10);
    $searchValue = request('search', '');
@endphp

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
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-title i {
        color: var(--accent-color);
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
        width: 200px;
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
    }

    .perpage-item:hover {
        background-color: var(--accent-soft);
        color: var(--accent-color);
    }

    .perpage-item.active {
        background-color: var(--accent-color);
        color: white;
    }

    /* ===== BADGE ===== */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .role-superadmin {
        background-color: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .role-admin {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .role-manager {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .role-operator {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .role-user {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .status-success {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-failed {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* ===== DEVICE BADGE ===== */
    .device-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        background-color: var(--accent-soft);
        color: var(--accent-color);
        white-space: nowrap;
    }

    .device-badge i {
        font-size: 12px;
    }

    .device-badge.desktop {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .device-badge.mobile {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .device-badge.tablet {
        background-color: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    /* ===== TABLE ===== */
    .table-responsive {
        overflow-x: auto;
        margin-bottom: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-primary);
        border-collapse: collapse;
        background-color: transparent;
        min-width: 1100px;
    }

    .table thead th {
        background-color: var(--accent-soft);
        color: var(--accent-color);
        border-bottom: 2px solid var(--accent-color);
        padding: 14px 12px;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .table thead th i {
        color: var(--accent-color);
        margin-right: 6px;
        font-size: 14px;
    }

    .table tbody td {
        padding: 16px 12px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 13px;
        background-color: transparent;
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover td {
        background-color: var(--accent-soft);
    }

    .table th:first-child,
    .table td:first-child {
        width: 50px;
        text-align: center;
        font-weight: 500;
    }

    /* ===== DEVICE INFO ===== */
    .device-info {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .device-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .device-browser {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }

    .device-os {
        font-size: 11px;
        color: var(--text-secondary);
    }

    /* ===== IP ADDRESS ===== */
    .ip-address {
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'Courier New', monospace;
        font-weight: 500;
    }

    .flag-icon {
        width: 20px;
        height: 14px;
        border-radius: 3px;
        background-color: #f0f0f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: var(--text-secondary);
    }

    /* ===== TIME INFO ===== */
    .time-info {
        display: flex;
        flex-direction: column;
    }

    .time-main {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }

    .time-sub {
        font-size: 11px;
        color: var(--text-secondary);
    }

    /* ===== LOCATION INFO ===== */
    .location-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .location-full {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 12px;
        line-height: 1.4;
        max-width: 200px;
    }

    .location-city {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }

    .location-country {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .location-coords {
        font-size: 10px;
        color: var(--text-secondary);
        font-family: monospace;
    }

    .location-source {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        background-color: var(--accent-soft);
        color: var(--accent-color);
        width: fit-content;
    }

    .location-source.gps {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .location-source.ip {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
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

    .pagination-info strong {
        color: var(--accent-color);
        font-weight: 600;
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

    /* ===== NOTIFICATION ===== */
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

    /* ===== RESPONSIVE TABLET ===== */
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

        .table-header {
            gap: 12px;
        }

        .table-actions {
            gap: 8px;
        }

        .search-wrapper {
            min-width: 200px;
        }

        .filter-btn, .perpage-btn {
            padding: 8px 14px;
            font-size: 13px;
        }

        .table {
            min-width: 1100px;
        }

        .table thead th {
            font-size: 12px;
            padding: 10px 8px;
        }

        .table tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }

        .page-link {
            min-width: 34px;
            height: 34px;
            font-size: 12px;
        }
    }

    /* ===== RESPONSIVE MOBILE ===== */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .stat-card {
            padding: 14px;
            gap: 10px;
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

        .filter-menu, .perpage-menu {
            width: 100%;
        }

        .table {
            min-width: 1100px;
        }

        .table thead th {
            font-size: 12px;
            padding: 10px 6px;
        }

        .table tbody td {
            font-size: 12px;
            padding: 10px 6px;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .pagination-info {
            font-size: 12px;
            text-align: center;
        }

        .page-link {
            min-width: 32px;
            height: 32px;
            font-size: 12px;
        }
    }

    /* ===== RESPONSIVE MOBILE KECIL ===== */
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

        .table {
            min-width: 1100px;
        }

        .table thead th {
            font-size: 11px;
            padding: 8px 4px;
        }

        .table tbody td {
            font-size: 11px;
            padding: 8px 4px;
        }

        .page-link {
            min-width: 28px;
            height: 28px;
            font-size: 11px;
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
                {{-- HIDDEN INPUT UNTUK FILTER --}}
                <input type="hidden" id="selectedRoleValue" value="{{ $selectedRole }}">
                <input type="hidden" id="selectedStatusValue" value="{{ $selectedStatus }}">
                <input type="hidden" id="selectedPerPageValue" value="{{ $selectedPerPage }}">
                <input type="hidden" id="searchValue" value="{{ $searchValue }}">

                {{-- ROW 1: CARD STATISTIK --}}
                <div class="row g-3 g-md-4 mb-3 mb-md-4">
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Login Hari Ini</div>
                                <div class="stat-value">{{ $loginHariIni }}</div>
                                <div class="stat-change positif">
                                    <i class="bi bi-arrow-up"></i> +{{ $loginHariIni - ($loginKemarin ?? 0) }} dari kemarin
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-calendar-month"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Login Bulan Ini</div>
                                <div class="stat-value">{{ $loginBulanIni }}</div>
                                <div class="stat-desc">Total aktivitas login</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Mobile</div>
                                <div class="stat-value">{{ $persentaseMobile }}%</div>
                                <div class="stat-change neutral">
                                    <i class="bi bi-phone"></i> {{ $totalMobile }} login
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrapper">
                                <i class="bi bi-laptop"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Desktop</div>
                                <div class="stat-value">{{ $persentaseDesktop }}%</div>
                                <div class="stat-desc">
                                    {{ $totalDesktop }} login
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ROW 2: TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">
                            <i class="bi bi-clock-history"></i>
                            Log Aktivitas Login
                        </h3>

                        <div class="table-actions">
                            {{-- SEARCH --}}
                            <div class="search-wrapper">
                                <i class="search-icon bi bi-search"></i>
                                <input type="text" class="search-input" placeholder="Cari IP, device, atau role..." value="{{ $searchValue }}">
                            </div>

                            {{-- FILTER ROLE DROPDOWN --}}
                            <div class="filter-dropdown" id="filterRoleDropdown">
                                <div class="filter-btn">
                                    <i class="bi bi-person-badge"></i>
                                    <span id="selectedRole">Role</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="filter-menu">
                                    <div class="filter-item {{ $selectedRole == 'all' ? 'active' : '' }}" data-role="all">
                                        <i class="bi bi-funnel"></i> Semua Role
                                    </div>
                                    <div class="filter-divider"></div>
                                    <div class="filter-item {{ $selectedRole == 'superadmin' ? 'active' : '' }}" data-role="superadmin">
                                        <i class="bi bi-shield-lock" style="color: #8b5cf6;"></i> Super Admin
                                    </div>
                                    <div class="filter-item {{ $selectedRole == 'admin' ? 'active' : '' }}" data-role="admin">
                                        <i class="bi bi-shield" style="color: #ef4444;"></i> Admin
                                    </div>
                                    <div class="filter-item {{ $selectedRole == 'manager' ? 'active' : '' }}" data-role="manager">
                                        <i class="bi bi-person-workspace" style="color: #3b82f6;"></i> Manager
                                    </div>
                                    <div class="filter-item {{ $selectedRole == 'operator' ? 'active' : '' }}" data-role="operator">
                                        <i class="bi bi-person-gear" style="color: #f59e0b;"></i> Operator
                                    </div>
                                    <div class="filter-item {{ $selectedRole == 'user' ? 'active' : '' }}" data-role="user">
                                        <i class="bi bi-person" style="color: #10b981;"></i> User
                                    </div>
                                </div>
                            </div>

                            {{-- FILTER STATUS DROPDOWN --}}
                            <div class="filter-dropdown" id="filterStatusDropdown">
                                <div class="filter-btn">
                                    <i class="bi bi-check-circle"></i>
                                    <span id="selectedStatus">Status</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="filter-menu">
                                    <div class="filter-item {{ $selectedStatus == 'all' ? 'active' : '' }}" data-status="all">
                                        <i class="bi bi-list-ul"></i> Semua Status
                                    </div>
                                    <div class="filter-divider"></div>
                                    <div class="filter-item {{ $selectedStatus == 'success' ? 'active' : '' }}" data-status="success">
                                        <i class="bi bi-check-circle" style="color: #10b981;"></i> Berhasil
                                    </div>
                                    <div class="filter-item {{ $selectedStatus == 'failed' ? 'active' : '' }}" data-status="failed">
                                        <i class="bi bi-x-circle" style="color: #ef4444;"></i> Gagal
                                    </div>
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
                                    <div class="perpage-item" data-perpage="5">5</div>
                                    <div class="perpage-item" data-perpage="10">10</div>
                                    <div class="perpage-item" data-perpage="15">15</div>
                                    <div class="perpage-item" data-perpage="20">20</div>
                                    <div class="perpage-item" data-perpage="50">50</div>
                                    <div class="perpage-item" data-perpage="100">100</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TABEL LOG --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash"></i></th>
                                    <th><i class="bi bi-calendar3"></i> Waktu</th>
                                    <th><i class="bi bi-person-badge"></i> Role</th>
                                    <th><i class="bi bi-pin-map"></i> IP Address</th>
                                    <th><i class="bi bi-geo-alt"></i> Lokasi</th>
                                    <th><i class="bi bi-laptop"></i> Device</th>
                                    <th><i class="bi bi-check-circle"></i> Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $index => $log)
                                <tr data-id="{{ $log->id }}"
                                    data-role="{{ $log->user->role->name ?? 'user' }}"
                                    data-status="{{ $log->status }}"
                                    data-ip="{{ $log->ip_address }}"
                                    data-browser="{{ $log->browser }}"
                                    data-search="{{ ($log->user->role->name ?? 'user') . ' ' . $log->ip_address . ' ' . $log->browser . ' ' . $log->os }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="time-info">
                                            <span class="time-main">{{ $log->created_at->format('H:i:s') }}</span>
                                            <span class="time-sub">{{ $log->created_at->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($log->user && $log->user->role)
                                            <span class="role-badge role-{{ $log->user->role->name ?? 'user' }}">
                                                <i class="bi {{ $log->action_icon }}"></i>
                                                {{ $log->user->role->display_name ?? $log->user->role->name ?? 'User' }}
                                            </span>
                                        @else
                                            <span class="role-badge role-user">
                                                <i class="bi bi-person"></i>
                                                Unknown
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="ip-address">
                                            <span class="flag-icon">{!! $log->flag !!}</span>
                                            {{ $log->ip_address ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="location-info">
                                            @if($log->full_address)
                                                <div class="location-full" title="{{ $log->full_address }}">
                                                    <i class="bi bi-geo-alt-fill" style="color: var(--accent-color); font-size: 10px;"></i>
                                                    {{ Str::limit($log->full_address, 40) }}
                                                </div>
                                            @elseif($log->city || $log->country)
                                                <div class="location-full">
                                                    <i class="bi bi-geo-alt-fill" style="color: var(--accent-color); font-size: 10px;"></i>
                                                    {{ $log->city ?? 'Unknown' }}, {{ $log->country ?? 'Unknown' }}
                                                </div>
                                            @else
                                                <div class="location-full">
                                                    <i class="bi bi-geo-alt-fill" style="color: var(--text-secondary); font-size: 10px;"></i>
                                                    Unknown Location
                                                </div>
                                            @endif

                                            @if($log->latitude && $log->longitude)
                                                <div class="location-coords">
                                                    ({{ number_format($log->latitude, 4) }}, {{ number_format($log->longitude, 4) }})
                                                </div>
                                            @endif

                                            @if($log->location_source)
                                                <span class="location-source {{ $log->location_source }}">
                                                    @if($log->location_source == 'gps')
                                                        <i class="bi bi-satellite"></i> GPS
                                                    @elseif($log->location_source == 'ip_fallback')
                                                        <i class="bi bi-globe"></i> IP
                                                    @else
                                                        {{ $log->location_source }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="device-info">
                                            <div class="device-row">
                                                <span class="device-badge {{ $log->device ?? 'desktop' }}">
                                                    <i class="bi bi-{{ $log->device == 'mobile' ? 'phone' : ($log->device == 'tablet' ? 'tablet' : 'laptop') }}"></i>
                                                    {{ ucfirst($log->device ?? 'Desktop') }}
                                                </span>
                                            </div>
                                            <div class="device-row">
                                                <i class="bi bi-window" style="color: var(--accent-color); font-size: 11px;"></i>
                                                <span class="device-browser">{{ $log->browser ?? 'Unknown' }} {{ $log->browser_version ?? '' }}</span>
                                            </div>
                                            <div class="device-row">
                                                <i class="bi bi-cpu" style="color: var(--accent-color); font-size: 11px;"></i>
                                                <span class="device-os">{{ $log->os ?? 'Unknown' }} {{ $log->os_version ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $log->status }}">
                                            <i class="bi bi-{{ $log->status == 'success' ? 'check-circle' : 'x-circle' }}"></i>
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--text-secondary);"></i>
                                        <p style="color: var(--text-secondary);">Belum ada data log aktivitas</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="pagination-wrapper">
                        <div class="pagination-info" id="paginationText">
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // ===== VARIABLES =====
        let currentPage = 1;
        let perPage = 5;
        let allRows = [];
        let filteredRows = [];

        // ===== LOAD ALL ROWS =====
        function loadAllRows() {
            allRows = [];
            $('.table tbody tr').each(function() {
                const $row = $(this);
                if (!$row.hasClass('no-data-row') && $row.find('td').length > 1) {
                    allRows.push({
                        id: $row.data('id'),
                        role: $row.data('role'),
                        status: $row.data('status'),
                        search: $row.data('search'),
                        element: $row,
                        index: allRows.length
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
            $('.table tbody tr').hide();

            if (pageRows.length === 0) {
                if ($('.table tbody .no-data-row').length === 0) {
                    $('.table tbody').append('<tr class="no-data-row"><td colspan="7" class="text-center py-4"><i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--text-secondary);"></i><p style="color: var(--text-secondary);">Tidak ada data log ditemukan</p></td></tr>');
                } else {
                    $('.table tbody .no-data-row').show();
                }
            } else {
                $('.table tbody .no-data-row').remove();

                pageRows.forEach((row, index) => {
                    const $row = row.element;
                    const rowNumber = start + index + 1;
                    $row.find('td:eq(0)').text(rowNumber);
                    $row.show();
                });
            }

            // Update info text
            const total = filteredRows.length;
            const startDisplay = total === 0 ? 0 : start + 1;
            const endDisplay = Math.min(end, total);
            $('#paginationText').html(`Menampilkan <strong>${startDisplay} - ${endDisplay}</strong> dari <strong>${total}</strong> data`);

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
                if (currentPage === 1) {
                    html += '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>';
                } else {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></span></li>`;
                }

                const maxVisible = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                let endPage = Math.min(totalPages, startPage + maxVisible - 1);
                if (endPage - startPage + 1 < maxVisible) startPage = Math.max(1, endPage - maxVisible + 1);

                if (startPage > 1) {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(1)">1</span></li>`;
                    if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }

                for (let i = startPage; i <= endPage; i++) {
                    html += `<li class="page-item ${i === currentPage ? 'active' : ''}"><span class="page-link" onclick="changePage(${i})">${i}</span></li>`;
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${totalPages})">${totalPages}</span></li>`;
                }

                if (currentPage === totalPages) {
                    html += '<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>';
                } else {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></span></li>`;
                }
            }
            $('#pagination').html(html);
        }

        window.changePage = function(page) {
            currentPage = page;
            renderTable();
        };

        // ===== NOTIFICATION SYSTEM =====
        window.showNotification = function(type, message, title = null) {
            const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };
            const titles = { success: 'Berhasil!', error: 'Gagal!', warning: 'Peringatan!', info: 'Informasi' };
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `<div class="notification-icon"><i class="bi ${icons[type]}"></i></div><div class="notification-content"><div class="notification-title">${title || titles[type]}</div><div class="notification-message">${message}</div></div><div class="notification-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></div><div class="notification-progress"></div>`;
            document.getElementById('notificationContainer').appendChild(notification);
            setTimeout(() => { if (notification.parentElement) { notification.style.animation = 'slideOut 0.3s ease forwards'; setTimeout(() => { if (notification.parentElement) notification.remove(); }, 300); } }, 3000);
        };

        // ===== FILTER FUNCTIONS =====
        function applyFilter() {
            const role = $('#filterRoleDropdown .filter-item.active').data('role') || 'all';
            const status = $('#filterStatusDropdown .filter-item.active').data('status') || 'all';
            const search = $('.search-input').val().toLowerCase();

            filteredRows = allRows.filter(row => {
                const matchRole = role === 'all' || row.role === role;
                const matchStatus = status === 'all' || row.status === status;
                const matchSearch = !search || row.search.toLowerCase().includes(search);
                return matchRole && matchStatus && matchSearch;
            });

            currentPage = 1;
            renderTable();
        }

        // Event Listeners
        $('#filterRoleDropdown .filter-item').click(function() {
            $('#filterRoleDropdown .filter-item').removeClass('active');
            $(this).addClass('active');
            $('#selectedRole').text($(this).text().trim());
            $('#filterRoleDropdown').removeClass('active');
            applyFilter();
        });

        $('#filterStatusDropdown .filter-item').click(function() {
            $('#filterStatusDropdown .filter-item').removeClass('active');
            $(this).addClass('active');
            $('#selectedStatus').text($(this).text().trim());
            $('#filterStatusDropdown').removeClass('active');
            applyFilter();
        });

        $('#perpageDropdown .perpage-item').click(function() {
            perPage = $(this).data('perpage');
            $('#selectedPerPage').text('Tampil: ' + perPage);
            $('#perpageDropdown .perpage-item').removeClass('active');
            $(this).addClass('active');
            $('#perpageDropdown').removeClass('active');
            currentPage = 1;
            renderTable();
        });

        $('.search-input').on('keyup', applyFilter);

        // Dropdown toggle
        $('.filter-btn, .perpage-btn').click(function(e) {
            e.stopPropagation();
            const $parent = $(this).parent();
            $('.filter-dropdown, .perpage-dropdown').not($parent).removeClass('active');
            $parent.toggleClass('active');
        });

        $(document).click(function() {
            $('.filter-dropdown, .perpage-dropdown').removeClass('active');
        });

        // Initialize
        loadAllRows();
        renderTable();
    });
</script>
@endpush
