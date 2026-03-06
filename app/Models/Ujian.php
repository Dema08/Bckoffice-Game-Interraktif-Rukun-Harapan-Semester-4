<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $fillable = [
        'guru_id', 'kelas_id', 'mapel_id', 'judul', 'tipe_ujian', 'waktu_mulai', 'waktu_selesai',
    ];

    public function guru() {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mapel() {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function soal()
    {
        return $this->belongsToMany(Soal::class, 'ujian_soal', 'ujian_id', 'soal_id');
    }
}
