<?php

namespace App\Services;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function attemptLogin(array $credentials)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ['success' => false, 'message' => 'Username atau password salah!'];
            }

            return ['success' => true, 'token' => $token];
        } catch (JWTException $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan saat membuat token!'];
        }
    }

    public function logout()
    {
        try {
            if (JWTAuth::getToken()) {
                JWTAuth::invalidate(JWTAuth::getToken());
            }

            return ['success' => true];
        } catch (JWTException $e) {
            return ['success' => false, 'message' => 'Gagal logout!'];
        }
    }

    public function authenticateFromToken(string $token)
    {
        return JWTAuth::setToken($token)->authenticate();
    }

    public function handle($request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan'
                ], 401);
            }
            $user = \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            Auth::setUser($user);

            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['success' => false, 'message' => 'Token kadaluarsa'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Autentikasi gagal: ' . $e->getMessage()], 500);
        }
    }
}
