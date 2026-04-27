<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ── Root redirect ke login ─────────────────────────────
Route::get('/', function () {
    return view('auth.login');
});

// ══════════════════════════════════════════════════════
// AUTH ROUTES
// ══════════════════════════════════════════════════════
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('logout/page', 'logoutPage')->name('logout/page');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'storeUser');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'getEmail')->name('forget-password');
    Route::post('forget-password', 'postEmail');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('reset-password/{token}', 'getPassword');
    Route::post('reset-password', 'updatePassword');
});

// ══════════════════════════════════════════════════════
// AUTHENTICATED ROUTES
// ══════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    // ── ONBOARDING ROUTES (NO middleware to avoid loops) ──
    Route::controller(AccountController::class)->group(function () {
        Route::get('onboarding', 'showOnboarding')->name('onboarding');
        Route::post('onboarding/ttd', 'onboardingTtd')->name('onboarding.ttd');
        Route::post('onboarding/pin', 'onboardingPin')->name('onboarding.pin');
    });

    // ── ALL OTHER ROUTES (with onboarding middleware) ────
    Route::middleware('onboarding')->group(function () {

        // ── Dashboard ──────────────────────────────────────
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // ── Profil user ────────────────────────────────────
        Route::get('profile', [AccountController::class, 'showProfile'])->name('profile.show');
        Route::post('profile/update', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::post('profile/ttd', [AccountController::class, 'uploadTtd'])->name('profile.ttd');
        Route::post('profile/pin', [AccountController::class, 'setPin'])->name('profile.pin');
        Route::get('profile/ttd/preview', [AccountController::class, 'showTtd'])->name('profile.ttd.preview');

        // ── Profil ─────────────────────────────────────────
        Route::get('page/account/{user_id}', [AccountController::class, 'profileDetail']);

        // ── Search ─────────────────────────────────────────
        Route::get('search', [SearchController::class, 'cari'])->name('search');

    // ── HR MODULE ──────────────────────────────────────
    Route::prefix('hr')->group(function () {

        Route::controller(HRController::class)->group(function () {
            // Karyawan
            Route::get('employee/list', 'employeeList')->name('hr/employee/list');
            Route::post('employee/save', 'employeeSaveRecord')->name('hr/employee/save');
            Route::post('employee/update', 'employeeUpdateRecord')->name('hr/employee/update');
            Route::post('employee/delete', 'employeeDeleteRecord')->name('hr/employee/delete');

            // Hari Libur
            Route::get('holidays/page', 'holidayPage')->name('hr/holidays/page');
            Route::post('holidays/save', 'holidaySaveRecord')->name('hr/holidays/save');
            Route::post('holidays/delete', 'holidayDeleteRecord')->name('hr/holidays/delete');

            // Cuti Karyawan
            Route::get('leave/employee/page', 'leaveEmployee')->name('hr/leave/employee/page');
            Route::get('create/leave/employee/page', 'createLeaveEmployee')->name('hr/create/leave/employee/page');
            Route::post('create/leave/employee/save', 'saveRecordLeave')->name('hr/create/leave/employee/save');
            Route::get('view/detail/leave/employee/{staff_id}', 'viewDetailLeave');

            // Cuti HR
            Route::get('leave/hr/page', 'leaveHR')->name('hr/leave/hr/page');
            Route::post('leave/approve', 'approveLeave')->name('hr/leave/approve');
            Route::post('leave/reject', 'rejectLeave')->name('hr/leave/reject');
            Route::get('create/leave/hr/page', 'createLeaveHR')->name('hr/create/leave/hr/page');
            Route::post('get/information/leave', 'getInformationLeave')->name('hr/get/information/leave');

            // Attendance
            Route::get('attendance/page', 'attendance')->name('hr/attendance/page');
            Route::get('attendance/main/page', 'attendanceMain')->name('hr/attendance/main/page');

            // Departemen
            Route::get('department/page', 'department')->name('hr/department/page');
            Route::post('department/save', 'saveRecorddepartment')->name('hr/department/save');
            Route::post('department/delete', 'deleteRecorddepartment')->name('hr/department/delete');
        });

        // ── Absensi ──────────────────────────────────────
        Route::controller(AbsensiController::class)->group(function () {
            Route::get('absensi/page', 'index')->name('hr/absensi/page');
            Route::post('absensi/store', 'store')->name('hr/absensi/store');
            Route::post('absensi/clock-in', 'clockIn')->name('hr/absensi/clock-in');
            Route::post('absensi/clock-out', 'clockOut')->name('hr/absensi/clock-out');
        });

        // ── Import Absensi ────────────────────────────────
        Route::controller(\App\Http\Controllers\AbsensiImportController::class)->group(function () {
            Route::get('absensi/import', 'showImport')->name('hr/absensi/import');
            Route::post('absensi/import', 'import')->name('hr/absensi/import/store');
        });

        // ── Shift ─────────────────────────────────────────
        Route::controller(ShiftController::class)->group(function () {
            Route::get('shift/page', 'index')->name('hr/shift/page');
            Route::post('shift/store', 'store')->name('hr/shift/store');
            Route::post('shift/delete', 'destroy')->name('hr/shift/delete');
            Route::get('shift/jadwal', 'jadwal')->name('hr/shift/jadwal');
            Route::post('shift/jadwal/store', 'simpanJadwal')->name('hr/shift/jadwal/store');
        });

        // ── Approval Flow Manager ─────────────────────────
        Route::controller(\App\Http\Controllers\ApprovalFlowController::class)->group(function () {
            Route::get('approval-flow', 'index')->name('hr.approval-flow.index');
            Route::get('approval-flow/audit', 'auditLog')->name('hr.approval-flow.audit');
            Route::post('approval-flow/store', 'store')->name('hr.approval-flow.store');
            Route::delete('approval-flow/{id}', 'destroy')->name('hr.approval-flow.destroy');
            Route::post('approval-flow/reorder', 'reorder')->name('hr.approval-flow.reorder');
            Route::post('approval-flow/reassign', 'reassign')->name('hr.approval-flow.reassign');
        });

        // ── Penggajian ────────────────────────────────────
        Route::controller(PenggajianController::class)->group(function () {
            Route::get('penggajian/page', 'index')->name('hr/penggajian/page');
            Route::post('penggajian/generate', 'generate')->name('hr/penggajian/generate');
            Route::get('penggajian/show/{id}', 'show')->name('hr/penggajian/show');
            Route::post('penggajian/update-komponen', 'updateKomponen')->name('hr/penggajian/update-komponen');
            Route::post('penggajian/bayar', 'bayar')->name('hr/penggajian/bayar');
        });

    }); // end prefix hr

    Route::controller(SuratController::class)
        ->prefix('surat')
        ->name('surat.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');

            Route::get('{surat}', 'show')->name('show');
            Route::get('{surat}/edit', 'edit')->name('edit');
            Route::put('{surat}', 'update')->name('update');
            Route::get('{surat}/download', 'download')->name('download');

            // Approve & reject berbasis jabatan (HOD→Purchasing→Owner Rep→Direktur)
            Route::post('{surat}/approve', 'approve')->name('approve');
            Route::post('{surat}/reject', 'reject')->name('reject');
        });

    }); // end middleware('onboarding')

}); // end middleware('auth')