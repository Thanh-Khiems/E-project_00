<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediConnect')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/logo64.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/logo64.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if(Auth::check())
        @include('partials.user-topbar')
    @else
        @include('partials.topbar')
    @endif

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
