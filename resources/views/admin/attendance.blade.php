@extends('layouts.partials.app')

@section('title', 'Data Absensi - Wadul Guse')
@section('page-title', 'Data Absensi')

@push('styles')
<!-- SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- FULLCALENDAR -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<style>
/* SELECT2 CUSTOM STYLING */
.select2-container--default .select2-selection--single {
    height: 45px;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    background-color: var(--bg-card);
    padding: 4px 0;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 45px;
    color: var(--text-primary);
    padding-left: 15px;
    font-size: 14px;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: var(--text-secondary);
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px;
    right: 12px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: var(--text-secondary) transparent transparent transparent;
}

.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent var(--text-secondary) transparent;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 12px;
    background-color: var(--bg-card);
    color: var(--text-primary);
}

.select2-dropdown {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    background-color: var(--bg-card);
    box-shadow: var(--shadow);
    margin-top: 5px;
}

.select2-results__option {
    padding: 10px 15px;
    color: var(--text-primary);
    background-color: var(--bg-card);
    font-size: 14px;
}

.select2-results__option--highlighted {
    background-color: var(--accent-soft) !important;
    color: var(--accent-color) !important;
}

.select2-results__option[aria-selected="true"] {
    background-color: var(--accent-medium);
    color: var(--accent-color);
}

/* FILTER HEADER */
.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-header-left h5 {
    margin: 0;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 18px;
}

.filter-header-left h5 i {
    color: var(--accent-color);
    margin-right: 8px;
}

/* FILTER ACTIVE TAGS */
.filter-tags {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin: 15px 0 20px;
    padding: 15px 0 0;
    border-top: 1px solid var(--border-color);
}

.filter-tag {
    background-color: var(--accent-soft);
    color: var(--accent-color);
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.filter-tag i:first-child {
    font-size: 14px;
}

.filter-tag i.bi-x {
    font-size: 16px;
    cursor: pointer;
    opacity: 0.7;
    transition: all 0.2s;
}

.filter-tag i.bi-x:hover {
    opacity: 1;
    color: #ef4444;
    transform: scale(1.1);
}

.filter-info {
    color: var(--text-secondary);
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* CONTENT AREA */
.content-area {
    padding: 24px;
    min-height: calc(100vh - 80px);
}

/* CARD CUSTOM */
.card-custom {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 24px;
    transition: all 0.3s ease;
}

.card-custom:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* STAT CARD */
.stat-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid var(--border-color);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-color), #86efac);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--accent-soft);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    color: var(--accent-color);
    font-size: 20px;
}

.stat-title {
    font-size: 13px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}

