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
            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="text-muted small mb-2">Điểm trung bình</div>
                    <div style="font-size:32px;font-weight:800;color:#ea580c;">{{ number_format($reviewStats['average_rating'], 1) }}/5</div>
                    <div class="mt-2 text-warning fw-semibold">{{ str_repeat('★', (int) floor($reviewStats['average_rating'])) }}{{ str_repeat('☆', max(0, 5 - (int) floor($reviewStats['average_rating']))) }}</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="text-muted small mb-2">Tổng lượt đánh giá</div>
                    <div style="font-size:32px;font-weight:800;color:#111827;">{{ $reviewStats['reviews_count'] }}</div>
                    <div class="mt-2 text-muted small">Số đánh giá bệnh nhân đã gửi cho bác sĩ này.</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white h-100">
                    <div class="text-muted small mb-2">Lịch hẹn đã hoàn tất</div>
                    <div style="font-size:32px;font-weight:800;color:#111827;">{{ $reviewStats['completed_appointments'] }}</div>
                    <div class="mt-2 text-muted small">Dùng để đối chiếu số ca khám hoàn thành và số đánh giá.</div>
                </div>
            </div>
        </div>

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


        <div class="panel-card mt-4">
            <div class="panel-head">
                <div>
                    <h5>Đánh giá từ bệnh nhân</h5>
                    <p>Admin có thể xem trực tiếp các đánh giá mà bệnh nhân đã gửi cho bác sĩ này.</p>
                </div>
            </div>

            @if($recentReviews->count())
                <div class="table-responsive mt-3">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Bệnh nhân</th>
                                <th>Lịch hẹn</th>
                                <th>Điểm</th>
                                <th>Nhận xét</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReviews as $review)
                                <tr>
                                    <td>
                                        <strong>{{ $review->patient->full_name ?? 'Bệnh nhân' }}</strong>
                                        <div class="text-muted small">{{ $review->patient->email ?? '—' }}</div>
                                    </td>
                                    <td>{{ $review->appointment->appointment_code ?? '—' }}</td>
                                    <td>
                                        <div class="fw-semibold text-warning">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</div>
                                        <div class="text-muted small">{{ $review->rating }}/5</div>
                                    </td>
                                    <td>{{ $review->review ?: 'Không có nhận xét thêm.' }}</td>
                                    <td>{{ optional($review->reviewed_at)->format('d/m/Y H:i') ?? optional($review->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted mt-3">Bác sĩ này chưa có đánh giá nào từ bệnh nhân.</div>
            @endif
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
