<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        
        if ($user->trial_ends_at && $user->trial_ends_at->isPast()) {
            return response()->json(['message' => 'Trial period expired'], 403);
        }
    
        return $next($request);
    }
}
