<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsProfessional
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if ($user && $user->role === 'professional') {
            return $next($request);
        }
        return response()->json(['message' => 'Access restricted to professionals'], 403);
    }
}
