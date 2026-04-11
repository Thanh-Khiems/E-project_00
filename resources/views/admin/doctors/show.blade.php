@extends('admin.layouts.app')

@section('content')
    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Hồ sơ bác sĩ</h5>
                <p>Xem chi tiết thông tin bác sĩ và thực hiện các thao tác quản lý.</p>
            </div>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-primary">Quay lại danh sách</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h5 class="mb-3">{{ $doctor->name }}</h5>
                    <div class="small text-muted mb-3">
                        {{ $doctor->email ?: 'Chưa có email' }} · {{ $doctor->phone ?: 'Chưa có số điện thoại' }}
                    </div>

                    <div class="mb-2"><strong>Chuyên khoa:</strong> {{ $doctor->specialty->name ?? '—' }}</div>
                    <div class="mb-2"><strong>Bằng cấp:</strong> {{ $doctor->degree ?? '—' }}</div>
                    <div class="mb-2"><strong>Kinh nghiệm:</strong> {{ $doctor->experience_years ?? 0 }} năm</div>
                    <div class="mb-2"><strong>Thành phố:</strong> {{ $doctor->city ?? '—' }}</div>
                    <div class="mb-2"><strong>Lịch làm việc:</strong> {{ $doctor->schedule_text ?? 'Chưa cập nhật' }}</div>
                    <div class="mb-2">
                        <strong>Trạng thái:</strong>
                        @if($doctor->status === 'active')
                            <span class="badge text-bg-success">Hoạt động</span>
                        @else
                            <span class="badge text-bg-secondary">Đã khóa</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Xét duyệt:</strong>
                        @if($doctor->approval_status === 'approved')
                            <span class="badge text-bg-success">Đã duyệt</span>
                        @elseif($doctor->approval_status === 'rejected')
                            <span class="badge text-bg-danger">Từ chối</span>
                        @else
                            <span class="badge text-bg-warning">Chờ duyệt</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <h6 class="mb-3">Giấy tờ xác thực</h6>

                    <div class="mb-2"><strong>Ngày sinh:</strong> {{ $doctor->doctor_dob ? \Carbon\Carbon::parse($doctor->doctor_dob)->format('d/m/Y') : '—' }}</div>
                    <div class="mb-3"><strong>Số CCCD:</strong> {{ $doctor->citizen_id ?? '—' }}</div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-2">
                                <div class="small fw-semibold mb-2">CCCD mặt trước</div>
                                @if($doctor->citizen_id_front)
                                    <img src="{{ asset('storage/' . $doctor->citizen_id_front) }}" alt="CCCD trước" style="width:100%; max-height:220px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div class="text-muted small">Chưa có ảnh</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-2">
                                <div class="small fw-semibold mb-2">CCCD mặt sau</div>
                                @if($doctor->citizen_id_back)
                                    <img src="{{ asset('storage/' . $doctor->citizen_id_back) }}" alt="CCCD sau" style="width:100%; max-height:220px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div class="text-muted small">Chưa có ảnh</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-2">
                                <div class="small fw-semibold mb-2">Bằng cấp</div>
                                @if($doctor->degree_image)
                                    <img src="{{ asset('storage/' . $doctor->degree_image) }}" alt="Bằng cấp" style="width:100%; max-height:220px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div class="text-muted small">Chưa có ảnh</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-3">
            <form method="POST" action="{{ route('admin.doctors.toggleStatus', $doctor) }}">
                @csrf
                <button type="submit" class="btn {{ $doctor->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                    {{ $doctor->status === 'active' ? 'Khóa bác sĩ' : 'Mở khóa bác sĩ' }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" onsubmit="return confirm('Bạn có chắc muốn xóa hồ sơ bác sĩ này không?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Xóa bác sĩ
                </button>
            </form>
        </div>
    </div>
@endsection
