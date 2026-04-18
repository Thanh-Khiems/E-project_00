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
    <aside class="sidebar d-none d-lg-flex">
        <div class="brand-box">
            <a href="{{ route('home') }}" aria-label="Back to homepage" style="display:inline-block;">
                <img src="{{ asset('admin-ui/images/logo.png') }}" alt="MediConnect logo" style="width:220px; max-width:100%;">
            </a>
        </div>

        @include('admin.layouts.partials.sidebar-nav')
    </aside>

    <div class="offcanvas offcanvas-start admin-mobile-drawer" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header">
            <div class="brand-box border-0 p-0 w-100">
                <a href="{{ route('home') }}" aria-label="Back to homepage" style="display:inline-block;">
                    <img src="{{ asset('admin-ui/images/logo.png') }}" alt="MediConnect logo" style="width:180px; max-width:100%;">
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @include('admin.layouts.partials.sidebar-nav')
        </div>
    </div>

    <main class="content-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
                <button class="btn btn-light admin-menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Open admin menu">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <a href="{{ route('home') }}" aria-label="Back to homepage" class="topbar-logo-link">
                    <img src="{{ asset('admin-ui/images/logo.png') }}" alt="MediConnect logo" class="topbar-logo">
                </a>
                <div class="min-w-0">
                    <p class="topbar-label">Admin Panel</p>
                    <h2>{{ $pageTitle ?? 'Dashboard' }}</h2>
                </div>
            </div>
            <div class="topbar-actions">
                <form method="POST" action="{{ route('logout') }}" class="logout-form" title="Log out">
                    @csrf
                    <button type="submit" class="logout-btn" aria-label="Log out">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
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
