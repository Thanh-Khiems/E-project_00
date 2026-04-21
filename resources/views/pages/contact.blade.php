@extends('layouts.app')

@section('title', 'Contact - MediConnect')

@section('content')
<style>
.contact-page-visual-top {
    margin-bottom: 20px;
}

.contact-page-visual-label {
    display: inline-block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #2563eb;
    margin-bottom: 8px;
}

.contact-page-visual-top h3 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
}

.contact-page-visual-top p {
    font-size: 15px;
    line-height: 1.7;
    color: #64748b;
    margin: 0;
}
</style>
<section class="contact-page-hero">
    <div class="contact-page-bg contact-page-bg-one"></div>
    <div class="contact-page-bg contact-page-bg-two"></div>

    <div class="container contact-page-hero-inner">
        <div class="contact-page-copy reveal-up delay-2">
            <div class="contact-page-badges reveal-up delay-3">
                <span>24/7 patient support</span>
                <span>Fast response team</span>
                <span>Reliable care connection</span>
            </div>

            <h1 class="contact-page-title reveal-up delay-4">
                Contact <span>MediConnect</span> for fast support tailored to your needs.
            </h1>

            <p class="contact-page-lead reveal-up delay-5">
                Whether you need appointment guidance, account support, help finding the right doctor, or answers about services,
                the MediConnect team is always ready to support you with a friendly, professional, and clear experience.
            </p>

            <p class="contact-page-sub reveal-up delay-5">
                We prioritize fast responses for requests related to appointments and specialty information,
                user support, and collaboration with doctors or healthcare facilities.
            </p>


            <div class="contact-page-stats reveal-up delay-6">
                <div class="contact-page-stat-card">
                    <h3>24/7</h3>
                    <p>Receive support requests from patients and doctors.</p>
                </div>
                <div class="contact-page-stat-card">
                    <h3>15m</h3>
                    <p>Prioritize fast responses for urgent appointment questions.</p>
                </div>
                <div class="contact-page-stat-card">
                    <h3>100%</h3>
                    <p>Focus on a clear and reassuring care experience.</p>
                </div>
            </div>
        </div>

        <div class="contact-page-visual reveal-up delay-4">
            <div class="contact-page-visual-card">
    <div class="contact-page-visual-top">
        <h3>We are here whenever you need support</h3>
        <p>
            Reach MediConnect through hotline, email, or office contact information below.
            Our team is ready to assist you with appointments, account questions, and general support.
        </p>
    </div>

    <div class="contact-page-quick-list">
        <div class="contact-page-quick-item">
            <strong>Priority hotline</strong>
            <p>1900 115 115</p>
        </div>
        <div class="contact-page-quick-item">
            <strong>Support email</strong>
            <p>mediconnect@gmail.com</p>
        </div>
        <div class="contact-page-quick-item">
            <strong>Working hours</strong>
            <p>Monday - Sunday, 7:00 - 22:00</p>
        </div>
        <div class="contact-page-quick-item">
            <strong>Address</strong>
            <p>123 Nguyen Van Cu, Ninh Kieu, Can Tho</p>
        </div>
    </div>
</div>
</section>

