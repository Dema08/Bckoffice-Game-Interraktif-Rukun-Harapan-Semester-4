<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiswaProfile;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MobileLoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $siswa = SiswaProfile::with('user')->where('user_id', $request->siswa_id)->first();

        if (!$siswa) {
            return response()->json(['error' => 'Profil siswa tidak ditemukan'], 404);
        }

        $existsInKelas = \DB::table('kelas_siswa')
            ->where([
                'kelas_id' => $request->kelas_id,
                'siswa_id' => $siswa->user_id
            ])->exists();

        if (!$existsInKelas) {
            return response()->json(['error' => 'Siswa tidak terdaftar di kelas ini.'], 403);
        }

        $user = $siswa->user;
        JWTAuth::factory()->setTTL(60 * 24 * 30); 
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $siswa->full_name,
                'nis' => $siswa->nis,
                'kelas_id' => $request->kelas_id,
            ],
        ]);
    }
}
