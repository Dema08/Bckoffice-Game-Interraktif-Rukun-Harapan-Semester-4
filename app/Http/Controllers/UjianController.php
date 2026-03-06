<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\GuruProfile;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\KelasGuru;
use App\Services\UjianService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\View;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    protected $ujianService;

    public function __construct(UjianService $ujianService)
    {
        $this->ujianService = $ujianService;
    }

    public function index()
    {
        $guruId = Auth::user()->id;
        $kelasIds = \DB::table('kelas_guru')->where('guru_id', $guruId)->pluck('kelas_id');
        $query = Ujian::with(['guru', 'kelas', 'mapel']);
        if ($kelasIds->isNotEmpty()) {
            $query->whereIn('kelas_id', $kelasIds);
        }
        $ujians = $query->paginate(100);
        return view('admin.ujian.index', compact('ujians'));
    }

    public function create()
    {
        $user = Auth::user();
        $guruId = $user->id;

        $kelasIds = \DB::table('kelas_guru')->where('guru_id', $guruId)->pluck('kelas_id');

        $kelasList = $kelasIds->isNotEmpty()
            ? Kelas::whereIn('id', $kelasIds)->get()
            : Kelas::all();

        $guruProfiles = $user->role_id === 1
            ? GuruProfile::where('user_id', $guruId)->get()
            : GuruProfile::all();

        $mapelList = MataPelajaran::all();

        return view('admin.ujian.create', compact('guruProfiles', 'kelasList', 'mapelList'));
    }

    public function store(Request $request)
    {
        $validator = $this->ujianService->validate($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $this->ujianService->store($request);
            return redirect()->route('ujian.index')->with('success', 'Ujian berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan ujian. Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ujian = Ujian::with(['soal.opsiJawaban'])->findOrFail($id);
        $user = Auth::user();
        $guruId = $user->id;

        $kelasIds = \DB::table('kelas_guru')->where('guru_id', $guruId)->pluck('kelas_id');

        $kelasList = $kelasIds->isNotEmpty()
            ? Kelas::whereIn('id', $kelasIds)->get()
            : Kelas::all();

        $guruProfiles = $user->role_id === 1
            ? GuruProfile::where('user_id', $guruId)->get()
            : GuruProfile::all();

        $mapelList = MataPelajaran::all();

        return view('admin.ujian.edit', compact('ujian', 'guruProfiles', 'kelasList', 'mapelList'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->ujianService->validate($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->ujianService->update($request, $id);
        return redirect()->route('ujian.index')->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->ujianService->destroy($id);
        return redirect()->back()->with('success', 'Ujian berhasil dihapus.');
    }

    public function exportMagicCard($id)
    {
        $ujian = $this->ujianService->getMagicCardQuestions($id);
        if ($ujian->soal->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada soal bertipe magic_card untuk diekspor.');
        }
        foreach ($ujian->soal as $soal) {
            if ($soal->tipe === 'pilihan_ganda') {
                $correctOption = $soal->opsiJawaban->firstWhere('is_correct', true);
                $answerText = $correctOption ? $correctOption->opsi : 'Tidak ada jawaban benar';
            } else {
                $answerText = $soal->jawaban_benar;
            }
            $result = Builder::create()
                ->data($answerText)
                ->size(300)
                ->margin(0)
                ->build();
            $base64 = 'data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString());
            $soal->qrcode_base64 = $base64;
        }
        $data = [
            'ujian' => $ujian,
            'soals' => $ujian->soal,
        ];
        $pdf = Pdf::loadView('admin.ujian.export_magic_card', $data)->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'enable_css_float' => true,
            'enable_backgrounds' => true,
            'defaultFont' => 'sans-serif',
            'chroot' => public_path(),
        ]);
        return $pdf->download("magic_card_ujian_{$ujian->id}.pdf");
    }
}
