@php
    $doctorFeedbacks = $doctorFeedbacks ?? collect();

    $renderStars = function ($rating) {
        $rounded = (int) round((float) $rating);
        $rounded = max(0, min(5, $rounded));

        return str_repeat('★', $rounded) . str_repeat('☆', 5 - $rounded);
    };
@endphp

<section class="feedback-section">
    <div class="container">
        <div class="section-heading" data-reveal="zoom" data-delay="0">
            <h2>Patient feedback on MediConnect</h2>
            <span class="section-line"></span>
        </div>

        <div class="feedback-grid">
            @forelse($doctorFeedbacks as $index => $feedback)
                @php
                    $patient = $feedback->patient;
                    $doctor = $feedback->doctor;

                    $patientName = $patient?->full_name ?: 'Bệnh nhân MediConnect';
                    $doctorName = $doctor?->name ?: ($doctor?->user?->full_name ?? 'Bác sĩ');
                    $doctorName = str_starts_with(strtolower($doctorName), 'dr') ? $doctorName : 'Dr. ' . $doctorName;
                    $specialty = $doctor?->specialty?->name;

                    $avatar = $patient?->avatar_url ?? asset('images/feedback/patient-1.webp');

                    $reviewText = trim((string) $feedback->review);
                    if ($reviewText === '') {
                        $reviewText = 'Bệnh nhân đã chấm ' . (int) $feedback->rating . '/5 sao cho ' . $doctorName . '.';
                    }
                @endphp

                <article class="feedback-card" data-reveal="up" data-delay="{{ 100 + ($index * 100) }}">
                    <div class="feedback-avatar-wrap">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $patientName }}"
                            class="feedback-avatar"
                            onerror="this.onerror=null;this.src='{{ asset('images/feedback/patient-1.webp') }}';"
                        >
                    </div>

                    <div class="feedback-content">
                        <h3>{{ $patientName }}</h3>
                        <div style="color:#f5b400;font-size:16px;letter-spacing:2px;margin-bottom:10px;">{{ $renderStars($feedback->rating) }}</div>
                        <p style="margin-bottom:12px;">“{{ \Illuminate\Support\Str::limit($reviewText, 140) }}”</p>
                        <div style="font-size:13px;line-height:1.6;color:#6b7280;">
                            Đánh giá cho <strong style="color:#1668e8;">{{ $doctorName }}</strong>
                            @if($specialty)
                                <br><span>{{ $specialty }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column:1 / -1;background:#fff;border:1px dashed #d9e3f0;border-radius:18px;padding:28px 24px;text-align:center;color:#6b7280;">
                    Chưa có đánh giá nào từ bệnh nhân dành cho bác sĩ trên hệ thống.
                </div>
            @endforelse
        </div>
    </div>
</section>