<section class="contact-support">
    <div class="container contact-support-inner">
        <div class="services-page-section-head reveal-up delay-2">
            <span class="contact-page-label">Support channels</span>
            <h2>Every need has the right contact point</h2>
            <p>
                We designed the contact section in the MediConnect spirit: clear, easy to access, and useful for both patients and doctors.
            </p>
        </div>

        <div class="contact-support-grid">
            <article class="contact-support-card reveal-up delay-2">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.5 21 3 13.5 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.2Z"/></svg>
                </div>
                <h3>Support hotline</h3>
                <p>Call directly for quick guidance about appointments, accounts, or services.</p>
                <a href="tel:1900115115">1900 115 115</a>
            </article>

            <article class="contact-support-card reveal-up delay-3">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 5h16a2 2 0 0 1 2 2v.4l-10 6.25L2 7.4V7a2 2 0 0 1 2-2Zm18 4.75-9.47 5.92a1 1 0 0 1-1.06 0L2 9.75V17a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9.75Z"/></svg>
                </div>
                <h3>Contact email</h3>
                <p>Suitable for detailed requests, partnerships, feedback, or support outside peak hours.</p>
                <a href="mailto:mediconnect@gmail.com">mediconnect@gmail.com</a>
            </article>

            <article class="contact-support-card reveal-up delay-4">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.1 7 13 7 13s7-7.9 7-13c0-3.9-3.1-7-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                </div>
                <h3>Support office</h3>
                <p>The official contact address for coordination, business, and partner support activities.</p>
                <p>123 Nguyen Van Cu, Ninh Kieu, Can Tho</p>
            </article>

            <article class="contact-support-card reveal-up delay-5">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1.75A10.25 10.25 0 1 0 22.25 12 10.26 10.26 0 0 0 12 1.75Zm.75 5.5v4.44l3.22 1.92-.77 1.29-4.2-2.5V7.25Z"/></svg>
                </div>
                <h3>Response hours</h3>
                <ul>
                    <li>Monday - Friday: 7:00 - 22:00</li>
                    <li>Saturday - Sunday: 8:00 - 21:00</li>
                    <li>Priority for appointments and urgent requests</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="contact-form-section" id="contact-form">
    <div class="container contact-form-inner">
        <div class="contact-benefit-card reveal-up delay-2">
            <div class="contact-form-card-head">
                <small>Why patients choose us</small>
                <h2>A contact page that truly feels like <span>MediConnect</span></h2>
                <p>
                    More than just a place to leave information, this is a touchpoint where patients and doctors can get support in the right context,
                    with the right expertise and at the right time.
                </p>
            </div>

            <div class="contact-benefit-list">
                <div class="contact-benefit-item">
                    <span>01</span>
                    <div>
                        <h3>Appointment priority</h3>
                        <p>Requests related to appointment changes, confirmations, or support are handled more clearly.</p>
                    </div>
                </div>
                <div class="contact-benefit-item">
                    <span>02</span>
                    <div>
                        <h3>Support for multiple audiences</h3>
                        <p>Suitable for patients, doctors, partners, and users who need consultation before registering.</p>
                    </div>
                </div>
                <div class="contact-benefit-item">
                    <span>03</span>
                    <div>
                        <h3>Consistent information</h3>
                        <p>All hotline, email, address, and schedule details are presented consistently with the brand.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-form-card reveal-up delay-3">
    <div class="contact-form-card-head">
        <small>About MediConnect</small>
        <h2>Connecting patients with <span>trusted healthcare</span></h2>
        <p>
            MediConnect is a modern healthcare platform built to make medical access simpler, faster, and more reliable.
            We help patients connect with suitable doctors, manage appointments conveniently, and receive support whenever needed.
        </p>
    </div>

    <div class="contact-benefit-list">
        <div class="contact-benefit-item">
            <span>01</span>
            <div>
                <h3>Easy appointment booking</h3>
                <p>
                    Patients can quickly find doctors, choose suitable specialties, and schedule appointments in just a few steps.
                </p>
            </div>
        </div>

        <div class="contact-benefit-item">
            <span>02</span>
            <div>
                <h3>Trusted medical connection</h3>
                <p>
                    MediConnect helps bridge patients with doctors and healthcare services through a clear and user-friendly experience.
                </p>
            </div>
        </div>

        <div class="contact-benefit-item">
            <span>03</span>
            <div>
                <h3>Reliable support</h3>
                <p>
                    Our platform is designed to support users with accessible information, responsive assistance, and a reassuring care journey.
                </p>
            </div>
        </div>
    </div>
</div>
</section>

<section class="contact-faq">
    <div class="container contact-faq-inner">
        <div class="contact-faq-head reveal-up delay-2">
            <span class="contact-page-label">Quick answers</span>
            <h2>A few frequently asked questions before you <span>contact us</span></h2>
            <p>
                The content below helps new users quickly understand how MediConnect supports them and when they should contact us directly.
            </p>
        </div>

        <div class="contact-faq-grid">
            <article class="contact-faq-item reveal-up delay-2">
                <h3>How can I change my appointment schedule?</h3>
                <p>You can check your appointment in your account, or contact the hotline for quick support if the appointment is coming soon.</p>
            </article>

            <article class="contact-faq-item reveal-up delay-3">
                <h3>I do not know which specialty to choose yet. What should I do?</h3>
                <p>Send a short description of your needs in the contact form, and the support team will help guide you to a suitable doctor or specialty.</p>
            </article>

            <article class="contact-faq-item reveal-up delay-4">
                <h3>Can doctors contact you for collaboration?</h3>
                <p>Yes. You can choose the “Doctor / partner collaboration” topic in the form so MediConnect can route and respond through the right team.</p>
            </article>

            <article class="contact-faq-item reveal-up delay-5">
                <h3>Does this contact page send real emails yet?</h3>
                <p>The form currently handles the UI and sends the internal request successfully. If you want, you can connect it to email or database storage in the next step.</p>
            </article>
        </div>
    </div>
</section>
@endsection


