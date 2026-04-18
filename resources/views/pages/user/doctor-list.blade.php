@extends('layouts.app')

@section('title', 'Doctor List')

@section('content')
@php
    $dayLabels = [
        'Mon' => 'Monday',
        'Tue' => 'Tuesday',
        'Wed' => 'Wednesday',
        'Thu' => 'Thursday',
        'Fri' => 'Friday',
        'Sat' => 'Saturday',
        'Sun' => 'Sunday',
    ];

    $renderStars = function ($rating) {
        $rounded = (int) round((float) $rating);
        $rounded = max(0, min(5, $rounded));

        return str_repeat('★', $rounded) . str_repeat('☆', 5 - $rounded);
    };
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .doctor-directory-page {
        background: #f4f7fb;
        padding: 44px 0 80px;
    }

    .doctor-directory-hero {
        background: linear-gradient(135deg, #ffffff 0%, #eef5ff 100%);
        border: 1px solid #e3ecf8;
        border-radius: 30px;
        padding: 34px;
        margin-bottom: 28px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.06);
    }

    .doctor-directory-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #e8f1ff;
        color: #1668e8;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .doctor-directory-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(320px, 0.9fr);
        gap: 28px;
        align-items: center;
    }

    .doctor-directory-title {
        font-size: 48px;
        line-height: 1.08;
        font-weight: 800;
        color: #111827;
        margin-bottom: 16px;
        letter-spacing: -0.03em;
    }

    .doctor-directory-title span {
        color: #1668e8;
        text-decoration: underline;
        text-underline-offset: 7px;
        text-decoration-thickness: 3px;
    }

    .doctor-directory-desc {
        max-width: 720px;
        font-size: 17px;
        line-height: 1.7;
        color: #4b5563;
    }

    .doctor-directory-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .doctor-summary-box {
        background: #fff;
        border: 1px solid #dbe7f6;
        border-radius: 22px;
        padding: 22px 18px;
        text-align: center;
        box-shadow: 0 12px 26px rgba(37, 99, 235, 0.08);
    }

    .doctor-summary-box h3 {
        font-size: 30px;
        line-height: 1;
        color: #1668e8;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .doctor-summary-box p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
        font-weight: 600;
    }

    .doctor-toolbar {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        margin-bottom: 26px;
    }

    .doctor-filter-form {
        display: grid;
        grid-template-columns: 2.1fr 1fr 1fr auto;
        gap: 14px;
        background: #fff;
        padding: 16px;
        border-radius: 22px;
        border: 1px solid #e5edf6;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
    }

    .doctor-filter-form input,
    .doctor-filter-form select {
        width: 100%;
        height: 52px;
        border-radius: 14px;
        border: 1px solid #d5dfeb;
        background: #fff;
        padding: 0px 10px;
        font-size: 15px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
    }

    .doctor-filter-form input:focus,
    .doctor-filter-form select:focus {
        border-color: #1668e8;
        box-shadow: 0 0 0 4px rgba(22, 104, 232, 0.08);
    }

    .doctor-filter-submit {
        height: 52px;
        padding: 0 26px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #1668e8, #1f7bf4);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 22px rgba(22, 104, 232, 0.2);
    }

    .doctor-toolbar-meta {
        background: #fff;
        border: 1px solid #e5edf6;
        border-radius: 18px;
        padding: 14px 18px;
        color: #374151;
        font-size: 15px;
        font-weight: 700;
        white-space: nowrap;
    }

    .doctor-directory-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
    }

    .doctor-directory-card {
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 28px;
        padding: 26px 22px 22px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        text-align: center;
        min-height: 100%;
    }

    .doctor-directory-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
    }

    .doctor-directory-avatar {
        width: 128px;
        height: 128px;
        border-radius: 999px;
        object-fit: cover;
        margin: 0 auto 18px;
        display: block;
        border: 5px solid #eef5ff;
        background: #f3f4f6;
    }

    .doctor-directory-name {
        font-size: 18px;
        line-height: 1.35;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
        min-height: 48px;
    }

    .doctor-directory-specialty {
        color: #1668e8;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .doctor-directory-bio {
        font-size: 14px;
        line-height: 1.7;
        color: #6b7280;
        margin-bottom: 18px;
        min-height: 96px;
        text-align: justify;
    }

    .doctor-directory-meta {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
        text-align: left;
        display: grid;
        gap: 10px;
    }

    .doctor-directory-meta li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #4b5563;
    }

    .doctor-directory-meta i {
        color: #1668e8;
        width: 16px;
        text-align: center;
    }

    .doctor-rating-box {
        margin-top: auto;
        background: #f8fbff;
        border: 1px solid #e3eefc;
        border-radius: 18px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }

    .doctor-rating-stars {
        color: #f59e0b;
        font-size: 16px;
        letter-spacing: 2px;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .doctor-rating-text {
        font-size: 13px;
        color: #6b7280;
    }

    .doctor-rating-text strong {
        color: #111827;
        font-size: 18px;
        margin-right: 6px;
    }

    .doctor-schedule-box {
        background: #fff;
        border: 1px dashed #d6e4f5;
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 18px;
        text-align: left;
    }

    .doctor-schedule-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 12px;
    }

    .doctor-schedule-title i {
        color: #1668e8;
    }

    .doctor-schedule-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px dashed #edf2f7;
    }

    .doctor-schedule-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .doctor-schedule-day {
        font-size: 13px;
        font-weight: 700;
        color: #1668e8;
        min-width: 64px;
    }

    .doctor-schedule-time {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
        flex: 1;
    }

    .doctor-time-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: #edf5ff;
        color: #1668e8;
        font-size: 12px;
        font-weight: 700;
    }

    .doctor-schedule-empty {
        font-size: 13px;
        color: #9ca3af;
        font-style: italic;
        margin: 0;
    }

    .doctor-directory-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .doctor-directory-btn {
        min-height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .doctor-directory-btn-outline {
        border: 1px solid #1668e8;
        color: #1668e8;
        background: #fff;
    }

    .doctor-directory-btn-outline:hover {
        background: #edf5ff;
    }

    .doctor-directory-btn-primary {
        background: linear-gradient(135deg, #1668e8, #1f7bf4);
        color: #fff;
        box-shadow: 0 10px 22px rgba(22, 104, 232, 0.2);
    }

    .doctor-directory-btn-primary:hover {
        opacity: 0.96;
    }

    .doctor-empty-state {
        grid-column: 1 / -1;
        background: #fff;
        border: 1px dashed #cddbeb;
        border-radius: 24px;
        padding: 44px 24px;
        text-align: center;
        color: #6b7280;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
    }

    .doctor-empty-state i {
        font-size: 36px;
        color: #1668e8;
        margin-bottom: 14px;
        display: inline-block;
    }

    @media (max-width: 1280px) {
        .doctor-directory-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1100px) {
        .doctor-directory-hero-grid,
        .doctor-toolbar {
            grid-template-columns: 1fr;
        }

        .doctor-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .doctor-toolbar-meta {
            white-space: normal;
        }
    }

    @media (max-width: 992px) {
        .doctor-directory-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .doctor-directory-title {
            font-size: 38px;
        }
    }

    @media (max-width: 768px) {
        .doctor-directory-page {
            padding: 28px 0 60px;
        }

        .doctor-directory-hero {
            padding: 24px;
            border-radius: 24px;
        }

        .doctor-directory-title {
            font-size: 32px;
        }

        .doctor-directory-summary,
        .doctor-filter-form,
        .doctor-directory-grid,
        .doctor-directory-actions {
            grid-template-columns: 1fr;
        }

        .doctor-directory-name,
        .doctor-directory-bio {
            min-height: auto;
        }
    }
</style>

<section class="doctor-directory-page">
    <div class="container">
        <div class="doctor-directory-hero">
            <span class="doctor-directory-badge">
                <i class="fa-solid fa-user-doctor"></i>
                Doctors available on the platform
            </span>

            <div class="doctor-directory-hero-grid">
                <div>
                    <h1 class="doctor-directory-title">
                        Connect with the <span>right doctor</span> to book an appointment quickly
                    </h1>
                </div>

                <div class="doctor-directory-summary">
                    <div class="doctor-summary-box">
                        <h3>{{ $doctors->count() }}</h3>
                        <p>Visible doctors</p>
                    </div>
                    <div class="doctor-summary-box">
                        <h3>{{ $specialties->count() }}</h3>
                        <p>Specialty</p>
                    </div>
                    <div class="doctor-summary-box">
                        <h3>{{ $cities->count() }}</h3>
                        <p>City</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="doctor-toolbar">
            <form method="GET" action="{{ route('doctors.index') }}" class="doctor-filter-form">
                <input
                    type="text"
                    name="keyword"
                    placeholder="Search by doctor name, degree, specialty..."
                    value="{{ request('keyword') }}"
                >

                <select name="specialty">
                    <option value="">All specialties</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ (string) request('specialty') === (string) $specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>

                <select name="city">
                    <option value="">All cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="doctor-filter-submit">Search</button>
            </form>

            <div class="doctor-toolbar-meta">
                Found <strong>{{ $doctors->count() }}</strong> matching doctors.
            </div>
        </div>

        <div class="doctor-directory-grid">
            @forelse($doctors as $doctor)
                @php
                    $avatar = $doctor->user?->avatar_url ?? asset('images/default-avatar.png');

                    $doctorName = $doctor->name ?: ($doctor->user->full_name ?? 'Doctor');
                    $doctorName = str_starts_with(strtolower($doctorName), 'dr') ? $doctorName : 'Dr. ' . $doctorName;
                    $specialty = optional($doctor->specialty)->name ?? 'Specialty not updated';
                    $experience = $doctor->experience_years
                        ? $doctor->experience_years . '+ years of experience'
                        : 'Experience not updated';
                    $city = $doctor->city ?: 'City not updated';
                    $hospital = $doctor->hospital ?: 'MediConnect Clinic';
                    $bio = $doctor->bio
                        ? \Illuminate\Support\Str::limit(strip_tags($doctor->bio), 110)
                        : 'The doctor is active on the MediConnect platform and ready to provide consultation and care for patients.';
                    $averageRating = round((float) ($doctor->reviews_avg_rating ?? 0), 1);
                    $reviewsCount = (int) ($doctor->reviews_count ?? 0);

                    $groupedSchedules = collect();
                    $now = now();
                    $today = $now->copy()->startOfDay();
                    $windowEnd = $now->copy()->addWeek()->endOfDay();

                    if ($doctor->schedules && $doctor->schedules->count()) {
                        $upcomingOccurrences = collect();

                        foreach ($doctor->schedules as $schedule) {
                            $scheduleStart = \Carbon\Carbon::parse($schedule->start_date)->startOfDay();
                            $scheduleEnd = \Carbon\Carbon::parse($schedule->end_date)->endOfDay();
                            $activeStart = $scheduleStart->greaterThan($today) ? $scheduleStart->copy() : $today->copy();
                            $activeEnd = $scheduleEnd->lessThan($windowEnd) ? $scheduleEnd->copy() : $windowEnd->copy();

                            if ($activeStart->gt($activeEnd)) {
                                continue;
                            }

                            $availableDays = collect(explode(',', (string) $schedule->days))
                                ->map(fn($day) => trim($day))
                                ->filter();

                            for ($date = $activeStart->copy(); $date->lte($activeEnd); $date->addDay()) {
                                if ($availableDays->isNotEmpty() && ! $availableDays->contains($date->format('D'))) {
                                    continue;
                                }

                                if ($date->isSameDay($now) && \Carbon\Carbon::parse($schedule->start_time)->format('H:i:s') <= $now->format('H:i:s')) {
                                    continue;
                                }

                                $upcomingOccurrences->push([
                                    'date_key' => $date->format('Y-m-d'),
                                    'date_label' => $date->isSameDay($now)
                                        ? 'Today'
                                        : (($dayLabels[$date->format('D')] ?? $date->translatedFormat('l')) . ' · ' . $date->format('d/m')),
                                    'start_time' => \Carbon\Carbon::parse($schedule->start_time),
                                    'end_time' => \Carbon\Carbon::parse($schedule->end_time),
                                ]);
                            }
                        }

                        $groupedSchedules = $upcomingOccurrences
                            ->sortBy(fn($item) => $item['date_key'] . ' ' . $item['start_time']->format('H:i:s'))
                            ->groupBy('date_label');
                    }
                @endphp

                <article class="doctor-directory-card">
                    <img
                        src="{{ $avatar }}"
                        alt="{{ $doctorName }}"
                        class="doctor-directory-avatar"
                        onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';"
                    >

                    <h3 class="doctor-directory-name">{{ $doctorName }}</h3>
                    <div class="doctor-directory-specialty">{{ $specialty }}</div>
                    <p class="doctor-directory-bio">{{ $bio }}</p>

                    <ul class="doctor-directory-meta">
                        <li>
                            <i class="fa-solid fa-briefcase-medical"></i>
                            <span>{{ $experience }}</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ $city }}</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-hospital"></i>
                            <span>{{ $hospital }}</span>
                        </li>
                    </ul>

                    <div class="doctor-rating-box">
                        <div class="doctor-rating-stars">{{ $renderStars($reviewsCount > 0 ? $averageRating : 0) }}</div>
                        <div class="doctor-rating-text">
                            @if($reviewsCount > 0)
                                <strong>{{ number_format($averageRating, 1) }}</strong>
                                {{ $reviewsCount }} reviews
                            @else
                                <strong>New</strong>
                                No reviews yet
                            @endif
                        </div>
                    </div>

                    <div class="doctor-schedule-box">
                        <div class="doctor-schedule-title">
                            <i class="fa-regular fa-clock"></i>
                            Schedule
                        </div>

                        @if($groupedSchedules->isNotEmpty())
                            @foreach($groupedSchedules->take(2) as $day => $daySchedules)
                                <div class="doctor-schedule-row">
                                    <div class="doctor-schedule-day">{{ $day }}</div>
                                    <div class="doctor-schedule-time">
                                        @foreach($daySchedules->take(2) as $schedule)
                                            <span class="doctor-time-chip">
                                                {{ $schedule['start_time']->format('H:i') }} - {{ $schedule['end_time']->format('H:i') }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="doctor-schedule-empty">The doctor has not updated their working schedule yet.</p>
                        @endif
                    </div>

                    <div class="doctor-directory-actions">
                        @auth
                            <a href="{{ route('doctor.booking', $doctor->id) }}" class="doctor-directory-btn doctor-directory-btn-outline">
                                View schedule
                            </a>
                            <a href="{{ route('doctor.booking', $doctor->id) }}" class="doctor-directory-btn doctor-directory-btn-primary">
                                Book appointment
                            </a>
                        @else
                            <span class="doctor-directory-btn doctor-directory-btn-outline auth-locked" aria-disabled="true" title="Please log in or register to continue">
                                View schedule
                            </span>
                            <span class="doctor-directory-btn doctor-directory-btn-primary auth-locked" aria-disabled="true" title="Please log in or register to continue">
                                Book appointment
                            </span>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="doctor-empty-state">
                    <i class="fa-solid fa-user-doctor"></i>
                    <h3 style="margin-bottom:10px;color:#111827;font-size:22px;font-weight:800;">No matching doctors found</h3>
                    <p style="margin:0;font-size:15px;line-height:1.7;">There are currently no doctors matching your selected filters. Please try again with a different keyword or specialty.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
