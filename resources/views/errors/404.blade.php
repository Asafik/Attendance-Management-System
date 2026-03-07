@extends('layouts.partials.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--bg-body) 0%, var(--bg-card) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                {{-- CARD UTAMA --}}
                <div class="error-card" style="background-color: var(--bg-card); border-radius: 30px; padding: 50px 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1), 0 4px 12px rgba(22,163,74,0.2); border: 1px solid var(--border-color); position: relative; overflow: hidden;">

                    {{-- ANIMASI BACKGROUND --}}
                    <div class="error-bg-animation">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-2"></div>
                        <div class="circle circle-3"></div>
                    </div>

                    {{-- ANIMASI 404 --}}
                    <div class="text-center position-relative" style="z-index: 2;">
                        <div class="error-number-container mb-4">
                            <span class="error-digit">4</span>
                            <span class="error-icon">
                                <i class="bi bi-emoji-frown"></i>
                            </span>
                            <span class="error-digit">4</span>
                        </div>

                        {{-- ANIMASI TEKS --}}
                        <h2 class="error-title mb-3">
                            <span class="fade-in">Halaman</span>
                            <span class="fade-in delay-1">Tidak</span>
                            <span class="fade-in delay-2">Ditemukan</span>
                        </h2>

                        <div class="error-description-wrapper mb-4">
                            <p class="error-description slide-up">
                                <i class="bi bi-exclamation-triangle me-2" style="color: var(--accent-color);"></i>
                                Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.
                            </p>
                        </div>

                        {{-- CARD INFO --}}
                        <div class="info-card mb-4 pulse-animation" style="background: linear-gradient(145deg, var(--accent-soft) 0%, rgba(22,163,74,0.05) 100%); border-radius: 20px; padding: 20px; border: 1px solid var(--accent-color);">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 50px; height: 50px; background: var(--accent-color); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-info-circle" style="font-size: 24px; color: white;"></i>
                                </div>
                                <div class="text-start">
                                    <h6 style="color: var(--text-primary); margin: 0; font-weight: 600;">Tidak menemukan halaman?</h6>
                                    <p style="color: var(--text-secondary); margin: 0; font-size: 13px;">Hubungi administrator untuk bantuan lebih lanjut</p>
                                </div>
                            </div>
                        </div>

                        {{-- BUTTONS DENGAN ANIMASI --}}
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('dashboard') }}" class="btn-dashboard hover-animation" style="background: var(--accent-color); color: white; border: none; padding: 14px 35px; border-radius: 50px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; text-decoration: none; box-shadow: 0 8px 20px rgba(22,163,74,0.3);">
                                <i class="bi bi-house-door"></i>
                                Kembali ke Dashboard
                                <i class="bi bi-arrow-right-short"></i>
                            </a>

                            <button onclick="window.history.back()" class="btn-back hover-animation" style="background: transparent; border: 2px solid var(--border-color); color: var(--text-secondary); padding: 14px 35px; border-radius: 50px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; transition: all 0.3s ease;">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </button>
                        </div>

                        {{-- ANIMASI LOADING DOTS --}}
                        <div class="loading-dots mt-5">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ===== CARD & BACKGROUND ===== */
    .error-card {
        position: relative;
        backdrop-filter: blur(10px);
        animation: cardFloat 3s ease-in-out infinite;
    }

    @keyframes cardFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* ===== ANIMASI BACKGROUND ===== */
    .error-bg-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }

    .circle {
        position: absolute;
        background: var(--accent-soft);
        border-radius: 50%;
        animation: moveCircle 15s linear infinite;
    }

    .circle-1 {
        width: 200px;
        height: 200px;
        top: -100px;
        right: -100px;
        animation-delay: 0s;
    }

    .circle-2 {
        width: 150px;
        height: 150px;
        bottom: -75px;
        left: -75px;
        animation-delay: 5s;
    }

    .circle-3 {
        width: 100px;
        height: 100px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: pulseCircle 3s ease-in-out infinite;
    }

    @keyframes moveCircle {
        0% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, 30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
        100% { transform: translate(0, 0) rotate(360deg); }
    }

    @keyframes pulseCircle {
        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
        50% { transform: translate(-50%, -50%) scale(1.5); opacity: 0.6; }
    }

    /* ===== ANIMASI ANGKA 404 ===== */
    .error-number-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .error-digit {
        font-size: 100px;
        font-weight: 800;
        color: var(--accent-color);
        text-shadow: 0 10px 20px rgba(22,163,74,0.3);
        animation: digitPop 2s ease-in-out infinite;
        display: inline-block;
    }

    .error-digit:nth-child(1) { animation-delay: 0s; }
    .error-digit:nth-child(3) { animation-delay: 0.5s; }

    .error-icon {
        font-size: 80px;
        color: var(--accent-color);
        animation: iconBounce 1.5s ease-in-out infinite;
        display: inline-block;
    }

    @keyframes digitPop {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); text-shadow: 0 15px 30px rgba(22,163,74,0.5); }
    }

    @keyframes iconBounce {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    /* ===== ANIMASI TEKS ===== */
    .error-title {
        font-size: 36px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .fade-in {
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards;
        display: inline-block;
        margin: 0 3px;
    }

    .fade-in.delay-1 { animation-delay: 0.3s; }
    .fade-in.delay-2 { animation-delay: 0.6s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-description-wrapper {
        overflow: hidden;
    }

    .error-description {
        animation: slideInLeft 1s ease forwards;
        color: var(--text-secondary);
        font-size: 16px;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ===== CARD INFO ANIMASI ===== */
    .info-card {
        transition: all 0.3s ease;
    }

    .pulse-animation {
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 5px 15px rgba(22,163,74,0.2); }
        50% { transform: scale(1.02); box-shadow: 0 8px 25px rgba(22,163,74,0.4); }
    }

    /* ===== BUTTON ANIMASI ===== */
    .btn-dashboard, .btn-back {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .hover-animation {
        position: relative;
        z-index: 1;
    }

    .hover-animation::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
        z-index: -1;
    }

    .hover-animation:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-dashboard:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 15px 30px rgba(22,163,74,0.4);
    }

    .btn-back:hover {
        background-color: var(--accent-soft) !important;
        color: var(--accent-color) !important;
        border-color: var(--accent-color) !important;
        transform: translateY(-3px);
    }

    /* ===== LOADING DOTS ===== */
    .loading-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .loading-dots span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent-color);
        animation: dots 1.5s ease-in-out infinite;
    }

    .loading-dots span:nth-child(1) { animation-delay: 0s; }
    .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
    .loading-dots span:nth-child(3) { animation-delay: 0.4s; }
    .loading-dots span:nth-child(4) { animation-delay: 0.6s; }
    .loading-dots span:nth-child(5) { animation-delay: 0.8s; }

    @keyframes dots {
        0%, 100% { transform: scale(0.5); opacity: 0.3; }
        50% { transform: scale(1.2); opacity: 1; }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .error-digit {
            font-size: 70px;
        }

        .error-icon {
            font-size: 60px;
        }

        .error-title {
            font-size: 28px;
        }

        .error-card {
            padding: 30px 20px;
        }

        .btn-dashboard, .btn-back {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .error-digit {
            font-size: 50px;
        }

        .error-icon {
            font-size: 40px;
        }

        .error-title {
            font-size: 24px;
        }
    }
</style>
@endpush
