<section class="doctors-section">
    <div class="container">
        <div class="section-heading doctor-heading zoom-in">
            <h2>List of top doctors at MediConnect</h2>
            <span class="section-line"></span>
        </div>

        <div class="doctor-grid">
            @for ($i = 1; $i <= 6; $i++)
                <div class="doctor-card card-pop delay-{{ $i }}">
                    <div class="doctor-card-top">
                        <img
                            src="{{ asset('images/doctors/doctor-1.webp') }}"
                            alt="Doctor"
                            class="doctor-avatar"
                        >

                        <div class="doctor-info">
                            <h3>Dr. Le Hieu Nghia</h3>
                            <span class="doctor-specialty">Cardiology Specialist</span>

                            <div class="doctor-exp">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2a5 5 0 0 1 5 5c0 1.9-1.1 3.6-2.7 4.4A7 7 0 0 1 19 18v1h-2v-1a5 5 0 0 0-10 0v1H5v-1a7 7 0 0 1 4.7-6.6A4.98 4.98 0 0 1 7 7a5 5 0 0 1 5-5Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm7.5 8a3.5 3.5 0 0 1 3.5 3.5V19h-2v-3.5a1.5 1.5 0 0 0-1.5-1.5H18v-2h1.5ZM4.5 12H6v2H4.5A1.5 1.5 0 0 0 3 15.5V19H1v-3.5A3.5 3.5 0 0 1 4.5 12Z"/>
                                </svg>
                                <span>14 years experience</span>
                            </div>
                        </div>
                    </div>

                    <div class="doctor-rating">
                        <div class="doctor-stars">★★★★★</div>
                        <div class="doctor-score">
                            <strong>4.9</strong>
                            <span>(127 reviews)</span>
                        </div>
                    </div>

                    <div class="doctor-actions">
                        <a href="#" class="doctor-btn doctor-btn-light">View Details</a>
                        <a href="#" class="doctor-btn doctor-btn-primary">Book Now</a>
                    </div>
                </div>
            @endfor
        </div>

        <div class="doctor-more-wrap zoom-in delay-3">
            <a href="#" class="doctor-more-btn">
                View All Doctors
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
</section>