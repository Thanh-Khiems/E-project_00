@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Pending approval', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split'],
        ['label' => 'Approved', 'value' => $stats['approved'], 'icon' => 'bi-check2-circle'],
        ['label' => 'Decline', 'value' => $stats['rejected'], 'icon' => 'bi-x-circle'],
        ['label' => 'Total doctors', 'value' => $stats['total'], 'icon' => 'bi-person-badge'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Doctor verification / approval</h5>
                <p>When doctors submit registration information, admins can come here to review all submitted details before approving or rejecting.</p>
            </div>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-6">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search by name, email, phone number...">
            </div>
            <div class="col-md-4">
                <select name="approval_status" class="form-select">
                    <option value="pending" @selected($approvalStatus === 'pending')>Pending approval</option>
                    <option value="approved" @selected($approvalStatus === 'approved')>Approved</option>
                    <option value="rejected" @selected($approvalStatus === 'rejected')>Decline</option>
                    <option value="all" @selected($approvalStatus === 'all')>All</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Filter data</button>
            </div>
        </form>

        <div class="mt-4 d-flex flex-column gap-3">
            @forelse($doctors as $doctor)
                <div class="border rounded-4 p-4 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1">{{ $doctor->name }}</h5>
                            <div class="text-muted small">{{ $doctor->email ?: 'No email yet' }} · {{ $doctor->phone ?: 'No phone number yet' }}</div>
                            <div class="mt-2 small">
                                <strong>Specialty:</strong> {{ $doctor->specialty->name ?? '—' }}<br>
                                <strong>Degree(s):</strong> {{ $doctor->degree_display ?? '—' }}<br>
                                <strong>Citizen ID:</strong> {{ $doctor->citizen_id ?? '—' }}<br>
                                <strong>Date of birth:</strong> {{ $doctor->doctor_dob ? $doctor->doctor_dob->format('d/m/Y') : '—' }}<br>
                                <strong>Experience:</strong> {{ $doctor->experience_years ?? 0 }} years<br>
                                <strong>City:</strong> {{ $doctor->city ?? '—' }}<br>
                                <strong>Submitted at:</strong> {{ optional($doctor->submitted_at)->format('d/m/Y H:i') ?? '—' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mb-2">
                                @if($doctor->approval_status === 'approved')
                                    <span class="badge text-bg-success">Approved</span>
                                @elseif($doctor->approval_status === 'rejected')
                                    <span class="badge text-bg-danger">Decline</span>
                                @else
                                    <span class="badge text-bg-warning">Pending approval</span>
                                @endif
                            </div>
                            <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-outline-primary btn-sm">View submitted info</a>
                        </div>
                    </div>

                    @if($doctor->approval_note)
                        <div class="alert alert-light border mt-3 mb-0">
                            <strong>Rejection note:</strong> {{ $doctor->approval_note }}
                        </div>
                    @endif

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
                        <div class="col-lg-4 text-lg-end d-flex justify-content-lg-end gap-2 flex-wrap">
                            @if($doctor->approval_status === 'pending')
                                <form method="POST" action="{{ route('admin.doctors.approve', $doctor) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">There are no doctor applications in this section.</div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $doctors->links() }}
        </div>
    </div>
@endsection
