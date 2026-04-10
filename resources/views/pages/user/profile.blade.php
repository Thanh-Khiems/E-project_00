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
    flex-direction: column; /* ảnh + tên + email xếp dọc */
    align-items: center;    /* căn giữa theo chiều ngang */
    justify-content: center; /* căn giữa theo chiều dọc nếu muốn */
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
    display: block; /* đảm bảo ảnh là block */
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
@media (max-width: 768px) { .dashboard-container { flex-direction: column; } }

</style>

<div class="dashboard-container">

    <!-- Sidebar + Profile Preview -->
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
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content-area">

        <!-- Profile Info -->
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

                <!-- Address -->
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

        <!-- Avatar Upload -->
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

        <!-- Password Change -->
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

    </div>
</div>

<script>
    // Tabs dynamic
    const tabs = document.querySelectorAll('.tab-link');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.querySelector(tab.getAttribute('href')).classList.add('active');
        });
    });

    // Address dropdown dynamic
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

    // Flash messages auto-hide
    setTimeout(() => {
        const successAlerts = document.querySelectorAll('.alert-success');
        successAlerts.forEach(alert => alert.style.display = 'none');
    }, 3000);
</script>
@endsection
