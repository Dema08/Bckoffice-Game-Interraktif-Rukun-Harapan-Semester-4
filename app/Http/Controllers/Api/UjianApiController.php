<?php

namespace App\Http\Controllers\Api;

use App\Models\Ujian;
use App\Models\RiwayatPoint;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\OpsiSoal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Controllers\Controller;

class UjianApiController extends Controller
{
    public function show(Request $request)
    {
        try {
            $kelas_id = (int) $request->kelas_id;
            $tipeujian = $request->tipe_ujian;

            $ujians = Ujian::where('kelas_id', $kelas_id)
                ->where('tipe_ujian', $tipeujian)
                ->get();

            if ($ujians->isEmpty()) {
                throw new \Exception('Data ujian tidak ditemukan.');
            }

            $siswa_id = auth::user()->id;

            $ujians = $ujians->map(function ($ujian) use ($siswa_id) {
                $ceksudahmengerjakan = \App\Models\Nilai::where('ujian_id', $ujian->id)
                    ->where('siswa_id', $siswa_id)
                    ->exists();
                $ujian->status_pengerjaan = !$ceksudahmengerjakan ? true : false;
                return $ujian;
            });

            return response()->json($ujians);

        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Terjadi kesalahan saat mengambil data ujian.',
                    'message' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function getSoal(Request $request)
    {
        $ujianId = $request->ujian_id;
        $perPage = 1;
        $page = (int) ($request->page ?? 1);

        $ujian = Ujian::with(['soal.opsiJawaban'])->findOrFail($ujianId);

        $soals = $ujian->soal()->paginate($perPage, ['*'], 'page', $page);

        if ($page > $soals->lastPage()) {
            return response()->json([
                'current_page' => $soals->currentPage(),
                'data' => 'Tidak ada',
                'total' => $soals->total(),
            ]);
        }

        $opsiLabels = ['A', 'B', 'C', 'D', 'E'];

        $soals->getCollection()->transform(function ($soal) use ($opsiLabels) {
            $opsiData = [];
            foreach ($opsiLabels as $index => $label) {
                $opsiData[$label] = $soal->opsiJawaban[$index]->opsi ?? '';
            }
            return [
                'id' => $soal->id,
                'pertanyaan' => $soal->pertanyaan,
                'tipe' => $soal->tipe,
                'jawaban_benar' => $soal->jawaban_benar,
                'gambar' => $soal->gambar ? asset('public/storage/' . $soal->gambar) : null,
                'opsi' => $opsiData,
            ];
        });

        return response()->json([
            'current_page' => $soals->currentPage(),
            'data' => $soals->items(),
            'total' => $soals->total(),
        ]);
    }

    public function getPeringkat()
    {
        $user = Auth::user()->load('kelas');
        $riwayatPoints = RiwayatPoint::select(
                'id_siswa',
                'siswa_profiles.full_name',
                'kelas.nama_kelas',
                'kelas.id',
                DB::raw('SUM(riwayat_point.jumlah_point) as total_point')
            )
            ->join('kelas_siswa', 'riwayat_point.id_siswa', '=', 'kelas_siswa.siswa_id')
            ->join('kelas', 'kelas_siswa.kelas_id', '=', 'kelas.id')
            ->join('siswa_profiles', 'riwayat_point.id_siswa', '=', 'siswa_profiles.user_id')
            ->where('kelas.id', $user->kelas->first()->id)
            ->groupBy('id_siswa', 'siswa_profiles.full_name', 'kelas.nama_kelas', 'kelas.id')
            ->orderBy('total_point', 'desc')
            ->limit(10)
            ->get();

        $result = $riwayatPoints->values()->map(function ($item, $index) {
            return [
                'id' => $item->id_siswa,
                'full_name' => $item->full_name,
                'total_point' => $item->total_point,
                'kelas' => $item->nama_kelas ?? null,
                'level' => 'Rank ' . ($index + 1),
            ];
        });

        return response()->json($result);
    }
}
