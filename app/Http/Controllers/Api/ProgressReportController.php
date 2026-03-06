<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use App\Models\Nilai;
use App\Models\Ujian;
use App\Models\RiwayatPoint;
use App\Models\SiswaProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProgressReportController extends Controller
{
    public function getProgressReport()
    {
        $siswaId = Auth::id();

        $totalQuizzesPlayed = JawabanSiswa::where('siswa_id', $siswaId)
            ->distinct()
            ->count('ujian_id');

        $quizzesWon = Nilai::where('siswa_id', $siswaId)
            ->where('nilai', '>=', 70)
            ->count();

        $totalPoints = RiwayatPoint::where('id_siswa', $siswaId)
            ->sum('jumlah_point');

        $topUjian = Nilai::with(['ujian.mapel'])
            ->where('siswa_id', $siswaId)
            ->whereNotNull('nilai')
            ->orderByDesc('nilai')
            ->first();

        $topCategory = '-';
        if ($topUjian && $topUjian->ujian) {
            $ujian = $topUjian->ujian;
            $mapel = $ujian->mapel ? $ujian->mapel->nama_mapel : 'Mata Pelajaran Tidak Diketahui';
            $judul = $ujian->judul;
            $topCategory = "$judul ($mapel)";
        }

        $activeDaysThisMonth = JawabanSiswa::where('siswa_id', $siswaId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_quizzes_played' => $totalQuizzesPlayed,
                'quizzes_won' => $quizzesWon,
                'total_points' => $totalPoints,
                'top_category' => $topCategory,
                'active_days_this_month' => $activeDaysThisMonth,
            ]
        ]);
    }
}
