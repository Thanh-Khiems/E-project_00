@php
    $homeDoctors = $homeDoctors ?? collect();

    $renderStars = function ($rating) {
        $rounded = (int) round((float) $rating);
        $rounded = max(0, min(5, $rounded));

        return str_repeat('★', $rounded) . str_repeat('☆', 5 - $rounded);
    };
@endphp

<section class="doctors-section">
    <div class="container">
        <div class="section-heading" data-reveal="zoom" data-delay="0">
            <h2>Meet our doctors at MediConnect</h2>
            <span class="section-line"></span>
        </div>

        <div class="doctor-grid">
            @forelse($homeDoctors as $index => $doctor)
                @php
                    $avatar = $doctor->user?->avatar_url ?? asset('images/default-avatar.png');

                    $doctorName = $doctor->name ?: ($doctor->user->full_name ?? 'Doctor');
                    $doctorName = str_starts_with(strtolower($doctorName), 'dr') ? $doctorName : 'Dr. ' . $doctorName;
                    $specialty = optional($doctor->specialty)->name ?? 'Specialty not updated';
                    $bio = $doctor->bio
                        ? \Illuminate\Support\Str::limit(strip_tags($doctor->bio), 120)
                        : 'The doctor is active on the MediConnect system and ready to help patients book appointments quickly.';
                    $experience = $doctor->experience_years
                        ? $doctor->experience_years . '+ years of experience'
                        : 'Experience not updated';
                    $averageRating = round((float) ($doctor->reviews_avg_rating ?? 0), 1);
                    $reviewsCount = (int) ($doctor->reviews_count ?? 0);
                @endphp

                <article class="doctor-card" data-reveal="up" data-delay="{{ 100 + ($index * 100) }}">
                    <div class="doctor-card-top">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $doctorName }}"
                            class="doctor-avatar"
                            onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';"
                        >

                        <div class="doctor-info">
                            <h3>{{ $doctorName }}</h3>
                            <div class="doctor-specialty">{{ $specialty }}</div>
                            <div class="doctor-exp">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>{{ $experience }}</span>
                            </div>
                        </div>
                    </div>

                    <p style="font-size:14px;line-height:1.65;color:#5a5a5a;margin:0 0 16px;min-height:68px;">{{ $bio }}</p>

                    <div class="doctor-rating">
                        <div class="doctor-stars">{{ $renderStars($reviewsCount > 0 ? $averageRating : 0) }}</div>
                        <div class="doctor-score">
                            @if($reviewsCount > 0)
                                <strong>{{ number_format($averageRating, 1) }}</strong>
                                <span>{{ $reviewsCount }} reviews</span>
                            @else
                                <strong>New</strong>
                                <span>No reviews yet</span>
                            @endif
                        </div>
                    </div>

                    <div class="doctor-actions">
                        @auth
                            <a href="{{ route('doctor.booking', $doctor->id) }}" class="doctor-btn doctor-btn-light">View schedule</a>
                            <a href="{{ route('doctor.booking', $doctor->id) }}" class="doctor-btn doctor-btn-primary">Book appointment</a>
                        @else
                            <span class="doctor-btn doctor-btn-light auth-locked" aria-disabled="true" title="Please log in or register to continue">View schedule</span>
                            <span class="doctor-btn doctor-btn-primary auth-locked" aria-disabled="true" title="Please log in or register to continue">Book appointment</span>
                        @endauth
                    </div>
                </article>
            @empty
                <div style="grid-column:1 / -1;background:#fff;border:1px dashed #d9e3f0;border-radius:18px;padding:28px 24px;text-align:center;color:#6b7280;">
                    There are currently no doctors eligible to be displayed on the homepage.
                </div>
            @endforelse
        </div>

        <div class="doctor-more-wrap zoom-in delay-3">
            <a href="{{ route('doctors.index') }}" class="doctor-more-btn">
                View All Doctors
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
</section>
