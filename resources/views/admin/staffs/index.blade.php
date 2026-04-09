@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Tổng nhân viên', 'value' => $stats['total'], 'icon' => 'bi-person-workspace'],
        ['label' => 'Đang làm việc', 'value' => $stats['working'], 'icon' => 'bi-briefcase'],
        ['label' => 'Đang nghỉ phép', 'value' => $stats['leave'], 'icon' => 'bi-airplane'],
        ['label' => 'Quản trị viên', 'value' => $stats['admin'], 'icon' => 'bi-shield-lock'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Danh sách nhân viên</h5>
                <p>Quản lý nhân sự lễ tân, điều dưỡng, kế toán, kỹ thuật viên và quản trị viên.</p>
            </div>
            <button class="btn btn-primary">+ Thêm nhân viên</button>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-5"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên, email, số điện thoại, phòng ban..."></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="working" @selected(request('status') == 'working')>Đang làm việc</option>
                    <option value="leave" @selected(request('status') == 'leave')>Nghỉ phép</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Ngưng hoạt động</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Lọc</button></div>
            <div class="col-md-2 d-grid"><button type="button" class="btn btn-light">Phân quyền</button></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Nhân viên</th>
                        <th>Vai trò</th>
                        <th>Phòng ban</th>
                        <th>Ca làm</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs as $staff)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ strtoupper(substr($staff->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $staff->name }}</strong>
                                        <small>{{ $staff->email }} · {{ $staff->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ ucfirst($staff->role ?? 'staff') }}</td>
                            <td>{{ $staff->department ?? '—' }}</td>
                            <td>{{ $staff->shift ?? 'Hành chính' }}</td>
                            <td><span class="status-badge {{ $staff->status }}">{{ $staff->status }}</span></td>
                            <td class="text-end table-actions">
                                <a href="#">Chi tiết</a>
                                <a href="#">Cập nhật</a>
                                <a href="#" class="text-danger">Ngưng</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Chưa có dữ liệu nhân viên.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $staffs->links() }}
    </div>
@endsection
