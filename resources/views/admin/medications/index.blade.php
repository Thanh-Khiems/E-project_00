@extends('admin.layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel-card">
            <div class="panel-head">
                <div>
                    <h5>Add new medication</h5>
                    <p>The admin manages the medication catalog, and doctors use this catalog when prescribing.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.medications.store') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Medication name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Strength / dosage form</label>
                    <input type="text" name="dosage" class="form-control" placeholder="500mg, siro 60ml..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Medication group</label>
                    <select name="medicine_type_id" class="form-select">
                        <option value="">-- Select medication group --</option>
                        @foreach($medicineTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Antibiotics, pain relievers...">
                </div>
                <button type="submit" class="btn btn-primary">Add medication</button>
            </form>

            <hr class="my-4">

            <h6>Add medication group</h6>
            <form method="POST" action="{{ route('admin.medicine-types.store') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Medication group name</label>
                    <input type="text" name="name" class="form-control" placeholder="Antibiotics" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-outline-primary">Add medication group</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel-card">
            <div class="panel-head">
                <div>
                    <h5>Medication catalog</h5>
                    <p>The standard medication list for doctors to choose from when prescribing.</p>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Medication name</th>
                            <th>Strength</th>
                            <th>Medication group</th>
                            <th>Category</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medications as $medication)
                            <tr>
                                <td>{{ $medication->name }}</td>
                                <td>{{ $medication->dosage }}</td>
                                <td>{{ $medication->medicineType->name ?? '—' }}</td>
                                <td>{{ $medication->category ?? '—' }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.medications.destroy', $medication) }}" onsubmit="return confirm('Delete this medication from the catalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">There are no medications in the catalog yet.</td></tr>
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
