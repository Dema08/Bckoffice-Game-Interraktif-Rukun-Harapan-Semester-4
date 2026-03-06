<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiSoal extends Model
{
    protected $table = 'opsi_soals';
    protected $fillable = ['soal_id', 'opsi', 'is_correct'];

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
