<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function create(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status === 'cancelled') {
            return redirect()->route('doctor.appointments')->with('error', 'Cannot issue a prescription for a cancelled appointment.');
        }

        if (! in_array($appointment->status, ['confirmed', 'completed'], true)) {
            return redirect()->route('doctor.appointments')->with('error', 'You can only complete the visit and issue a prescription for a confirmed appointment.');
        }

        $appointment->load([
            'patient',
            'doctor.specialty',
            'prescriptions.items.medication.medicineType',
        ]);

        $medications = Medication::with('medicineType')->orderBy('name')->get();

        return view('pages.doctor.prescriptions.create', compact('appointment', 'doctor', 'medications'));
    }

    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Cannot issue a prescription for a cancelled appointment.');
        }

        $validated = $request->validate([
            'diagnosis' => ['required', 'string', 'max:5000'],
            'doctor_advice' => ['nullable', 'string', 'max:5000'],
            'prescription_notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medication_id' => ['required', 'exists:medications,id'],
            'items.*.dosage' => ['required', 'string', 'max:255'],
            'items.*.frequency' => ['required', 'string', 'max:255'],
            'items.*.duration' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.instructions' => ['nullable', 'string', 'max:1000'],
            'items.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $appointment, $doctor) {
            $appointment->update([
                'diagnosis' => $validated['diagnosis'],
                'doctor_advice' => $validated['doctor_advice'] ?? null,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $prescription = Prescription::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $appointment->patient_id,
                'diagnosis' => $validated['diagnosis'],
                'advice' => $validated['doctor_advice'] ?? null,
                'notes' => $validated['prescription_notes'] ?? null,
                'status' => 'issued',
                'issued_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {
                $prescription->items()->create([
                    'medication_id' => $item['medication_id'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration' => $item['duration'],
                    'quantity' => $item['quantity'],
                    'instructions' => $item['instructions'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('doctor.appointments')->with('success', 'The visit has been completed and the prescription has been issued.');
    }
}
