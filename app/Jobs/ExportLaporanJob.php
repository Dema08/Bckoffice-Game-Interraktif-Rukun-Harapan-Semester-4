<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanUjianExport;
use App\Models\ExportLog;

class ExportLaporanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ujianId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($ujianId, $userId)
    {
        $this->ujianId = $ujianId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
{
    try {
        $fileName = "laporan_ujian_{$this->ujianId}_" . now()->format('Ymd_His') . ".xlsx";
        $filePath = 'exports/' . $fileName;

        Log::info("Starting export for ujianId: {$this->ujianId}");

        Excel::store(new LaporanUjianExport($this->ujianId), $filePath, 'public');

        ExportLog::create([
            'user_id'   => $this->userId,
            'file_path' => $filePath,
        ]);

        Log::info("Export successful: $filePath");
    } catch (\Exception $e) {
        Log::error("ExportLaporanJob failed: " . $e->getMessage());
        throw $e; 
    }
}
}
