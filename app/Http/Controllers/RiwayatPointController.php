<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPoint;
use App\Models\SiswaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RiwayatPointController extends Controller
{
    public function index(Request $request)
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
            $siswaProfiles = SiswaProfile::whereHas('kelasSiswa', function ($query) use ($kelasIds) {
                    $query->whereIn('kelas_id', $kelasIds);
                })
                ->with(['riwayatPoint', 'kelasSiswa.kelas'])
                ->get();
        } else {
            $siswaProfiles = SiswaProfile::with(['riwayatPoint', 'kelasSiswa.kelas'])->get();
        }

        $siswaProfiles = $siswaProfiles->map(function ($siswa) {
            $siswa->total_point = $siswa->riwayatPoint->sum('jumlah_point');
            $siswa->last_date = optional($siswa->riwayatPoint->sortByDesc('created_at')->first())->created_at;
            return $siswa;
        });

        $ranked = $siswaProfiles->sortByDesc('total_point')->values();

        $ranked->each(function ($item, $index) {
            $item->rank = $index + 1;
            $item->levelLabel = match ($item->rank) {
                1 => '<span class="badge bg-warning">Rank 1 🥇</span>',
                2 => '<span class="badge" style="background-color: silver; color: white;">Rank 2 🥈</span>',
                3 => '<span class="badge" style="background-color: saddlebrown; color: white;">Rank 3 🥉</span>',
                default => '<span class="badge" style="background-color: white; color: black; border: 1px solid #ccc;">- 🏵️</span>',
            };
        });

        return view('admin.riwayat_point.index', [
            'riwayatPoints' => $ranked,
        ]);
    }
}
