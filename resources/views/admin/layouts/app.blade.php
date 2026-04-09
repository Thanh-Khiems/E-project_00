<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Hospital Admin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('admin-ui/css/admin-hospital.css') }}" rel="stylesheet">
</head>
<body>
<div class="admin-shell">
    <aside class="sidebar">
        <div class="brand-box">
            <img src="{{ asset('admin-ui/images/logo.png') }}" alt="logo" style="width:200px;">
        
        </div>

        <div class="menu-label">Quản trị hệ thống</div>
        <nav class="nav flex-column sidebar-nav">
            <a class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}" href="{{ route('admin.doctors.index') }}">
                <i class="bi bi-person-badge"></i><span>Bác sĩ</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}" href="{{ route('admin.specialties.index') }}">
                <i class="bi bi-grid"></i><span>Chuyên khoa</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}" href="{{ route('admin.patients.index') }}">
                <i class="bi bi-people"></i><span>Bệnh nhân</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}" href="{{ route('admin.staffs.index') }}">
                <i class="bi bi-person-workspace"></i><span>Nhân viên</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
                <i class="bi bi-calendar-check"></i><span>Lịch hẹn</span>
            </a>
        </nav>

        <div class="sidebar-card mt-auto">
            <h6>Hệ thống ổn định</h6>
            <p>Theo dõi vận hành toàn bộ phòng khám trên từng trang quản lý riêng chắc vậy.</p>
        </div>
    </aside>

    <main class="content-wrapper">
        <header class="topbar">
            <div>
                <p class="topbar-label">Admin Panel</p>
                <h2>{{ $pageTitle ?? 'Dashboard' }}</h2>
            </div>
            <div class="topbar-actions">
                <div class="search-pill">
                    <i class="bi bi-search"></i>
                </div>
                <div class="avatar-pill">AD</div>
            </div>
        </header>

        @yield('content')
    </main>
</div>
</body>
</html>
