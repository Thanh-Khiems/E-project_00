@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(!$tableReady)
        <div class="alert alert-warning">
            The locations table has not been created yet. Please run <code>php artisan migrate</code> and reload this page.
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>An error occurred:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-xl-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-3">Add new city</h4>
                    <p class="text-muted mb-4">Enter the city, then add districts and the list of wards/communes. After saving, the data will automatically appear in the registration and profile edit forms.</p>

                    <form method="POST" action="{{ route('admin.locations.store') }}" class="location-form" data-form-type="create">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">City name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Example: Hai Phong" {{ !$tableReady ? 'disabled' : '' }}>
                        </div>

                        <div class="district-list" data-district-list>
                            @php
                                $oldDistricts = old('districts', [['name' => '', 'wards' => '']]);
                            @endphp
                            @foreach($oldDistricts as $index => $district)
                                <div class="border rounded-4 p-3 mb-3 district-item" data-district-item>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong>District {{ $loop->iteration }}</strong>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-remove-district>Delete</button>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">District name</label>
                                        <input type="text" class="form-control" name="districts[{{ $index }}][name]" value="{{ $district['name'] ?? '' }}" placeholder="Example: Le Chan" {{ !$tableReady ? 'disabled' : '' }}>
                                    </div>

                                    <div>
                                        <label class="form-label">Ward/Commune</label>
                                        <textarea class="form-control" rows="4" name="districts[{{ $index }}][wards]" placeholder="Separate wards/communes with commas or line breaks" {{ !$tableReady ? 'disabled' : '' }}>{{ $district['wards'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" data-add-district {{ !$tableReady ? 'disabled' : '' }}>
                                <i class="bi bi-plus-circle me-1"></i>Add district
                            </button>
                            <button type="submit" class="btn btn-primary" {{ !$tableReady ? 'disabled' : '' }}>
                                <i class="bi bi-save me-1"></i>Save city
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">City list</h4>
                        <span class="badge text-bg-primary">{{ $cities->count() }} cities</span>
                    </div>

                    @forelse($cities as $city)
                        <div class="border rounded-4 p-3 p-md-4 mb-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $city->name }}</h5>
                                    <div class="text-muted small">
                                        {{ count($city->districts ?? []) }} districts
                                        •
                                        {{ collect($city->districts ?? [])->flatten()->count() }} wards/communes
                                    </div>
                                </div>
                                <form action="{{ route('admin.locations.destroy', $city) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this city?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('admin.locations.update', $city) }}" class="location-form" data-form-type="edit">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">City name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') === $city->name ? old('name') : $city->name }}">
                                </div>

                                <div class="district-list" data-district-list>
                                    @foreach(($city->districts ?? []) as $districtName => $wards)
                                        <div class="border rounded-4 p-3 mb-3 district-item" data-district-item>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <strong>{{ $districtName }}</strong>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-remove-district>Delete</button>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">District name</label>
                                                <input type="text" class="form-control" name="districts[{{ $loop->index }}][name]" value="{{ $districtName }}">
                                            </div>

                                            <div>
                                                <label class="form-label">Ward/Commune</label>
                                                <textarea class="form-control" rows="4" name="districts[{{ $loop->index }}][wards]" placeholder="Separate wards/communes with commas or line breaks">{{ implode(', ', $wards) }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary" data-add-district>
                                        <i class="bi bi-plus-circle me-1"></i>Add district
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check2-circle me-1"></i>Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="text-muted">There are no cities in the system yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<template id="district-template">
    <div class="border rounded-4 p-3 mb-3 district-item" data-district-item>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <strong class="district-title">New district</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-district>Delete</button>
        </div>

        <div class="mb-3">
            <label class="form-label">District name</label>
            <input type="text" class="form-control" data-name-field placeholder="Example: Ninh Kieu">
        </div>

        <div>
            <label class="form-label">Ward/Commune</label>
            <textarea class="form-control" rows="4" data-wards-field placeholder="Separate wards/communes with commas or line breaks"></textarea>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const template = document.getElementById('district-template');

        function reindex(form) {
            const items = form.querySelectorAll('[data-district-item]');
            items.forEach((item, index) => {
                const title = item.querySelector('.district-title');
                if (title && !title.dataset.staticTitle) {
                    title.textContent = `District ${index + 1}`;
                }

                const nameField = item.querySelector('[data-name-field], input[name*="[name]"]');
                const wardsField = item.querySelector('[data-wards-field], textarea[name*="[wards]"]');

                if (nameField) {
                    nameField.name = `districts[${index}][name]`;
                }
                if (wardsField) {
                    wardsField.name = `districts[${index}][wards]`;
                }
            });
        }

        function bindForm(form) {
            const list = form.querySelector('[data-district-list]');
            const addBtn = form.querySelector('[data-add-district]');

            addBtn?.addEventListener('click', function () {
                const clone = template.content.firstElementChild.cloneNode(true);
                list.appendChild(clone);
                reindex(form);
            });

            form.addEventListener('click', function (event) {
                const removeBtn = event.target.closest('[data-remove-district]');
                if (!removeBtn) return;

                const item = removeBtn.closest('[data-district-item]');
                const items = form.querySelectorAll('[data-district-item]');

                if (items.length === 1) {
                    const nameInput = item.querySelector('input');
                    const wardsInput = item.querySelector('textarea');
                    if (nameInput) nameInput.value = '';
                    if (wardsInput) wardsInput.value = '';
                    return;
                }

                item.remove();
                reindex(form);
            });

            reindex(form);
        }

        document.querySelectorAll('.location-form').forEach(bindForm);
    });
</script>
@endsection
