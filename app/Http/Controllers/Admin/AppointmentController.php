<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        Appointment::purgeExpired();

        $query = Appointment::query()->with(['doctor.user', 'doctor.specialty', 'patient', 'schedule', 'prescriptions', 'review.patient']);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('id', $keyword)
                    ->orWhere('location', 'like', "%{$keyword}%")
                    ->orWhere('type', 'like', "%{$keyword}%")
                    ->orWhereHas('doctor', function ($doctorQuery) use ($keyword) {
                        $doctorQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhereHas('user', function ($userQuery) use ($keyword) {
                                $userQuery->where('full_name', 'like', "%{$keyword}%")
                                    ->orWhere('email', 'like', "%{$keyword}%");
                            });
                    })
                    ->orWhereHas('patient', function ($patientQuery) use ($keyword) {
                        $patientQuery->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
            });
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        $doctors = Doctor::query()->orderBy('name')->get();

        $stats = [
            'total' => Appointment::count(),
            'today' => Appointment::whereDate('appointment_date', Carbon::today())->count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'doctors', 'stats'));
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->isExpired()) {
            $appointment->delete();

            return redirect()->route('admin.appointments.index')->with('error', 'The expired appointment has been removed from the system.');
        }

        $appointment->load([
            'doctor.user',
            'doctor.specialty',
            'patient',
            'schedule',
            'prescriptions.items.medication.medicineType',
            'review.patient',
        ]);

        $patientHistory = Appointment::with(['doctor.specialty', 'prescriptions.items.medication.medicineType'])
            ->where('patient_id', $appointment->patient_id)
            ->where('status', 'completed')
            ->where('id', '!=', $appointment->id)
            ->latest('appointment_date')
            ->get();

        return view('admin.appointments.show', compact('appointment', 'patientHistory'));
    }
}
