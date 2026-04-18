@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total patients', 'value' => $stats['total'], 'icon' => 'bi-people'],
        ['label' => 'Nam', 'value' => $stats['male'], 'icon' => 'bi-gender-male'],
        ['label' => 'Female', 'value' => $stats['female'], 'icon' => 'bi-gender-female'],
        ['label' => 'New this month', 'value' => $stats['new_this_month'], 'icon' => 'bi-person-plus'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Patient list</h5>
                <p>Newly registered patient accounts will automatically appear here for admin tracking and management.</p>
            </div>
            <span class="btn btn-light">Auto-synced from registration</span>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-5"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Patient code, name, phone, email..."></div>
            <div class="col-md-3">
                <select name="gender" class="form-select">
                    <option value="">All genders</option>
                    <option value="male" @selected(request('gender') == 'male')>Nam</option>
                    <option value="female" @selected(request('gender') == 'female')>Female</option>
                    <option value="other" @selected(request('gender') == 'other')>Other</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
            <div class="col-md-2 d-grid"><a href="{{ route('admin.patients.index') }}" class="btn btn-light">Reset</a></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date of birth</th>
                        <th>Gender</th>
                        <th>Appointment count</th>
                        <th>Address</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $patient->name }}</strong>
                                        <small>{{ $patient->patient_code ?? 'BN-0000' }} · {{ $patient->phone ?: ($patient->user->phone ?? 'No phone number yet') }}</small><br>
                                        <small>{{ $patient->email ?: ($patient->user->email ?? 'No email yet') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ optional($patient->date_of_birth)->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ match($patient->gender){'male' => 'Male', 'female' => 'Female', default => 'Other'} }}</td>
                            <td>{{ $patient->appointments_count }}</td>
                            <td>{{ $patient->address ?? '—' }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.patients.show', $patient) }}">Profile</a>
                                <a href="{{ route('admin.patients.edit', $patient) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" class="action-form" onsubmit="return confirm('Are you sure you want to delete this patient account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link text-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No patient data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $patients->links() }}
    </div>
@endsection
