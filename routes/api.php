<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MataPelajaranController;
use App\Http\Controllers\Api\KelasApiController;
use App\Http\Controllers\Api\SoalApiController;
use App\Http\Controllers\Api\MobileLoginController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\Auth\LogoutApiController;
use App\Http\Controllers\Api\UjianApiController;
use App\Http\Controllers\Api\JawabanSiswaController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ProgressReportController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/mobile', [MobileLoginController::class, 'login'])->name('api.login.mobile');
Route::apiResource('matapelajaran', MataPelajaranController::class);
Route::post('/getkelasbyidsiswa', [KelasApiController::class, 'getSiswaByKelas']);
Route::get('/kelas', [KelasApiController::class, 'getKelas']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/dashboard', [DashboardApiController::class, 'dashboard'])->name('api.dashboard');
    Route::get('/peringkat', [UjianApiController::class, 'getPeringkat'])->name('api.peringkat.index');
    Route::get('soal/{ujianid}', [SoalApiController::class, 'index'])->name('api.soal.index');
    Route::post('/jawaban-siswa', [JawabanSiswaController::class, 'store']);
    Route::get('/ujian/status/{ujian}/{siswa}', [UjianApiController::class, 'cekStatusSiswa']);
    Route::post('/ujian', [UjianApiController::class, 'show'])->name('api.ujian.show');
    Route::get('/ujian/soal', [UjianApiController::class, 'getSoal'])->name('api.ujian.soal');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile', [ProfileApiController::class, 'show'])->name('api.profile.show');
    Route::get('/progress-report', [ProgressReportController::class, 'getProgressReport']);
});
