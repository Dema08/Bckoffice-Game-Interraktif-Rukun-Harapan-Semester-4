<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPoint extends Model
{
    protected $table = 'riwayat_point';

    protected $fillable = [
        'id_siswa',
        'ujian_id',
        'soal_id',
        'jumlah_point',
        'point_dasar',
        'bonus_point'
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'id_siswa', 'user_id');
    }

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class, 'id_siswa', 'siswa_id');
    }

    public function getRank()
    {
        $ranked = self::all()->sortByDesc(fn($siswa) => $siswa->point)->pluck('id')->toArray();

        $position = array_search($this->id, $ranked);

        return $position !== false ? $position + 1 : null;
    }

    public function getLevelAttribute()
    {
        $rank = $this->getRank();

        return match ($rank) {
            1 => 'Rank 1',
            2 => 'Rank 2',
            3 => 'Rank 3',
            default => '-',
        };
    }

}
