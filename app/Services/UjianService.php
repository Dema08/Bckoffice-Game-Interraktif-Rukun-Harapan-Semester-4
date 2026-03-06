<?php

namespace App\Services;

use App\Models\Ujian;
use App\Models\Soal;
use App\Models\OpsiSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UjianService
{
    public function validate(Request $request)
    {
        return Validator::make($request->all(), [
            'judul' => 'required|string',
            'guru_id' => 'required|integer',
            'kelas_id' => 'required|integer',
            'mapel_id' => 'required|integer',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
            'soal' => 'array',
            'soal.*.pertanyaan' => 'required',
            'soal.*.tipe' => 'required',
            'soal.*.jawaban_benar' => 'nullable|required_if:soal.*.tipe,pilihan_ganda',
        ]);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $ujian = Ujian::create($request->only([
                'judul', 'guru_id', 'kelas_id', 'mapel_id', 'waktu_mulai', 'waktu_selesai', 'tipe_ujian'
            ]));

            $updatedSoals = [];

            foreach ($request->soal as $key => $soalData) {
                if (str_starts_with($key, 'new_')) {
                    $gambarPath = null;

                    if (isset($soalData['gambar']) && $soalData['gambar'] instanceof \Illuminate\Http\UploadedFile) {
                        $gambarPath = $soalData['gambar']->store('soal_gambar', 'public');
                    }

                    $newSoal = Soal::create([
                        'pertanyaan' => $soalData['pertanyaan'],
                        'gambar' => $gambarPath,
                        'tipe' => $soalData['tipe'],
                        'jawaban_benar' => $soalData['jawaban_benar'] ?? null,
                    ]);

                    $ujian->soal()->attach($newSoal->id);
                    $updatedSoals[] = $newSoal->id;

                    if ($newSoal->tipe === 'pilihan_ganda' && isset($soalData['opsi'])) {
                        $newSoal->opsiJawaban()->delete();

                        foreach ($soalData['opsi'] as $label => $isiOpsi) {
                            $newSoal->opsiJawaban()->create([
                                'opsi' => $isiOpsi,
                                'is_correct' => $label === $soalData['jawaban_benar'],
                            ]);
                        }
                    }
                }
            }
        });
    }




    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $ujian = Ujian::findOrFail($id);
            $ujian->update($request->only([
                'judul', 'guru_id', 'kelas_id', 'mapel_id', 'waktu_mulai', 'waktu_selesai', 'tipe_ujian'
            ]));

            $existingSoals = $ujian->soal->pluck('id')->toArray();
            $updatedSoals = [];

            foreach ($request->soal as $key => $soalData) {
                if (str_starts_with($key, 'new_')) {
                    $gambarPath = null;
                    if (isset($soalData['gambar']) && $soalData['gambar'] instanceof \Illuminate\Http\UploadedFile) {
                        $gambarPath = $soalData['gambar']->store('soal_gambar', 'public');
                    }

                    $newSoal = Soal::create([
                        'pertanyaan' => $soalData['pertanyaan'],
                        'gambar' => $gambarPath,
                        'tipe' => $soalData['tipe'],
                        'jawaban_benar' => $soalData['jawaban_benar'] ?? null,
                    ]);

                    $ujian->soal()->attach($newSoal->id);
                    $updatedSoals[] = $newSoal->id;

                    if ($newSoal->tipe === 'pilihan_ganda' && isset($soalData['opsi'])) {
                        $newSoal->opsiJawaban()->delete();
                        foreach ($soalData['opsi'] as $label => $isiOpsi) {
                            $newSoal->opsiJawaban()->create([
                                'opsi' => $isiOpsi,
                                'is_correct' => $label === $soalData['jawaban_benar']
                            ]);
                        }
                    }

                } else {
                    $existingSoal = Soal::find($key);

                    if ($existingSoal) {
                        $gambarPath = $existingSoal->gambar;

                        if (isset($soalData['gambar']) && $soalData['gambar'] instanceof \Illuminate\Http\UploadedFile) {
                            if ($gambarPath) {
                                Storage::disk('public')->delete($gambarPath);
                            }
                            $gambarPath = $soalData['gambar']->store('soal_gambar', 'public');
                        }

                        $existingSoal->update([
                            'pertanyaan' => $soalData['pertanyaan'],
                            'gambar' => $gambarPath,
                            'tipe' => $soalData['tipe'],
                            'jawaban_benar' => $soalData['jawaban_benar'] ?? null,
                        ]);

                        if ($existingSoal->tipe === 'pilihan_ganda' && isset($soalData['opsi'])) {
                            $existingSoal->opsiJawaban()->delete();

                            foreach ($soalData['opsi'] as $label => $isiOpsi) {
                                $existingSoal->opsiJawaban()->create([
                                    'opsi' => $isiOpsi,
                                    'is_correct' => $label === $soalData['jawaban_benar']
                                ]);
                            }
                        }

                        $updatedSoals[] = $existingSoal->id;
                    }
                }
            }

            $soalsToDelete = array_diff($existingSoals, $updatedSoals);

            $ujian->soal()->detach($soalsToDelete);
            Soal::destroy($soalsToDelete);
        });
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $ujian = Ujian::findOrFail($id);
            $soalsToDelete = $ujian->soal->pluck('id')->toArray();

            $ujian->soal()->detach($soalsToDelete);

            Soal::destroy($soalsToDelete);

            $ujian->delete();
        });
    }

    public function getMagicCardQuestions($ujianId)
    {
        $ujian = Ujian::with('soal')->findOrFail($ujianId);

        if ($ujian->tipe_ujian !== 'magic_card') {
            throw new \Exception("Hanya ujian bertipe 'magic_card' yang bisa diekspor.");
        }

        return $ujian;
    }
}
