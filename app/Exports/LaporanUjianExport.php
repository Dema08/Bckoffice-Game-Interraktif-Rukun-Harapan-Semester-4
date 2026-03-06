<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class LaporanUjianExport implements FromView
{
    protected $ujianId;

    public function __construct($ujianId)
    {
        $this->ujianId = $ujianId;
    }

    public function view(): View
    {
        return view('admin.laporan.excel_template', [
            'data' => DB::select('CALL GetLaporanNilaiUjian(?)', [$this->ujianId]),
        ]);
    }
}
