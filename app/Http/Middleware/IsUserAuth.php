<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('api')->user()){
            return $next($request);
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
