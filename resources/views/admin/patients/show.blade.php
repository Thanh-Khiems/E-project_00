@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Patient profile</h5>
                <p>View the patient account information and profile data.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">Edit information</a>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-primary">Back</a>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h5 class="mb-3">{{ $patient->name }}</h5>
                    <div class="small text-muted mb-3">
                        {{ $patient->patient_code ?: 'No patient code yet' }} · {{ $patient->email ?: 'No email yet' }}
                    </div>

                    <div class="mb-2"><strong>Phone number:</strong> {{ $patient->phone ?: '—' }}</div>
                    <div class="mb-2"><strong>Date of birth:</strong> {{ optional($patient->date_of_birth)->format('d/m/Y') ?? '—' }}</div>
                    <div class="mb-2"><strong>Gender:</strong> {{ match($patient->gender){'male' => 'Male', 'female' => 'Female', default => 'Other'} }}</div>
                    <div class="mb-2"><strong>Address:</strong> {{ $patient->address ?: '—' }}</div>
                    <div class="mb-2"><strong>Appointment count:</strong> {{ $patient->appointments->count() }}</div>
                    <div class="mb-2"><strong>Created at:</strong> {{ $patient->created_at?->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h6 class="mb-3">Account information</h6>
                    <div class="mb-2"><strong>User ID:</strong> {{ $patient->user?->id ?? 'Not linked' }}</div>
                    <div class="mb-2"><strong>Account full name:</strong> {{ $patient->user?->full_name ?? $patient->name }}</div>
                    <div class="mb-2"><strong>Login email:</strong> {{ $patient->user?->email ?? $patient->email ?? '—' }}</div>
                    <div class="mb-2"><strong>Role:</strong> {{ $patient->user?->role ?? 'patient' }}</div>
                    <div class="mb-2"><strong>Locations:</strong>
                        {{ collect([$patient->user?->ward, $patient->user?->district, $patient->user?->province])->filter()->implode(', ') ?: '—' }}
                    </div>
                    <div class="mb-2"><strong>Detailed address:</strong> {{ $patient->user?->address_detail ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-3">
            <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">Edit patient</a>
            <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" onsubmit="return confirm('Are you sure you want to delete this patient account?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete account</button>
            </form>
        </div>
    </div>
@endsection
