@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Tổng bằng cấp', 'value' => $stats['total'], 'icon' => 'bi-mortarboard'],
        ['label' => 'Đang hiển thị', 'value' => $stats['visible'], 'icon' => 'bi-eye'],
        ['label' => 'Đang ẩn', 'value' => $stats['hidden'], 'icon' => 'bi-eye-slash'],
        ['label' => 'Được sử dụng', 'value' => $stats['used'], 'icon' => 'bi-person-badge'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Danh sách bằng cấp</h5>
                <p>Admin có thể thêm bằng cấp mới để bác sĩ chọn khi gửi hồ sơ, và bệnh nhân sẽ thấy bằng cấp đó trên hồ sơ bác sĩ.</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#degreeCreateForm" aria-expanded="false" aria-controls="degreeCreateForm">+ Thêm bằng cấp</button>
        </div>

        <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="degreeCreateForm">
            <div class="info-card">
                <h5 class="mb-3">Thêm bằng cấp mới</h5>
                <form method="POST" action="{{ route('admin.degrees.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Tên bằng cấp</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Bác sĩ chuyên khoa I" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" @selected(old('status', 'active') === 'active')>Hiển thị</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Tạm ẩn</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả ngắn về bằng cấp...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Lưu bằng cấp</button>
                    </div>
                </form>
            </div>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-10"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên hoặc mô tả bằng cấp..."></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Tìm kiếm</button></div>
        </form>

        <div class="row g-4 mt-1">
            @forelse($degrees as $degree)
                <div class="col-md-6 col-xl-4">
                    <div class="info-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-soft"><i class="bi bi-mortarboard"></i></div>
                            <span class="status-badge {{ $degree->status }}">{{ $degree->status === 'active' ? 'Hiển thị' : 'Tạm ẩn' }}</span>
                        </div>
                        <h5>{{ $degree->name }}</h5>
                        <p>{{ $degree->description ?? 'Chưa có mô tả cho bằng cấp này.' }}</p>

                        <div class="card-actions mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editDegree{{ $degree->id }}" aria-expanded="false" aria-controls="editDegree{{ $degree->id }}">Sửa</button>
                            <form method="POST" action="{{ route('admin.degrees.destroy', $degree) }}" onsubmit="return confirm('Bạn có chắc muốn xóa bằng cấp {{ addslashes($degree->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                            </form>
                        </div>

                        <div class="collapse mt-3" id="editDegree{{ $degree->id }}">
                            <div class="border-top pt-3">
                                <h6 class="mb-3">Chỉnh sửa bằng cấp</h6>
                                <form method="POST" action="{{ route('admin.degrees.update', $degree) }}" class="row g-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-12">
                                        <label class="form-label">Tên bằng cấp</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name_' . $degree->id, $degree->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="active" @selected(old('status_' . $degree->id, $degree->status) === 'active')>Hiển thị</option>
                                            <option value="inactive" @selected(old('status_' . $degree->id, $degree->status) === 'inactive')>Tạm ẩn</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description_' . $degree->id, $degree->description) }}</textarea>
                                    </div>
                                    <div class="col-12 d-flex gap-2">
                                        <button class="btn btn-primary btn-sm" type="submit">Lưu thay đổi</button>
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editDegree{{ $degree->id }}" aria-expanded="true" aria-controls="editDegree{{ $degree->id }}">Đóng</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-box">Chưa có bằng cấp nào.</div></div>
            @endforelse
        </div>

        <div class="mt-4">{{ $degrees->links() }}</div>
    </div>
@endsection
