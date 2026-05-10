<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ── Root redirect ─────────────────────────────────────
Route::get('/', fn() => redirect('/home'));
Route::get('/dashboard', fn() => redirect('/home'))->middleware('auth');

// ══════════════════════════════════════════════
// AUTH
// ══════════════════════════════════════════════
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

    // ══════════════════════════════════════════════
    // ONBOARDING
    // ══════════════════════════════════════════════
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
        Route::post('profile/update/{id?}', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::post('profile/photo', [AccountController::class, 'updatePhoto'])->name('profile.photo');
        Route::post('profile/email', [AccountController::class, 'updateEmail'])->name('profile.email');
        Route::post('profile/password', [AccountController::class, 'updatePassword'])->name('profile.password');
        Route::post('profile/ttd', [AccountController::class, 'uploadTtd'])->name('profile.ttd');
        Route::post('profile/pin', [AccountController::class, 'setPin'])->name('profile.pin');
        Route::get('profile/ttd/preview', [AccountController::class, 'showTtd'])->name('profile.ttd.preview');

        Route::get('/ttd-preview/{userId}', function($userId) {
            $profile = \App\Models\EmployeeProfile::where('user_id', $userId)->firstOrFail();
            if (!$profile->ttd_path) abort(404);
            $path = storage_path('app/private/' . $profile->ttd_path);
            if (!file_exists($path)) abort(404);
            return response()->file($path, ['Content-Type' => 'image/png']);
        })->name('ttd.preview.user')->middleware('auth');

        // ── Digital Signature (New Public Storage approach) ──
        Route::post('profile/signature/{id?}', [AccountController::class, 'uploadSignature'])->name('profile.signature.upload');
        Route::delete('profile/signature/{id?}', [AccountController::class, 'deleteSignature'])->name('profile.signature.delete');

        // ── Profil ─────────────────────────────────────────
        Route::get('page/account/{user_id}', [AccountController::class, 'profileDetail']);

        // ── Search ─────────────────────────────────────────
        Route::get('search', [SearchController::class, 'cari'])->name('search');

        // ══════════════════════════════════════════════
        // HR MANAGEMENT (role: hr)
        // ══════════════════════════════════════════════
    Route::prefix('hr')->group(function () {

        Route::controller(HRController::class)->group(function () {
            // Karyawan
            Route::get('employee/list', 'employeeList')->name('hr/employee/list');
            Route::post('employee/save', 'employeeSaveRecord')->name('hr/employee/save');
            Route::post('employee/update', 'employeeUpdateRecord')->name('hr/employee/update');
            Route::post('employee/delete', 'employeeDeleteRecord')->name('hr/employee/delete');
            Route::get('employee/show/{id}', 'showEmployee')->name('hr/employee/show');
            Route::get('employee/{id}/edit', 'editEmployee')->name('hr/employee/edit');

            // Hari Libur
            Route::get('holidays/page', 'holidayPage')->name('hr/holidays/page');
            Route::post('holidays/save', 'holidaySaveRecord')->name('hr/holidays/save');
            Route::post('holidays/delete', 'holidayDeleteRecord')->name('hr/holidays/delete');


            // Attendance
            Route::get('attendance/page', 'attendance')->name('hr/attendance/page');
            Route::get('attendance/main/page', 'attendanceMain')->name('hr/attendance/main/page');

        });

        // ── Absensi ──────────────────────────────────────
        Route::controller(AbsensiController::class)->group(function () {
            Route::get('absensi/page', 'index')->name('hr/absensi/page');
            Route::get('absensi/export/excel', 'exportExcel')->name('hr/absensi/export/excel');
            Route::get('absensi/export/pdf', 'exportPdf')->name('hr/absensi/export/pdf');
            Route::delete('absensi/{id}', 'destroy')->name('hr/absensi/delete');
        });

        // ── Import Absensi ────────────────────────────────
        Route::controller(\App\Http\Controllers\AbsensiImportController::class)->group(function () {
            Route::get('absensi/import', 'showImport')->name('hr/absensi/import');
            Route::post('absensi/import', 'import')->name('hr/absensi/import/store');
            Route::post('absensi/import/map', 'mapFingerprint')->name('hr/absensi/import/map');
        });

        // ── AI Absensi ────────────────────────────────────
        Route::controller(\App\Http\Controllers\AbsensiAiController::class)->group(function () {
            Route::get('absensi/ai', 'showUpload')->name('hr/absensi/ai');
            Route::post('absensi/ai/analyze', 'analyze')->name('hr/absensi/ai/analyze');
            Route::get('absensi/ai/preview', 'preview')->name('hr/absensi/ai/preview');
            Route::post('absensi/ai/save', 'save')->name('hr/absensi/ai/save');
        });

        // ── Shift ─────────────────────────────────────────
        Route::controller(ShiftController::class)->group(function () {
            Route::get('shift/page', 'index')->name('hr/shift/page');
            Route::post('shift/store', 'store')->name('hr/shift/store');
            Route::post('shift/delete', 'destroy')->name('hr/shift/delete');
            Route::get('shift/jadwal', 'jadwal')->name('hr/shift/jadwal');
            Route::post('shift/jadwal/store', 'simpanJadwal')->name('hr/shift/jadwal/store');
        });


        // ══════════════════════════════════════════════
        // SETTINGS DOKUMEN
        // ══════════════════════════════════════════════
        Route::controller(\App\Http\Controllers\DocumentSettingController::class)
            ->prefix('settings')
            ->middleware('role:hr|super-admin')
            ->group(function () {
                Route::get('document', 'index')->name('hr.settings.document');
                Route::post('document', 'update')->name('hr.settings.document.update');
                Route::post('document/logo', 'uploadLogo')->name('hr.settings.document.logo');
            });

    }); // end prefix hr

        // ══════════════════════════════════════════════
        // SURAT (role: staff, supervisor, hr)
        // ══════════════════════════════════════════════
        Route::controller(SuratController::class)
        ->prefix('surat')
        ->name('surat.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');

            Route::get('ttd-mode', 'getTtdMode')->name('ttd-mode');
            Route::get('ttd-preview/{jabatan}', 'getTtdPreview')->name('ttd-preview');
            Route::get('{surat}', 'show')->name('show');
            Route::get('{surat}/edit', 'edit')->name('edit');
            Route::put('{surat}', 'update')->name('update');
            Route::get('{surat}/download', 'download')->name('download');
            Route::delete('{surat}', 'destroy')->name('destroy');

            // Approve & reject berbasis jabatan (HOD→Purchasing→Owner Rep→Direktur)
            Route::middleware(['role:hr|supervisor|super-admin'])->group(function () {
                Route::post('{surat}/approve', 'approve')->name('approve');
                Route::post('{surat}/reject', 'reject')->name('reject');
            });

            // Route sementara untuk regenerate cover PDF / Stamp
            Route::get('{id}/regenerate-final', function($id) {
                $surat = \App\Models\Surat::findOrFail($id);
                $coverService = app(\App\Services\ApprovalCoverService::class);
                $stampService = app(\App\Services\PdfStampService::class);
                try {
                    $documentType = 'surat_' . $surat->jenis_surat;
                    $step = \App\Models\ApprovalStep::where('document_type', $documentType)->first();
                    $ttdMode = $step?->ttd_mode ?? 'append';

                    if ($ttdMode === 'stamp') {
                        $path = $stampService->stamp($surat);
                        $surat->update(['final_pdf_path' => $path]);
                    } else {
                        $path = $coverService->generateCover($surat);
                        $surat->update(['cover_pdf_path' => $path]);
                        $finalPath = $coverService->processMerge($surat);
                        if ($finalPath) {
                            $surat->update(['final_pdf_path' => $finalPath]);
                            $path = $finalPath;
                        }
                    }
                    return response()->json(['success' => true, 'path' => $path]);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            })->name('regenerate-final');
        });

        // ══════════════════════════════════════════════
        // SURAT TYPE MANAGEMENT (role: hr)
        // ══════════════════════════════════════════════
        Route::middleware('role:hr')->prefix('surat-type')->name('surat-type.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SuratTypeController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\SuratTypeController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\SuratTypeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\SuratTypeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\SuratTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\SuratTypeController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle', [\App\Http\Controllers\SuratTypeController::class, 'toggle'])->name('toggle');
        });

    }); // end middleware('onboarding')

}); // end middleware('auth')