/* LABEL */
label {
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

label i {
    color: var(--accent-color);
    font-size: 16px;
}

/* CARD TITLE */
.card-custom h5 {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-custom h5 i {
    color: var(--accent-color);
    font-size: 20px;
}

/* CALENDAR CUSTOM */
#attendanceCalendar {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 5px;
    min-height: 500px;
}

/* FullCalendar Customization */
.fc {
    background: var(--bg-card);
    color: var(--text-primary);
}

.fc-toolbar-title {
    color: var(--text-primary) !important;
    font-size: 1.5rem !important;
    font-weight: 600 !important;
}

.fc-button {
    background: var(--bg-card) !important;
    border: 1px solid var(--border-color) !important;
    color: var(--text-primary) !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    text-transform: capitalize !important;
    box-shadow: none !important;
}

.fc-button:hover {
    background: var(--accent-soft) !important;
    border-color: var(--accent-color) !important;
    color: var(--accent-color) !important;
}

.fc-button-primary:not(:disabled):active,
.fc-button-primary:not(:disabled).fc-button-active {
    background: var(--accent-color) !important;
    border-color: var(--accent-color) !important;
    color: white !important;
}

.fc-col-header-cell {
    background: var(--accent-soft);
    color: var(--text-primary);
    padding: 12px 0 !important;
    font-weight: 600 !important;
}

.fc-col-header-cell-cushion {
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 600;
}

.fc-daygrid-day {
    border: 1px solid var(--border-color) !important;
    cursor: pointer;
    min-height: 100px !important;
}

.fc-daygrid-day:hover {
    background-color: var(--accent-soft) !important;
}

.fc-daygrid-day-number {
    color: var(--text-primary);
    text-decoration: none;
    padding: 8px !important;
    font-weight: 500;
}

.fc-day-today {
    background: var(--accent-soft) !important;
}

.fc-day-today .fc-daygrid-day-number {
    color: var(--accent-color);
    font-weight: 700;
}

.fc-day-other {
    background: var(--bg-body);
    opacity: 0.7;
}

/* ===== EVENT COLORS ===== */
/* WFH - Biru */
.event-wfh {
    background-color: #3b82f6 !important;
    border-color: #2563eb !important;
    color: white !important;
}

/* Izin - Kuning */
.event-izin {
    background-color: #f59e0b !important;
    border-color: #d97706 !important;
    color: white !important;
}

/* Alpha - Merah */
.event-alpha {
    background-color: #ef4444 !important;
    border-color: #dc2626 !important;
    color: white !important;
}

/* Libur - Abu-abu gelap (TAMBAHAN) */
.event-libur {
    background-color: #6b7280 !important;
    border-color: #4b5563 !important;
    color: white !important;
}

/* MODAL CUSTOM */
.modal-custom {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.modal-custom.show {
    display: flex;
}

.modal-content {
    background: var(--bg-card);
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow);
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
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: var(--bg-card);
    border-radius: 20px 20px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header h3 i {
    color: var(--accent-color);
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: all 0.2s;
}

.modal-close:hover {
    color: var(--accent-color);
    transform: scale(1.1);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    position: sticky;
    bottom: 0;
    background: var(--bg-card);
    border-radius: 0 0 20px 20px;
}

/* DATE INFO */
.date-info {
    background: var(--accent-soft);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.date-info-day {
    width: 70px;
    height: 70px;
    background: var(--accent-color);
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 4px 10px rgba(22, 163, 74, 0.3);
}

.date-info-day .day {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
}

.date-info-day .month {
    font-size: 14px;
    text-transform: uppercase;
    opacity: 0.9;
}

.date-info-text {
    flex: 1;
}

.date-info-text .day-name {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.date-info-text .date-full {
    font-size: 14px;
    color: var(--text-secondary);
}

/* FORM STYLES */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-primary);
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-label i {
    color: var(--accent-color);
    font-size: 16px;
}

.form-select, .form-control {
    width: 100%;
    height: 45px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    background-color: var(--bg-card);
    color: var(--text-primary);
    padding: 0 15px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-select:focus, .form-control:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px var(--accent-soft);
}

textarea.form-control {
    height: 100px;
    padding: 12px 15px;
    resize: vertical;
}

/* INFO BOX */
.info-box {
    background: var(--accent-soft);
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid var(--accent-color);
}

.info-box i {
    color: var(--accent-color);
    font-size: 20px;
}

.info-box p {
    color: var(--text-primary);
    font-size: 13px;
    margin: 0;
}

/* BUTTONS */
.btn {
    padding: 10px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: var(--accent-color);
    color: white;
}

.btn-primary:hover {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.btn-secondary {
    background: transparent;
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--accent-soft);
    border-color: var(--accent-color);
    color: var(--accent-color);
}

/* CUSTOM NOTIFICATION */
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

/* LOADING OVERLAY */
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

/* ALERT CUSTOM */
.alert-success {
    background: var(--accent-soft);
    border: 1px solid var(--accent-color);
    color: var(--accent-color);
    border-radius: 12px;
    padding: 16px 20px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.alert-success i {
    font-size: 20px;
}

/* DARK MODE */
body.dark-mode .fc-button {
    background: var(--bg-card) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
}

body.dark-mode .fc-button:hover {
    background: var(--accent-soft) !important;
    border-color: var(--accent-color) !important;
    color: var(--accent-text) !important;
}

body.dark-mode .fc-col-header-cell {
    background: var(--sidebar-hover);
}

body.dark-mode .btn-secondary {
    background: transparent;
    border-color: var(--border-color);
    color: var(--text-primary);
}

body.dark-mode .btn-secondary:hover {
    background: var(--accent-soft);
    border-color: var(--accent-color);
    color: var(--accent-text);
}

/* ===== RESPONSIVE TABLET (768px - 992px) ===== */
@media (min-width: 769px) and (max-width: 992px) {
    .content-area {
        padding: 20px;
    }

    .stat-card {
        padding: 16px;
        gap: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }

    .stat-value {
        font-size: 22px;
    }

    .stat-title {
        font-size: 12px;
    }

    .filter-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-header-left {
        width: 100%;
    }

    .filter-header-left h5 {
        font-size: 16px;
    }

    .filter-tags {
        margin: 10px 0 15px;
    }

    .fc-toolbar {
        flex-wrap: wrap;
        gap: 10px;
    }

    .fc-toolbar-title {
        font-size: 1.3rem !important;
    }

    .fc-button {
        padding: 6px 12px !important;
        font-size: 12px !important;
    }

    .modal-content {
        max-width: 450px;
    }

    .date-info-day {
        width: 60px;
        height: 60px;
    }

    .date-info-day .day {
        font-size: 24px;
    }

    .date-info-day .month {
        font-size: 12px;
    }

    .date-info-text .day-name {
        font-size: 18px;
    }

    .notification {
        min-width: 280px;
        max-width: 350px;
        padding: 14px 18px;
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
        padding: 25px 35px;
    }

    .loading-spinner i {
        font-size: 45px;
    }
}

/* ===== RESPONSIVE MOBILE (< 768px) ===== */
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

    .stat-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 20px;
    }

    .stat-title {
        font-size: 11px;
    }

    .filter-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-header-left {
        width: 100%;
    }

    .filter-header-left h5 {
        font-size: 16px;
    }

    .filter-tags {
        margin: 8px 0 12px;
    }

    .filter-tag {
        padding: 4px 10px;
        font-size: 12px;
    }

    .select2-container--default .select2-selection--single {
        height: 40px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        font-size: 13px;
    }

    .card-custom {
        padding: 16px;
    }

    .card-custom h5 {
        font-size: 16px;
        margin-bottom: 15px;
    }

    .fc-toolbar {
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .fc-toolbar-title {
        font-size: 1.2rem !important;
        margin: 5px 0 !important;
    }

    .fc-button-group {
        display: flex;
        gap: 5px;
    }

    .fc-button {
        padding: 5px 10px !important;
        font-size: 11px !important;
    }

    .fc-col-header-cell {
        padding: 8px 0 !important;
        font-size: 12px !important;
    }

    .fc-daygrid-day-number {
        padding: 5px !important;
        font-size: 12px !important;
    }

    .fc-event {
        padding: 1px 3px !important;
        font-size: 10px !important;
        margin: 0 1px 1px !important;
    }

    .modal-content {
        width: 95%;
        max-width: 100%;
        margin: 0 10px;
    }

    .modal-header {
        padding: 15px 20px;
    }

    .modal-header h3 {
        font-size: 18px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
    }

    .date-info {
        padding: 12px;
        gap: 12px;
    }

    .date-info-day {
        width: 50px;
        height: 50px;
    }

    .date-info-day .day {
        font-size: 22px;
    }

    .date-info-day .month {
        font-size: 11px;
    }

    .date-info-text .day-name {
        font-size: 16px;
    }

    .date-info-text .date-full {
        font-size: 12px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        font-size: 13px;
        margin-bottom: 6px;
    }

    .form-select, .form-control {
        height: 40px;
        padding: 0 12px;
        font-size: 13px;
    }

    textarea.form-control {
        height: 80px;
        padding: 10px 12px;
    }

    .btn {
        padding: 8px 20px;
        font-size: 13px;
    }

    .notification-container {
        top: 10px;
        right: 10px;
        left: 10px;
    }

    .notification {
        min-width: auto;
        width: 100%;
        max-width: 100%;
        padding: 12px 16px;
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
        padding: 20px 25px;
    }

    .loading-spinner i {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .loading-spinner p {
        font-size: 14px;
    }

    .loading-spinner small {
        font-size: 11px;
    }
}

/* ===== RESPONSIVE MOBILE KECIL (< 480px) ===== */
@media (max-width: 480px) {
    .content-area {
        padding: 12px;
    }

    .stat-card {
        padding: 10px;
    }

    .stat-icon {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }

    .stat-value {
        font-size: 18px;
    }

    .stat-title {
        font-size: 10px;
    }

    .filter-tags {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .filter-tag {
        width: 100%;
        justify-content: space-between;
    }

    .fc-toolbar-title {
        font-size: 1rem !important;
    }

    .fc-button {
        padding: 4px 8px !important;
        font-size: 10px !important;
    }

    .fc-col-header-cell {
        padding: 6px 0 !important;
        font-size: 11px !important;
    }

    .fc-daygrid-day-number {
        padding: 3px !important;
        font-size: 11px !important;
    }

    .modal-footer {
        flex-direction: column-reverse;
        gap: 8px;
    }

    .modal-footer .btn {
        width: 100%;
    }

    .date-info {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }

    .date-info-day {
        width: 45px;
        height: 45px;
    }

    .date-info-day .day {
        font-size: 20px;
    }

    .date-info-text .day-name {
        font-size: 15px;
    }

    .loading-spinner {
        padding: 15px 20px;
        width: 90%;
    }

    .loading-spinner i {
        font-size: 35px;
    }

    .loading-spinner p {
        font-size: 13px;
    }

    .loading-spinner small {
        font-size: 10px;
    }
}

/* ===== LANDSCAPE MODE ===== */
@media (max-height: 500px) and (orientation: landscape) {
    .modal-content {
        max-height: 85vh;
    }

    .modal-body {
        max-height: 50vh;
        overflow-y: auto;
    }

    .loading-spinner {
        padding: 15px 25px;
    }

    .loading-spinner i {
        font-size: 30px;
        margin-bottom: 8px;
    }

    .loading-spinner p {
        font-size: 13px;
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

    @include('layouts.sidebar')

    <div class="main-content">
        @include('layouts.navbar')

        <div class="content-area">
            <!-- ===================== -->
            <!-- CARD STATISTIK -->
            <!-- ===================== -->
            <div class="row mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-title">Total Karyawan</div>
                        <div class="stat-value">{{ $employees->count() }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                        <div class="stat-title">WFH</div>
                        <div class="stat-value" style="color: #3b82f6;">{{ $totalWFH }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div class="stat-title">Izin</div>
                        <div class="stat-value" style="color: #f59e0b;">{{ $totalIzin }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="stat-title">Alpha</div>
                        <div class="stat-value" style="color: #ef4444;">{{ $totalAlpha }}</div>
                    </div>
                </div>
            </div>

            <!-- ===================== -->
            <!-- FILTER KARYAWAN DENGAN SEARCH -->
            <!-- ===================== -->
            <div class="card-custom">
                <div class="filter-header">
                    <div class="filter-header-left">
                        <h5>
                            <i class="bi bi-funnel-fill"></i>
                            Filter Karyawan
                        </h5>
                    </div>
                </div>

                <!-- FILTER ACTIVE TAGS -->
                <div class="filter-tags" id="filterTags">
                    <span class="filter-info">
                        <i class="bi bi-info-circle-fill"></i>
                        Filter aktif:
                    </span>
                    <span class="filter-tag" id="employeeTag" style="display: none;">
                        <i class="bi bi-person-badge"></i>
                        <span id="selectedEmployeeName"></span>
                        <i class="bi bi-x" onclick="resetEmployee()"></i>
                    </span>
                </div>

                <!-- SELECT KARYAWAN DENGAN SEARCH -->
                <label>
                    <i class="bi bi-person-badge"></i>
                    Pilih Karyawan
                </label>
                <select id="employeeSelect" class="form-control">
                    <option value="">-- Semua Karyawan (Global) --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}"
                                data-regular-off-day="{{ $employee->regular_off_day }}"
                                {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} - {{ $employee->division->name ?? 'Tanpa Divisi' }}
                            @if($employee->regular_off_day && $employee->regular_off_day != 'Tidak Libur')
                                (Libur: {{ $employee->regular_off_day }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <small class="text-muted mt-2 d-block">
                    <i class="bi bi-search"></i> Ketik untuk mencari karyawan
                </small>
            </div>

            <!-- ===================== -->
            <!-- KALENDER -->
            <!-- ===================== -->
            <div class="card-custom">
                <h5>
                    <i class="bi bi-calendar3"></i>
                    Kalender Absensi - {{ now()->format('F Y') }}
                </h5>

                <div id="emptyMessage" class="alert-success d-none">
                    <i class="bi bi-check-circle-fill"></i>
                    Bulan ini tidak ada ketidakhadiran
                </div>

                <div id="attendanceCalendar"></div>
            </div>
        </div>

        @include('layouts.footer')
    </div>
</div>

<!-- ===================== -->
<!-- MODAL INPUT ABSENSI -->
<!-- ===================== -->
<div class="modal-custom" id="attendanceModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <i class="bi bi-pencil-square"></i>
                Atur Absensi
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="modal-body">
            <!-- Date Info -->
            <div class="date-info" id="modalDateInfo">
                <div class="date-info-day">
                    <span class="day" id="modalDay">15</span>
                    <span class="month" id="modalMonth">MAR</span>
                </div>
                <div class="date-info-text">
                    <div class="day-name" id="modalDayName">Senin</div>
                    <div class="date-full" id="modalDateFull">23 Februari 2026</div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <p>
                    <strong>Karyawan: <span id="modalEmployeeName">-</span></strong><br>
                    <small id="modalLiburInfo"></small>
                </p>
            </div>

            <!-- Status Absensi -->
            <div class="form-group">
                <div class="form-label">
                    <i class="bi bi-tag"></i>
                    Status Absensi
                </div>
                <select class="form-select" id="statusSelect">
                    <option value="Hadir" style="color: #22c55e;">Hadir (Default)</option>
                    <option value="WFH" style="color: #3b82f6;">WFH</option>
                    <option value="Izin" style="color: #f59e0b;">Izin</option>
                    <option value="Alpha" style="color: #ef4444;">Alpha</option>
                    <option value="Libur" style="color: #6b7280;">Libur</option>
                </select>
            </div>

            <!-- Keterangan Opsional -->
            <div class="form-group">
                <div class="form-label">
                    <i class="bi bi-chat-text"></i>
                    Keterangan <span style="color: var(--text-secondary); font-weight: normal;">(opsional)</span>
                </div>
                <textarea class="form-control" id="note" placeholder="Contoh: Izin sakit, WFH karena banjir, dll..."></textarea>
            </div>

            <!-- Informasi Tambahan -->
            <div style="background: var(--accent-soft); border-radius: 8px; padding: 12px; font-size: 12px; color: var(--text-secondary);">
                <i class="bi bi-database"></i>
                <span id="dbInfo">Data tersimpan: Hadir (tidak tersimpan di database)</span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">
                <i class="bi bi-x"></i>
                Batal
            </button>
            <button class="btn btn-primary" onclick="saveAttendance()">
                <i class="bi bi-check-lg"></i>
                Simpan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SELECT2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- FULLCALENDAR -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// Data karyawan dari server
const employees = @json($employees);

// State
let selectedEmployee = '';
let currentDate = '';
let currentEmployeeId = null;
let currentRecord = null;
let calendar = null;
let regularOffDays = {};

// Nama hari dan bulan dalam Bahasa Indonesia
const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Map hari Indonesia ke angka (0 = Minggu, 1 = Senin, dll)
const dayMap = {
    'Minggu': 0,
    'Senin': 1,
    'Selasa': 2,
    'Rabu': 3,
    'Kamis': 4,
    'Jumat': 5,
    'Sabtu': 6
};

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

// CSRF Token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('#employeeSelect').select2({
        placeholder: "Pilih atau cari karyawan...",
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 1,
        language: {
            searching: function() {
                return 'Mencari...';
            },
            noResults: function() {
                return 'Karyawan tidak ditemukan';
            }
        }
    });

    // Build regular off days mapping
    employees.forEach(emp => {
        if (emp.regular_off_day && emp.regular_off_day !== 'Tidak Libur') {
            regularOffDays[emp.id] = emp.regular_off_day;
        }
    });

    initCalendar();

    $('#employeeSelect').on('change', function() {
        selectedEmployee = $(this).val();

        if (selectedEmployee) {
            const emp = employees.find(e => e.id == selectedEmployee);
            $('#employeeTag').show();
            $('#selectedEmployeeName').text(emp ? emp.name : '');

            // 🔥 SYNC LIBUR TETAP KE DATABASE
            syncRegularOffDays(selectedEmployee, emp.name);

        } else {
            $('#employeeTag').hide();
            refreshCalendarEvents();
        }
    });
});

// Fungsi sinkronisasi libur tetap
function syncRegularOffDays(employeeId, employeeName) {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth() + 1;

    showLoading(`Menyinkronkan libur tetap...`);

    $.ajax({
        url: '{{ route("attendance.sync") }}',
        method: 'POST',
        data: {
            employee_id: employeeId,
            month: month,
            year: year
        },
        success: function(response) {
            hideLoading();

            // 🔥 CEK RESPONSE DARI SERVER
            if (response.deleted) {
                // Kalau karyawan tidak punya libur tetap
                showNotification('info', `📅 ${employeeName} tidak memiliki libur tetap`);
            }
            else if (response.inserted > 0) {
                if (response.existing) {
                    // 🔥 KALAU SEBELUMNYA SUDAH ADA DATA (UPDATE)
                    showNotification('success', `✅ Libur tetap ${response.off_day} sudah diperbarui (${response.inserted} hari)`);
                } else {
                    // 🔥 KALAU PERTAMA KALI INSERT
                    showNotification('success', `✅ Berhasil menambahkan ${response.inserted} hari libur tetap (${response.off_day})`);
                }
            }
            else {
                // Kalau tidak ada yang diinsert
                const emp = employees.find(e => e.id == employeeId);
                if (emp && emp.regular_off_day) {
                    showNotification('success', `✅ Hari libur ${emp.regular_off_day} sudah sesuai`);
                } else {
                    showNotification('info', `📅 Libur tetap sudah sesuai`);
                }
            }

            refreshCalendarEvents();
        },
        error: function(xhr) {
            hideLoading();
            console.error('Sync error:', xhr);
            showNotification('error', '❌ Gagal sinkronisasi libur tetap');
            refreshCalendarEvents();
        }
    });
}

function resetEmployee() {
    $('#employeeSelect').val('').trigger('change');
    selectedEmployee = '';
    $('#employeeTag').hide();
    refreshCalendarEvents();
}

// Calendar initialization
function initCalendar() {
    var calendarEl = document.getElementById('attendanceCalendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        height: 'auto',
        aspectRatio: 1.5,
        contentHeight: 'auto',
        handleWindowResize: true,
        locale: 'id',
        firstDay: 1,
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            const events = [];
            const year = fetchInfo.start.getFullYear();
            const month = fetchInfo.start.getMonth();

            $.ajax({
                url: '{{ route("attendance.events") }}',
                data: {
                    employee_id: selectedEmployee || null
                },
                success: function(data) {
                    // 🔥 FILTER: Halaman global (tanpa pilih karyawan)
                    // Hanya tampilkan yang bukan libur tetap
                    if (!selectedEmployee) {
                        const filteredData = data.filter(event => {
                            // Cek apakah ini libur tetap dari note
                            return !event.extendedProps?.note?.includes('Libur tetap');
                        });
                        events.push(...filteredData);
                    } else {
                        events.push(...data);
                    }

                    successCallback(events);
                    checkEmptyMessage(events);
                },
                error: function() {
                    failureCallback();
                }
            });
        },
        eventDidMount: function(info) {
            if (info.event.title.includes('WFH')) {
                info.el.classList.add('event-wfh');
            } else if (info.event.title.includes('Izin')) {
                info.el.classList.add('event-izin');
            } else if (info.event.title.includes('Alpha')) {
                info.el.classList.add('event-alpha');
            } else if (info.event.title.includes('Libur')) {
                info.el.classList.add('event-libur');
            }
        },
        dateClick: function(info) {
            if (selectedEmployee) {
                openModalForEmployee(selectedEmployee, info.dateStr);
            } else {
                showNotification('warning', 'Silakan pilih karyawan terlebih dahulu');
            }
        }
    });

    calendar.render();
}

function refreshCalendarEvents() {
    calendar.refetchEvents();
}

function checkEmptyMessage(events) {
    if (events.length === 0) {
        $('#emptyMessage').removeClass('d-none');
    } else {
        $('#emptyMessage').addClass('d-none');
    }
}

// Open modal
function openModalForEmployee(employeeId, dateStr) {
    const employee = employees.find(e => e.id == employeeId);
    if (!employee) return;

    currentEmployeeId = employeeId;
    currentDate = dateStr;

    const date = new Date(dateStr + 'T00:00:00');
    const day = date.getDate();
    const month = date.getMonth();
    const year = date.getFullYear();
    const dayName = dayNames[date.getDay()];

    $('#modalDay').text(day);
    $('#modalMonth').text(monthNames[month].substring(0, 3).toUpperCase());
    $('#modalDayName').text(dayName);
    $('#modalDateFull').text(`${day} ${monthNames[month]} ${year}`);
    $('#modalEmployeeName').text(employee.name);

    const isRegularOffDay = employee.regular_off_day === dayName && employee.regular_off_day !== 'Tidak Libur';

    if (isRegularOffDay) {
        $('#modalLiburInfo').html('Ini adalah hari libur tetap karyawan');
        $('#statusSelect').val('Libur');
        $('#dbInfo').html(`<i class="bi bi-info-circle"></i> Libur tetap (otomatis)`);
    } else {
        $('#modalLiburInfo').html('');
        $('#statusSelect').val('Hadir');
        $('#dbInfo').html(`<i class="bi bi-database"></i> Data tersimpan: Hadir`);
    }

    $('#note').val('');
    $('#attendanceModal').addClass('show');
}

// Save attendance
function saveAttendance() {
    const status = $('#statusSelect').val();
    const note = $('#note').val();
    const employee = employees.find(e => e.id == currentEmployeeId);

    const date = new Date(currentDate);
    const dayName = dayNames[date.getDay()];
    const isRegularOffDay = employee.regular_off_day === dayName && employee.regular_off_day !== 'Tidak Libur';

    if (isRegularOffDay && status === 'Libur') {
        showNotification('info', 'Libur tetap sudah tersimpan otomatis');
        closeModal();
        return;
    }

    showLoading('Menyimpan data...');

    $.ajax({
        url: '{{ route("attendance.store") }}',
        method: 'POST',
        data: {
            employee_id: currentEmployeeId,
            date: currentDate,
            status: status,
            note: note
        },
        success: function(response) {
            hideLoading();
            showNotification('success', response.message);
            refreshCalendarEvents();
            closeModal();
        },
        error: function(xhr) {
            hideLoading();
            let message = 'Terjadi kesalahan';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showNotification('error', message);
        }
    });
}

function closeModal() {
    $('#attendanceModal').removeClass('show');
}

$(document).on('click', function(e) {
    if ($(e.target).hasClass('modal-custom')) {
        closeModal();
    }
});

$(document).on('keydown', function(e) {
    if (e.key === 'Escape' && $('#attendanceModal').hasClass('show')) {
        closeModal();
    }
});

window.addEventListener('pageshow', function() {
    hideLoading();
});

// Handle resize untuk FullCalendar
window.addEventListener('resize', function() {
    if (calendar) {
        calendar.updateSize();
    }
});
</script>
@endpush
