@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(!$tableReady)
        <div class="alert alert-warning">
            Bảng khu vực chưa được tạo. Hãy chạy <code>php artisan migrate</code> rồi tải lại trang này.
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Có lỗi xảy ra:</strong>
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
                    <h4 class="mb-3">Thêm thành phố mới</h4>
                    <p class="text-muted mb-4">Nhập thành phố, rồi thêm các quận/huyện và danh sách phường/xã. Sau khi lưu, dữ liệu sẽ tự động hiện ở form đăng ký và chỉnh sửa hồ sơ.</p>

                    <form method="POST" action="{{ route('admin.locations.store') }}" class="location-form" data-form-type="create">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tên thành phố</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Hải Phòng" {{ !$tableReady ? 'disabled' : '' }}>
                        </div>

                        <div class="district-list" data-district-list>
                            @php
                                $oldDistricts = old('districts', [['name' => '', 'wards' => '']]);
                            @endphp
                            @foreach($oldDistricts as $index => $district)
                                <div class="border rounded-4 p-3 mb-3 district-item" data-district-item>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong>Quận/Huyện {{ $loop->iteration }}</strong>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-remove-district>Xóa</button>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tên quận/huyện</label>
                                        <input type="text" class="form-control" name="districts[{{ $index }}][name]" value="{{ $district['name'] ?? '' }}" placeholder="Ví dụ: Lê Chân" {{ !$tableReady ? 'disabled' : '' }}>
                                    </div>

                                    <div>
                                        <label class="form-label">Phường/Xã</label>
                                        <textarea class="form-control" rows="4" name="districts[{{ $index }}][wards]" placeholder="Mỗi phường/xã cách nhau bằng dấu phẩy hoặc xuống dòng" {{ !$tableReady ? 'disabled' : '' }}>{{ $district['wards'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" data-add-district {{ !$tableReady ? 'disabled' : '' }}>
                                <i class="bi bi-plus-circle me-1"></i>Thêm quận/huyện
                            </button>
                            <button type="submit" class="btn btn-primary" {{ !$tableReady ? 'disabled' : '' }}>
                                <i class="bi bi-save me-1"></i>Lưu thành phố
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
                        <h4 class="mb-0">Danh sách thành phố</h4>
                        <span class="badge text-bg-primary">{{ $cities->count() }} thành phố</span>
                    </div>

                    @forelse($cities as $city)
                        <div class="border rounded-4 p-3 p-md-4 mb-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $city->name }}</h5>
                                    <div class="text-muted small">
                                        {{ count($city->districts ?? []) }} quận/huyện
                                        •
                                        {{ collect($city->districts ?? [])->flatten()->count() }} phường/xã
                                    </div>
                                </div>
                                <form action="{{ route('admin.locations.destroy', $city) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa thành phố này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('admin.locations.update', $city) }}" class="location-form" data-form-type="edit">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Tên thành phố</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') === $city->name ? old('name') : $city->name }}">
                                </div>

                                <div class="district-list" data-district-list>
                                    @foreach(($city->districts ?? []) as $districtName => $wards)
                                        <div class="border rounded-4 p-3 mb-3 district-item" data-district-item>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <strong>{{ $districtName }}</strong>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-remove-district>Xóa</button>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Tên quận/huyện</label>
                                                <input type="text" class="form-control" name="districts[{{ $loop->index }}][name]" value="{{ $districtName }}">
                                            </div>

                                            <div>
                                                <label class="form-label">Phường/Xã</label>
                                                <textarea class="form-control" rows="4" name="districts[{{ $loop->index }}][wards]" placeholder="Mỗi phường/xã cách nhau bằng dấu phẩy hoặc xuống dòng">{{ implode(', ', $wards) }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary" data-add-district>
                                        <i class="bi bi-plus-circle me-1"></i>Thêm quận/huyện
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check2-circle me-1"></i>Cập nhật
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="text-muted">Chưa có thành phố nào trong hệ thống.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<template id="district-template">
    <div class="border rounded-4 p-3 mb-3 district-item" data-district-item>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <strong class="district-title">Quận/Huyện mới</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-district>Xóa</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Tên quận/huyện</label>
            <input type="text" class="form-control" data-name-field placeholder="Ví dụ: Ninh Kiều">
        </div>

        <div>
            <label class="form-label">Phường/Xã</label>
            <textarea class="form-control" rows="4" data-wards-field placeholder="Mỗi phường/xã cách nhau bằng dấu phẩy hoặc xuống dòng"></textarea>
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
                    title.textContent = `Quận/Huyện ${index + 1}`;
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
