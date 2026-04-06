<div class="hero-right">
    <div class="login-card reveal-up delay-4">
        <div class="login-logo-wrap">
            <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="login-logo-img">
        </div>

        <h2>Log In</h2>

        <form class="login-form" method="POST" action="#">
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
            <a href="/register">Create new account</a>
            <a href="#">Forgot password?</a>
        </div>

        <div class="login-divider">
            <span>Or sign in with</span>
        </div>

        <div class="login-socials">
            <a href="#" class="login-social-btn">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M21.8 12.2c0-.7-.1-1.3-.2-1.9H12v3.6h5.5a4.7 4.7 0 0 1-2 3.1v2.6h3.2c1.9-1.8 3.1-4.4 3.1-7.4Z"/>
                    <path d="M12 22c2.8 0 5.2-.9 6.9-2.5l-3.2-2.6c-.9.6-2.1 1-3.7 1-2.8 0-5.1-1.9-5.9-4.4H2.8v2.7A10 10 0 0 0 12 22Z"/>
                    <path d="M6.1 13.5A6 6 0 0 1 5.8 12c0-.5.1-1 .3-1.5V7.8H2.8A10 10 0 0 0 2 12c0 1.5.4 3 1 4.2l3.1-2.7Z"/>
                    <path d="M12 6.1c1.5 0 2.8.5 3.9 1.5l2.9-2.9A10 10 0 0 0 2.8 7.8l3.3 2.7C6.9 8 9.2 6.1 12 6.1Z"/>
                </svg>
                Google
            </a>

            <a href="#" class="login-social-btn">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.6-1.6h1.7V4.8c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4.2V11H8v3h2.4v8h3.1Z"/>
                </svg>
                Facebook
            </a>

            <a href="#" class="login-social-btn">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M22 5.8c-.7.3-1.5.5-2.3.6.8-.5 1.4-1.2 1.7-2.1-.8.5-1.6.8-2.5 1A4 4 0 0 0 12 8.3c0 .3 0 .6.1.9-3.3-.2-6.3-1.8-8.2-4.2-.4.6-.5 1.3-.5 2 0 1.4.7 2.7 1.9 3.5-.6 0-1.3-.2-1.8-.5v.1c0 2 1.4 3.6 3.2 4-.3.1-.7.1-1 .1-.2 0-.5 0-.7-.1.5 1.6 2 2.7 3.8 2.8A8 8 0 0 1 3 18.6 11.3 11.3 0 0 0 9.1 20c7.4 0 11.5-6.1 11.5-11.5v-.5c.8-.5 1.4-1.1 1.9-1.8Z"/>
                </svg>
                Twitter
            </a>
        </div>
    </div>
</div>