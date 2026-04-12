@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Tổng bệnh nhân', 'value' => $stats['total'], 'icon' => 'bi-people'],
        ['label' => 'Nam', 'value' => $stats['male'], 'icon' => 'bi-gender-male'],
        ['label' => 'Nữ', 'value' => $stats['female'], 'icon' => 'bi-gender-female'],
        ['label' => 'Mới tháng này', 'value' => $stats['new_this_month'], 'icon' => 'bi-person-plus'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Danh sách bệnh nhân</h5>
                <p>Tài khoản bệnh nhân đăng ký mới sẽ tự động hiển thị tại đây để admin theo dõi và quản lý.</p>
            </div>
            <span class="btn btn-light">Tự động đồng bộ từ đăng ký</span>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-5"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Mã BN, tên, điện thoại, email..."></div>
            <div class="col-md-3">
                <select name="gender" class="form-select">
                    <option value="">Tất cả giới tính</option>
                    <option value="male" @selected(request('gender') == 'male')>Nam</option>
                    <option value="female" @selected(request('gender') == 'female')>Nữ</option>
                    <option value="other" @selected(request('gender') == 'other')>Khác</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Lọc</button></div>
            <div class="col-md-2 d-grid"><a href="{{ route('admin.patients.index') }}" class="btn btn-light">Đặt lại</a></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Bệnh nhân</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Số lịch hẹn</th>
                        <th>Địa chỉ</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $patient->name }}</strong>
                                        <small>{{ $patient->patient_code ?? 'BN-0000' }} · {{ $patient->phone ?: ($patient->user->phone ?? 'Chưa có SĐT') }}</small><br>
                                        <small>{{ $patient->email ?: ($patient->user->email ?? 'Chưa có email') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ optional($patient->date_of_birth)->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ match($patient->gender){'male' => 'Nam', 'female' => 'Nữ', default => 'Khác'} }}</td>
                            <td>{{ $patient->appointments_count }}</td>
                            <td>{{ $patient->address ?? '—' }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.patients.show', $patient) }}">Hồ sơ</a>
                                <a href="{{ route('admin.patients.edit', $patient) }}">Sửa</a>
                                <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" class="action-form" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản bệnh nhân này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link text-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Chưa có dữ liệu bệnh nhân.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $patients->links() }}
    </div>
@endsection
