<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorBookingController extends Controller
{
    public function show(Doctor $doctor)
    {
        $today = Carbon::today()->toDateString();

        $doctor->load([
            'user',
            'specialty',
            'schedules' => function ($query) use ($today) {
                $query->whereDate('end_date', '>=', $today)
                    ->orderBy('start_date')
                    ->orderBy('start_time');
            },
        ]);

        $doctor->setRelation(
            'schedules',
            $doctor->schedules
                ->filter(fn ($schedule) => $this->scheduleHasUpcomingSlot($schedule))
                ->values()
        );

        return view('pages.user.doctor-booking', compact('doctor'));
    }

    private function scheduleHasUpcomingSlot($schedule): bool
    {
        $start = Carbon::parse($schedule->start_date)->startOfDay();
        $end = Carbon::parse($schedule->end_date)->startOfDay();
        $now = now();
        $today = $now->copy()->startOfDay();
        $scheduleStartTime = Carbon::parse($schedule->start_time)->format('H:i:s');
        $days = collect(explode(',', (string) $schedule->days))
            ->map(fn ($day) => trim($day))
            ->filter();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->lt($today)) {
                continue;
            }

            if ($days->isNotEmpty() && ! $days->contains($date->format('D'))) {
                continue;
            }

            if ($date->isSameDay($today) && $scheduleStartTime <= $now->format('H:i:s')) {
                continue;
            }

            return true;
        }

        return false;
    }
}
