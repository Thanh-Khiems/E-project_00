<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediConnect')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/logo64.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/logo64.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @if(Auth::check())
        @if(Auth::user()->role === 'doctor')
            @include('partials.doctor-topbar')
            @include('partials.navbar-doctor')
        @else
            @include('partials.user-topbar')
            @include('partials.navbar')
        @endif
    @else
        @include('partials.topbar')
        @include('partials.navbar')
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
