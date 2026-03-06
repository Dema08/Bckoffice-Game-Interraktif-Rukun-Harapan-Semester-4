<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\RiwayatPoint;
use App\Models\KelasSiswa;
use App\Models\SiswaProfile;
use Auth;

class DashboardApiController extends \App\Http\Controllers\Controller
{
    public function dashboard()
{
    $user = Auth::user()->load('kelas', 'siswaProfile');
    $kelasId = $user->kelas->first()?->id;

    $siswaDalamKelas = KelasSiswa::where('kelas_id', $kelasId)
        ->with('siswa')
        ->get();


    $latestPoints = RiwayatPoint::selectRaw('MAX(id) as max_id')
        ->groupBy('id_siswa');

    $totalPoints = RiwayatPoint::selectRaw('id_siswa, SUM(jumlah_point) as total_point')
        ->groupBy('id_siswa')
        ->get()
        ->keyBy('id_siswa');

    $riwayatPoints = RiwayatPoint::whereIn('id', function ($query) {
        $query->selectRaw('MAX(id)')
            ->from('riwayat_point')
            ->groupBy('id_siswa');
    })->get()->keyBy('id_siswa');

    $siswaProfiles = collect();
    foreach ($siswaDalamKelas as $ks) {
        $data = $riwayatPoints->get($ks->siswa_id);
        $siswaProfiles->push((object) [
            'id_siswa' => $ks->siswa_id,
            'full_name' => $ks->siswa->full_name ?? 'Unknown',
            'total_point' => $totalPoints[$ks->siswa_id]->total_point ?? 0,
            'level' => $data?->level ?? '-',
        ]);
    }

    $ranked = $siswaProfiles->sortByDesc('total_point')->values();
    foreach ($ranked as $index => $item) {
        $item->rank = $index + 1;
    }

    $userData = $ranked->firstWhere('id_siswa', $user->id);
    $userRank = $userData?->rank ?? $ranked->count();
    $pointUser = $totalPoints[$user->id]->total_point ?? 0;
    $totalUsers = SiswaProfile::count();

    if ($pointUser == 0) {
        $rankingString = "{$totalUsers} / {$totalUsers}";
    } else {
        $rankingString = "{$userRank} / {$totalUsers}";
    }

    return response()->json([
        'user_id' => $user->id,
        'full_name' => $user->siswaProfile->full_name ?? '',
        'point' => intval($pointUser),
        'ranking' => $rankingString,
    ]);
}
}
