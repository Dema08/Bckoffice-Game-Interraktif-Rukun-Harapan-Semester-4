<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaProfile extends Model
{
    protected $table = 'siswa_profiles';
    protected $fillable = ['user_id', 'full_name', 'nis','point'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class, 'user_id','siswa_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(
            \App\Models\Kelas::class,
            'kelas_siswa',
            'siswa_id',
            'kelas_id',
        )->withTimestamps();
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanSiswa::class, 'siswa_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id', 'user_id');
    }

    public function riwayatPoint()
    {
        return $this->hasMany(RiwayatPoint::class, 'id_siswa', 'user_id');
    }
}
