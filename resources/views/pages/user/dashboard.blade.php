@extends('layouts.app')

@section('content')

    <section class="user-hero-section">
        <div class="container user-hero-container">
            <div class="user-hero-left">
                <div class="user-hero-badges">
                    <div class="user-hero-badge">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2 4 5v6c0 5.25 3.44 10.74 8 12 4.56-1.26 8-6.75 8-12V5l-8-3Zm0 2.18 6 2.25V11c0 4.23-2.67 8.85-6 10-3.33-1.15-6-5.77-6-10V6.43l6-2.25Zm-1 4.32h2v2h2v2h-2v2h-2v-2H9v-2h2v-2Z"/>
                        </svg>
                        <span>Accredited</span>
                    </div>

                    <div class="user-hero-badge">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 1.75A10.25 10.25 0 1 0 22.25 12 10.26 10.26 0 0 0 12 1.75Zm0 18.5A8.25 8.25 0 1 1 20.25 12 8.26 8.26 0 0 1 12 20.25Zm.75-13h-1.5v5.06l4.2 2.52.77-1.28-3.47-2.08Z"/>
                        </svg>
                        <span>24/7 Emergency</span>
                    </div>

                    <div class="user-hero-badge">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="m12 2.5 2.94 5.96 6.58.96-4.76 4.64 1.12 6.55L12 17.52 6.12 20.61l1.12-6.55L2.48 9.42l6.58-.96L12 2.5Z"/>
                        </svg>
                        <span>4.9/5 Rating</span>
                    </div>
                </div>

                <h1 class="user-hero-title">
                    Connecting Patients to
                    <span>Trusted Care</span>, Anytime
                    Anywhere
                </h1>

                <p class="user-hero-desc">
                    MediConnect helps patients connect with trusted doctors, search
                    medical information, and book appointments online anytime,
                    anywhere.
                    <br>
                    Our platform makes healthcare access easier, faster, and more
                    convenient through a seamless digital experience
                </p>

                <div class="user-hero-stats">
                    <div class="user-hero-stat">
                        <h3>15+</h3>
                        <p>Years Experience</p>
                    </div>

                    <div class="user-hero-stat">
                        <h3>5000+</h3>
                        <p>Patients Treated</p>
                    </div>

                    <div class="user-hero-stat">
                        <h3>50+</h3>
                        <p>Medical Experts</p>
                    </div>
                </div>

                <div class="user-hero-actions">
                    <a href="#" class="user-btn-primary">Book Appointment</a>
                    <a href="#" class="user-btn-outline">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2a10 10 0 1 0 10 10A10.01 10.01 0 0 0 12 2Zm-2 14.5v-9l7 4.5Z"/>
                        </svg>
                        <span>Watch Our Story</span>
                    </a>
                </div>

                <div class="user-hero-hotline">
                    <div class="user-hero-hotline-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.5 21 3 13.5 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.2Z"/>
                        </svg>
                    </div>

                    <div class="user-hero-hotline-text">
                        <small>Emergency Hotline</small>
                        <strong>1900 115 115</strong>
                    </div>
                </div>
            </div>

            <div class="user-hero-right">
                <div class="user-hero-welcome">
                    <h2>
                        Welcome back,
                        <span>{{ Auth::user()->full_name }}</span>. How are you today?
                        <br>
                        Book your appointment early to receive more benefits.
                    </h2>
                </div>

                <div class="user-hero-image">
                    <img src="{{ asset('images/doctors/doctor-2.png') }}" alt="Doctor">
                </div>
            </div>
        </div>
    </section>
    @include('components.about-section')
    @include('components.doctors-section')
    @include('components.services-section')
    @include('components.news-section')
    @include('components.feedback-section')
@endsection
