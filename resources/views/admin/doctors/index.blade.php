@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total doctors', 'value' => $stats['total'], 'icon' => 'bi-person-badge'],
        ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'bi-check-circle'],
        ['label' => 'Temporarily locked', 'value' => $stats['inactive'], 'icon' => 'bi-pause-circle'],
        ['label' => 'Pending approval', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Doctor List</h5>
                <p>Manage profiles, specialties, working schedules, and approval status.</p>
            </div>
            <a href="{{ route('admin.doctors.approvals') }}" class="btn btn-primary">View doctor approvals</a>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-3"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Name, email, phone number..."></div>
            <div class="col-md-3">
                <select name="specialty_id" class="form-select">
                    <option value="">All specialties</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" @selected(request('specialty_id') == $specialty->id)>{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') == 'active')>Active</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Temporarily locked</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="approval_status" class="form-select">
                    <option value="">All approvals</option>
                    <option value="pending" @selected(request('approval_status') == 'pending')>Pending approval</option>
                    <option value="approved" @selected(request('approval_status') == 'approved')>Approved</option>
                    <option value="rejected" @selected(request('approval_status') == 'rejected')>Decline</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter data</button></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Experience</th>
                        <th>Working schedule</th>
                        <th>Status</th>
                        <th>Approval</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ strtoupper(substr($doctor->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $doctor->name }}</strong>
                                        <small>{{ $doctor->email }} · {{ $doctor->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $doctor->specialty->name ?? '—' }}</td>
                            <td>{{ $doctor->experience_years ?? 0 }} years</td>
                            <td>{{ $doctor->schedule_text ?? 'Monday - Saturday' }}</td>
                            <td><span class="status-badge {{ $doctor->status }}">{{ $doctor->status === 'active' ? 'Active' : 'Temporarily locked' }}</span></td>
                            <td>
                                @if($doctor->approval_status === 'approved')
                                    <span class="badge text-bg-success">Approved</span>
                                @elseif($doctor->approval_status === 'rejected')
                                    <span class="badge text-bg-danger">Decline</span>
                                @else
                                    <span class="badge text-bg-warning">Pending approval</span>
                                @endif
                            </td>
                            <td class="text-end table-actions">
                                @if($doctor->approval_status === 'pending')
                                    <a href="{{ route('admin.doctors.approvals', ['keyword' => $doctor->name, 'approval_status' => 'pending']) }}">Approval</a>
                                @else
                                    <a href="{{ route('admin.doctors.show', $doctor) }}">View profile</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">No doctor data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $doctors->links() }}
    </div>
@endsection
