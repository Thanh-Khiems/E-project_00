@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Tổng bác sĩ', 'value' => $stats['total'], 'icon' => 'bi-person-badge'],
        ['label' => 'Đang hoạt động', 'value' => $stats['active'], 'icon' => 'bi-check-circle'],
        ['label' => 'Tạm khóa', 'value' => $stats['inactive'], 'icon' => 'bi-pause-circle'],
        ['label' => 'Chờ duyệt', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Danh sách bác sĩ</h5>
                <p>Quản lý hồ sơ, chuyên khoa, lịch làm việc và trạng thái xét duyệt.</p>
            </div>
            <a href="{{ route('admin.doctors.approvals') }}" class="btn btn-primary">Xem duyệt bác sĩ</a>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-3"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên, email, số điện thoại..."></div>
            <div class="col-md-3">
                <select name="specialty_id" class="form-select">
                    <option value="">Tất cả chuyên khoa</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" @selected(request('specialty_id') == $specialty->id)>{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" @selected(request('status') == 'active')>Hoạt động</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Tạm khóa</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="approval_status" class="form-select">
                    <option value="">Tất cả duyệt</option>
                    <option value="pending" @selected(request('approval_status') == 'pending')>Chờ duyệt</option>
                    <option value="approved" @selected(request('approval_status') == 'approved')>Đã duyệt</option>
                    <option value="rejected" @selected(request('approval_status') == 'rejected')>Từ chối</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Lọc dữ liệu</button></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Bác sĩ</th>
                        <th>Chuyên khoa</th>
                        <th>Kinh nghiệm</th>
                        <th>Lịch làm việc</th>
                        <th>Trạng thái</th>
                        <th>Xét duyệt</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ strtoupper(substr($doctor->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $doctor->name }}</strong>
                                        <small>{{ $doctor->email }} · {{ $doctor->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $doctor->specialty->name ?? '—' }}</td>
                            <td>{{ $doctor->experience_years ?? 0 }} năm</td>
                            <td>{{ $doctor->schedule_text ?? 'Thứ 2 - Thứ 7' }}</td>
                            <td><span class="status-badge {{ $doctor->status }}">{{ $doctor->status === 'active' ? 'Hoạt động' : 'Tạm khóa' }}</span></td>
                            <td>
                                @if($doctor->approval_status === 'approved')
                                    <span class="badge text-bg-success">Đã duyệt</span>
                                @elseif($doctor->approval_status === 'rejected')
                                    <span class="badge text-bg-danger">Từ chối</span>
                                @else
                                    <span class="badge text-bg-warning">Chờ duyệt</span>
                                @endif
                            </td>
                            <td class="text-end table-actions">
                                @if($doctor->approval_status === 'pending')
                                    <a href="{{ route('admin.doctors.approvals', ['keyword' => $doctor->name, 'approval_status' => 'pending']) }}">Xét duyệt</a>
                                @else
                                    <a href="{{ route('admin.doctors.show', $doctor) }}">Xem hồ sơ</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">Chưa có dữ liệu bác sĩ.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $doctors->links() }}
    </div>
@endsection
