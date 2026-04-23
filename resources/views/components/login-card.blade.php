<div class="hero-right">
    <div class="login-card reveal-up delay-4">
        <div class="login-logo-wrap">
            <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="login-logo-img">
        </div>

        <h2>Log In</h2>

        @php
            $authRequiredMessage = (request()->boolean('auth_required') || old('redirect'))
                ? (request('notice') ?: 'Please log in to continue using this feature.')
                : null;
        @endphp

        @if (session('success'))
            <div class="auth-recovery-alert mb-3">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="auth-recovery-alert mb-3" style="background:#fef2f2;color:#991b1b;border-color:#fecaca;">{{ session('error') }}</div>
        @endif

        @if ($authRequiredMessage)
            <div class="auth-recovery-alert mb-3" style="background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;">{{ $authRequiredMessage }}</div>
        @endif

        <form class="login-form" method="POST" action="{{ route('login.submit') }}">
            <input type="hidden" name="redirect" value="{{ old('redirect', request('redirect')) }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >
                @error('email')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember password?</span>
                </label>
            </div>

            <button type="submit" class="login-btn">LOG IN NOW</button>
        </form>

        <div class="login-links">
            <a href="{{ route('register') }}">Create new account</a>
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>
    </div>
</div>
