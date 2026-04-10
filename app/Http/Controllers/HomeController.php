<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Nếu đã đăng nhập → chuyển tới dashboard user
            return redirect()->route('user.dashboard');
        }

        // Nếu chưa đăng nhập → hiển thị Home page bình thường
        return view('pages.home');
    }
}
