<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = [
        'pertanyaan', 'gambar', 'tipe', 'jawaban_benar'
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function opsiJawaban()
    {
        return $this->hasMany(OpsiSoal::class, 'soal_id');
    }
    
    public function getQrText()
    {
        if ($this->tipe === 'pilihan_ganda') {
            $correctOption = $this->opsiJawaban->firstWhere('is_correct', true);
            return $correctOption ? $correctOption->opsi : '-';
        }

        return $this->jawaban_benar ?? '-';
    }

}
