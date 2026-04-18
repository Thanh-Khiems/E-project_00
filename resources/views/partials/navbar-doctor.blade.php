<header class="navbar reveal-up delay-1" data-mobile-nav>
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="logo" aria-label="MediConnect">
            <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="logo-img">
        </a>

        <button
            type="button"
            class="nav-toggle"
            aria-label="Mở menu điều hướng"
            aria-expanded="false"
            aria-controls="doctor-nav-menu"
            data-nav-toggle
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="nav-menu" id="doctor-nav-menu" data-nav-menu>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a>
            <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.index', 'doctor-list', 'user.doctor-list') ? 'active' : '' }}">Doctor</a>
            <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.index', 'blog.show') ? 'active' : '' }}">Blog</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>

            <div class="nav-menu-mobile-extra">
                <span class="nav-mobile-label">Tài khoản</span>

                <a href="{{ route('doctor.dashboard') }}"
                   class="nav-mobile-action {{ request()->routeIs('doctor.dashboard', 'doctor.manage') ? 'active' : '' }}">
                    Manage
                </a>

                <a href="{{ route('user.profile') }}"
                   class="nav-mobile-action {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="nav-mobile-form">
                    @csrf
                    <button type="submit" class="nav-mobile-button">Logout</button>
                </form>
            </div>
        </nav>
    </div>
</header>