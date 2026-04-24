<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WeeklyOffDayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ReportAttendanceController;
use App\Http\Controllers\ReportMonthlyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckActiveUser;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LocationController; // ← TAMBAHKAN INI

use App\Http\Controllers\OfficeLocationController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Route untuk Error 419 (Session Expired)
|--------------------------------------------------------------------------
*/
Route::get('/session-expired', function () {
    return view('errors.419');
})->name('error.419');

/*
|--------------------------------------------------------------------------
| Protected Routes (Harus Login, User Aktif & Single Session)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', CheckActiveUser::class, \App\Http\Middleware\CheckSingleSession::class])->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Master Data - Employees
    |--------------------------------------------------------------------------
    */
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Master Data - Divisions
    |--------------------------------------------------------------------------
    */
    Route::prefix('divisions')->name('divisions.')->group(function () {
        Route::get('/', [DivisionController::class, 'index'])->name('index');
        Route::post('/', [DivisionController::class, 'store'])->name('store');
        Route::put('/{division}', [DivisionController::class, 'update'])->name('update');
        Route::delete('/{division}', [DivisionController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Master Data - Banks
    |--------------------------------------------------------------------------
    */
    Route::prefix('banks')->name('banks.')->group(function () {
        Route::get('/', [BankController::class, 'index'])->name('index');
        Route::post('/', [BankController::class, 'store'])->name('store');
        Route::put('/{bank}', [BankController::class, 'update'])->name('update');
        Route::delete('/{bank}', [BankController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Master Data - Positions
    |--------------------------------------------------------------------------
    */
    Route::prefix('positions')->name('positions.')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('index');
        Route::post('/', [PositionController::class, 'store'])->name('store');
        Route::put('/{position}', [PositionController::class, 'update'])->name('update');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Weekly Off Days (Libur Mingguan)
    |--------------------------------------------------------------------------
    */
    Route::prefix('weekly-off-days')->name('weekly-off-days.')->group(function () {
        Route::get('/', [WeeklyOffDayController::class, 'index'])->name('index');
        Route::post('/', [WeeklyOffDayController::class, 'store'])->name('store');
        Route::delete('/', [WeeklyOffDayController::class, 'destroy'])->name('destroy');
        Route::get('/get-week-data', [WeeklyOffDayController::class, 'getWeekData'])->name('get-week-data');
        Route::post('/copy-from-previous', [WeeklyOffDayController::class, 'copyFromPreviousWeek'])->name('copy');
    });

    /*
    |--------------------------------------------------------------------------
    | Attendance (Absensi)
    |--------------------------------------------------------------------------
    */
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/events', [AttendanceController::class, 'events'])->name('events');
        Route::post('/store', [AttendanceController::class, 'store'])->name('store');
        Route::post('/sync-regular-off-days', [AttendanceController::class, 'syncRegularOffDays'])->name('sync');
    });

    /*
    |--------------------------------------------------------------------------
    | Leaves (Izin & Cuti)
    |--------------------------------------------------------------------------
    */
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::post('/', [LeaveController::class, 'store'])->name('store');
        Route::put('/{leave}', [LeaveController::class, 'update'])->name('update');
        Route::delete('/{leave}', [LeaveController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    // Report Attendance
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/attendance', [ReportAttendanceController::class, 'index'])->name('attendance');
        Route::get('/attendance/export', [ReportAttendanceController::class, 'export'])->name('attendance.export');
        Route::get('/monthly', [ReportMonthlyController::class, 'index'])->name('monthly');
    });

    /*
    |--------------------------------------------------------------------------
    | Settings (Pengaturan)
    |--------------------------------------------------------------------------
    */
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

// Jalur untuk menampilkan daftar semua lokasi
Route::get('/office-locations', [OfficeLocationController::class, 'index'])->name('office-locations.index');

// Jalur untuk menampilkan satu lokasi (QR Code) berdasarkan ID
Route::get('/office-locations/{id}', [OfficeLocationController::class, 'show'])->name('office-locations.show');
    /*
    |--------------------------------------------------------------------------
    | ActivityLog
    |--------------------------------------------------------------------------
    */
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    /*
    |--------------------------------------------------------------------------
    | ===== LOCATION API ROUTES (TAMBAHAN) =====
    |--------------------------------------------------------------------------
    */
    Route::post('/save-location-permission', [LocationController::class, 'savePermission'])->name('location.save-permission');
    Route::post('/update-location', [LocationController::class, 'updateLocation'])->name('location.update');
    Route::get('/get-location', [LocationController::class, 'getLocation'])->name('location.get');
    Route::post('/reverse-geocode', [LocationController::class, 'reverseGeocode'])->name('location.reverse-geocode');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
