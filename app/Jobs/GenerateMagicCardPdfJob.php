<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ujian;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Log;

class GenerateMagicCardPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ujianId;
    protected $userId;

    public function __construct($ujianId, $userId)
    {
        $this->ujianId = $ujianId;
        $this->userId = $userId;
    }

    public function handle()
{
    Log::info("Processing Magic Card Export for Ujian ID: {$this->ujianId}");

    try {
        $ujian = Ujian::with('soal')->findOrFail($this->ujianId);

        $data = [
            'ujian' => $ujian,
            'soals' => $ujian->soal,
        ];

        $pdf = Pdf::loadView('admin.ujian.export_magic_card', $data)->setOptions([
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);

        $filePath = "exports/magic_card_{$ujian->id}.pdf";
        \Storage::put($filePath, $pdf->output());

        Log::info("PDF exported successfully: " . storage_path('app/' . $filePath));

    } catch (\Exception $e) {
        Log::error("Failed to export Magic Card PDF: " . $e->getMessage());
    }
}
}
