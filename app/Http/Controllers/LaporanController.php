<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ExportLaporanJob;
use App\Jobs\ExportNilaiToExcelJob;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LaporanController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();
        $kelasIds = DB::table('kelas_guru')
            ->where('guru_id', $guruId)
            ->pluck('kelas_id');

        $query = Ujian::with(['guru', 'kelas', 'mapel']);

        if ($kelasIds->isNotEmpty()) {
            $query->whereIn('kelas_id', $kelasIds);
        }

        $ujians = $query->paginate(100);

        return view('admin.laporan.index', compact('ujians'));
    }

    public function show($ujianId)
    {
        $data = DB::select('CALL GetLaporanNilaiUjian(?)', [$ujianId]);

        if (empty($data)) {
            return redirect()->back()->with('error', 'Tidak ada data nilai untuk ujian ini.');
        }

        $ujian = Ujian::findOrFail($ujianId);
        $guruId = Auth::id();
        $kelasIds = DB::table('kelas_guru')
            ->where('guru_id', $guruId)
            ->pluck('kelas_id');

        if ($kelasIds->isNotEmpty()) {
            $kelasList = Kelas::whereIn('id', $kelasIds)->get(['id', 'nama_kelas']);
        } else {
            $kelasList = Kelas::all(['id', 'nama_kelas']);
        }

        return view('admin.laporan.detail', compact('data', 'ujian', 'kelasList'));
    }

    public function export($ujianId)
    {
        ExportLaporanJob::dispatch($ujianId, Auth::id());

        return back()->with('success', 'Proses export sedang dijalankan. Silakan cek bagian Riwayat Export setelah beberapa saat.');
    }

    public function exportExcel(Request $request, $ujianId)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $kelasId = $request->input('kelas_id');

        ExportNilaiToExcelJob::dispatch($ujianId, $kelasId);

        return back()->with('success', 'Export sedang diproses. File akan disimpan di storage/app/exports.');
    }

    public function exportMagicCard(Request $request, $ujianId)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $kelasId = $request->kelas_id;

        $ujian = Ujian::findOrFail($ujianId);
        $kelas = Kelas::findOrFail($kelasId);

        $data = DB::select('CALL GetLaporanNilaiUjianByKelas(?, ?)', [$ujianId, $kelasId]);

        if (empty($data)) {
            return back()->with('error', 'Tidak ada data nilai untuk kelas ini.');
        }

        $pdf = PDF::loadView('admin.laporan.magic_card_pdf', compact('data', 'ujian', 'kelas'))
            ->setPaper('a4', 'portrait');

        $filename = 'magic_card_' . $ujian->id . '_' . $kelas->id . '.pdf';

        return $pdf->download($filename);
    }
}
