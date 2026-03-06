<?php

namespace App\Services;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasService
{
    public function validate(Request $request)
    {
        return Validator::make($request->all(), [
            'nama_kelas' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'tahun_ajaran' => [
                'required',
                'regex:/^\d{4}\/\d{4}$/'
            ],
            'semester' => [
                'required',
                'in:Ganjil,Genap'
            ],
        ], [
            'nama_kelas.regex' => 'Nama kelas hanya boleh berisi huruf, angka, spasi, dan tanda strip.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2024/2025.',
        ]);
    }


    public function store(Request $request)
    {
        return Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester === 'Ganjil' ? 1 : 2,
        ]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester === 'Ganjil' ? 1 : 2,
        ]);

        return $kelas;
    }

    public function delete(Kelas $kelas)
    {
        return $kelas->delete();
    }
}
