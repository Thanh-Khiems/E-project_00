<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'selected_slot' => ['required', 'string'],
        ]);

        $parts = explode('|', (string) $request->selected_slot, 2);
        if (count($parts) !== 2) {
            return back()->with('error', 'Khung giờ đã chọn không hợp lệ.');
        }

        [$scheduleId, $selectedDay] = $parts;

        $doctor = Doctor::query()
            ->where('id', $request->doctor_id)
            ->firstOrFail();

        $schedule = Schedule::query()
            ->where('id', $scheduleId)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $availableDays = collect(explode(',', (string) $schedule->days))
            ->map(fn ($day) => trim($day))
            ->filter();

        if ($availableDays->isNotEmpty() && ! $availableDays->contains($selectedDay)) {
            return back()->with('error', 'Khung giờ đã chọn không thuộc lịch làm việc của bác sĩ.');
        }

        $appointmentDate = $this->resolveNearestDate($schedule, $selectedDay);

        if (! $appointmentDate) {
            return back()->with('error', 'Không tìm được ngày khám hợp lệ cho khung giờ này.');
        }

        $patientId = Auth::id();

        $duplicate = Appointment::query()
            ->where('patient_id', $patientId)
            ->whereDate('appointment_date', $appointmentDate->toDateString())
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
            return back()->with('error', 'Bạn đã có lịch hẹn trùng với khung giờ này.');
        }

        $bookedCount = Appointment::query()
            ->where('schedule_id', $schedule->id)
            ->whereDate('appointment_date', $appointmentDate->toDateString())
            ->count();

        if ($schedule->max_patients && $bookedCount >= $schedule->max_patients) {
            return back()->with('error', 'Khung giờ này đã đủ số lượng bệnh nhân.');
        }

        Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctor->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => $appointmentDate->toDateString(),
            'appointment_day' => $selectedDay,
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
            ->with('success', 'Đặt lịch hẹn thành công.');
    }

    public function patientIndex()
    {
        $appointments = Appointment::query()
            ->with(['doctor.user', 'doctor.specialty', 'schedule'])
            ->where('patient_id', Auth::id())
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->get();

        return view('pages.user.patient-appointments', compact('appointments'));
    }

    public function doctorIndex()
    {
        $doctor = Doctor::query()
            ->with('user')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $appointments = Appointment::query()
            ->with(['patient', 'schedule', 'doctor.specialty'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Appointment::query()->where('doctor_id', $doctor->id)->count(),
            'today' => Appointment::query()->where('doctor_id', $doctor->id)->whereDate('appointment_date', today())->count(),
            'pending' => Appointment::query()->where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'confirmed' => Appointment::query()->where('doctor_id', $doctor->id)->where('status', 'confirmed')->count(),
            'completed' => Appointment::query()->where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
            'cancelled' => Appointment::query()->where('doctor_id', $doctor->id)->where('status', 'cancelled')->count(),
        ];

        $statusBoards = [
            'confirmed' => Appointment::query()
                ->with(['patient'])
                ->where('doctor_id', $doctor->id)
                ->where('status', 'confirmed')
                ->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->take(5)
                ->get(),
            'cancelled' => Appointment::query()
                ->with(['patient'])
                ->where('doctor_id', $doctor->id)
                ->where('status', 'cancelled')
                ->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->take(5)
                ->get(),
            'completed' => Appointment::query()
                ->with(['patient'])
                ->where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->take(5)
                ->get(),
        ];

        return view('pages.doctor.appointments', compact('appointments', 'doctor', 'stats', 'statusBoards'));
    }

    public function confirm(Appointment $appointment): RedirectResponse
    {
        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'Bạn không có quyền xác nhận lịch hẹn này.');
        }

        if (! in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'Lịch hẹn này không thể xác nhận ở trạng thái hiện tại.');
        }

        $appointment->update([
            'status' => 'confirmed',
        ]);

        return back()->with('success', 'Xác nhận lịch hẹn thành công.');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'Bạn không có quyền hủy lịch hẹn này.');
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Lịch hẹn này đã được hủy trước đó.');
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Không thể hủy lịch hẹn đã hoàn tất.');
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Đã hủy lịch hẹn thành công.');
    }


    public function complete(Appointment $appointment): RedirectResponse
    {
        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'Bạn không có quyền hoàn thành lịch hẹn này.');
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Không thể hoàn thành lịch hẹn đã bị hủy.');
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Lịch hẹn này đã hoàn thành trước đó.');
        }

        if (! in_array($appointment->status, ['confirmed'], true)) {
            return back()->with('error', 'Chỉ có thể hoàn thành lịch hẹn đã được xác nhận.');
        }

        $appointment->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Đã cập nhật lịch hẹn sang trạng thái hoàn thành.');
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

    private function resolveNearestDate(Schedule $schedule, string $selectedDay): ?Carbon
    {
        $start = Carbon::parse($schedule->start_date)->startOfDay();
        $end = Carbon::parse($schedule->end_date)->startOfDay();
        $today = Carbon::today();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->lt($today)) {
                continue;
            }

            if ($date->format('D') === $selectedDay) {
                return $date->copy();
            }
        }

        return null;
    }
}
