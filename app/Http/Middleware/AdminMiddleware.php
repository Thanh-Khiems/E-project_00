<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in with an admin account.');
        }

        if (Auth::user()?->role !== 'admin') {
            return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access the admin page.');
        }

        return $next($request);
    }
}
