@extends('layouts.app')

@section('content')
@php
    $locations = $locations ?? config('locations');
    $provinces = $provinces ?? array_keys($locations);
@endphp

<style>
.dashboard-container { display: flex; flex-wrap: wrap; gap: 20px; margin: 20px; }
.sidebar { flex: 1 1 250px; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 20px; min-width: 200px; height: fit-content; }
.profile-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}
.profile-preview h3 {
    color: #1d4ed8;
}
.profile-preview img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
    display: block;
}
.sidebar ul { list-style: none; padding: 0; }
.sidebar ul li { margin-bottom: 10px; }
.sidebar ul li a { text-decoration: none; display: block; padding: 10px 15px; border-radius: 5px; color: #333; font-weight: 500; transition: 0.2s; }
.sidebar ul li a:hover, .sidebar ul li a.active { background-color: #1d4ed8; color: #fff; }
.content-area { flex: 3 1 600px; display: flex; flex-direction: column; gap: 20px; }
.content-card { background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 20px; transition: 0.2s; }
.content-card h2 { font-size: 1.5rem; margin-bottom: 15px; color: #1d4ed8; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 5px; }
.form-group input, .form-group select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
.btn-primary { background-color: #1d4ed8; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; transition: 0.2s; }
.btn-primary:hover { background-color: #2563eb; }
.alert-success { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; padding: 12px 20px; border-radius: 8px; margin-bottom: 15px; font-weight: 500; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.form-group input[type="file"] {
    background: #fff;
    padding: 8px;
}
@media (max-width: 768px) { .dashboard-container { flex-direction: column; } }
</style>

<div class="dashboard-container">

    <div class="sidebar">
        <div class="profile-preview">
            <img src="{{ $user->avatar ? asset('storage/avatars/'.$user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar">
            <h3>{{ $user->full_name }}</h3>
            <p>{{ $user->email }}</p>
            <p>{{ $user->province ?? '' }} - {{ $user->district ?? '' }}</p>
        </div>

        <ul>
            <li><a href="#account-settings" class="tab-link active">Cài đặt tài khoản</a></li>
            <li><a href="#medical-history" class="tab-link">Lịch sử khám bệnh</a></li>
            <li><a href="#appointments" class="tab-link">Lịch hẹn</a></li>
            <li><a href="#doctor-reviews" class="tab-link">Đánh giá bác sĩ</a></li>
            <li><a href="#doctor-verification" class="tab-link">Xác thực bác sĩ</a></li>
        </ul>
    </div>

    <div class="content-area">

        <div id="account-settings" class="content-card tab-content active">
            <h2>Cài đặt tài khoản</h2>

            @if($errors->any())
                <div class="alert-success" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="full_name" value="{{ $user->full_name }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}">
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" value="{{ $user->phone ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Tỉnh / Thành phố</label>
                    <select id="province" name="province">
                        <option value="">-- Chọn tỉnh/thành phố --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}" {{ $user->province == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Quận / Huyện</label>
                    <select id="district" name="district">
                        <option value="">-- Chọn quận/huyện --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Phường / Xã</label>
                    <select id="ward" name="ward">
                        <option value="">-- Chọn phường/xã --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <input type="text" name="address_detail" value="{{ $user->address_detail ?? '' }}">
                </div>

                <button type="submit" class="btn-primary">Lưu thay đổi</button>
            </form>
        </div>

        <div id="appointments" class="content-card tab-content">
            <h2>Lịch hẹn</h2>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Bác sĩ</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Chuyên khoa</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Ngày khám</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Thứ</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Giờ</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Hình thức</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Địa điểm</th>
                            <th style="padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ $appointment->doctor->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ optional($appointment->doctor->specialty)->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ $appointment->appointment_day }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ ucfirst($appointment->type ?? 'N/A') }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ $appointment->location ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    {{ ucfirst($appointment->status) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 16px; text-align: center; color: #6b7280;">
                                    Bạn chưa có lịch hẹn nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="doctor-verification" class="content-card tab-content">
            <h2>Xác thực bác sĩ</h2>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert-success" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444;">
                    {{ session('error') }}
                </div>
            @endif

            @if($user->doctor_verification_status === 'pending')
                <div class="alert-success" style="background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;">
                    Tài khoản của bạn đang trong trạng thái <strong>chờ duyệt xác thực bác sĩ</strong>.
                </div>
            @elseif($user->doctor_verification_status === 'approved')
                <div class="alert-success">
                    Tài khoản của bạn đã được <strong>xác thực bác sĩ thành công</strong>.
                </div>
            @elseif($user->doctor_verification_status === 'rejected')
                <div class="alert-success" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444;">
                    Yêu cầu xác thực đã bị từ chối.
                    @if($user->doctor_rejection_reason)
                        <br><strong>Lý do:</strong> {{ $user->doctor_rejection_reason }}
                    @endif
                </div>
            @endif

            <p style="margin-bottom: 20px; color: #666;">
                Vui lòng điền đầy đủ thông tin để gửi yêu cầu xác thực tài khoản bác sĩ.
            </p>

            <form action="{{ route('user.profile.verifyDoctor') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="doctor_full_name" value="{{ old('doctor_full_name', $user->full_name) }}" required>
                    @error('doctor_full_name')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="doctor_dob" value="{{ old('doctor_dob', $user->dob) }}" required>
                    @error('doctor_dob')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Số CCCD</label>
                    <input type="text" name="citizen_id" value="{{ old('citizen_id') }}" required>
                    @error('citizen_id')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ảnh mặt trước CCCD</label>
                    <input type="file" name="citizen_id_front" accept="image/*" required>
                    @error('citizen_id_front')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ảnh mặt sau CCCD</label>
                    <input type="file" name="citizen_id_back" accept="image/*" required>
                    @error('citizen_id_back')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="doctor_phone" value="{{ old('doctor_phone', $user->phone) }}" required>
                    @error('doctor_phone')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Bằng cấp</label>
                    <select name="degree" required>
                        <option value="">-- Chọn bằng cấp --</option>
                        <option value="Thạc sĩ" {{ old('degree') == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                        <option value="Tiến sĩ" {{ old('degree') == 'Tiến sĩ' ? 'selected' : '' }}>Tiến sĩ</option>
                    </select>
                    @error('degree')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ảnh bằng cấp bác sĩ</label>
                    <input type="file" name="degree_image" accept="image/*" required>
                    @error('degree_image')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Chuyên khoa</label>
                    <select name="specialty" required>
                        <option value="">-- Chọn chuyên khoa --</option>
                        <option value="Tim mạch" {{ old('specialty') == 'Tim mạch' ? 'selected' : '' }}>Tim mạch</option>
                        <option value="Nhi" {{ old('specialty') == 'Nhi' ? 'selected' : '' }}>Nhi</option>
                        <option value="Da liễu" {{ old('specialty') == 'Da liễu' ? 'selected' : '' }}>Da liễu</option>
                        <option value="Ngoại tổng quát" {{ old('specialty') == 'Ngoại tổng quát' ? 'selected' : '' }}>Ngoại tổng quát</option>
                        <option value="Chấn thương chỉnh hình" {{ old('specialty') == 'Chấn thương chỉnh hình' ? 'selected' : '' }}>Chấn thương chỉnh hình</option>
                        <option value="Tai mũi họng" {{ old('specialty') == 'Tai mũi họng' ? 'selected' : '' }}>Tai mũi họng</option>
                        <option value="Sản phụ khoa" {{ old('specialty') == 'Sản phụ khoa' ? 'selected' : '' }}>Sản phụ khoa</option>
                        <option value="Thần kinh" {{ old('specialty') == 'Thần kinh' ? 'selected' : '' }}>Thần kinh</option>
                        <option value="Hô hấp" {{ old('specialty') == 'Hô hấp' ? 'selected' : '' }}>Hô hấp</option>
                        <option value="Tiêu hóa" {{ old('specialty') == 'Tiêu hóa' ? 'selected' : '' }}>Tiêu hóa</option>
                    </select>
                    @error('specialty')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Số năm kinh nghiệm</label>
                    <input type="number" name="experience_years" min="0" value="{{ old('experience_years') }}" required>
                    @error('experience_years')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
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
    const tabs = document.querySelectorAll('.tab-link');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            tab.classList.add('active');

            const target = document.querySelector(tab.getAttribute('href'));
            if (target) {
                target.classList.add('active');
            }
        });
    });

    const locations = @json($locations);
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    const selectedDistrict = "{{ $user->district ?? '' }}";
    const selectedWard = "{{ $user->ward ?? '' }}";

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function populateDistricts(province, selectedDistrict = '') {
        resetSelect(districtSelect, '-- Chọn quận/huyện --');
        resetSelect(wardSelect, '-- Chọn phường/xã --');
        if (!province || !locations[province]) return;
        Object.keys(locations[province]).forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            if (district === selectedDistrict) option.selected = true;
            districtSelect.appendChild(option);
        });
        if (selectedDistrict) populateWards(province, selectedDistrict, selectedWard);
    }

    function populateWards(province, district, selectedWard = '') {
        resetSelect(wardSelect, '-- Chọn phường/xã --');
        if (!province || !district || !locations[province][district]) return;
        locations[province][district].forEach(ward => {
            const option = document.createElement('option');
            option.value = ward;
            option.textContent = ward;
            if (ward === selectedWard) option.selected = true;
            wardSelect.appendChild(option);
        });
    }

    provinceSelect.addEventListener('change', () => populateDistricts(provinceSelect.value));
    districtSelect.addEventListener('change', () => populateWards(provinceSelect.value, districtSelect.value));

    document.addEventListener('DOMContentLoaded', () => {
        if (provinceSelect.value) populateDistricts(provinceSelect.value, selectedDistrict);

        @if(session('success'))
            const appointmentTab = document.querySelector('.tab-link[href="#appointments"]');
            const appointmentContent = document.querySelector('#appointments');

            if (appointmentTab && appointmentContent) {
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));
                appointmentTab.classList.add('active');
                appointmentContent.classList.add('active');
            }
        @endif
    });

    setTimeout(() => {
        const successAlerts = document.querySelectorAll('.alert-success');
        successAlerts.forEach(alert => alert.style.display = 'none');
    }, 3000);
</script>
@endsection
