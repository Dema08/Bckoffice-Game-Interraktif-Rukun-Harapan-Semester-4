<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class LogoutApiController extends Controller
{
    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'error' => 'Token tidak ditemukan',
                ], 401);
            }
            \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->invalidate();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout.',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal logout',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
