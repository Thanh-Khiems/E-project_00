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
            return back()->with('error', 'Không thể tạo lịch hẹn cho ngày hoặc giờ đã qua. Vui lòng chọn khung giờ khác.');
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

            return back()->with('error', 'Lịch hẹn đã quá hạn nên đã được xóa khỏi hệ thống.');
        }

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
        if ($appointment->isExpired()) {
            $appointment->delete();

            return back()->with('error', 'Lịch hẹn đã quá hạn nên đã được xóa khỏi hệ thống.');
        }

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


    public function storeReview(Request $request, Appointment $appointment): RedirectResponse
    {
        if ((int) $appointment->patient_id !== (int) Auth::id()) {
            return back()->with('error', 'Bạn không có quyền đánh giá lịch hẹn này.');
        }

        if ($appointment->status !== 'completed') {
            return back()->with('error', 'Chỉ có thể đánh giá sau khi lịch hẹn đã hoàn tất.');
        }

        if ($appointment->review()->exists()) {
            return back()->with('error', 'Bạn đã đánh giá lịch hẹn này rồi.');
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

        return back()->with('success', 'Đánh giá bác sĩ thành công.');
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        if ($appointment->isExpired()) {
            $appointment->delete();

            return back()->with('error', 'Lịch hẹn đã quá hạn nên đã được xóa khỏi hệ thống.');
        }

        if (! $this->canManageAppointment($appointment)) {
            return back()->with('error', 'Bạn không có quyền hoàn tất lịch hẹn này.');
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Không thể hoàn tất lịch hẹn đã bị hủy.');
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

    private function resolveNearestDate(Schedule $schedule, string $selectedDay): ?Carbon
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
