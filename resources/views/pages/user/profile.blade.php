@extends('layouts.app')

@section('content')
<style>
.dashboard-container { display: flex; flex-wrap: wrap; gap: 20px; margin: 20px; }
.sidebar { flex: 1 1 250px; background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); padding: 20px; min-width: 220px; height: fit-content; }
.sidebar ul { list-style: none; padding: 0; margin: 20px 0 0; }
.sidebar ul li { margin-bottom: 10px; }
.sidebar ul li a { text-decoration: none; display: block; padding: 10px 15px; border-radius: 8px; color: #333; font-weight: 600; }
.sidebar ul li a:hover, .sidebar ul li a.active { background-color: #1d4ed8; color: #fff; }
.content-area { flex: 3 1 700px; display: flex; flex-direction: column; gap: 20px; }
.content-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); padding: 24px; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.form-group { margin-bottom: 16px; }
.form-group label { display:block; font-weight:700; margin-bottom:8px; }
.form-group input, .form-group select, .form-group textarea { width:100%; border:1px solid #d1d5db; border-radius:8px; padding:12px; }
.form-group input[type="file"] { background:#fff; padding:10px; }
.btn-primary { background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:12px 16px; font-weight:700; cursor:pointer; }
.btn-primary:hover { background:#1e40af; }
.alert-success { margin-bottom:16px; padding:12px 16px; background:#ecfdf5; color:#065f46; border-radius:10px; }
.profile-preview { display:flex; flex-direction:column; align-items:center; }
.profile-preview img { width:120px; height:120px; object-fit:cover; border-radius:50%; margin-bottom:12px; }
.history-card { border:1px solid #e5e7eb; border-radius:16px; padding:18px; margin-bottom:16px; }
.section-divider { margin: 28px 0 18px; padding-top: 18px; border-top: 1px solid #e5e7eb; }
@media (max-width: 768px) { .dashboard-container { flex-direction: column; } }
</style>

<div class="dashboard-container">
    <div class="sidebar">
        <div class="profile-preview">
            <img src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar">
            <h3 style="margin:0;color:#1d4ed8;">{{ $user->full_name }}</h3>
            <p style="margin:6px 0;color:#6b7280;">{{ $user->email }}</p>
            <p style="margin:0;color:#6b7280;">{{ $user->province ?? '' }}{{ $user->district ? ' - ' . $user->district : '' }}</p>
        </div>

        <ul>
            <li><a href="#account-settings" class="tab-link active">Cài đặt tài khoản</a></li>
            <li><a href="#medical-history" class="tab-link">Lịch sử khám bệnh</a></li>
            <li><a href="#appointments" class="tab-link">Lịch hẹn</a></li>
            <li><a href="#doctor-verification" class="tab-link">Xác thực bác sĩ</a></li>
        </ul>
    </div>

    <div class="content-area">
        <div id="account-settings" class="content-card tab-content active">
            <h2 style="margin-top:0;">Cài đặt tài khoản</h2>

            @if($errors->any())
                <div class="alert-success" style="background:#fef2f2;color:#991b1b;">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert-success" style="background:#fef2f2;color:#991b1b;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Tỉnh / Thành phố</label>
                    <select name="province">
                        <option value="">-- Chọn tỉnh/thành phố --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}" {{ old('province', $user->province) == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quận / Huyện</label>
                    <input type="text" name="district" value="{{ old('district', $user->district ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Phường / Xã</label>
                    <input type="text" name="ward" value="{{ old('ward', $user->ward ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <input type="text" name="address_detail" value="{{ old('address_detail', $user->address_detail ?? '') }}">
                </div>
                <button type="submit" class="btn-primary">Lưu thay đổi</button>
            </form>

            <div class="section-divider">
                <h3 style="margin:0 0 12px;color:#1d4ed8;">Cập nhật ảnh đại diện</h3>
                <p style="margin:0 0 16px;color:#6b7280;">Tải lên ảnh đại diện mới cho tài khoản của bạn.</p>
                <form action="{{ route('user.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Chọn ảnh đại diện</label>
                        <input type="file" name="avatar" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn-primary">Cập nhật avatar</button>
                </form>
            </div>
        </div>

        <div id="medical-history" class="content-card tab-content">
            <h2 style="margin-top:0;">Lịch sử khám bệnh</h2>
            <p style="color:#6b7280;">Bệnh nhân có thể xem lại các cuộc khám đã hoàn tất, chẩn đoán và những toa thuốc bác sĩ từng kê.</p>

            @forelse($completedAppointments as $appointment)
                <div class="history-card">
                    <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div>
                            <div style="font-weight:800;color:#1d4ed8;">{{ $appointment->appointment_code }}</div>
                            <div style="margin-top:8px;font-size:18px;font-weight:700;">{{ $appointment->doctor->name ?? '—' }}</div>
                            <div style="color:#6b7280;">{{ optional($appointment->doctor->specialty)->name ?? '—' }}</div>
                        </div>
                        <div>
                            <div><strong>Ngày khám:</strong> {{ optional($appointment->appointment_date)->format('d/m/Y') }}</div>
                            <div><strong>Giờ:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</div>
                            <div><strong>Trạng thái:</strong> {{ ucfirst($appointment->status) }}</div>
                        </div>
                    </div>

                    <div style="margin-top:14px;padding:14px;border-radius:12px;background:#f8fafc;">
                        <div><strong>Chẩn đoán:</strong> {{ $appointment->diagnosis ?: 'Chưa có chẩn đoán.' }}</div>
                        <div style="margin-top:8px;"><strong>Lời dặn của bác sĩ:</strong> {{ $appointment->doctor_advice ?: 'Chưa có lời dặn.' }}</div>
                    </div>

                    @if($appointment->status === 'completed')
                        <div style="margin-top:16px;padding:16px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;">
                            <h4 style="margin:0 0 12px;color:#111827;">Đánh giá bác sĩ</h4>

                            @if($appointment->review)
                                <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:flex-start;">
                                    <div>
                                        <div style="font-size:22px;font-weight:800;color:#f59e0b;">{{ str_repeat('★', (int) $appointment->review->rating) }}{{ str_repeat('☆', 5 - (int) $appointment->review->rating) }}</div>
                                        <div style="margin-top:8px;"><strong>Số điểm:</strong> {{ $appointment->review->rating }}/5</div>
                                        <div style="margin-top:6px;"><strong>Nhận xét:</strong> {{ $appointment->review->review ?: 'Không có nhận xét thêm.' }}</div>
                                    </div>
                                    <div style="color:#6b7280;font-size:13px;">
                                        Đã gửi lúc {{ optional($appointment->review->reviewed_at)->format('d/m/Y H:i') ?? '—' }}
                                    </div>
                                </div>
                            @else
                                <form method="POST" action="{{ route('appointments.review', $appointment) }}" style="margin-top:8px;">
                                    @csrf
                                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
                                        <div>
                                            <label for="profile-rating-{{ $appointment->id }}" style="display:block;margin-bottom:6px;font-weight:700;">Chấm điểm</label>
                                            <select id="profile-rating-{{ $appointment->id }}" name="rating" style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid #d1d5db;" required>
                                                <option value="">-- Chọn số sao --</option>
                                                @for($star = 5; $star >= 1; $star--)
                                                    <option value="{{ $star }}">{{ $star }} sao</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div style="grid-column:1 / -1;">
                                            <label for="profile-review-{{ $appointment->id }}" style="display:block;margin-bottom:6px;font-weight:700;">Nhận xét</label>
                                            <textarea id="profile-review-{{ $appointment->id }}" name="review" rows="4" placeholder="Nhập cảm nhận của bạn về bác sĩ..." style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid #d1d5db;resize:vertical;"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" style="margin-top:12px;padding:12px 18px;border:none;border-radius:12px;background:#1d4ed8;color:#fff;font-weight:700;cursor:pointer;">Gửi đánh giá</button>
                                </form>
                            @endif
                        </div>
                    @endif

                    @if($appointment->prescriptions->count())
                        <div style="margin-top:16px;">
                            <h4 style="margin:0 0 12px;">Toa thuốc đã kê</h4>
                            @foreach($appointment->prescriptions as $prescription)
                                <div style="border:1px solid #e5e7eb;border-radius:12px;padding:14px;margin-bottom:12px;">
                                    <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                                        <strong style="color:#1d4ed8;">{{ $prescription->prescription_code }}</strong>
                                        <span style="color:#6b7280;">{{ optional($prescription->issued_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div style="margin-top:8px;"><strong>Ghi chú toa thuốc:</strong> {{ $prescription->notes ?: '—' }}</div>
                                    <div style="margin-top:12px;overflow:auto;">
                                        <table style="width:100%;border-collapse:collapse;min-width:720px;">
                                            <thead>
                                                <tr style="background:#eff6ff;">
                                                    <th style="padding:10px;text-align:left;">Thuốc</th>
                                                    <th style="padding:10px;text-align:left;">Liều dùng</th>
                                                    <th style="padding:10px;text-align:left;">Tần suất</th>
                                                    <th style="padding:10px;text-align:left;">Thời gian dùng</th>
                                                    <th style="padding:10px;text-align:left;">Số lượng</th>
                                                    <th style="padding:10px;text-align:left;">Hướng dẫn</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($prescription->items as $item)
                                                    <tr>
                                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->medication->name ?? '—' }}<div style="font-size:12px;color:#6b7280;">{{ $item->medication->dosage ?? '' }}</div></td>
                                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->dosage }}</td>
                                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->frequency }}</td>
                                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->duration }}</td>
                                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->quantity }}</td>
                                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->instructions ?: '—' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p style="color:#6b7280;">Bạn chưa có cuộc khám nào đã hoàn tất.</p>
            @endforelse
        </div>

        <div id="appointments" class="content-card tab-content">
            <h2 style="margin-top:0;">Lịch hẹn</h2>
            <p style="margin:0 0 16px;color:#6b7280;">Chỉ hiển thị các lịch hẹn còn hiệu lực trong 7 ngày tính từ hiện tại.</p>
            <div style="overflow:auto;">
                <table style="width:100%;border-collapse:collapse;min-width:900px;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:12px;text-align:left;">Bác sĩ</th>
                            <th style="padding:12px;text-align:left;">Chuyên khoa</th>
                            <th style="padding:12px;text-align:left;">Ngày khám</th>
                            <th style="padding:12px;text-align:left;">Giờ</th>
                            <th style="padding:12px;text-align:left;">Hình thức</th>
                            <th style="padding:12px;text-align:left;">Địa điểm</th>
                            <th style="padding:12px;text-align:left;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ optional($appointment->doctor->specialty)->name ?? 'N/A' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ optional($appointment->appointment_date)->format('d/m/Y') ?? 'N/A' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ ucfirst($appointment->type ?? 'N/A') }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->location ?? 'N/A' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ ucfirst($appointment->status ?? 'pending') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="padding:18px;text-align:center;color:#6b7280;">Bạn chưa có lịch hẹn nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="doctor-verification" class="content-card tab-content">
            <h2 style="margin-top:0;">Xác thực bác sĩ</h2>

            @if($user->doctor_verification_status === 'pending')
                <div class="alert-success" style="background:#fef3c7;color:#92400e;">
                    Tài khoản của bạn đang trong trạng thái <strong>chờ duyệt xác thực bác sĩ</strong>.
                </div>
            @elseif($user->doctor_verification_status === 'approved')
                <div class="alert-success">
                    Tài khoản của bạn đã được <strong>xác thực bác sĩ thành công</strong>.
                </div>
            @elseif($user->doctor_verification_status === 'rejected')
                <div class="alert-success" style="background:#fef2f2;color:#991b1b;">
                    Yêu cầu xác thực đã bị từ chối.
                    @if($user->doctor_rejection_reason)
                        <br><strong>Lý do:</strong> {{ $user->doctor_rejection_reason }}
                    @endif
                </div>
            @endif

            <p style="margin-bottom:20px;color:#6b7280;">
                Vui lòng điền đầy đủ thông tin để gửi yêu cầu xác thực tài khoản bác sĩ.
            </p>

            <form action="{{ route('user.profile.verifyDoctor') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="doctor_full_name" value="{{ old('doctor_full_name', $user->full_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="doctor_dob" value="{{ old('doctor_dob', optional($user->dob)->format('Y-m-d') ?? $user->dob) }}" required>
                </div>

                <div class="form-group">
                    <label>Số CCCD</label>
                    <input type="text" name="citizen_id" value="{{ old('citizen_id') }}" required>
                </div>

                <div class="form-group">
                    <label>Ảnh mặt trước CCCD</label>
                    <input type="file" name="citizen_id_front" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label>Ảnh mặt sau CCCD</label>
                    <input type="file" name="citizen_id_back" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="doctor_phone" value="{{ old('doctor_phone', $user->phone) }}" required>
                </div>

                <div class="form-group">
                    <label>Bằng cấp</label>
                    <select name="degree" required>
                        <option value="">-- Chọn bằng cấp --</option>
                        @forelse($degrees as $degree)
                            <option value="{{ $degree->name }}" {{ old('degree') == $degree->name ? 'selected' : '' }}>{{ $degree->name }}</option>
                        @empty
                            <option value="Bác sĩ đa khoa">Bác sĩ đa khoa</option>
                            <option value="Thạc sĩ">Thạc sĩ</option>
                            <option value="Tiến sĩ">Tiến sĩ</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group">
                    <label>Ảnh bằng cấp bác sĩ</label>
                    <input type="file" name="degree_image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label>Chuyên khoa</label>
                    <select name="specialty" required>
                        <option value="">-- Chọn chuyên khoa --</option>
                        @forelse($specialties as $specialty)
                            <option value="{{ $specialty->name }}" {{ old('specialty') == $specialty->name ? 'selected' : '' }}>{{ $specialty->name }}</option>
                        @empty
                            <option value="Tim mạch">Tim mạch</option>
                            <option value="Nhi">Nhi</option>
                            <option value="Da liễu">Da liễu</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group">
                    <label>Số năm kinh nghiệm</label>
                    <input type="number" name="experience_years" min="0" value="{{ old('experience_years') }}" required>
                </div>

                <div class="form-group">
                    <label>Thành phố</label>
                    <input type="text" value="{{ $user->province ?? '' }}" readonly>
                    <input type="hidden" name="doctor_city" value="{{ $user->province ?? '' }}">
                </div>

                @if($user->doctor_verification_status !== 'approved')
                    <button type="submit" class="btn-primary">Yêu Cầu Xác Thực</button>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    const links = document.querySelectorAll('.tab-link');
    const tabs = document.querySelectorAll('.tab-content');

    function activateTab(hash) {
        links.forEach(link => link.classList.toggle('active', link.getAttribute('href') === hash));
        tabs.forEach(tab => tab.classList.toggle('active', '#' + tab.id === hash));
    }

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const hash = this.getAttribute('href');
            history.replaceState(null, '', hash);
            activateTab(hash);
        });
    });

    const queryTab = new URLSearchParams(window.location.search).get('tab');
    const initialHash = window.location.hash || (queryTab ? '#' + queryTab : '#account-settings');
    activateTab(initialHash);
</script>
@endsection
