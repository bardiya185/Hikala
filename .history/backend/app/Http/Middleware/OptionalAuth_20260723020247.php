<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptionalAuth
{
    public function handle(Request $request, Closure $next)
    {
        // ⚠️ تست موقت - این خط رو اضافه کن
        \Log::info('OptionalAuth middleware called', [
            'has_token' => (bool) $request->bearerToken(),
            'user_before' => auth()->user()?->id,
        ]);
        
        if ($request->bearerToken()) {
            try {
                $user = auth('sanctum')->user();
                
                if ($user) {
                    auth()->setUser($user);
                }
            } catch (\Exception $e) {
                //
            }
        }
        
        // ⚠️ تست موقت
        \Log::info('OptionalAuth after auth', [
            'user_after' => auth()->user()?->id,
        ]);
        
        return $next($request);
    }
}