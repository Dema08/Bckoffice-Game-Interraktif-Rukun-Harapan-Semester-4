<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasApiController extends Controller
{
   
    public function getKelas()
    {
        $kelas = Kelas::all();

        if ($kelas->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data kelas.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Data Kelas ditemukan.',
            'data' => $kelas
        ]);
    }

    
    public function getSiswaByKelas(Request $request)
    {
        $id = $request->id_kelas;
        $kelas = Kelas::with(['siswa.siswaProfile'])->find($id);

        if (!$kelas) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan.',
                'data' => []
            ], 404);
        }

        

        $dataSiswa = $kelas->siswa->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => optional($user->siswaProfile)->full_name,
                'nis' => optional($user->siswaProfile)->nis
            ];
        });

        return response()->json([
            'message' => 'Data siswa berdasarkan kelas ditemukan.',
            'data' => $dataSiswa
        ]);
    }
}
