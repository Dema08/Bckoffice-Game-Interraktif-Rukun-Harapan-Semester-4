<?php

namespace App\Http\Controllers\Api;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\OpsiSoal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SoalApiController extends Controller
{
    public function index(Request $request, $ujianid): JsonResponse
    {
        $perPage = 1;
        $page = $request->query('page', 1);

        $ujian = Ujian::with('soal.opsiJawaban')->findOrFail($ujianid);

        $soalQuery = $ujian->soal()->with('opsiJawaban')->orderBy('soal.id');

        $soals = $soalQuery->paginate($perPage, ['*'], 'page', $page);

        $formattedData = $soals->map(function ($soal) {
            $opsi = [];

            if ($soal->tipe === 'pilihan_ganda') {
                $labels = ['A', 'B', 'C', 'D', 'E'];
                foreach ($soal->opsiJawaban as $index => $opsiRow) {
                    if (isset($labels[$index])) {
                        $opsi[$labels[$index]] = $opsiRow->opsi;
                    }
                }
            }

            return [
                'id' => $soal->id,
                'pertanyaan' => $soal->pertanyaan,
                'tipe' => $soal->tipe,
                'jawaban_benar' => $soal->jawaban_benar,
                'gambar' => $soal->gambar ? asset('public/storage/' . $soal->gambar) : null,
                'opsi' => $opsi
            ];
        });

        return response()->json([
            'data' => $formattedData,
            'current_page' => $soals->currentPage(),
            'total_pages' => $soals->lastPage(),
            'total' => $soals->total(),
            'per_page' => $soals->perPage(),
            'next_page_url' => $soals->nextPageUrl()
                ? route('api.soal.index', ['ujianid' => $ujianid, 'page' => $soals->currentPage() + 1])
                : null,
            'prev_page_url' => $soals->previousPageUrl()
                ? route('api.soal.index', ['ujianid' => $ujianid, 'page' => $soals->currentPage() - 1])
                : null,
        ]);
    }
}
