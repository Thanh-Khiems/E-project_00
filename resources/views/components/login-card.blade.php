<div class="hero-right">
    <div class="login-card reveal-up delay-4">
        <div class="login-logo-wrap">
            <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="login-logo-img">
        </div>

        <h2>Log In</h2>

        <form class="login-form">
            <label for="email">Email:</label>
            <input id="email" type="email">

            <label for="password">Password:</label>
            <input id="password" type="password">

            <div class="remember-row">
                <input type="checkbox" id="remember">
                <label for="remember">remember password ?</label>
            </div>

            <button type="submit" class="login-btn">LOG IN NOW</button>
        </form>

        <div class="login-links">
            <a href="/register">Create new account</a>
            <a href="#">Forgot password?</a>
        </div>
    </div>
</div>