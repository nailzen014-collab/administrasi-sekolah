<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\PemeriksaanController;
use App\Http\Controllers\Admin\PengajaranController;
use App\Http\Controllers\Guru\PersetujuanController;
use App\Http\Controllers\Siswa\PengajuanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman awal: redirect ke dashboard jika sudah login
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// ─────────────────────────────────────────────────────────────
// Route yang memerlukan autentikasi
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'force.password.change'])->group(function () {

    // Dashboard — controller menentukan view berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── ADMIN ───────────────────────────────────────────────
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Data siswa — CRUD lengkap
            Route::resource('students', StudentController::class);

            // Data guru — CRUD lengkap
            Route::resource('guru', GuruController::class);
            Route::post('guru/{guru}/reset-password', [GuruController::class, 'resetPassword'])
                ->name('guru.reset-password');

            // Data pengajaran (penugasan guru ke mapel & kelas) — CRUD lengkap
            Route::resource('pengajaran', PengajaranController::class)->except(['show']);

            // Pemeriksaan pengajuan — hanya index, show, update (patch)
            Route::get('pemeriksaan', [PemeriksaanController::class, 'index'])
                ->name('pemeriksaan.index');
            Route::get('pemeriksaan/{pengajuan}', [PemeriksaanController::class, 'show'])
                ->name('pemeriksaan.show');
            Route::patch('pemeriksaan/{pengajuan}', [PemeriksaanController::class, 'update'])
                ->name('pemeriksaan.update');
        });

    // ─── GURU ─────────────────────────────────────────────────
    Route::middleware('role:guru')
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {

            Route::get('persetujuan', [PersetujuanController::class, 'index'])
                ->name('persetujuan.index');
            Route::get('persetujuan/{pengajuan}', [PersetujuanController::class, 'show'])
                ->name('persetujuan.show');
            Route::patch('persetujuan/{pengajuan}', [PersetujuanController::class, 'update'])
                ->name('persetujuan.update');
        });

    // ─── SISWA ────────────────────────────────────────────────
    Route::middleware('role:siswa')
        ->prefix('siswa')
        ->name('siswa.')
        ->group(function () {

            Route::get('pengajuan', [PengajuanController::class, 'index'])
                ->name('pengajuan.index');
            Route::get('pengajuan/buat', [PengajuanController::class, 'create'])
                ->name('pengajuan.create');
            Route::post('pengajuan', [PengajuanController::class, 'store'])
                ->name('pengajuan.store');
            Route::get('pengajuan/{pengajuan}', [PengajuanController::class, 'show'])
                ->name('pengajuan.show');
        });
});

require __DIR__.'/auth.php';
