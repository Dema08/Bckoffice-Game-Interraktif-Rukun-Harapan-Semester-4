<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\SiswaProfile;
use Illuminate\Http\Request;
use App\Models\RiwayatPoint;
use App\Models\JawabanSiswa;
use App\Http\Controllers\Controller;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::with(['siswaProfile'])->find($userId);

        $riwayatPoints = RiwayatPoint::with('siswa')
            ->select('riwayat_point.*', 'siswa_profiles.*', 'kelas.nama_kelas', 'kelas_siswa.kelas_id')
            ->joinSub(
                RiwayatPoint::selectRaw('MAX(id) as max_id')
                    ->groupBy('id_siswa'),
                'latest_points',
                'riwayat_point.id',
                '=',
                'latest_points.max_id'
            )
            ->join('kelas_siswa', 'riwayat_point.id_siswa', '=', 'kelas_siswa.siswa_id')
            ->join('kelas', 'kelas_siswa.kelas_id', '=', 'kelas.id')
            ->join('siswa_profiles', 'riwayat_point.id_siswa', '=', 'siswa_profiles.user_id')
            ->where('kelas_id', $user->kelas->first()->id)
            ->orderBy('jumlah_point', 'desc')
            ->get();

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $siswaId = $user->id;
        $activeDaysThisMonth = JawabanSiswa::where('siswa_id', $siswaId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->count();

        $withRank = $riwayatPoints->filter(function ($item) {
            return isset($item->siswa->level) && $item->siswa->level !== '-';
        })->sortBy(function ($item) {
            return intval(str_replace('Rank ', '', $item->siswa->level));
        })->values();

        $sorted = $withRank->values();

        $userRank = null;
        foreach ($sorted as $index => $item) {
            if ($item->id_siswa == $user->id) {
                $userRank = $index + 1;
                break;
            }
        }

        $totalUsers = $sorted->count();
        $userRankString = $userRank && $totalUsers ? "{$userRank} / {$totalUsers}" : null;

        $role = $user->role ? $user->role->name : 'unknown';
        $kelas = $user->kelas->first();

        $profileData = [
            'id' => $user->siswaProfile->id,
            'full_name' => $user->siswaProfile->full_name,
            'username' => $user->username,
            'nis' => $user->siswaProfile->nis,
            'point' => (int) ($user->siswaProfile->point ?? 0),
            'level' => $user->siswaProfile->level,
            'level_label' => $user->siswaProfile->levelLabel,
            'id_kelas' => $kelas->id,
            'ranking' => $userRankString,
            'harimasuk' => $activeDaysThisMonth,
            'nama_kelas' => $kelas->nama_kelas
        ];

        return response()->json([
            'profile' => $profileData,
        ]);
    }
}
