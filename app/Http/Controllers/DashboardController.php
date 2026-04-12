<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
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
