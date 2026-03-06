<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruProfileController;
use App\Http\Controllers\SiswaProfileController;
use App\Http\Middleware\JwtSessionMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RiwayatPointController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ExportLaporanController;

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([JwtSessionMiddleware::class])->group(function () {
    Route::resource('/users', UserController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('guru_profiles', GuruProfileController::class)->names([
        'index'   => 'guru_profiles.index',
        'create'  => 'guru_profiles.create',
        'store'   => 'guru_profiles.store',
        'show'    => 'guru_profiles.show',
        'edit'    => 'guru_profiles.edit',
        'update'  => 'guru_profiles.update',
        'destroy' => 'guru_profiles.destroy',
    ]);

    Route::resource('siswa_profiles', SiswaProfileController::class)->names([
        'index'   => 'siswa_profiles.index',
        'create'  => 'siswa_profiles.create',
        'store'   => 'siswa_profiles.store',
        'show'    => 'siswa_profiles.show',
        'edit'    => 'siswa_profiles.edit',
        'update'  => 'siswa_profiles.update',
        'destroy' => 'siswa_profiles.destroy',
    ]);

    Route::resource('mata_pelajaran', MataPelajaranController::class)->names([
        'index'   => 'mata_pelajaran.index',
        'create'  => 'mata_pelajaran.create',
        'store'   => 'mata_pelajaran.store',
        'edit'    => 'mata_pelajaran.edit',
        'update'  => 'mata_pelajaran.update',
        'destroy' => 'mata_pelajaran.destroy',
    ]);

    Route::resource('ujian', UjianController::class)->names([
        'index' => 'ujian.index',
        'create' => 'ujian.create',
        'store' => 'ujian.store',
        'edit' => 'ujian.edit',
        'update' => 'ujian.update',
        'destroy' => 'ujian.destroy',
    ]);

    Route::prefix('ujian')->group(function () {
        Route::get('/', [UjianController::class, 'index'])->name('ujian.index');
        Route::get('/create', [UjianController::class, 'create'])->name('ujian.create');
        Route::post('/create', [UjianController::class, 'store'])->name('ujian.store');
        Route::get('/{id}/edit', [UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/{id}/edit', [UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/{id}', [UjianController::class, 'destroy'])->name('ujian.destroy');
        Route::get('/{id}/export/magic-card', [UjianController::class, 'exportMagicCard'])
             ->name('ujian.export.magic_card')
             ->whereNumber('id');
    });

    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'kelas'
    ])->names([
        'index'   => 'kelas.index',
        'create'  => 'kelas.create',
        'store'   => 'kelas.store',
        'show'    => 'kelas.show',
        'edit'    => 'kelas.edit',
        'update'  => 'kelas.update',
        'destroy' => 'kelas.destroy',
    ]);

    Route::prefix('riwayat-point')->group(function () {
        Route::get('/', [RiwayatPointController::class, 'index'])->name('riwayat.point.index');
        Route::get('/chart', [RiwayatPointController::class, 'chart'])->name('riwayat.point.chart');
    });

    Route::prefix('nilai')->group(function () {
        Route::get('/', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('/ujian/{ujianId}', [NilaiController::class, 'showSiswa'])->name('nilai.siswa');
        Route::get('/ujian/{ujianId}/siswa/{siswaId}', [NilaiController::class, 'detailJawaban'])->name('nilai.detail');
        Route::post('/simpan-nilai', [NilaiController::class, 'simpanManual'])->name('nilai.simpan.manual');
        Route::get('/nilai-siswa', [NilaiController::class, 'daftarNilai'])->name('nilai.daftar');
    });

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/detail/{ujianId}', [LaporanController::class, 'show'])->name('laporan.detail');
        Route::post('/export/{ujianId}', [LaporanController::class, 'export'])->name('laporan.export');
        Route::post('/export/magic-card/{ujianId}', [LaporanController::class, 'exportMagicCard'])->name('laporan.export.magic_card');
    });
});
