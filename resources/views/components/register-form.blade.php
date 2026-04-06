<section class="register-page">
    <div class="container">
        <div class="register-card" data-reveal="zoom" data-delay="0">
            <div class="register-logo-wrap">
                <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="register-logo-img">
            </div>

            <form class="register-form">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input id="full_name" type="text">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input id="phone" type="text">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="gender-group">
                        <label class="radio-item">
                            <input type="radio" name="gender" value="male">
                            <span>Male</span>
                        </label>

                        <label class="radio-item">
                            <input type="radio" name="gender" value="female">
                            <span>Female</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input id="address" type="text">
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" type="date">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" type="password">
                </div>

                <button type="submit" class="register-submit-btn">Register an account</button>

                <div class="register-socials">
                    <a href="#" class="register-social-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M21.8 12.2c0-.7-.1-1.3-.2-1.9H12v3.6h5.5a4.7 4.7 0 0 1-2 3.1v2.6h3.2c1.9-1.8 3.1-4.4 3.1-7.4Z"/>
                            <path d="M12 22c2.8 0 5.2-.9 6.9-2.5l-3.2-2.6c-.9.6-2.1 1-3.7 1-2.8 0-5.1-1.9-5.9-4.4H2.8v2.7A10 10 0 0 0 12 22Z"/>
                            <path d="M6.1 13.5A6 6 0 0 1 5.8 12c0-.5.1-1 .3-1.5V7.8H2.8A10 10 0 0 0 2 12c0 1.5.4 3 1 4.2l3.1-2.7Z"/>
                            <path d="M12 6.1c1.5 0 2.8.5 3.9 1.5l2.9-2.9A10 10 0 0 0 2.8 7.8l3.3 2.7C6.9 8 9.2 6.1 12 6.1Z"/>
                        </svg>
                        Google
                    </a>

                    <a href="#" class="register-social-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.6-1.6h1.7V4.8c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4.2V11H8v3h2.4v8h3.1Z"/>
                        </svg>
                        Facebook
                    </a>

                    <a href="#" class="register-social-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M22 5.8c-.7.3-1.5.5-2.3.6.8-.5 1.4-1.2 1.7-2.1-.8.5-1.6.8-2.5 1A4 4 0 0 0 12 8.3c0 .3 0 .6.1.9-3.3-.2-6.3-1.8-8.2-4.2-.4.6-.5 1.3-.5 2 0 1.4.7 2.7 1.9 3.5-.6 0-1.3-.2-1.8-.5v.1c0 2 1.4 3.6 3.2 4-.3.1-.7.1-1 .1-.2 0-.5 0-.7-.1.5 1.6 2 2.7 3.8 2.8A8 8 0 0 1 3 18.6 11.3 11.3 0 0 0 9.1 20c7.4 0 11.5-6.1 11.5-11.5v-.5c.8-.5 1.4-1.1 1.9-1.8Z"/>
                        </svg>
                        Twitter
                    </a>
                </div>

                <p class="register-login-text">
                    Already have an account?
                    <a href="/#">Log in</a>
                </p>
            </form>
        </div>
    </div>
</section>