<?php

namespace App\Services;

use App\Models\RiwayatPoint;

class PointService
{
    public function addPointsForAnswers($ujianId, $siswaId, $soalId, $selisihDetik, $bonus, $isJawabanBenar = null)
{
    RiwayatPoint::where('id_siswa', $siswaId)
        ->where('ujian_id', $ujianId)
        ->where('soal_id', $soalId)
        ->delete();

    $pointDasar = 0;
    $jumlahPoint = $bonus;

    if ($isJawabanBenar === true) {
        $pointDasar = 5;
        $jumlahPoint = 5 + $bonus;
    }

    RiwayatPoint::create([
        'id_siswa' => $siswaId,
        'ujian_id' => $ujianId,
        'soal_id' => $soalId,
        'point_dasar' => $pointDasar,
        'bonus_point' => $bonus,
        'jumlah_point' => $jumlahPoint
    ]);
}

}
