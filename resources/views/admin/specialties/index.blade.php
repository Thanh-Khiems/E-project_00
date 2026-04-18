@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total specialties', 'value' => $stats['total'], 'icon' => 'bi-grid'],
        ['label' => 'Visible', 'value' => $stats['visible'], 'icon' => 'bi-eye'],
        ['label' => 'Hidden', 'value' => $stats['hidden'], 'icon' => 'bi-eye-slash'],
        ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'bi-award'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Specialty list</h5>
                <p>Manage service descriptions, doctor counts, and appointment capacity.</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#specialtyCreateForm" aria-expanded="false" aria-controls="specialtyCreateForm">+ Add specialty</button>
        </div>



        <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="specialtyCreateForm">
            <div class="info-card">
                <h5 class="mb-3">Add new specialty</h5>
                <form method="POST" action="{{ route('admin.specialties.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Specialty name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Example: Endocrinology" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" @selected(old('status', 'active') === 'active')>Visible</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured'))>
                            <label class="form-check-label" for="is_featured">Mark as featured</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Short description for the specialty...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Save specialty</button>
                    </div>
                </form>
            </div>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-10"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search by specialty name or description..."></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Search</button></div>
        </form>

        <div class="row g-4 mt-1">
            @forelse($specialties as $specialty)
                <div class="col-md-6 col-xl-4">
                    <div class="info-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-soft"><i class="bi bi-heart-pulse"></i></div>
                            <span class="status-badge {{ $specialty->status }}">{{ $specialty->status === 'active' ? 'Visible' : 'Hidden' }}</span>
                        </div>
                        <h5>{{ $specialty->name }}</h5>
                        <p>{{ $specialty->description ?? 'No description for this specialty yet.' }}</p>
                        <div class="meta-grid">
                            <div><strong>{{ $specialty->doctors_count }}</strong><span>Doctor</span></div>
                            <div><strong>{{ $specialty->appointments_count }}</strong><span>Appointments</span></div>
                        </div>
                        <div class="card-actions mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editSpecialty{{ $specialty->id }}" aria-expanded="false" aria-controls="editSpecialty{{ $specialty->id }}">Edit</button>
                            <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}" onsubmit="return confirm('Are you sure you want to delete the specialty {{ addslashes($specialty->name) }}? Doctor data will be unassigned from this specialty.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>

                        <div class="collapse mt-3" id="editSpecialty{{ $specialty->id }}">
                            <div class="border-top pt-3">
                                <h6 class="mb-3">Edit specialty</h6>
                                <form method="POST" action="{{ route('admin.specialties.update', $specialty) }}" class="row g-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-12">
                                        <label class="form-label">Specialty name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name_' . $specialty->id, $specialty->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="active" @selected(old('status_' . $specialty->id, $specialty->status) === 'active')>Visible</option>
                                            <option value="inactive" @selected(old('status_' . $specialty->id, $specialty->status) === 'inactive')>Hidden</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured_{{ $specialty->id }}" @checked((bool) old('is_featured_' . $specialty->id, $specialty->is_featured))>
                                            <label class="form-check-label" for="featured_{{ $specialty->id }}">Mark as featured</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description_' . $specialty->id, $specialty->description) }}</textarea>
                                    </div>
                                    <div class="col-12 d-flex gap-2">
                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editSpecialty{{ $specialty->id }}" aria-expanded="true" aria-controls="editSpecialty{{ $specialty->id }}">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-box">There are no specialties yet.</div></div>
            @endforelse
        </div>

        <div class="mt-4">{{ $specialties->links() }}</div>
    </div>
@endsection
