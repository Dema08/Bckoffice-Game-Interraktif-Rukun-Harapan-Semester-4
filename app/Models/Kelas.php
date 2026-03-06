<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas'; 


    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran',
        'semester',
    ];

    public function guruProfiles()
    {
        return $this->belongsToMany(GuruProfile::class, 'kelas_guru', 'kelas_id', 'guru_id');
    }

    public function waliGuru()
    {
        return $this->guruProfiles()->where('is_wali', 1)->limit(1);
    }

    public function siswa()
    {
        return $this->belongsToMany(User::class, 'kelas_siswa', 'kelas_id', 'siswa_id')->withTimestamps();
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'kelas_id');
    }

}
