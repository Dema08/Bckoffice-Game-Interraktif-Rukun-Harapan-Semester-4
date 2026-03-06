<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';
    protected $fillable = ['ujian_id', 'siswa_id', 'guru_id', 'nilai', 'status', 'feedback'];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }
}
