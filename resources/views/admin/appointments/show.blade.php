@extends('admin.layouts.app')

@section('content')
<div class="panel-card mb-4">
    <div class="panel-head">
        <div>
            <h5>Appointment details {{ $appointment->appointment_code }}</h5>
            <p>Track the visit, doctor diagnosis, and all prescriptions issued for this appointment.</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary">Back to list</a>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="border rounded-4 p-4 h-100 bg-white">
                <h6 class="mb-3">Appointment information</h6>
                <table class="table mb-0">
                    <tr><th width="35%">Appointment code</th><td>{{ $appointment->appointment_code }}</td></tr>
                    <tr><th>Appointment date</th><td>{{ optional($appointment->appointment_date)->format('d/m/Y') }}</td></tr>
                    <tr><th>Time slot</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td></tr>
                    <tr><th>Location</th><td>{{ $appointment->location ?? '—' }}</td></tr>
                    <tr><th>Type</th><td>{{ $appointment->type === 'online' ? 'Online' : 'In-person' }}</td></tr>
                    <tr><th>Status</th><td>{{ ucfirst($appointment->status) }}</td></tr>
                    <tr><th>Diagnosis</th><td>{{ $appointment->diagnosis ?: 'No diagnosis yet.' }}</td></tr>
                    <tr><th>Advice</th><td>{{ $appointment->doctor_advice ?: 'No advice yet.' }}</td></tr>
                    <tr><th>Notes</th><td>{{ $appointment->notes ?: 'No notes yet.' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="border rounded-4 p-4 h-100 bg-white">
                <h6 class="mb-3">Patient and doctor information</h6>
                <table class="table mb-0">
                    <tr><th width="35%">Patient</th><td>{{ $appointment->patient->full_name ?? '—' }}</td></tr>
                    <tr><th>Patient email</th><td>{{ $appointment->patient->email ?? '—' }}</td></tr>
                    <tr><th>Phone number</th><td>{{ $appointment->patient->phone ?? '—' }}</td></tr>
                    <tr><th>Doctor</th><td>{{ $appointment->doctor->name ?? '—' }}</td></tr>
                    <tr><th>Specialty</th><td>{{ optional($appointment->doctor->specialty)->name ?? '—' }}</td></tr>
                    <tr><th>Doctor email</th><td>{{ $appointment->doctor->email ?? ($appointment->doctor->user->email ?? '—') }}</td></tr>
                    <tr><th>Number of prescriptions</th><td>{{ $appointment->prescriptions->count() }}</td></tr>
                    <tr><th>Completed at</th><td>{{ optional($appointment->completed_at)->format('d/m/Y H:i') ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel-card mt-4">
        <div class="panel-head">
            <div>
                <h5>Prescriptions for this appointment</h5>
                <p>One appointment can issue multiple prescriptions.</p>
            </div>
        </div>

        @forelse($appointment->prescriptions as $prescription)
            <div class="border rounded-4 p-4 mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong class="text-primary">{{ $prescription->prescription_code }}</strong>
                        <div class="text-muted small mt-1">Issued: {{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? '—' }}</div>
                    </div>
                    <div class="text-muted">Status: {{ ucfirst($prescription->status) }}</div>
                </div>
                <div class="mt-3"><strong>Diagnosis:</strong> {{ $prescription->diagnosis ?: '—' }}</div>
                <div class="mt-2"><strong>Advice:</strong> {{ $prescription->advice ?: '—' }}</div>
                <div class="mt-2"><strong>Prescription notes:</strong> {{ $prescription->notes ?: '—' }}</div>

                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Quantity</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $item)
                                <tr>
                                    <td>{{ $item->medication->name ?? '—' }}<div class="text-muted small">{{ $item->medication->dosage ?? '' }}</div></td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->frequency }}</td>
                                    <td>{{ $item->duration }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->instructions ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">This visit does not have any prescriptions yet.</p>
        @endforelse
    </div>

    <div class="panel-card mt-4">
        <div class="panel-head">
            <div>
                <h5>Patient review</h5>
                <p>This information was submitted from the patient account after the doctor confirmed the visit as completed.</p>
            </div>
        </div>

        @if($appointment->review)
            <div class="border rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong>{{ $appointment->review->patient->full_name ?? 'Patient' }}</strong>
                        <div class="text-muted small">{{ $appointment->review->patient->email ?? '—' }}</div>
                    </div>
                    <div class="text-warning fw-bold fs-5">
                        {{ str_repeat('★', (int) $appointment->review->rating) }}{{ str_repeat('☆', 5 - (int) $appointment->review->rating) }}
                    </div>
                </div>
                <div class="mt-3"><strong>Rating:</strong> {{ $appointment->review->rating }}/5</div>
                <div class="mt-2"><strong>Comment:</strong> {{ $appointment->review->review ?: 'No additional comments.' }}</div>
                <div class="mt-2 text-muted small">Reviewed at: {{ optional($appointment->review->reviewed_at)->format('d/m/Y H:i') ?? '—' }}</div>
            </div>
        @else
            <p class="text-muted mb-0">The patient has not submitted a review for this visit yet.</p>
        @endif
    </div>

    <div class="panel-card mt-4">
        <div class="panel-head">
            <div>
                <h5>Patient completed visit history</h5>
                <p>Admins can review previous visits and previously issued prescriptions.</p>
            </div>
        </div>

        @forelse($patientHistory as $history)
            <div class="border rounded-4 p-4 mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong>{{ $history->appointment_code }}</strong>
                        <div class="text-muted small">{{ optional($history->appointment_date)->format('d/m/Y') }} · {{ $history->doctor->name ?? '—' }}</div>
                    </div>
                    <div class="text-muted">{{ $history->prescriptions->count() }} prescriptions</div>
                </div>
                <div class="mt-3"><strong>Diagnosis:</strong> {{ $history->diagnosis ?: '—' }}</div>
                <div class="mt-2"><strong>Advice:</strong> {{ $history->doctor_advice ?: '—' }}</div>
            </div>
        @empty
            <p class="text-muted mb-0">There is no other completed visit history for this patient.</p>
        @endforelse
    </div>
</div>
@endsection
