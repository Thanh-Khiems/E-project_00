<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query()->with(['doctor', 'patient', 'specialty']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('appointment_code', 'like', "%{$keyword}%")
                  ->orWhere('patient_name', 'like', "%{$keyword}%")
                  ->orWhere('doctor_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderByDesc('appointment_date')->paginate(12)->withQueryString();

        return view('admin.appointments.index', [
            'pageTitle' => 'Quản lý lịch hẹn toàn hệ thống',
            'appointments' => $appointments,
            'doctors' => Doctor::orderBy('name')->get(),
            'stats' => [
                'total' => Appointment::count(),
                'today' => Appointment::whereDate('appointment_date', today())->count(),
                'pending' => Appointment::where('status', 'pending')->count(),
                'completed' => Appointment::where('status', 'completed')->count(),
            ]
        ]);
    }
}
