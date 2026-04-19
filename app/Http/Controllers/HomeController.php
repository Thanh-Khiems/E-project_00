<?php
namespace App\Http\Controllers;

use App\Models\AppointmentReview;
use App\Models\Blog;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }

        $featuredBlogs = Blog::featuredWithHardcoded(3);

        $homeDoctors = Doctor::query()
            ->with(['user', 'specialty'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->orderByDesc('is_featured')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->orderByDesc('experience_years')
            ->latest()
            ->take(6)
            ->get();

        $doctorFeedbacks = AppointmentReview::query()
            ->with([
                'doctor.specialty',
                'doctor.user',
                'patient',
            ])
            ->whereHas('doctor', function ($query) {
                $query->where('approval_status', 'approved')
                    ->where('status', 'active');
            })
            ->where(function ($query) {
                $query->whereNotNull('review')
                    ->where('review', '!=', '')
                    ->orWhereNotNull('rating');
            })
            ->orderByDesc('reviewed_at')
            ->orderByDesc('rating')
            ->latest('id')
            ->take(3)
            ->get();

        return view('pages.home', compact('featuredBlogs', 'homeDoctors', 'doctorFeedbacks'));
    }
}
