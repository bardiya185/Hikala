<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptionalAuth
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            try {
                $user = auth('sanctum')->user();
                
                if ($user) {
                    auth()->setUser($user);
                }
            } catch (\Exception $e) {
            }
        }
        
        return $next($request);
    }
}