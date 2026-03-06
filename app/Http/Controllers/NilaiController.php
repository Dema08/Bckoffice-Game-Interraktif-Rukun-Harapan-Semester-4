<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\Nilai;
use App\Models\GuruProfile;
use App\Models\KelasGuru;
use App\Models\JawabanSiswa;
use App\Models\SiswaProfile;
use App\Services\PointService;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatPoint;

class NilaiController extends Controller
{
    public function index()
    {
        $guruId = Auth::user()->id;
        $kelasIds = \DB::table('kelas_guru')->where('guru_id', $guruId)->pluck('kelas_id');

        $query = Ujian::with(['guru', 'kelas', 'mapel']);

        if ($kelasIds->isNotEmpty()) {
            $query->whereIn('kelas_id', $kelasIds);
        }

        $ujians = $query->paginate(100);

        return view('admin.nilai.index', compact('ujians'));
    }

    public function showSiswa($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $siswaIds = JawabanSiswa::where('ujian_id', $ujianId)->distinct()->pluck('siswa_id');
        $siswas = SiswaProfile::whereIn('user_id', $siswaIds)
            ->with(['nilai' => function ($query) use ($ujianId) {
                $query->where('ujian_id', $ujianId);
            }])
            ->get();

        return view('admin.nilai.siswa', compact('siswas', 'ujian'));
    }

    public function detailJawaban($ujianId, $siswaId)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $siswas = SiswaProfile::where('user_id', $siswaId)->first();
        $siswa = SiswaProfile::where('user_id', $siswaId)->first();

        $jawaban = JawabanSiswa::where('ujian_id', $ujianId)
            ->where('siswa_id', $siswaId)
            ->with('soal')
            ->get();

        return view('admin.nilai.detail', compact('jawaban', 'ujian', 'siswa', 'siswas'));
    }

    public function simpanManual(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujian,id',
            'siswa_id' => 'required|exists:users,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'koreksi' => 'array',
        ]);

        $siswaProfile = SiswaProfile::where('user_id', $request->siswa_id)->first();

        if (!$siswaProfile) {
            return back()->withErrors(['siswa_id' => 'Profil siswa tidak ditemukan']);
        }

        $siswa_id = $siswaProfile->user_id;
        $pointService = new PointService();

        if ($request->has('koreksi')) {
            foreach ($request->koreksi as $soal_id => $status) {
                $jawaban = JawabanSiswa::where('ujian_id', $request->ujian_id)
                    ->where('siswa_id', $siswa_id)
                    ->where('soal_id', $soal_id)
                    ->first();

                if (!$jawaban) continue;

                $isCorrect = $status == 'benar';
                $bonus = 0;

                if ($isCorrect) {
                    $waktuSubmit = $jawaban->waktu_submit;
                    $bonus = match (true) {
                        $waktuSubmit <= 60 => 4,
                        $waktuSubmit <= 120 => 3,
                        $waktuSubmit <= 180 => 2,
                        default => 0,
                    };
                }

                $pointService->addPointsForAnswers(
                    $request->ujian_id,
                    $siswa_id,
                    $soal_id,
                    $jawaban->waktu_submit,
                    $bonus,
                    $isCorrect
                );
            }
        }

        Nilai::updateOrCreate(
            [
                'ujian_id' => $request->ujian_id,
                'siswa_id' => $siswa_id,
            ],
            [
                'guru_id' => Auth::user()->id,
                'nilai' => $request->nilai,
                'feedback' => $request->feedback ?? null,
                'status' => 'graded'
            ]
        );

        $totalPoint = RiwayatPoint::where('id_siswa', $siswa_id)->sum('jumlah_point');

        $siswaProfile->update([
            'point' => $totalPoint
        ]);

        return redirect()
            ->route('nilai.siswa', $request->ujian_id)
            ->with('success', 'Nilai berhasil disimpan dan total point telah diperbarui.');
    }

    public function daftarNilai()
    {
        $nilais = Nilai::with(['siswa', 'ujian'])->paginate(10);
        return view('admin.nilai.daftar', compact('nilais'));
    }
}
