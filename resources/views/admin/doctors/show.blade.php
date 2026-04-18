@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Doctor profile</h5>
                <p>View doctor details and perform management actions.</p>
            </div>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-primary">Back to list</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif


        <div class="row g-4 mt-1">
            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="text-muted small mb-2">Average score</div>
                    <div style="font-size:32px;font-weight:800;color:#ea580c;">{{ number_format($reviewStats['average_rating'], 1) }}/5</div>
                    <div class="mt-2 text-warning fw-semibold">{{ str_repeat('★', (int) floor($reviewStats['average_rating'])) }}{{ str_repeat('☆', max(0, 5 - (int) floor($reviewStats['average_rating']))) }}</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="text-muted small mb-2">Total reviews</div>
                    <div style="font-size:32px;font-weight:800;color:#111827;">{{ $reviewStats['reviews_count'] }}</div>
                    <div class="mt-2 text-muted small">Number of reviews patients have submitted for this doctor.</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="text-muted small mb-2">Completed appointments</div>
                    <div style="font-size:32px;font-weight:800;color:#111827;">{{ $reviewStats['completed_appointments'] }}</div>
                    <div class="mt-2 text-muted small">Used to compare the number of completed visits and reviews.</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h5 class="mb-3">{{ $doctor->name }}</h5>
                    <div class="small text-muted mb-3">
                        {{ $doctor->email ?: 'No email yet' }} · {{ $doctor->phone ?: 'No phone number yet' }}
                    </div>

                    <div class="mb-2"><strong>Specialty:</strong> {{ $doctor->specialty->name ?? '—' }}</div>
                    <div class="mb-2"><strong>Degree:</strong> {{ $doctor->degree ?? '—' }}</div>
                    <div class="mb-2"><strong>Experience:</strong> {{ $doctor->experience_years ?? 0 }} years</div>
                    <div class="mb-2"><strong>City:</strong> {{ $doctor->city ?? '—' }}</div>
                    <div class="mb-2"><strong>Working schedule:</strong> {{ $doctor->schedule_text ?? 'Not updated' }}</div>
                    <div class="mb-2">
                        <strong>Status:</strong>
                        @if($doctor->status === 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-secondary">Locked</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Approval:</strong>
                        @if($doctor->approval_status === 'approved')
                            <span class="badge text-bg-success">Approved</span>
                        @elseif($doctor->approval_status === 'rejected')
                            <span class="badge text-bg-danger">Decline</span>
                        @else
                            <span class="badge text-bg-warning">Pending approval</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h6 class="mb-3">Verification documents</h6>

                    <div class="mb-2"><strong>Date of birth:</strong> {{ $doctor->doctor_dob ? \Carbon\Carbon::parse($doctor->doctor_dob)->format('d/m/Y') : '—' }}</div>
                    <div class="mb-3"><strong>Citizen ID number:</strong> {{ $doctor->citizen_id ?? '—' }}</div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-2">
                                <div class="small fw-semibold mb-2">Citizen ID front</div>
                                @if($doctor->citizen_id_front)
                                    <img src="{{ asset('storage/' . $doctor->citizen_id_front) }}" alt="Citizen ID front" style="width:100%; max-height:220px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div class="text-muted small">No image yet</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-2">
                                <div class="small fw-semibold mb-2">Citizen ID back</div>
                                @if($doctor->citizen_id_back)
                                    <img src="{{ asset('storage/' . $doctor->citizen_id_back) }}" alt="CCCD sau" style="width:100%; max-height:220px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div class="text-muted small">No image yet</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-2">
                                <div class="small fw-semibold mb-2">Degree</div>
                                @if($doctor->degree_image)
                                    <img src="{{ asset('storage/' . $doctor->degree_image) }}" alt="Degree" style="width:100%; max-height:220px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div class="text-muted small">No image yet</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel-card mt-4">
            <div class="panel-head">
                <div>
                    <h5>Patient reviews</h5>
                    <p>Admins can directly view the reviews patients submitted for this doctor.</p>
                </div>
            </div>

            @if($recentReviews->count())
                <div class="table-responsive mt-3">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Appointments</th>
                                <th>Score</th>
                                <th>Comment</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReviews as $review)
                                <tr>
                                    <td>
                                        <strong>{{ $review->patient->full_name ?? 'Patient' }}</strong>
                                        <div class="text-muted small">{{ $review->patient->email ?? '—' }}</div>
                                    </td>
                                    <td>{{ $review->appointment->appointment_code ?? '—' }}</td>
                                    <td>
                                        <div class="fw-semibold text-warning">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</div>
                                        <div class="text-muted small">{{ $review->rating }}/5</div>
                                    </td>
                                    <td>{{ $review->review ?: 'No additional comments.' }}</td>
                                    <td>{{ optional($review->reviewed_at)->format('d/m/Y H:i') ?? optional($review->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted mt-3">This doctor does not have any patient reviews yet.</div>
            @endif
        </div>

        <div class="mt-4 d-flex gap-3">
            <form method="POST" action="{{ route('admin.doctors.toggleStatus', $doctor) }}">
                @csrf
                <button type="submit" class="btn {{ $doctor->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                    {{ $doctor->status === 'active' ? 'Lock doctor' : 'Unlock doctor' }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" onsubmit="return confirm('Are you sure you want to delete this doctor profile?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Delete doctor
                </button>
            </form>
        </div>
    </div>
@endsection
