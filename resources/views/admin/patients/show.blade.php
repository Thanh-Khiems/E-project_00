@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Hồ sơ bệnh nhân</h5>
                <p>Xem thông tin tài khoản và dữ liệu hồ sơ của bệnh nhân.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">Sửa thông tin</a>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-primary">Quay lại</a>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h5 class="mb-3">{{ $patient->name }}</h5>
                    <div class="small text-muted mb-3">
                        {{ $patient->patient_code ?: 'Chưa có mã bệnh nhân' }} · {{ $patient->email ?: 'Chưa có email' }}
                    </div>

                    <div class="mb-2"><strong>Số điện thoại:</strong> {{ $patient->phone ?: '—' }}</div>
                    <div class="mb-2"><strong>Ngày sinh:</strong> {{ optional($patient->date_of_birth)->format('d/m/Y') ?? '—' }}</div>
                    <div class="mb-2"><strong>Giới tính:</strong> {{ match($patient->gender){'male' => 'Nam', 'female' => 'Nữ', default => 'Khác'} }}</div>
                    <div class="mb-2"><strong>Địa chỉ:</strong> {{ $patient->address ?: '—' }}</div>
                    <div class="mb-2"><strong>Số lịch hẹn:</strong> {{ $patient->appointments->count() }}</div>
                    <div class="mb-2"><strong>Ngày tạo:</strong> {{ $patient->created_at?->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h6 class="mb-3">Thông tin tài khoản</h6>
                    <div class="mb-2"><strong>User ID:</strong> {{ $patient->user?->id ?? 'Không liên kết' }}</div>
                    <div class="mb-2"><strong>Họ tên tài khoản:</strong> {{ $patient->user?->full_name ?? $patient->name }}</div>
                    <div class="mb-2"><strong>Email đăng nhập:</strong> {{ $patient->user?->email ?? $patient->email ?? '—' }}</div>
                    <div class="mb-2"><strong>Vai trò:</strong> {{ $patient->user?->role ?? 'patient' }}</div>
                    <div class="mb-2"><strong>Khu vực:</strong>
                        {{ collect([$patient->user?->ward, $patient->user?->district, $patient->user?->province])->filter()->implode(', ') ?: '—' }}
                    </div>
                    <div class="mb-2"><strong>Địa chỉ chi tiết:</strong> {{ $patient->user?->address_detail ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-3">
            <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">Sửa bệnh nhân</a>
            <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản bệnh nhân này không?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Xóa tài khoản</button>
            </form>
        </div>
    </div>
@endsection
