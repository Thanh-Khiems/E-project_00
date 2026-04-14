@php
    $topDoctors = $topDoctors ?? \App\Models\Doctor::query()
        ->with(['user', 'specialty'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->where('approval_status', 'approved')
        ->where('status', 'active')
        ->orderByDesc('reviews_avg_rating')
        ->orderByDesc('reviews_count')
        ->orderByDesc('experience_years')
        ->take(6)
        ->get();

    $renderStars = function ($rating) {
        $rounded = (int) round((float) $rating);
        return str_repeat('★', max(0, min(5, $rounded))) . str_repeat('☆', max(0, 5 - max(0, min(5, $rounded))));
    };
@endphp

<section class="doctors-section">
    <div class="container">
        <div class="section-heading doctor-heading zoom-in">
            <h2>List of top doctors at MediConnect</h2>
            <span class="section-line"></span>
        </div>

        <div class="doctor-grid">
            @forelse ($topDoctors as $index => $doctor)
                @php
                    $avatar = ($doctor->user && $doctor->user->avatar)
                        ? asset('storage/avatars/' . $doctor->user->avatar)
                        : asset('images/default-avatar.png');

                    $doctorName = $doctor->name ?: ($doctor->user->full_name ?? 'Bác sĩ');
                    $specialty = optional($doctor->specialty)->name ?? 'Chưa cập nhật chuyên khoa';
                    $experience = $doctor->experience_years
                        ? $doctor->experience_years . ' years experience'
                        : 'Updating experience';
                    $averageRating = round((float) ($doctor->reviews_avg_rating ?? 0), 1);
                    $reviewsCount = (int) ($doctor->reviews_count ?? 0);
                    $displayRating = $reviewsCount > 0 ? number_format($averageRating, 1) : 'New';
                @endphp

                <div class="doctor-card card-pop delay-{{ ($index % 6) + 1 }}">
                    <div class="doctor-card-top">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $doctorName }}"
                            class="doctor-avatar"
                        >

                        <div class="doctor-info">
                            <h3>{{ str_starts_with(strtolower($doctorName), 'dr') ? $doctorName : 'Dr. ' . $doctorName }}</h3>
                            <span class="doctor-specialty">{{ $specialty }}</span>

                            <div class="doctor-exp">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2a5 5 0 0 1 5 5c0 1.9-1.1 3.6-2.7 4.4A7 7 0 0 1 19 18v1h-2v-1a5 5 0 0 0-10 0v1H5v-1a7 7 0 0 1 4.7-6.6A4.98 4.98 0 0 1 7 7a5 5 0 0 1 5-5Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm7.5 8a3.5 3.5 0 0 1 3.5 3.5V19h-2v-3.5a1.5 1.5 0 0 0-1.5-1.5H18v-2h1.5ZM4.5 12H6v2H4.5A1.5 1.5 0 0 0 3 15.5V19H1v-3.5A3.5 3.5 0 0 1 4.5 12Z"/>
                                </svg>
                                <span>{{ $experience }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="doctor-rating">
                        <div class="doctor-stars">{{ $renderStars($reviewsCount > 0 ? $averageRating : 0) }}</div>
                        <div class="doctor-score">
                            <strong>{{ $displayRating }}</strong>
                            <span>
                                @if($reviewsCount > 0)
                                    ({{ $reviewsCount }} reviews)
                                @else
                                    (No reviews yet)
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="doctor-actions">
                        <a href="{{ route('doctor.booking', $doctor->id) }}" class="doctor-btn doctor-btn-light">View Details</a>
                        <a href="{{ route('doctor.booking', $doctor->id) }}" class="doctor-btn doctor-btn-primary">Book Now</a>
                    </div>
                </div>
            @empty
                <div class="doctor-card" style="grid-column: 1 / -1; text-align:center; padding:24px;">
                    Hiện chưa có bác sĩ nào đủ điều kiện hiển thị.
                </div>
            @endforelse
        </div>

        <div class="doctor-more-wrap zoom-in delay-3">
            <a href="{{ route('doctor-list') }}" class="doctor-more-btn">
                View All Doctors
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
</section>
