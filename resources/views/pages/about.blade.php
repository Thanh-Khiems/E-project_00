@extends('layouts.app')

@section('title', 'About MediConnect')

@section('content')
@php
    $aboutDisplayName = auth()->check()
        ? (auth()->user()->full_name ?? 'MediConnect User')
        : 'Lê Hiếu Nghĩa';

    if (auth()->check()) {
        $aboutEditUrl = auth()->user()->role === 'doctor'
            ? route('doctor.dashboard')
            : route('user.profile');
    } else {
        $aboutEditUrl = route('login');
    }
@endphp

<section class="about-page-hero">
    <div class="container about-page-hero-inner">
        <div class="about-page-copy reveal-up delay-2">
            <div class="about-page-badges reveal-up delay-3">
                <span>Reliable Healthcare Ecosystem</span>
                <span>Swift &amp; Easy Booking</span>
                <span>Compassionate Care &amp; Support</span>
            </div>

            <h1 class="about-page-title reveal-up delay-4">
                <span>MediConnect</span> – Your modern, reliable, and person-centered healthcare connection.
            </h1>

            <p class="about-page-lead reveal-up delay-5">
                MediConnect is a premier platform designed to seamlessly connect patients with healthcare professionals
                and medical services in a rapid, transparent, and convenient manner. We believe that quality healthcare
                stems not only from clinical expertise but also from a simplified, intuitive experience that stays by the
                patient's side every step of the way. With a human-centric approach, MediConnect bridges the gap between
                technology and medicine, making information discovery, appointment scheduling, and expert consultations
                more accessible than ever before.
            </p>

            <div class="about-page-actions reveal-up delay-6">
                <a href="{{ route('doctor-list') }}" class="btn btn-primary">Find the Right Doctor</a>
                <a href="#about-story" class="btn btn-outline">Brand Story</a>
            </div>

            <div class="about-page-stats reveal-up delay-6">
                <div class="about-page-stat-card">
                    <h3>15+</h3>
                    <p>Years of Expertise</p>
                </div>
                <div class="about-page-stat-card">
                    <h3>5000+</h3>
                    <p>Successful Consultations</p>
                </div>
                <div class="about-page-stat-card">
                    <h3>50+</h3>
                    <p>Medical Specialists</p>
                </div>
                <div class="about-page-stat-card">
                    <h3>24/7</h3>
                    <p>Concierge Scheduling</p>
                </div>
            </div>
        </div>

        <div class="about-page-visual reveal-up delay-4">
            <div class="about-page-panel">
                <div class="about-page-panel-top">
                    <div>
                        <small>Welcome back !</small>
                        <h2>{{ $aboutDisplayName }}</h2>
                    </div>

                    <a href="{{ $aboutEditUrl }}" class="about-page-panel-action">Edit Information</a>
                </div>

                <div class="about-page-panel-body">
                    <div class="about-page-panel-text">
                        <h3>Connecting you to <span>Trusted Healthcare</span>—anytime, anywhere.</h3>
                        <p>
                            At MediConnect, we envision a future where technology and humanity converge to redefine the
                            healthcare experience. Our mission is to build the leading digital healthcare ecosystem that
                            breaks down traditional barriers, providing everyone with seamless, transparent, and
                            high-quality medical access. By empowering patients with intuitive tools and connecting them
                            to a dedicated network of specialists, we strive to make every healthcare journey more
                            personalized, efficient, and reliable than ever before.
                        </p>

                        <div class="about-page-vision-box">
                            <small>Our Vision &amp; Mission</small>
                            <strong>
                                To be the most trusted digital healthcare bridge, making care more accessible, convenient,
                                and human for all.
                            </strong>
                        </div>
                    </div>

                    <div class="about-page-doctor-wrap">
                        <img src="{{ asset('images/about/about-den.png') }}" alt="Doctor avatar" class="about-page-doctor-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-story" id="about-story">
    <div class="container about-story-inner">
        <div class="about-story-copy reveal-up delay-2">
            <span class="about-story-label">Reliable Healthcare Ecosystem</span>
            <h2>Connecting Care,<br>Advancing Health</h2>
            <p>
                MediConnect was established with a heartfelt mission: to bridge the gap between patients and premium
                medical services. In an increasingly digitized world, we believe that healthcare must evolve to be
                faster, more accessible, and above all, more human-centric.
            </p>
            <p>
                By harmonizing cutting-edge technology with a profound empathy for the patient journey, MediConnect
                offers a unified ecosystem. Within this space, every stage—from discovering the right specialists to
                scheduling appointments effortlessly—is meticulously crafted to be seamless, intuitive, and dedicated to
                your lifelong wellbeing.
            </p>
        </div>

        <div class="about-story-media reveal-up delay-4">
            <div class="about-story-image-box">
                <img src="{{ asset('images/about/BG.png') }}" alt="Hospital reception" class="about-story-image">
            </div>

            <div class="about-story-steps">
                <div class="about-story-step">
                    <span>01</span>
                    <p>Connecting patients with a network of reputable specialists.</p>
                </div>
                <div class="about-story-step">
                    <span>02</span>
                    <p>Streamlining a fast, transparent, and intuitive booking process.</p>
                </div>
                <div class="about-story-step">
                    <span>03</span>
                    <p>Providing round-the-clock service with 24/7 dedicated support.</p>
                </div>
                <div class="about-story-step">
                    <span>04</span>
                    <p>Building a comprehensive and human-centered healthcare ecosystem.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-values">
    <div class="container">
        <div class="about-values-head reveal-up delay-2">
            <span>Core Values</span>
            <h2>What Makes MediConnect Different !</h2>
            <p>
                More than just a booking platform, MediConnect strives to create a holistic healthcare experience
                where trust, convenience, and dedication go hand in hand.
            </p>
        </div>

        <div class="about-values-grid">
            <article class="about-value-card reveal-up delay-2">
                <div class="about-value-icon">+</div>
                <h3>Patient-Centric Approach</h3>
                <p>Every experience in MediConnect is designed to help patients find the right doctor, book fast, and feel supported with dignity.</p>
            </article>

            <article class="about-value-card reveal-up delay-3">
                <div class="about-value-icon">+</div>
                <h3>Trusted Connections</h3>
                <p>We build an ecosystem where patients, doctors, and healthcare facilities connect with transparency, safety, and confidence.</p>
            </article>

            <article class="about-value-card reveal-up delay-4">
                <div class="about-value-icon">+</div>
                <h3>Technology for Care</h3>
                <p>From information lookup and online booking to follow-up support, technology is used to simplify every single step.</p>
            </article>

            <article class="about-value-card reveal-up delay-5">
                <div class="about-value-icon">+</div>
                <h3>Long-term Partnership</h3>
                <p>MediConnect goes beyond one-time appointments to build a continuous, sustainable, and reassuring care journey.</p>
            </article>
        </div>
    </div>
</section>

<section class="about-cta">
    <div class="container">
        <div class="about-cta-box reveal-up delay-2">
            <div class="about-cta-copy">
                <small>MEDICONNECT</small>
                <h2>Beyond Appointments<br>– We Connect You to<br>Peace of Mind</h2>
                <p>
                    At MediConnect, we bridge the gap between technology and compassion, ensuring a seamless healthcare
                    experience for you and your loved ones.
                </p>
            </div>

            <div class="about-cta-contact">
                <span>Support Hotline</span>
                <h3>1900 115 115</h3>
                <p>Email: mediconnect@gmail.com</p>
                <a href="{{ route('contact') }}" class="about-cta-button">Connect with Us</a>
            </div>
        </div>
    </div>
</section>
@endsection
