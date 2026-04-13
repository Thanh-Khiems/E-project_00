@php
    $variant = $variant ?? 'email';
    $backRoute = $backRoute ?? route('login');
@endphp

<section class="auth-recovery-section">
    <div class="container auth-recovery-inner">
        <div class="auth-recovery-card">
            <div class="auth-recovery-logo-wrap">
                <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="auth-recovery-logo-img">
            </div>

            <a href="{{ $backRoute }}" class="auth-recovery-back">&larr; Back</a>

            @if($variant === 'email')
                <h1>Forgot password</h1>
                <p>Enter your email address and we will send you a verification code to reset your password.</p>

                <form method="POST" action="{{ route('password.email') }}" class="auth-recovery-form">
                    @csrf
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    @error('email')<small class="form-error">{{ $message }}</small>@enderror
                    <button type="submit" class="auth-recovery-btn">Continue</button>
                </form>
            @elseif($variant === 'otp')
                <h1>Enter Verification code</h1>
                <p>Enter the 6-digit verification code sent to <strong>{{ $email }}</strong>.</p>

                @if(session('success'))
                    <div class="auth-recovery-alert">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('password.otp.verify') }}" class="auth-recovery-form">
                    @csrf
                    <label for="otp">Verification code</label>
                    <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" value="{{ old('otp') }}" placeholder="Enter 6-digit code" required>
                    @error('otp')<small class="form-error">{{ $message }}</small>@enderror
                    <button type="submit" class="auth-recovery-btn">Next</button>
                </form>
            @else
                <h1>Create New Password</h1>
                <p>Create a new password for <strong>{{ $email }}</strong>.</p>

                <form method="POST" action="{{ route('password.update.custom') }}" class="auth-recovery-form">
                    @csrf
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter new password" required>
                    @error('password')<small class="form-error">{{ $message }}</small>@enderror

                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password" required>

                    <button type="submit" class="auth-recovery-btn">Continue</button>
                </form>
            @endif
        </div>
    </div>
</section>
