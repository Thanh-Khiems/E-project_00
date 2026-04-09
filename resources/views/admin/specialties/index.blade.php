@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Tổng chuyên khoa', 'value' => $stats['total'], 'icon' => 'bi-grid'],
        ['label' => 'Đang hiển thị', 'value' => $stats['visible'], 'icon' => 'bi-eye'],
        ['label' => 'Đang ẩn', 'value' => $stats['hidden'], 'icon' => 'bi-eye-slash'],
        ['label' => 'Nổi bật', 'value' => $stats['featured'], 'icon' => 'bi-award'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Danh sách chuyên khoa</h5>
                <p>Quản lý mô tả dịch vụ, số lượng bác sĩ và khả năng tiếp nhận lịch hẹn.</p>
            </div>
            <button class="btn btn-primary">+ Thêm chuyên khoa</button>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-10"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên hoặc mô tả chuyên khoa..."></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Tìm kiếm</button></div>
        </form>

        <div class="row g-4 mt-1">
            @forelse($specialties as $specialty)
                <div class="col-md-6 col-xl-4">
                    <div class="info-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-soft"><i class="bi bi-heart-pulse"></i></div>
                            <span class="status-badge {{ $specialty->status }}">{{ $specialty->status === 'active' ? 'Hiển thị' : 'Tạm ẩn' }}</span>
                        </div>
                        <h5>{{ $specialty->name }}</h5>
                        <p>{{ $specialty->description ?? 'Chưa có mô tả cho chuyên khoa này.' }}</p>
                        <div class="meta-grid">
                            <div><strong>{{ $specialty->doctors_count }}</strong><span>Bác sĩ</span></div>
                            <div><strong>{{ $specialty->appointments_count }}</strong><span>Lịch hẹn</span></div>
                        </div>
                        <div class="card-actions mt-3">
                            <a href="#">Cập nhật</a>
                            <a href="#">Quản lý bác sĩ</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-box">Chưa có chuyên khoa nào.</div></div>
            @endforelse
        </div>

        <div class="mt-4">{{ $specialties->links() }}</div>
    </div>
@endsection
