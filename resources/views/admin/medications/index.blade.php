@extends('admin.layouts.app')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <strong>Please check the information again.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="panel-card mb-4">
                <div class="panel-head">
                    <div>
                        <h5>Add new medication</h5>
                        <p>The admin manages the medication catalog, and doctors use this catalog when prescribing.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.medications.store') }}" class="mt-3 row g-3">
                    @csrf
                    <input type="hidden" name="form_type" value="medication_create">
                    <div class="col-12">
                        <label class="form-label">Medication name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('form_type') === 'medication_create' ? old('name') : '' }}" placeholder="Example: Paracetamol" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Strength / dosage form</label>
                        <input type="text" name="dosage" class="form-control" value="{{ old('form_type') === 'medication_create' ? old('dosage') : '' }}" placeholder="500mg tablet, syrup 60ml..." required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Medication group</label>
                        <select name="medicine_type_id" class="form-select">
                            <option value="">-- Select medication group --</option>
                            @foreach($medicineTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('form_type') === 'medication_create' && (string) old('medicine_type_id') === (string) $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ old('form_type') === 'medication_create' ? old('category') : '' }}" placeholder="Pain reliever, antibiotic, anti-inflammatory...">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add medication</button>
                    </div>
                </form>
            </div>

            <div class="panel-card">
                <div class="panel-head">
                    <div>
                        <h5>Medication groups</h5>
                        <p>Create and manage the groups used to organize the medication catalog.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.medicine-types.store') }}" class="mt-3 row g-3">
                    @csrf
                    <input type="hidden" name="form_type" value="group_create">
                    <div class="col-12">
                        <label class="form-label">Medication group name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('form_type') === 'group_create' ? old('name') : '' }}" placeholder="Antibiotics" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Short description for this medication group...">{{ old('form_type') === 'group_create' ? old('description') : '' }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">Add medication group</button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Existing groups</h6>
                    <span class="badge text-bg-light">{{ $medicineTypes->count() }} groups</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($medicineTypes as $type)
                        <div class="border rounded-4 p-3">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $type->name }}</div>
                                    <div class="text-muted small mt-1">
                                        {{ $type->medications_count }} medication{{ $type->medications_count === 1 ? '' : 's' }}
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap justify-content-end">
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editType{{ $type->id }}" aria-expanded="false" aria-controls="editType{{ $type->id }}">Edit</button>
                                    <form method="POST" action="{{ route('admin.medicine-types.destroy', $type) }}" onsubmit="return confirm('Delete medication group {{ addslashes($type->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </div>

                            @if($type->description)
                                <p class="text-muted small mb-0 mt-2">{{ old('form_type') === 'medicine_type_update_' . $type->id ? old('description') : $type->description }}</p>
                            @endif

                            <div class="collapse mt-3 {{ old('form_type') === 'medicine_type_update_' . $type->id ? 'show' : '' }}" id="editType{{ $type->id }}">
                                <div class="border-top pt-3">
                                    <h6 class="mb-3">Edit medication group</h6>
                                    <form method="POST" action="{{ route('admin.medicine-types.update', $type) }}" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="form_type" value="medicine_type_update_{{ $type->id }}">
                                        <div class="col-12">
                                            <label class="form-label">Group name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('form_type') === 'medicine_type_update_' . $type->id ? old('name') : $type->name }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" rows="3" class="form-control">{{ old('form_type') === 'medicine_type_update_' . $type->id ? old('description') : $type->description }}</textarea>
                                        </div>
                                        <div class="col-12 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editType{{ $type->id }}" aria-expanded="true" aria-controls="editType{{ $type->id }}">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">There are no medication groups yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="panel-card">
                <div class="panel-head">
                    <div>
                        <h5>Medication catalog</h5>
                        <p>The standard medication list for doctors to choose from when prescribing.</p>
                    </div>
                    <span class="badge text-bg-light">{{ $medications->total() }} medications</span>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Medication name</th>
                                <th>Strength</th>
                                <th>Medication group</th>
                                <th>Category</th>
                                <th>Usage</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medications as $medication)
                                <tr>
                                    <td class="fw-semibold">{{ $medication->name }}</td>
                                    <td>{{ $medication->dosage }}</td>
                                    <td>{{ $medication->medicineType->name ?? '—' }}</td>
                                    <td>{{ $medication->category ?? '—' }}</td>
                                    <td>
                                        @if($medication->prescription_items_count > 0)
                                            <span class="badge text-bg-light">Used {{ $medication->prescription_items_count }} time{{ $medication->prescription_items_count === 1 ? '' : 's' }}</span>
                                        @else
                                            <span class="text-muted small">Not used yet</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editMedication{{ $medication->id }}" aria-expanded="false" aria-controls="editMedication{{ $medication->id }}">Edit</button>
                                            <form method="POST" action="{{ route('admin.medications.destroy', $medication) }}" onsubmit="return confirm('Delete medication {{ addslashes($medication->name) }} from the catalog?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse-row">
                                    <td colspan="6" class="border-0 pt-0 pb-3">
                                        <div class="collapse {{ old('form_type') === 'medication_update_' . $medication->id ? 'show' : '' }}" id="editMedication{{ $medication->id }}">
                                            <div class="border rounded-4 p-3 p-md-4 bg-light-subtle">
                                                <div class="d-flex justify-content-between align-items-center mb-3 gap-3">
                                                    <h6 class="mb-0">Edit medication</h6>
                                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editMedication{{ $medication->id }}" aria-expanded="true" aria-controls="editMedication{{ $medication->id }}">Close</button>
                                                </div>

                                                <form method="POST" action="{{ route('admin.medications.update', $medication) }}" class="row g-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="form_type" value="medication_update_{{ $medication->id }}">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Medication name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ old('form_type') === 'medication_update_' . $medication->id ? old('name') : $medication->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Strength / dosage form</label>
                                                        <input type="text" name="dosage" class="form-control" value="{{ old('form_type') === 'medication_update_' . $medication->id ? old('dosage') : $medication->dosage }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Medication group</label>
                                                        <select name="medicine_type_id" class="form-select">
                                                            <option value="">-- Select medication group --</option>
                                                            @foreach($medicineTypes as $type)
                                                                <option value="{{ $type->id }}" @selected((string) (old('form_type') === 'medication_update_' . $medication->id ? old('medicine_type_id') : $medication->medicine_type_id) === (string) $type->id)>
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Category</label>
                                                        <input type="text" name="category" class="form-control" value="{{ old('form_type') === 'medication_update_' . $medication->id ? old('category') : $medication->category }}" placeholder="Pain reliever, antibiotic...">
                                                    </div>
                                                    <div class="col-12 d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">There are no medications in the catalog yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $medications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
