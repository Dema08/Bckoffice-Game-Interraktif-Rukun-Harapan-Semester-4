<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class JWTMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!$request->session()->has('jwt_token')) {
                return redirect()->route('login');
            }

            JWTAuth::setToken($request->session()->get('jwt_token'))->authenticate();
        } catch (Exception $e) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

