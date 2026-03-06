<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    use HasFactory;

    protected $table = 'guru_profiles';

    protected $fillable = [
        'user_id',
        'full_name',
        'nip',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_guru', 'guru_id', 'kelas_id')
                    ->withPivot('is_wali')
                    ->withTimestamps();
    }
    
    public function kelasGuru()
    {
        return $this->belongsTo(KelasGuru::class, 'user_id', 'guru_id');
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'guru_id'); 
    }


}
