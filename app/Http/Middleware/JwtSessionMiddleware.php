<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;

class JwtSessionMiddleware
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('jwt_token')) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
        }

        try {
            $user = $this->authService->authenticateFromToken($request->session()->get('jwt_token'));
            Auth::setUser($user);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Token tidak valid atau sudah kedaluwarsa!']);
        }

        return $next($request);
    }
}
