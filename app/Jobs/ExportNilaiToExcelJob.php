<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiExport;

class ExportNilaiToExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ujianId;
    protected $kelasId;

    public function __construct($ujianId, $kelasId)
    {
        $this->ujianId = $ujianId;
        $this->kelasId = $kelasId;
    }

    public function handle()
    {
        $filename = "nilai_ujian_{$this->ujianId}_kelas_{$this->kelasId}_" . now()->format('YmdHis') . ".xlsx";
        Excel::store(new NilaiExport($this->ujianId, $this->kelasId), "exports/{$filename}");
    }
}
