@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Chờ duyệt', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split'],
        ['label' => 'Đã duyệt', 'value' => $stats['approved'], 'icon' => 'bi-check2-circle'],
        ['label' => 'Từ chối', 'value' => $stats['rejected'], 'icon' => 'bi-x-circle'],
        ['label' => 'Tổng bác sĩ', 'value' => $stats['total'], 'icon' => 'bi-person-badge'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Xác thực / duyệt bác sĩ</h5>
                <p>Khi có bác sĩ gửi thông tin đăng ký, admin có thể vào đây để đồng ý hoặc từ chối.</p>
            </div>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-6">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên, email, số điện thoại...">
            </div>
            <div class="col-md-4">
                <select name="approval_status" class="form-select">
                    <option value="pending" @selected($approvalStatus === 'pending')>Chờ duyệt</option>
                    <option value="approved" @selected($approvalStatus === 'approved')>Đã duyệt</option>
                    <option value="rejected" @selected($approvalStatus === 'rejected')>Từ chối</option>
                    <option value="all" @selected($approvalStatus === 'all')>Tất cả</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Lọc dữ liệu</button>
            </div>
        </form>

        <div class="mt-4 d-flex flex-column gap-3">
            @forelse($doctors as $doctor)
                <div class="border rounded-4 p-4 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1">{{ $doctor->name }}</h5>
                            <div class="text-muted small">{{ $doctor->email ?: 'Chưa có email' }} · {{ $doctor->phone ?: 'Chưa có số điện thoại' }}</div>
                            <div class="mt-2 small">
                                <strong>Chuyên khoa:</strong> {{ $doctor->specialty->name ?? '—' }}<br>
                                <strong>Kinh nghiệm:</strong> {{ $doctor->experience_years ?? 0 }} năm<br>
                                <strong>Lịch làm việc:</strong> {{ $doctor->schedule_text ?? 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <div>
                            @if($doctor->approval_status === 'approved')
                                <span class="badge text-bg-success">Đã duyệt</span>
                            @elseif($doctor->approval_status === 'rejected')
                                <span class="badge text-bg-danger">Từ chối</span>
                            @else
                                <span class="badge text-bg-warning">Chờ duyệt</span>
                            @endif
                        </div>
                    </div>

                    @if($doctor->approval_note)
                        <div class="alert alert-light border mt-3 mb-0">
                            <strong>Ghi chú từ chối:</strong> {{ $doctor->approval_note }}
                        </div>
                    @endif

                    <div class="row g-3 mt-1 align-items-end">
                        <div class="col-lg-8">
                            <form method="POST" action="{{ route('admin.doctors.reject', $doctor) }}">
                                @csrf
                                <label class="form-label">Lý do từ chối</label>
                                <div class="input-group">
                                    <input type="text" name="approval_note" class="form-control" placeholder="Nhập lý do nếu từ chối hồ sơ..." value="{{ old('approval_note') }}">
                                    <button type="submit" class="btn btn-outline-danger">Từ chối</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <form method="POST" action="{{ route('admin.doctors.approve', $doctor) }}">
                                @csrf
                                <button type="submit" class="btn btn-success">Đồng ý duyệt</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">Không có hồ sơ bác sĩ nào trong mục này.</div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $doctors->links() }}
        </div>
    </div>
@endsection
