<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtApiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException) {
                return response()->json(['token_expired' => $e->getMessage()], 401);
            } elseif ($e instanceof TokenInvalidException) {
                return response()->json(['token_invalid' => $e->getMessage()], 401);
            } else {
                return response()->json(['unauthorized' => $e->getMessage()], 401);
            }
        }

        return $next($request);
    }
}
