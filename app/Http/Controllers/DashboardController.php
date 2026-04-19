<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AppointmentReview;
use App\Models\Blog;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function user()
    {
        $featuredBlogs = Blog::featuredWithHardcoded(6);

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
            ->take(3)
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

        return view('pages.user.dashboard', compact('featuredBlogs', 'homeDoctors', 'doctorFeedbacks'));
    }

    public function doctor()
    {
        return view('pages.doctor.doctor-main');
    }

    public function manageAppointments()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        $schedules = collect();
        $todaySchedules = collect();

        if ($doctor) {
            $schedules = Schedule::where('doctor_id', $doctor->id)
                ->orderBy('start_date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();

            $today = Carbon::today()->toDateString();
            $todayDay = Carbon::today()->format('D'); // Mon, Tue, Wed...

            $todaySchedules = Schedule::where('doctor_id', $doctor->id)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->get()
                ->filter(function ($schedule) use ($todayDay) {
                    $days = collect(explode(',', $schedule->days))
                        ->map(fn($day) => trim($day));

                    return $days->contains($todayDay);
                })
                ->sortBy('start_time')
                ->values();
        }

        return view('pages.doctor.dashboard', compact('schedules', 'todaySchedules'));
    }

    public function appointments()
    {
        return view('pages.doctor.appointments');
    }
}
