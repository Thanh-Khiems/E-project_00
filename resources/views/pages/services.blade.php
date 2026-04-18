@extends('layouts.app')

@section('title', 'Services - MediConnect')

@section('content')
<section class="services-page-hero">
    <div class="services-page-hero-bg services-page-hero-bg-one"></div>
    <div class="services-page-hero-bg services-page-hero-bg-two"></div>

    <div class="container services-page-hero-inner">
        <div class="services-page-copy reveal-up delay-2">
            <div class="services-page-badges reveal-up delay-3">
                <span>Fast appointment booking</span>
                <span>Support for multiple specialties</span>
                <span>24/7 support</span>
            </div>

            <h1 class="services-page-title reveal-up delay-4">
                Comprehensive digital healthcare services from <span>MediConnect</span>
            </h1>

            <p class="services-page-lead reveal-up delay-5">
                MediConnect offers a modern healthcare ecosystem where patients can easily
                find the right doctor, book the right appointment, and receive timely support throughout their care journey.
            </p>

            <p class="services-page-sub reveal-up delay-5">
                We combine technology, user experience, and professional expertise to make every healthcare interaction
                clearer, more convenient, and more reassuring.
            </p>

            <div class="services-page-actions reveal-up delay-6">
                <a href="{{ route('doctors.index') }}" class="btn btn-primary">Find a doctor now</a>
                <a href="#services-list" class="btn btn-outline">Explore services</a>
            </div>

            <div class="services-page-stats reveal-up delay-6">
                <div class="services-page-stat-card">
                    <h3>10+</h3>
                    <p>Featured care services</p>
                </div>
                <div class="services-page-stat-card">
                    <h3>24/7</h3>
                    <p>Booking support & consultation</p>
                </div>
                <div class="services-page-stat-card">
                    <h3>100%</h3>
                    <p>Focused on the patient experience</p>
                </div>
            </div>
        </div>

        <div class="services-page-visual reveal-up delay-4">
            <div class="services-page-panel">
                <div class="services-page-panel-head">
                    <div>
                        <small>Medical service overview</small>
                        <h2>Professional care<span>.</span></h2>
                    </div>
                    <div class="services-page-chip">MediConnect Services</div>
                </div>

                <div class="services-page-panel-body">
                    <div class="services-page-image-wrap">
                        <img src="{{ asset('images/services/service-main.webp') }}" alt="MediConnect services" class="services-page-image">
                        <div class="services-page-floating-card">
                            <strong>Trusted Care</strong>
                            <p>Sync booking, consultation, and follow-up easily in one single platform.</p>
                        </div>
                    </div>

                    <div class="services-page-panel-grid">
                        <div class="services-page-mini-card">
                            <span>01</span>
                            <h3>General checkup</h3>
                            <p>Book appointments quickly with a transparent and easy-to-follow process.</p>
                        </div>
                        <div class="services-page-mini-card">
                            <span>02</span>
                            <h3>Specialty</h3>
                            <p>Connect with the right doctor based on specific treatment needs.</p>
                        </div>
                        <div class="services-page-mini-card">
                            <span>03</span>
                            <h3>Track appointments</h3>
                            <p>Receive reminders, manage statuses, and get convenient updates.</p>
                        </div>
                        <div class="services-page-mini-card">
                            <span>04</span>
                            <h3>Dedicated support</h3>
                            <p>Support before, during, and after booking.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-page-section" id="services-list">
    <div class="container">
        <div class="services-page-section-head reveal-up delay-2">
            <span class="services-page-label">Service categories</span>
            <h2>Featured services on MediConnect</h2>
            <p>
                Each service is designed to help patients access healthcare more conveniently,
                while optimizing the experience from information lookup to appointment completion.
            </p>
        </div>

        <div class="services-page-grid">
            <article class="services-page-card reveal-up delay-2">
                <div class="services-page-card-icon">✚</div>
                <h3>Book an appointment online</h3>
                <p>Choose a doctor, select a suitable time slot, and confirm the appointment in just a few simple steps.</p>
                <ul>
                    <li>Clear process</li>
                    <li>Save waiting time</li>
                    <li>Easy booking history management</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-3">
                <div class="services-page-card-icon">✚</div>
                <h3>Specialty consultation</h3>
                <p>Connect with doctors by specialty to receive guidance suited to your condition.</p>
                <ul>
                    <li>Support for many specialties</li>
                    <li>Transparent doctor information</li>
                    <li>Easy to choose based on your needs</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-4">
                <div class="services-page-card-icon">✚</div>
                <h3>Track visits</h3>
                <p>Check appointment status, receive reminders, and proactively manage your time more effectively.</p>
                <ul>
                    <li>Smart reminders</li>
                    <li>Centralized appointment information</li>
                    <li>Reduce missed follow-up visits</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-5">
                <div class="services-page-card-icon">✚</div>
                <h3>Convenient patient profile</h3>
                <p>Manage personal information and interaction history in a friendly, easy-to-use interface.</p>
                <ul>
                    <li>Quick profile updates</li>
                    <li>Improve continuity of care</li>
                    <li>Intuitive actions</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-6">
                <div class="services-page-card-icon">✚</div>
                <h3>Doctor & coordination support</h3>
                <p>Support working schedule management, optimize intake workflows, and improve the patient service experience.</p>
                <ul>
                    <li>Efficient schedule organization</li>
                    <li>Reduce manual work</li>
                    <li>Improve internal coordination</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-7">
                <div class="services-page-card-icon">✚</div>
                <h3>Post-booking support</h3>
                <p>MediConnect does not stop at creating appointments, but continues to support patients throughout the care journey.</p>
                <ul>
                    <li>Post-confirmation support</li>
                    <li>Clear, easy-to-find information</li>
                    <li>Increase patient confidence</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="services-process">
    <div class="container services-process-inner">
        <div class="services-process-copy reveal-up delay-2">
            <span class="services-page-label">Experience flow</span>
            <h2>A simple, fast, and professional service journey</h2>
            <p>
                We designed the process to be streamlined so patients can access services easily,
                while still ensuring clarity, control, and reliability at every step.
            </p>
        </div>

        <div class="services-process-steps">
            <div class="services-process-step reveal-up delay-3">
                <span>01</span>
                <div>
                    <h3>Find the right service</h3>
                    <p>Explore specialties, doctors, and choose services that match your current needs.</p>
                </div>
            </div>
            <div class="services-process-step reveal-up delay-4">
                <span>02</span>
                <div>
                    <h3>Flexible appointment booking</h3>
                    <p>Choose a suitable time, confirm quickly, and receive detailed information right in the system.</p>
                </div>
            </div>
            <div class="services-process-step reveal-up delay-5">
                <span>03</span>
                <div>
                    <h3>Track & update</h3>
                    <p>Manage appointments, update statuses, and make changes proactively when needed.</p>
                </div>
            </div>
            <div class="services-process-step reveal-up delay-6">
                <span>04</span>
                <div>
                    <h3>Receive continuous support</h3>
                    <p>The MediConnect team is always ready to support you so the care experience feels more complete.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-advantages">
    <div class="container">
        <div class="services-page-section-head reveal-up delay-2">
            <span class="services-page-label">MediConnect advantages</span>
            <h2>Why do users trust and choose us?</h2>
            <p>
                Beyond providing features, MediConnect also focuses on peace of mind and transparency
                as well as a smooth overall user experience.
            </p>
        </div>

        <div class="services-advantages-grid">
            <div class="services-advantage-item reveal-up delay-2">
                <h3>User-friendly interface</h3>
                <p>Easy to use even for new users, with quick access to important information.</p>
            </div>
            <div class="services-advantage-item reveal-up delay-3">
                <h3>Transparent information</h3>
                <p>Patients can easily track appointments, processing statuses, and choose more suitable services.</p>
            </div>
            <div class="services-advantage-item reveal-up delay-4">
                <h3>Professional direction</h3>
                <p>Designed around healthcare, creating a trustworthy and modern feel across the platform.</p>
            </div>
            <div class="services-advantage-item reveal-up delay-5">
                <h3>Scalability</h3>
                <p>Ready to expand with more specialties, features, and support workflows in the future.</p>
            </div>
        </div>
    </div>
</section>

<section class="services-page-cta">
    <div class="container">
        <div class="services-page-cta-box reveal-up delay-2">
            <div class="services-page-cta-copy">
                <small>MediConnect Services</small>
                <h2>Ready to start a more convenient healthcare journey?</h2>
                <p>
                    Explore MediConnect's doctors and featured services to find the best option for you.
                </p>
            </div>

            <div class="services-page-cta-actions">
                <a href="{{ route('doctors.index') }}" class="services-page-cta-primary">View doctor list</a>
                <a href="{{ url('/about') }}" class="services-page-cta-secondary">Learn about MediConnect</a>
            </div>
        </div>
    </div>
</section>
@endsection
