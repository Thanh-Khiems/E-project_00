@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total degrees', 'value' => $stats['total'], 'icon' => 'bi-mortarboard'],
        ['label' => 'Visible', 'value' => $stats['visible'], 'icon' => 'bi-eye'],
        ['label' => 'Hidden', 'value' => $stats['hidden'], 'icon' => 'bi-eye-slash'],
        ['label' => 'Used', 'value' => $stats['used'], 'icon' => 'bi-person-badge'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Degree list</h5>
                <p>Admins can add new degrees for doctors to choose when submitting applications, and patients will see those degrees on doctor profiles.</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#degreeCreateForm" aria-expanded="false" aria-controls="degreeCreateForm">+ Add degree</button>
        </div>

        <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="degreeCreateForm">
            <div class="info-card">
                <h5 class="mb-3">Add new degree</h5>
                <form method="POST" action="{{ route('admin.degrees.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Degree name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Example: Specialist Doctor I" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" @selected(old('status', 'active') === 'active')>Visible</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Hidden</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Short description for the degree...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Save degree</button>
                    </div>
                </form>
            </div>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-10"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search by degree name or description..."></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Search</button></div>
        </form>

        <div class="row g-4 mt-1">
            @forelse($degrees as $degree)
                <div class="col-md-6 col-xl-4">
                    <div class="info-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-soft"><i class="bi bi-mortarboard"></i></div>
                            <span class="status-badge {{ $degree->status }}">{{ $degree->status === 'active' ? 'Visible' : 'Hidden' }}</span>
                        </div>
                        <h5>{{ $degree->name }}</h5>
                        <p>{{ $degree->description ?? 'No description for this degree yet.' }}</p>

                        <div class="card-actions mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editDegree{{ $degree->id }}" aria-expanded="false" aria-controls="editDegree{{ $degree->id }}">Edit</button>
                            <form method="POST" action="{{ route('admin.degrees.destroy', $degree) }}" onsubmit="return confirm('Are you sure you want to delete degree {{ addslashes($degree->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>

                        <div class="collapse mt-3" id="editDegree{{ $degree->id }}">
                            <div class="border-top pt-3">
                                <h6 class="mb-3">Edit degree</h6>
                                <form method="POST" action="{{ route('admin.degrees.update', $degree) }}" class="row g-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-12">
                                        <label class="form-label">Degree name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name_' . $degree->id, $degree->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="active" @selected(old('status_' . $degree->id, $degree->status) === 'active')>Visible</option>
                                            <option value="inactive" @selected(old('status_' . $degree->id, $degree->status) === 'inactive')>Hidden</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description_' . $degree->id, $degree->description) }}</textarea>
                                    </div>
                                    <div class="col-12 d-flex gap-2">
                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editDegree{{ $degree->id }}" aria-expanded="true" aria-controls="editDegree{{ $degree->id }}">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-box">There are no degrees yet.</div></div>
            @endforelse
        </div>

        <div class="mt-4">{{ $degrees->links() }}</div>
    </div>
@endsection
