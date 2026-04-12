@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Chỉnh sửa bệnh nhân</h5>
                <p>Cập nhật thông tin hồ sơ bệnh nhân và tài khoản liên kết.</p>
            </div>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-primary">Quay lại</a>
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
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="full_name" class="form-control"
                        value="{{ old('full_name', $patient->user?->full_name ?? $patient->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $patient->user?->email ?? $patient->email) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', $patient->user?->phone ?? $patient->phone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Giới tính</label>
                    @php($selectedGender = old('gender', $patient->gender))
                    <select name="gender" class="form-select">
                        <option value="">Chọn giới tính</option>
                        <option value="male" @selected($selectedGender === 'male')>Nam</option>
                        <option value="female" @selected($selectedGender === 'female')>Nữ</option>
                        <option value="other" @selected($selectedGender === 'other')>Khác</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="dob" class="form-control"
                        value="{{ old('dob', optional($patient->date_of_birth)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tỉnh/Thành phố</label>
                    <select name="province" id="province" class="form-select">
                        <option value="">Chọn tỉnh/thành phố</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province }}" @selected($currentProvince === $province)>
                                {{ $province }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Quận/Huyện</label>
                    <select name="district" id="district" class="form-select">
                        <option value="">Chọn quận/huyện</option>
                        @foreach ($districtOptions as $district)
                            <option value="{{ $district }}" @selected($currentDistrict === $district)>
                                {{ $district }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phường/Xã</label>
                    <select name="ward" id="ward" class="form-select">
                        <option value="">Chọn phường/xã</option>
                        @foreach ($wardOptions as $ward)
                            <option value="{{ $ward }}" @selected($currentWard === $ward)>
                                {{ $ward }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Địa chỉ chi tiết</label>
                    <input type="text" name="address_detail" class="form-control"
                        value="{{ old('address_detail', $patient->user?->address_detail) }}"
                        placeholder="Số nhà, tên đường...">
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-light">Xem hồ sơ</a>
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
            fillSelect(districtSelect, districts, 'Chọn quận/huyện');
            fillSelect(wardSelect, [], 'Chọn phường/xã');
        });

        districtSelect.addEventListener('change', function () {
            const wards = locations[provinceSelect.value]?.[this.value] || [];
            fillSelect(wardSelect, wards, 'Chọn phường/xã');
        });

        if (selectedProvince) {
            fillSelect(
                districtSelect,
                Object.keys(locations[selectedProvince] || {}),
                'Chọn quận/huyện',
                selectedDistrict
            );

            if (selectedDistrict) {
                fillSelect(
                    wardSelect,
                    locations[selectedProvince]?.[selectedDistrict] || [],
                    'Chọn phường/xã',
                    selectedWard
                );
            }
        }
    </script>
@endsection