@extends('layouts.partials.app')

@section('title', 'Login - Wadul Guse')
@section('page-title', 'Login')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center position-relative" style="background-color: var(--bg-body); overflow: hidden;">

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

    {{-- DECORATIVE ICONS --}}
    <div style="position: absolute; top: 5%; left: 5%; opacity: 0.1; transform: rotate(-15deg);">
        <i class="bi bi-building" style="font-size: 120px; color: var(--accent-color);"></i>
    </div>
    <div style="position: absolute; top: 10%; right: 8%; opacity: 0.1; transform: rotate(10deg);">
        <i class="bi bi-person-badge" style="font-size: 100px; color: var(--accent-color);"></i>
    </div>
    <div style="position: absolute; left: 2%; top: 40%; opacity: 0.1; transform: rotate(20deg);">
        <i class="bi bi-clock-history" style="font-size: 80px; color: var(--accent-color);"></i>
    </div>
    <div style="position: absolute; right: 3%; top: 60%; opacity: 0.1; transform: rotate(-10deg);">
        <i class="bi bi-calendar-check" style="font-size: 90px; color: var(--accent-color);"></i>
    </div>
    <div style="position: absolute; left: 8%; bottom: 10%; opacity: 0.1; transform: rotate(5deg);">
        <i class="bi bi-fingerprint" style="font-size: 110px; color: var(--accent-color);"></i>
    </div>
    <div style="position: absolute; right: 5%; bottom: 5%; opacity: 0.1; transform: rotate(-20deg);">
        <i class="bi bi-people" style="font-size: 130px; color: var(--accent-color);"></i>
    </div>

    {{-- DECORATIVE LINES --}}
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
        <div style="position: absolute; top: 15%; left: -5%; width: 200px; height: 2px; background: linear-gradient(90deg, transparent, var(--accent-color), transparent); transform: rotate(45deg); opacity: 0.2;"></div>
        <div style="position: absolute; bottom: 20%; right: -5%; width: 300px; height: 2px; background: linear-gradient(90deg, transparent, var(--accent-color), transparent); transform: rotate(-30deg); opacity: 0.15;"></div>
    </div>

    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                {{-- CARD LOGIN --}}
                <div style="background-color: var(--bg-card); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1), 0 4px 12px rgba(22, 163, 74, 0.2); border: 1px solid var(--border-color); backdrop-filter: blur(10px);">

                    {{-- LOGO & HEADER --}}
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: var(--accent-soft); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 2px solid var(--accent-color);">
                            <i class="bi bi-building" style="font-size: 40px; color: var(--accent-color);"></i>
                        </div>
                        <h2 style="color: var(--text-primary); font-weight: 700; margin-bottom: 5px;">Wadul Guse</h2>
                        <p style="color: var(--text-secondary); font-size: 14px;">Attendance System</p>
                    </div>

                    {{-- FORM LOGIN --}}
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary); font-weight: 500; margin-bottom: 8px;">
                                <i class="bi bi-envelope me-2" style="color: var(--accent-color);"></i>
                                Email
                            </label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus style="background-color: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary); padding: 12px 16px; border-radius: 10px;">
                            @error('email')
                                <small style="color: #ef4444; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- PASSWORD DENGAN EYE ICON --}}
                        <div class="mb-4">
                            <label class="form-label" style="color: var(--text-primary); font-weight: 500; margin-bottom: 8px;">
                                <i class="bi bi-lock me-2" style="color: var(--accent-color);"></i>
                                Password
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="password" id="password" class="form-control" required style="background-color: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary); padding: 12px 16px; padding-right: 45px; border-radius: 10px; width: 100%;">
                                <span onclick="togglePassword()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <small style="color: #ef4444; margin-top: 5px; display: block;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- REMEMBER ME --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" style="accent-color: var(--accent-color);">
                                <label class="form-check-label" for="remember" style="color: var(--text-secondary); font-size: 14px;">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="#" style="color: var(--accent-color); text-decoration: none; font-size: 14px; font-weight: 500;">
                                Lupa password?
                            </a>
                        </div>

                        {{-- BUTTON LOGIN --}}
                        <button type="submit" class="btn w-100" id="loginBtn" style="background: var(--accent-color); color: white; border: none; padding: 14px; border-radius: 10px; font-weight: 600; font-size: 16px; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer;">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </button>
                    </form>

                    {{-- FOOTER --}}
                    <div class="text-center mt-4">
                        <p style="color: var(--text-secondary); font-size: 12px;">
                            &copy; 2026 Wadul Guse. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .min-vh-100 {
        min-height: 100vh;
    }

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

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        outline: none;
    }

    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        background: #15803d !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3) !important;
    }

    .btn:active {
        transform: translateY(0);
    }

    a:hover {
        color: #15803d !important;
        text-decoration: underline !important;
    }
</style>
@endpush

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

    // ===== TOGGLE PASSWORD VISIBILITY =====
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }

    // ===== FORM SUBMIT WITH LOADING =====
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        showLoading('Memproses login...');

        // Disable button dan tambah class loading
        const loginBtn = document.getElementById('loginBtn');
        loginBtn.classList.add('btn-loading');
        loginBtn.disabled = true;

        // Biarkan form submit
        return true;
    });

    // ===== HIDE LOADING WHEN PAGE LOADS =====
    window.addEventListener('pageshow', function() {
        hideLoading();
    });
</script>
@endpush
