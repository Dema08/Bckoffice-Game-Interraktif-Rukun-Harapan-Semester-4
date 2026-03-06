<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasGuru extends Model
{
    protected $table = 'kelas_guru';
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
