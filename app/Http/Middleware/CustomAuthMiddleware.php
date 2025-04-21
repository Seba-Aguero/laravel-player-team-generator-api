<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuthMiddleware
{
    private const EXPECTED_TOKEN = 'SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE=';

    public function handle(Request $request, Closure $next) {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($authHeader, 7); // Remove "Bearer " prefix
        if ($token !== self::EXPECTED_TOKEN) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}