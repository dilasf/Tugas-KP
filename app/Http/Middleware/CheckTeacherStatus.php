<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTeacherStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->teacher && Auth::user()->teacher->status === 'active') {
            return $next($request);
        }

        Auth::logout(); // Logout user if not an active teacher
        return redirect()->route('login')->with('error', 'Your teacher account is not active.');
    }
}
