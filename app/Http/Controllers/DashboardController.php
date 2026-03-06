<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\GuruProfile;
use App\Models\SiswaProfile;
use App\Models\RiwayatPoint;
use App\Services\PointService;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    try {
        if (!session()->has('jwt_token')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $user = JWTAuth::setToken(session('jwt_token'))->authenticate();
    } catch (JWTException $e) {
        return redirect()->route('login')->with('error', 'Token tidak valid!');
    }

    $guruId = $user->id;
    $kelasIds = DB::table('kelas_guru')->where('guru_id', $guruId)->pluck('kelas_id');

    if ($kelasIds->isNotEmpty()) {
        $guruProfiles = GuruProfile::whereHas('user.kelasGuru', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })
            ->with(['user', 'user.kelasGuru'])
            ->latest()
            ->take(5)
            ->get();
    } else {
        $guruProfiles = GuruProfile::with(['user', 'user.kelasGuru'])
            ->latest()
            ->take(5)
            ->get();
    }

    $kelasAktif = Kelas::count();

    if ($kelasIds->isNotEmpty()) {
        $siswaProfiles = SiswaProfile::whereHas('kelasSiswa', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })
            ->with(['riwayatPoint', 'kelasSiswa.kelas'])
            ->get();

        $totalGuru = GuruProfile::whereHas('user.kelasGuru', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })->count();

        $totalSiswa = $siswaProfiles->count();
    } else {
        $siswaProfiles = SiswaProfile::with(['riwayatPoint', 'kelasSiswa.kelas'])->get();
        $totalGuru = GuruProfile::count();
        $totalSiswa = $siswaProfiles->count();
    }

    $siswaProfiles = $siswaProfiles->map(function ($siswa) {
        $siswa->total_point = $siswa->riwayatPoint->sum('jumlah_point');
        return $siswa;
    });

    $rankedStudents = $siswaProfiles->sortByDesc('total_point')->values()->take(5);

    $rankedStudents->each(function ($siswa, $index) {
        $siswa->rank = $index + 1;
        $siswa->levelLabel = match ($siswa->rank) {
            1 => '<span class="badge bg-warning">Rank 1 🥇</span>',
            2 => '<span class="badge" style="background-color: silver; color: white;">Rank 2 🥈</span>',
            3 => '<span class="badge" style="background-color: saddlebrown; color: white;">Rank 3 🥉</span>',
            default => '<span class="badge" style="background-color: white; color: black; border: 1px solid #ccc;">- 🏵️</span>',
        };
    });

    $topStudent = $rankedStudents->first();

    return view('admin.dashboard.index', compact(
        'user',
        'totalGuru',
        'totalSiswa',
        'kelasAktif',
        'guruProfiles',
        'rankedStudents',
        'topStudent',
        'siswaProfiles'
    ));
}
}
