<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentReview;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        Appointment::purgeExpired();

        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'selected_slot' => ['required', 'string'],
        ]);

        $parts = explode('|', (string) $request->selected_slot, 2);
        if (count($parts) !== 2) {
            return back()->with('error', 'The selected time slot is invalid.');
        }

        [$scheduleId, $selectedValue] = $parts;

        $doctor = Doctor::query()
            ->where('id', $request->doctor_id)
            ->firstOrFail();

        $schedule = Schedule::query()
            ->where('id', $scheduleId)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $appointmentDate = $this->resolveAppointmentDate($schedule, $selectedValue);

        if (! $appointmentDate) {
            return back()->with('error', 'Cannot create an appointment for a past date or time. Please choose another time slot.');
        }

        if ($appointmentDate->gt(now()->copy()->addWeek())) {
            return back()->with('error', 'Appointments can only be booked within the next 7 days.');
        }

        $patientId = Auth::id();

        $duplicate = Appointment::query()
            ->where('patient_id', $patientId)
            ->whereDate('appointment_date', $appointmentDate->toDateString())
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($schedule) {
                $query->where('schedule_id', $schedule->id)
                    ->orWhere(function ($subQuery) use ($schedule) {
                        $subQuery->where('doctor_id', $schedule->doctor_id)
                            ->where('start_time', $schedule->start_time)
                            ->where('end_time', $schedule->end_time);
                    });
            })
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'You already have an appointment in this time slot.');
        }

        $bookedCount = Appointment::query()
            ->where('schedule_id', $schedule->id)
            ->whereDate('appointment_date', $appointmentDate->toDateString())
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($schedule->max_patients && $bookedCount >= $schedule->max_patients) {
            return back()->with('error', 'This time slot has reached its patient capacity.');
        }

        Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctor->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => $appointmentDate->toDateString(),
            'appointment_day' => $appointmentDate->format('D'),
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'type' => $schedule->type,
            'location' => $schedule->location,
            'max_patients' => $schedule->max_patients,
            'status' => 'pending',
            'notes' => null,
        ]);

        return redirect()
            ->route('user.profile', ['tab' => 'appointments'])
            ->with('success', 'Appointment booked successfully.');
    }

    public function patientIndex()
    {
        Appointment::purgeExpired();

        $appointments = Appointment::query()
            ->with(['doctor.user', 'doctor.specialty', 'schedule', 'prescriptions.items.medication', 'review.patient'])
            ->where('patient_id', Auth::id())
            ->visibleInCurrentWeek()
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        return view('pages.user.patient-appointments', compact('appointments'));
    }

    public function doctorIndex()
    {
        Appointment::purgeExpired();

        $doctor = Doctor::query()
            ->with('user')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $visibleAppointmentsQuery = Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->visibleInCurrentWeek();

        $appointments = (clone $visibleAppointmentsQuery)
            ->with(['patient', 'schedule', 'doctor.specialty', 'prescriptions.items.medication', 'review.patient'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => (clone $visibleAppointmentsQuery)->count(),
            'today' => Appointment::query()->where('doctor_id', $doctor->id)->whereDate('appointment_date', today())->whereTime('end_time', '>=', now()->format('H:i:s'))->count(),
            'pending' => (clone $visibleAppointmentsQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $visibleAppointmentsQuery)->where('status', 'confirmed')->count(),
            'completed' => (clone $visibleAppointmentsQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $visibleAppointmentsQuery)->where('status', 'cancelled')->count(),
            'reviews_count' => AppointmentReview::query()->where('doctor_id', $doctor->id)->count(),
            'average_rating' => round((float) AppointmentReview::query()->where('doctor_id', $doctor->id)->avg('rating'), 1),
        ];

        $statusBoards = [
            'confirmed' => Appointment::query()->with(['patient'])->where('doctor_id', $doctor->id)->visibleInCurrentWeek()->where('status', 'confirmed')->latest('appointment_date')->take(5)->get(),
            'cancelled' => Appointment::query()->with(['patient'])->where('doctor_id', $doctor->id)->visibleInCurrentWeek()->where('status', 'cancelled')->latest('appointment_date')->take(5)->get(),
            'completed' => Appointment::query()->with(['patient'])->where('doctor_id', $doctor->id)->visibleInCurrentWeek()->where('status', 'completed')->latest('appointment_date')->take(5)->get(),
        ];

        return view('pages.doctor.appointments', compact('appointments', 'doctor', 'stats', 'statusBoards'));
    }

    public function confirm(Appointment $appointment): RedirectResponse
    {
        if ($appointment->isExpired()) {
            $appointment->delete();

            return back()->with('error', 'The expired appointment has been removed from the system.');
        }

        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'You do not have permission to confirm this appointment.');
        }

        if (! in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'This appointment cannot be confirmed in its current status.');
        }

        $appointment->update([
            'status' => 'confirmed',
        ]);

        return back()->with('success', 'Appointment confirmed successfully.');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        if ($appointment->isExpired()) {
            $appointment->delete();

            return back()->with('error', 'The expired appointment has been removed from the system.');
        }

        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'You do not have permission to cancel this appointment.');
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'This appointment was already cancelled earlier.');
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Cannot cancel a completed appointment.');
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }


    public function storeReview(Request $request, Appointment $appointment): RedirectResponse
    {
        if ((int) $appointment->patient_id !== (int) Auth::id()) {
            return back()->with('error', 'You do not have permission to review this appointment.');
        }

        if ($appointment->status !== 'completed') {
            return back()->with('error', 'You can only submit a review after the appointment is completed.');
        }

        if ($appointment->review()->exists()) {
            return back()->with('error', 'You have already reviewed this appointment.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:2000'],
        ]);

        $appointment->review()->create([
            'patient_id' => Auth::id(),
            'doctor_id' => $appointment->doctor_id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Doctor review submitted successfully.');
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        if ($appointment->isExpired()) {
            $appointment->delete();

            return back()->with('error', 'The expired appointment has been removed from the system.');
        }

        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'You do not have permission to complete this appointment.');
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Cannot complete a cancelled appointment.');
        }

        return redirect()->route('doctor.appointments.prescriptions.create', $appointment);
    }

    private function canManageAppointment(Appointment $appointment): bool
    {
        $doctor = Doctor::query()
            ->where('user_id', Auth::id())
            ->first();

        $isAdmin = Auth::user()?->role === 'admin';
        $isDoctorOwner = $doctor && $appointment->doctor_id === $doctor->id;

        return $isAdmin || $isDoctorOwner;
    }

    private function resolveAppointmentDate(Schedule $schedule, string $selectedValue): ?Carbon
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedValue) === 1) {
            return $this->resolveExactDate($schedule, $selectedValue);
        }

        return $this->resolveNearestDateByWeekday($schedule, $selectedValue);
    }

    private function resolveExactDate(Schedule $schedule, string $selectedDate): ?Carbon
    {
        $date = Carbon::parse($selectedDate)->startOfDay();
        $start = Carbon::parse($schedule->start_date)->startOfDay();
        $end = Carbon::parse($schedule->end_date)->startOfDay();
        $now = now();
        $allowedDays = collect(explode(',', (string) $schedule->days))
            ->map(fn ($day) => trim($day))
            ->filter();

        if ($date->lt($now->copy()->startOfDay()) || $date->lt($start) || $date->gt($end)) {
            return null;
        }

        if ($allowedDays->isNotEmpty() && ! $allowedDays->contains($date->format('D'))) {
            return null;
        }

        if ($date->isSameDay($now) && Carbon::parse($schedule->start_time)->format('H:i:s') <= $now->format('H:i:s')) {
            return null;
        }

        return $date;
    }

    private function resolveNearestDateByWeekday(Schedule $schedule, string $selectedDay): ?Carbon
    {
        $start = Carbon::parse($schedule->start_date)->startOfDay();
        $end = Carbon::parse($schedule->end_date)->startOfDay();
        $now = now();
        $today = $now->copy()->startOfDay();
        $scheduleStartTime = Carbon::parse($schedule->start_time)->format('H:i:s');

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->lt($today)) {
                continue;
            }

            if ($date->format('D') !== $selectedDay) {
                continue;
            }

            if ($date->isSameDay($today) && $scheduleStartTime <= $now->format('H:i:s')) {
                continue;
            }

            return $date->copy();
        }

        return null;
    }
}
