@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Edit patient</h5>
                <p>Update the patient profile information and linked account.</p>
            </div>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-primary">Back</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $currentProvince = old('province', $patient->user?->province ?? '');
            $currentDistrict = old('district', $patient->user?->district ?? '');
            $currentWard = old('ward', $patient->user?->ward ?? '');

            $districtOptions = $currentProvince ? array_keys($locations[$currentProvince] ?? []) : [];
            $wardOptions = ($currentProvince && $currentDistrict)
                ? ($locations[$currentProvince][$currentDistrict] ?? [])
                : [];
        @endphp

        <form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="mt-3">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full name</label>
                    <input type="text" name="full_name" class="form-control"
                        value="{{ old('full_name', $patient->user?->full_name ?? $patient->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $patient->user?->email ?? $patient->email) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phone number</label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', $patient->user?->phone ?? $patient->phone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    @php($selectedGender = old('gender', $patient->gender))
                    <select name="gender" class="form-select">
                        <option value="">Select gender</option>
                        <option value="male" @selected($selectedGender === 'male')>Nam</option>
                        <option value="female" @selected($selectedGender === 'female')>Female</option>
                        <option value="other" @selected($selectedGender === 'other')>Other</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date of birth</label>
                    <input type="date" name="dob" class="form-control"
                        value="{{ old('dob', optional($patient->date_of_birth)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Account role</label>
                    @php($selectedRole = old('role', $patient->user?->role ?? 'user'))
                    <select name="role" class="form-select">
                        <option value="user" @selected($selectedRole === 'user')>User</option>
                        <option value="admin" @selected($selectedRole === 'admin')>Admin</option>
                    </select>
                    <small class="text-muted">Changing to admin will move this account data to the staff table.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Province/City</label>
                    <select name="province" id="province" class="form-select">
                        <option value="">Select province/city</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province }}" @selected($currentProvince === $province)>
                                {{ $province }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">District</label>
                    <select name="district" id="district" class="form-select">
                        <option value="">Select district</option>
                        @foreach ($districtOptions as $district)
                            <option value="{{ $district }}" @selected($currentDistrict === $district)>
                                {{ $district }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ward/Commune</label>
                    <select name="ward" id="ward" class="form-select">
                        <option value="">Select ward/commune</option>
                        @foreach ($wardOptions as $ward)
                            <option value="{{ $ward }}" @selected($currentWard === $ward)>
                                {{ $ward }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Detailed address</label>
                    <input type="text" name="address_detail" class="form-control"
                        value="{{ old('address_detail', $patient->user?->address_detail) }}"
                        placeholder="House number, street name...">
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-light">View profile</a>
            </div>
        </form>
    </div>

    <script>
        const locations = @json($locations);
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        const selectedProvince = @json(old('province', $patient->user?->province ?? ''));
        const selectedDistrict = @json(old('district', $patient->user?->district ?? ''));
        const selectedWard = @json(old('ward', $patient->user?->ward ?? ''));

        function fillSelect(select, items, placeholder, selectedValue = '') {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                if (selectedValue === item) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        }

        provinceSelect.addEventListener('change', function () {
            const districts = Object.keys(locations[this.value] || {});
            fillSelect(districtSelect, districts, 'Select district');
            fillSelect(wardSelect, [], 'Select ward/commune');
        });

        districtSelect.addEventListener('change', function () {
            const wards = locations[provinceSelect.value]?.[this.value] || [];
            fillSelect(wardSelect, wards, 'Select ward/commune');
        });

        if (selectedProvince) {
            fillSelect(
                districtSelect,
                Object.keys(locations[selectedProvince] || {}),
                'Select district',
                selectedDistrict
            );

            if (selectedDistrict) {
                fillSelect(
                    wardSelect,
                    locations[selectedProvince]?.[selectedDistrict] || [],
                    'Select ward/commune',
                    selectedWard
                );
            }
        }
    </script>
@endsection