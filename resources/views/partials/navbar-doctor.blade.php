<header class="navbar reveal-up delay-1">
    <div class="container navbar-inner">
        <a href="{{ route('doctor.dashboard') }}" class="logo" aria-label="MediConnect">
            <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="logo-img">
        </a>

        <nav class="nav-menu">
            <a href="{{ route('doctor.dashboard') }}">Home</a>
            <a href="/about">About</a>
            <a href="/services">Services</a>
            <a href="{{ route('doctors.index') }}">Doctor</a>
            <a href="/blog">Blog</a>
            <a href="/contact">Contact</a>
        </nav>
    </div>
</header>
