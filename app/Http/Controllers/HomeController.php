<?php
namespace App\Http\Controllers;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }

        $featuredBlogs = Blog::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.home', compact('featuredBlogs'));
    }
}
