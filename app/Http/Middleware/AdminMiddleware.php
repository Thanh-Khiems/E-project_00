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
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập bằng tài khoản admin.');
        }

        if (Auth::user()?->role !== 'admin') {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }

        return $next($request);
    }
}
