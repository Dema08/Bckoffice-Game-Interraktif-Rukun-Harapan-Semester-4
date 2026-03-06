<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JawabanSiswa;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\Nilai;
use App\Models\RiwayatPoint;
use App\Services\PointService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JawabanSiswaController extends \App\Http\Controllers\Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ujian_id' => 'required|exists:ujian,id',
            'soal_id' => 'required|exists:soal,id',
            'jawaban' => 'required|string',
            'waktu_submit' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $siswaId = Auth::id();
        $ujianId = $request->ujian_id;
        $soalId = $request->soal_id;

        $nilai = Nilai::where('ujian_id', $ujianId)->where('siswa_id', $siswaId)->first();
        if ($nilai && $nilai->nilai !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Ujian ini sudah dinilai. Tidak bisa mengirim jawaban lagi.',
            ], 403);
        }

        $ujian = Ujian::with('soal')->findOrFail($ujianId);
        $validSoalIds = $ujian->soal->pluck('id')->toArray();

        if (!in_array($soalId, $validSoalIds)) {
            return response()->json([
                'success' => false,
                'message' => "Soal ID {$soalId} tidak termasuk dalam ujian ini.",
            ], 400);
        }

        DB::beginTransaction();

        try {
            $soal = Soal::findOrFail($soalId);
            $isBenar = strtolower(trim($request->jawaban)) === strtolower(trim($soal->jawaban_benar));

            $existing = JawabanSiswa::where([
                'ujian_id' => $ujianId,
                'siswa_id' => $siswaId,
                'soal_id' => $soalId,
            ])->first();

            if ($existing) {
                $existing->update([
                    'jawaban' => $request->jawaban,
                    'waktu_submit' => $request->waktu_submit,
                    'is_jawaban_benar' => $isBenar ? 1 : 0
                ]);
            } else {
                $existing = JawabanSiswa::create([
                    'ujian_id' => $ujianId,
                    'siswa_id' => $siswaId,
                    'soal_id' => $soalId,
                    'jawaban' => $request->jawaban,
                    'waktu_submit' => $request->waktu_submit,
                    'is_jawaban_benar' => $isBenar ? 1 : 0
                ]);
            }

            $bonus = 0;
            if ($isBenar) {
                $bonus = match (true) {
                    $request->waktu_submit <= 60 => 4,
                    $request->waktu_submit <= 120 => 3,
                    $request->waktu_submit <= 180 => 2,
                    default => 0,
                };
            }
            $SelisihDetik = $request->waktu_submit;
            $isJawbanBenar = $isBenar;

            $pointService = new PointService;
            $pointService->addPointsForAnswers(
                $ujianId,
                $siswaId,
                $soalId,
                $SelisihDetik,
                $bonus,
                $isJawbanBenar
            );

            $riwayatPoint = RiwayatPoint::where([
                'id_siswa' => $siswaId,
                'ujian_id' => $ujianId,
                'soal_id' => $soalId,
            ])->first();

            $jumlahPoint = $riwayatPoint ? $riwayatPoint->jumlah_point : 0;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan.',
                'is_jawaban_benar' => $isBenar ? 1 : 0,
                'bonus_point' => $bonus,
                'jumlah_point' => $jumlahPoint,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
