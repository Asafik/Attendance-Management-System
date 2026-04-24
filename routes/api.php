<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\AttendanceApiController;

/*
|--------------------------------------------------------------------------
| Jalur API Aleena (Khusus Mobile App)
|--------------------------------------------------------------------------
*/

// Pintu Masuk Karyawan
Route::post('/login-employee', [AuthApiController::class, 'loginEmployee']);

// Jalur yang Butuh Token (Hanya bisa diakses setelah login)
Route::middleware('auth:sanctum')->group(function () {

    // Simpan Absensi (Hasil Scan QR/GPS)
    Route::post('/scan-absen', [AttendanceApiController::class, 'scan']);

    // Ambil Data Profil Karyawan buat Dashboard Flutter
    Route::get('/me', [AuthApiController::class, 'me']);

    // Keluar Aplikasi
    Route::post('/logout', [AuthApiController::class, 'logout']);
});
