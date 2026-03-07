@extends('layouts.partials.app')

@section('title', 'Pengaturan Sistem - Wadul Guse')
@section('page-title', 'Pengaturan Sistem')

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

    /* ===== CARD SETTINGS ===== */
    .settings-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        max-width: 900px;
        margin: 0 auto;
    }

    .settings-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .settings-title i {
        color: var(--accent-color);
        font-size: 24px;
    }

    /* ===== INFO CARD ===== */
    .info-card {
        background-color: var(--accent-soft);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border-left: 4px solid var(--accent-color);
    }

    .info-card p {
        color: var(--text-primary);
        font-size: 13px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card i {
        color: var(--accent-color);
        font-size: 16px;
    }

    /* ===== INFO LOG ===== */
    .info-log {
        background-color: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .info-log-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-log-title i {
        color: var(--accent-color);
    }

    .info-log-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .info-log-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background-color: var(--accent-soft);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .info-log-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background-color: var(--accent-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .info-log-content {
        flex: 1;
    }

    .info-log-label {
        font-size: 11px;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-log-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        word-break: break-all;
    }

    .info-log-time {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    /* ===== SETTINGS SECTION ===== */
    .settings-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .settings-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: var(--accent-color);
    }

    /* ===== FORM ===== */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        min-width: 250px;
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
        opacity: 0.7;
    }

    /* ===== UPLOAD LOGO ===== */
    .upload-container {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .logo-preview {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 2px dashed var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: var(--accent-soft);
    }

    .logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .logo-preview i {
        font-size: 40px;
        color: var(--text-secondary);
    }

    .upload-wrapper {
        flex: 1;
        min-width: 200px;
    }

    .upload-btn {
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

    .upload-btn:hover {
        background: #16a34a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    .upload-info {
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 12px;
    }

    /* ===== FAVICON UPLOAD ===== */
    .favicon-preview {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: var(--accent-soft);
    }

    .favicon-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .favicon-preview i {
        font-size: 16px;
        color: var(--text-secondary);
    }

    .favicon-row {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* ===== BUTTON SAVE ===== */
    .btn-save {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 20px;
    }

    .btn-save:hover {
        background: #16a34a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    .btn-save i {
        font-size: 18px;
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
        max-width: 400px;
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
        text-align: center;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #16a34a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .content-area {
            padding: 16px;
        }

        .settings-card {
            padding: 20px;
        }

        .form-row {
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            min-width: 100%;
        }

        .btn-save {
            width: 100%;
            justify-content: center;
        }

        .upload-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .logo-preview {
            width: 100px;
            height: 100px;
        }

        .info-log-grid {
            grid-template-columns: 1fr;
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
                <div class="settings-card">
                    <div class="settings-title">
                        <i class="bi bi-gear"></i>
                        Pengaturan Perusahaan
                    </div>

                    {{-- INFO CARD --}}
                    <div class="info-card">
                        <p>
                            <i class="bi bi-info-circle"></i>
                            Data perusahaan akan digunakan di sidebar, navbar, laporan, dan halaman login.
                        </p>
                    </div>

                    {{-- INFO LOG - IP, DEVICE, OS, WAKTU --}}
                    <div class="info-log">
                        <div class="info-log-title">
                            <i class="bi bi-shield-lock"></i>
                            Informasi Akses
                        </div>
                        <div class="info-log-grid" id="infoLogContainer">
                            <div class="info-log-item">
                                <div class="info-log-icon">
                                    <i class="bi bi-hdd-network"></i>
                                </div>
                                <div class="info-log-content">
                                    <div class="info-log-label">IP Address</div>
                                    <div class="info-log-value" id="ipAddress">Memuat...</div>
                                </div>
                            </div>
                            <div class="info-log-item">
                                <div class="info-log-icon">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="info-log-content">
                                    <div class="info-log-label">Device</div>
                                    <div class="info-log-value" id="device">Memuat...</div>
                                </div>
                            </div>
                            <div class="info-log-item">
                                <div class="info-log-icon">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div class="info-log-content">
                                    <div class="info-log-label">Operating System</div>
                                    <div class="info-log-value" id="os">Memuat...</div>
                                </div>
                            </div>
                            <div class="info-log-item">
                                <div class="info-log-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="info-log-content">
                                    <div class="info-log-label">Waktu Akses</div>
                                    <div class="info-log-value" id="waktuAkses">Memuat...</div>
                                    <div class="info-log-time" id="timezone"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="settingForm" enctype="multipart/form-data">
                        {{-- SECTION 1: IDENTITAS PERUSAHAAN --}}
                        <div class="settings-section">
                            <div class="section-title">
                                <i class="bi bi-building"></i>
                                Identitas Perusahaan
                            </div>

                            {{-- NAMA PERUSAHAAN --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-building"></i>
                                        Nama Perusahaan <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="namaPerusahaan" value="Alena Mandiri Group" placeholder="Masukkan nama perusahaan">
                                </div>
                            </div>

                            {{-- LOGO PERUSAHAAN --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-image"></i>
                                        Logo Perusahaan
                                    </label>
                                    <div class="upload-container">
                                        <div class="logo-preview" id="logoPreview">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div class="upload-wrapper">
                                            <button type="button" class="upload-btn" onclick="document.getElementById('logoInput').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                Upload Logo
                                            </button>
                                            <input type="file" id="logoInput" accept="image/*" style="display: none;" onchange="previewLogo(this)">
                                            <div class="upload-info">
                                                Format: JPG, PNG. Maks 2MB
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- FAVICON --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-browser-edge"></i>
                                        Favicon
                                    </label>
                                    <div class="favicon-row">
                                        <div class="favicon-preview" id="faviconPreview">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <button type="button" class="upload-btn" style="padding: 8px 16px;" onclick="document.getElementById('faviconInput').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                Upload Favicon
                                            </button>
                                            <input type="file" id="faviconInput" accept=".ico,.png" style="display: none;" onchange="previewFavicon(this)">
                                            <div class="upload-info">
                                                Format: ICO, PNG. Maks 500KB
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: KONTAK PERUSAHAAN --}}
                        <div class="settings-section">
                            <div class="section-title">
                                <i class="bi bi-envelope"></i>
                                Kontak Perusahaan
                            </div>

                            <div class="form-row">
                                {{-- EMAIL --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-envelope"></i>
                                        Email Perusahaan
                                    </label>
                                    <input type="email" class="form-control" id="emailPerusahaan" value="info@alenamandiri.com" placeholder="contoh@perusahaan.com">
                                </div>

                                {{-- TELEPON --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-telephone"></i>
                                        Nomor Telepon
                                    </label>
                                    <input type="text" class="form-control" id="teleponPerusahaan" value="(021) 1234-5678" placeholder="(021) 1234-5678">
                                </div>
                            </div>

                            {{-- ALAMAT --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-geo-alt"></i>
                                        Alamat Perusahaan
                                    </label>
                                    <textarea class="form-control" id="alamatPerusahaan" rows="3" placeholder="Jl. Contoh No. 123, Jakarta">Jl. Raya Contoh No. 123, Jakarta Selatan</textarea>
                                </div>
                            </div>

                            {{-- WEBSITE --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-globe"></i>
                                        Website (Opsional)
                                    </label>
                                    <input type="url" class="form-control" id="websitePerusahaan" value="https://www.alenamandiri.com" placeholder="https://www.perusahaan.com">
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL SIMPAN --}}
                        <button type="button" class="btn-save" onclick="simpanPengaturan()">
                            <i class="bi bi-save"></i>
                            Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- FOOTER --}}
        @include('layouts.footer')
    </div>
</div>

{{-- MODAL SUKSES --}}
<div class="modal" id="successModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="bi bi-check-circle me-2"></i>Berhasil
            </h5>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-body">
            <div style="font-size: 60px; color: #10b981; margin-bottom: 20px;">
                <i class="bi bi-check-circle"></i>
            </div>
            <h4 style="color: var(--text-primary); margin-bottom: 10px;">Pengaturan Disimpan!</h4>
            <p style="color: var(--text-secondary); font-size: 14px;" id="successMessage">
                Data perusahaan berhasil disimpan.
            </p>
        </div>

        <div class="modal-footer">
            <button class="btn-primary" onclick="closeModal()">
                <i class="bi bi-check-lg me-2"></i>OK
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ===== FUNGSI UNTUK MENDAPATKAN INFORMASI AKSES =====
    function getIPAddress() {
        // Menggunakan API publik untuk mendapatkan IP
        fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => {
                document.getElementById('ipAddress').innerText = data.ip;
            })
            .catch(error => {
                document.getElementById('ipAddress').innerText = 'Tidak dapat mendeteksi IP';
                console.error('Error fetching IP:', error);
            });
    }

    function getDeviceInfo() {
        const ua = navigator.userAgent;
        let device = 'Desktop';

        // Deteksi device
        if (/mobile/i.test(ua)) {
            device = 'Mobile';
        } else if (/tablet/i.test(ua)) {
            device = 'Tablet';
        }

        // Deteksi browser
        let browser = 'Unknown';
        if (ua.indexOf('Chrome') > -1) {
            browser = 'Chrome';
        } else if (ua.indexOf('Firefox') > -1) {
            browser = 'Firefox';
        } else if (ua.indexOf('Safari') > -1) {
            browser = 'Safari';
        } else if (ua.indexOf('Edge') > -1) {
            browser = 'Edge';
        } else if (ua.indexOf('MSIE') > -1 || ua.indexOf('Trident') > -1) {
            browser = 'Internet Explorer';
        }

        document.getElementById('device').innerText = `${device} (${browser})`;
    }

    function getOSInfo() {
        const ua = navigator.userAgent;
        let os = 'Unknown';

        if (ua.indexOf('Windows') > -1) {
            os = 'Windows';
        } else if (ua.indexOf('Mac OS') > -1) {
            os = 'macOS';
        } else if (ua.indexOf('Linux') > -1) {
            os = 'Linux';
        } else if (ua.indexOf('Android') > -1) {
            os = 'Android';
        } else if (ua.indexOf('iOS') > -1 || ua.indexOf('iPhone') > -1 || ua.indexOf('iPad') > -1) {
            os = 'iOS';
        }

        document.getElementById('os').innerText = os;
    }

    function getWaktuAkses() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };

        const waktu = now.toLocaleDateString('id-ID', options);
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        document.getElementById('waktuAkses').innerText = waktu;
        document.getElementById('timezone').innerText = `Zona waktu: ${timezone}`;
    }

    // ===== PREVIEW FUNCTIONS =====
    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewFavicon(input) {
        const preview = document.getElementById('faviconPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Favicon Preview">`;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // ===== SIMPAN PENGATURAN =====
    function simpanPengaturan() {
        // Validasi nama perusahaan
        const namaPerusahaan = document.getElementById('namaPerusahaan').value.trim();
        if (!namaPerusahaan) {
            alert('Nama perusahaan harus diisi!');
            return;
        }

        // Ambil data
        const data = {
            namaPerusahaan: namaPerusahaan,
            emailPerusahaan: document.getElementById('emailPerusahaan').value,
            teleponPerusahaan: document.getElementById('teleponPerusahaan').value,
            alamatPerusahaan: document.getElementById('alamatPerusahaan').value,
            websitePerusahaan: document.getElementById('websitePerusahaan').value,
            ipAddress: document.getElementById('ipAddress').innerText,
            device: document.getElementById('device').innerText,
            os: document.getElementById('os').innerText,
            waktuAkses: document.getElementById('waktuAkses').innerText,
            timezone: document.getElementById('timezone').innerText
        };

        // Ambil file logo dan favicon
        const logo = document.getElementById('logoInput').files[0];
        const favicon = document.getElementById('faviconInput').files[0];

        // Log ke console
        console.log('=================================');
        console.log('📋 INFORMASI AKSES');
        console.log('=================================');
        console.log('🌐 IP Address:', data.ipAddress);
        console.log('📱 Device:', data.device);
        console.log('💻 Operating System:', data.os);
        console.log('⏰ Waktu Akses:', data.waktuAkses);
        console.log('🕒 Timezone:', data.timezone);
        console.log('=================================');
        console.log('🏢 DATA PERUSAHAAN');
        console.log('=================================');
        console.log('📌 Nama Perusahaan:', data.namaPerusahaan);
        console.log('📧 Email:', data.emailPerusahaan);
        console.log('📞 Telepon:', data.teleponPerusahaan);
        console.log('📍 Alamat:', data.alamatPerusahaan);
        console.log('🌍 Website:', data.websitePerusahaan);
        console.log('=================================');
        console.log('🖼️ Logo:', logo ? logo.name : 'Tidak diubah');
        console.log('🔖 Favicon:', favicon ? favicon.name : 'Tidak diubah');
        console.log('=================================');

        // Tampilkan modal sukses
        document.getElementById('successModal').classList.add('active');

        // Update pesan sukses dengan info akses
        document.getElementById('successMessage').innerHTML = `
            Data perusahaan berhasil disimpan.<br>
            <small style="color: var(--text-secondary); font-size: 11px; margin-top: 8px; display: block;">
                IP: ${data.ipAddress} | ${data.device} | ${data.os}<br>
                ${data.waktuAkses}
            </small>
        `;
    }

    // ===== MODAL FUNCTIONS =====
    function closeModal() {
        document.getElementById('successModal').classList.remove('active');
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', function() {
        getIPAddress();
        getDeviceInfo();
        getOSInfo();
        getWaktuAkses();

        // Update waktu setiap detik
        setInterval(getWaktuAkses, 1000);
    });

    // Close modal with Escape key
    $(document).keydown(function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Close modal when clicking outside
    $(document).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            closeModal();
        }
    });
</script>
@endpush
