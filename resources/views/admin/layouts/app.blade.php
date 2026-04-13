<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Hospital Admin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('admin-ui/css/admin-hospital.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .logout-form { margin: 0; }
        .logout-btn {
            border: none;
            background: #fff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
            color: #0f172a;
        }
        .logout-btn:hover { background: #f8fafc; }
    </style>
</head>
<body>
<div class="admin-shell">
    <aside class="sidebar">
        <div class="brand-box">
            <img src="{{ asset('admin-ui/images/logo.png') }}" alt="MediConnect logo" style="width:220px; max-width:100%;">
        </div>

        <div class="menu-label">Quản trị hệ thống</div>
        <nav class="nav flex-column sidebar-nav">
            <a class="nav-link {{ request()->routeIs('admin.doctors.index') ? 'active' : '' }}" href="{{ route('admin.doctors.index') }}">
                <i class="bi bi-person-badge"></i><span>Bác sĩ</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.doctors.approvals') ? 'active' : '' }}" href="{{ route('admin.doctors.approvals') }}">
                <i class="bi bi-patch-check"></i><span>Duyệt bác sĩ</span>
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
            <a class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}" href="{{ route('admin.locations.index') }}">
                <i class="bi bi-geo-alt"></i><span>Khu vực</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                <i class="bi bi-journal-richtext"></i><span>Blog</span>              
            </a>
        </nav>
    </aside>

    <main class="content-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('admin-ui/images/logo.png') }}" alt="MediConnect logo" style="height:48px; width:auto;">
                <div>
                    <p class="topbar-label">Admin Panel</p>
                    <h2>{{ $pageTitle ?? 'Dashboard' }}</h2>
                </div>
            </div>
            <div class="topbar-actions">
                <div class="search-pill">
                    <i class="bi bi-search"></i>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form" title="Đăng xuất">
                    @csrf
                    <button type="submit" class="logout-btn" aria-label="Đăng xuất">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
                <div class="avatar-pill">AD</div>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
