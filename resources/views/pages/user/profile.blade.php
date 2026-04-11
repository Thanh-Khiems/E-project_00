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

        <div class="content-card">
            <h2>Đổi Avatar</h2>
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('user.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Chọn ảnh</label>
                    <input type="file" name="avatar" accept="image/*" required>
                </div>
                <button type="submit" class="btn-primary">Cập nhật Avatar</button>
            </form>
        </div>

        <div id="password-change" class="content-card tab-content">
            <h2>Đổi mật khẩu</h2>
            @if(session('password_success'))
                <div class="alert-success">{{ session('password_success') }}</div>
            @endif
            @if(session('password_error'))
                <div class="alert-success">{{ session('password_error') }}</div>
            @endif
            <form action="{{ route('user.profile.password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" required>
                </div>
                <button type="submit" class="btn-primary">Đổi mật khẩu</button>
            </form>
        </div>

        <div id="doctor-verification" class="content-card tab-content">
            <h2>Xác thực bác sĩ</h2>
            <p style="margin-bottom: 20px; color: #666;">
                Vui lòng điền đầy đủ thông tin để gửi yêu cầu xác thực tài khoản bác sĩ.
            </p>

            <form action="#" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Họ và tên</label>
        <input type="text" name="doctor_full_name" required>
    </div>

    <div class="form-group">
        <label>Ngày sinh</label>
        <input type="date" name="doctor_dob" required>
    </div>

    <div class="form-group">
        <label>Số CCCD</label>
        <input type="text" name="citizen_id" required>
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
        <input type="text" name="doctor_phone" required>
    </div>

    <div class="form-group">
        <label>Bằng cấp</label>
        <select name="degree" required>
            <option value="">-- Chọn bằng cấp --</option>
            <option value="Thạc sĩ">Thạc sĩ</option>
            <option value="Tiến sĩ">Tiến sĩ</option>
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
            <option value="Tim mạch">Tim mạch</option>
            <option value="Nhi">Nhi</option>
            <option value="Da liễu">Da liễu</option>
            <option value="Ngoại tổng quát">Ngoại tổng quát</option>
            <option value="Chấn thương chỉnh hình">Chấn thương chỉnh hình</option>
            <option value="Tai mũi họng">Tai mũi họng</option>
            <option value="Sản phụ khoa">Sản phụ khoa</option>
            <option value="Thần kinh">Thần kinh</option>
            <option value="Hô hấp">Hô hấp</option>
            <option value="Tiêu hóa">Tiêu hóa</option>
        </select>
    </div>

    <div class="form-group">
        <label>Số năm kinh nghiệm</label>
        <input type="number" name="experience_years" min="0" required>
    </div>

    <div class="form-group">
        <label>Thành phố</label>
        <input type="text" value="{{ $user->province ?? '' }}" readonly>
        <input type="hidden" name="doctor_city" value="{{ $user->province ?? '' }}">
    </div>

    <button type="submit" class="btn-primary">Yêu Cầu Xác Thực</button>
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
    });

    setTimeout(() => {
        const successAlerts = document.querySelectorAll('.alert-success');
        successAlerts.forEach(alert => alert.style.display = 'none');
    }, 3000);
</script>
@endsection
