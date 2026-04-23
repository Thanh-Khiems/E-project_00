@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Doctor profile</h5>
                <p>View doctor details, verification documents, and approval actions.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.doctors.approvals') }}" class="btn btn-outline-secondary">Back to approvals</a>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-primary">Back to list</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mt-3 mb-0">
                {{ $errors->first() }}
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
                    <h5 class="mb-3">Submitted verification information</h5>

                    <div class="small text-muted mb-3">
                        Submitted by user account: <strong>{{ $doctor->user->full_name ?? $doctor->name }}</strong>
                        @if($doctor->submitted_at)
                            · {{ $doctor->submitted_at->format('d/m/Y H:i') }}
                        @endif
                    </div>

                    <div class="row g-2 small">
                        <div class="col-sm-6"><strong>Full name:</strong> {{ $doctor->name ?: '—' }}</div>
                        <div class="col-sm-6"><strong>Email:</strong> {{ $doctor->email ?: ($doctor->user->email ?? '—') }}</div>
                        <div class="col-sm-6"><strong>Phone number:</strong> {{ $doctor->phone ?: '—' }}</div>
                        <div class="col-sm-6"><strong>Date of birth:</strong> {{ $doctor->doctor_dob ? $doctor->doctor_dob->format('d/m/Y') : '—' }}</div>
                        <div class="col-sm-6"><strong>Citizen ID number:</strong> {{ $doctor->citizen_id ?: '—' }}</div>
                        <div class="col-sm-6"><strong>Specialty:</strong> {{ $doctor->specialty->name ?? '—' }}</div>
                        <div class="col-sm-6"><strong>Degree(s):</strong> {{ $doctor->degree_display ?? '—' }}</div>
                        <div class="col-sm-6"><strong>Experience:</strong> {{ $doctor->experience_years ?? 0 }} years</div>
                        <div class="col-sm-6"><strong>City:</strong> {{ $doctor->city ?? ($doctor->user->province ?? '—') }}</div>
                        <div class="col-sm-6"><strong>Working schedule:</strong> {{ $doctor->schedule_text ?? 'Not updated' }}</div>
                        <div class="col-sm-6">
                            <strong>Status:</strong>
                            @if($doctor->status === 'active')
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-secondary">Locked</span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <strong>Approval:</strong>
                            @if($doctor->approval_status === 'approved')
                                <span class="badge text-bg-success">Approved</span>
                            @elseif($doctor->approval_status === 'rejected')
                                <span class="badge text-bg-danger">Declined</span>
                            @else
                                <span class="badge text-bg-warning">Pending approval</span>
                            @endif
                        </div>
                        <div class="col-sm-6"><strong>Verification status:</strong> {{ ucfirst($doctor->verification_status ?? 'draft') }}</div>
                    </div>

                    @if($doctor->approval_note)
                        <div class="alert alert-light border mt-3 mb-0">
                            <strong>Rejection note:</strong> {{ $doctor->approval_note }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="mb-0">Verification documents</h5>
                        <div class="small text-muted">Admin can review all uploaded images before approving.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-2 h-100">
                                <div class="small fw-semibold mb-2">Citizen ID front</div>
                                @if($doctor->citizen_id_front)
                                    <a href="{{ route('admin.doctors.document', [$doctor, 'document' => 'citizen-front']) }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ route('admin.doctors.document', [$doctor, 'document' => 'citizen-front']) }}" alt="Citizen ID front" style="width:100%; height:220px; object-fit:contain; background:#f8fafc; border-radius:8px;">
                                    </a>
                                    <a href="{{ route('admin.doctors.document', [$doctor, 'document' => 'citizen-front']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary w-100 mt-2">Open image</a>
                                @else
                                    <div class="text-muted small">No image yet</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-2 h-100">
                                <div class="small fw-semibold mb-2">Citizen ID back</div>
                                @if($doctor->citizen_id_back)
                                    <a href="{{ route('admin.doctors.document', [$doctor, 'document' => 'citizen-back']) }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ route('admin.doctors.document', [$doctor, 'document' => 'citizen-back']) }}" alt="Citizen ID back" style="width:100%; height:220px; object-fit:contain; background:#f8fafc; border-radius:8px;">
                                    </a>
                                    <a href="{{ route('admin.doctors.document', [$doctor, 'document' => 'citizen-back']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary w-100 mt-2">Open image</a>
                                @else
                                    <div class="text-muted small">No image yet</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-2 h-100">
                                <div class="small fw-semibold mb-2">Degree certificate</div>
                                @if($doctor->degree_image)
                                    <a href="{{ route('admin.doctors.document', [$doctor, 'document' => 'degree']) }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ route('admin.doctors.document', [$doctor, 'document' => 'degree']) }}" alt="Degree certificate" style="width:100%; height:220px; object-fit:contain; background:#f8fafc; border-radius:8px;">
                                    </a>
                                    <a href="{{ route('admin.doctors.document', [$doctor, 'document' => 'degree']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary w-100 mt-2">Open image</a>
                                @else
                                    <div class="text-muted small">No image yet</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($doctor->approval_status === 'pending')
            <div class="border rounded-4 p-4 bg-white mt-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Approval actions</h5>
                        <p class="text-muted mb-0">Only approve after checking the submitted information and verification images.</p>
                    </div>
                </div>

                <div class="row g-3 mt-1 align-items-end">
                    <div class="col-lg-8">
                        <form method="POST" action="{{ route('admin.doctors.reject', $doctor) }}">
                            @csrf
                            <label class="form-label">Reason for rejection</label>
                            <div class="input-group">
                                <input type="text" name="approval_note" class="form-control" placeholder="Enter a reason if rejecting the application..." value="{{ old('approval_note') }}">
                                <button type="submit" class="btn btn-outline-danger">Decline</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <form method="POST" action="{{ route('admin.doctors.approve', $doctor) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve doctor</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

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
                                <th class="text-end">Actions</th>
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
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Are you sure you want to delete this patient review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete review</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted mt-3">This doctor does not have any patient reviews yet.</div>
            @endif
        </div>

        <div class="mt-4 d-flex gap-3 flex-wrap">
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
