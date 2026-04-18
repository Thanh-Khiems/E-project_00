<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

use Illuminate\Support\Collection;

use Illuminate\Http\Request;


class DoctorBookingController extends Controller
{
    public function show(Doctor $doctor)
    {

        Appointment::purgeExpired();

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


        $doctor->load([
            'user',
            'specialty',
            'schedules' => function ($query) {
                $query->whereDate('end_date', '>=', today()->toDateString())
                    ->orderBy('start_date')
                    ->orderBy('start_time');
            },
        ]);

        $bookingSlots = $this->buildBookingSlots($doctor);

        return view('pages.user.doctor-booking', compact('doctor', 'bookingSlots'));
    }

    private function buildBookingSlots(Doctor $doctor): Collection
    {
        $now = now();
        $windowStart = $now->copy();
        $windowEnd = $now->copy()->addWeek();

        $slots = collect();

        foreach ($doctor->schedules as $schedule) {
            $scheduleStart = Carbon::parse($schedule->start_date)->startOfDay();
            $scheduleEnd = Carbon::parse($schedule->end_date)->endOfDay();
            $activeStart = $scheduleStart->greaterThan($windowStart->copy()->startOfDay())
                ? $scheduleStart->copy()
                : $windowStart->copy()->startOfDay();
            $activeEnd = $scheduleEnd->lessThan($windowEnd)
                ? $scheduleEnd->copy()
                : $windowEnd->copy();

            if ($activeStart->gt($activeEnd)) {
                continue;
            }

            $availableDays = collect(explode(',', (string) $schedule->days))
                ->map(fn ($day) => trim($day))
                ->filter();

            for ($date = $activeStart->copy(); $date->lte($activeEnd); $date->addDay()) {
                if ($availableDays->isNotEmpty() && ! $availableDays->contains($date->format('D'))) {
                    continue;
                }

                if ($date->isSameDay($now) && Carbon::parse($schedule->start_time)->format('H:i:s') <= $now->format('H:i:s')) {
                    continue;
                }

                $bookedCount = Appointment::query()
                    ->where('schedule_id', $schedule->id)
                    ->whereDate('appointment_date', $date->toDateString())
                    ->where('status', '!=', 'cancelled')
                    ->count();

                $remainingSlots = $schedule->max_patients
                    ? max((int) $schedule->max_patients - $bookedCount, 0)
                    : null;

                $slots->push([
                    'schedule_id' => $schedule->id,
                    'date' => $date->copy(),
                    'start_time' => Carbon::parse($schedule->start_time),
                    'end_time' => Carbon::parse($schedule->end_time),
                    'type' => $schedule->type,
                    'location' => $schedule->location,
                    'notes' => $schedule->notes,
                    'max_patients' => $schedule->max_patients,
                    'booked_count' => $bookedCount,
                    'remaining_slots' => $remainingSlots,
                    'is_full' => $schedule->max_patients ? $bookedCount >= (int) $schedule->max_patients : false,
                    'value' => $schedule->id . '|' . $date->toDateString(),
                ]);
            }
        }

        return $slots
            ->sortBy(fn (array $slot) => $slot['date']->format('Y-m-d') . ' ' . $slot['start_time']->format('H:i:s'))
            ->groupBy(fn (array $slot) => $slot['date']->format('Y-m-d'));
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
