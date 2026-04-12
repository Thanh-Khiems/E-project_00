@extends('layouts.app')

@section('content')
<style>
    .booking-page {
        background: #f5f7fb;
        min-height: 100vh;
        padding: 30px 20px;
    }

    .booking-container {
        max-width: 1180px;
        margin: 0 auto;
    }

    .booking-header {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        border-radius: 22px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.18);
    }

    .booking-header h2 {
        margin: 0 0 8px;
        font-size: 30px;
        font-weight: 800;
    }

    .booking-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 15px;
    }

    .booking-grid {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 24px;
    }

    .booking-card {
        background: #fff;
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
    }

    .doctor-profile {
        text-align: center;
    }

    .doctor-avatar {
        width: 120px;
        height: 120px;
        border-radius: 20px;
        object-fit: cover;
        display: block;
        margin: 0 auto 18px;
        border: 4px solid #eff6ff;
        background: #f3f4f6;
    }

    .doctor-name {
        font-size: 26px;
        font-weight: 800;
        color: #1d4ed8;
        margin-bottom: 8px;
    }

    .doctor-meta {
        color: #6b7280;
        font-size: 15px;
        margin-bottom: 8px;
    }

    .doctor-info-list {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        text-align: left;
    }

    .doctor-info-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 12px 14px;
        font-size: 14px;
        color: #374151;
    }

    .doctor-info-item strong {
        color: #111827;
        display: block;
        margin-bottom: 4px;
    }

    .schedule-section-title {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 18px;
        color: #111827;
    }

    .schedule-note {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 18px;
    }

    .schedule-day-block {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px;
        margin-bottom: 18px;
        background: #fcfdff;
    }

    .schedule-day-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .schedule-day-name {
        font-size: 18px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .schedule-day-date {
        font-size: 13px;
        color: #6b7280;
    }

    .slot-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .slot-option input[type="radio"] {
        display: none;
    }

    .slot-option label {
        display: inline-block;
        padding: 12px 16px;
        border-radius: 14px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        color: #334155;
        transition: 0.2s ease;
        min-width: 140px;
        text-align: center;
    }

    .slot-option input[type="radio"]:checked + label {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
        box-shadow: 0 8px 20px rgba(29, 78, 216, 0.18);
    }

    .slot-sub {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        font-weight: 500;
        opacity: 0.9;
    }

    .empty-slot {
        padding: 14px;
        border-radius: 14px;
        background: #fff7ed;
        color: #9a3412;
        font-size: 14px;
        font-weight: 600;
    }

    .booking-submit-box {
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .booking-submit-btn {
        width: 100%;
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        padding: 16px 20px;
        cursor: pointer;
    }

    .booking-submit-btn:hover {
        opacity: 0.96;
    }

    .booking-warning {
        margin-top: 12px;
        font-size: 13px;
        color: #6b7280;
        text-align: center;
    }

    @media (max-width: 992px) {
        .booking-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $avatar = ($doctor->user && $doctor->user->avatar)
        ? asset('storage/avatars/' . $doctor->user->avatar)
        : asset('images/default-avatar.png');

    $dayLabels = [
        'Mon' => 'Thứ 2',
        'Tue' => 'Thứ 3',
        'Wed' => 'Thứ 4',
        'Thu' => 'Thứ 5',
        'Fri' => 'Thứ 6',
        'Sat' => 'Thứ 7',
        'Sun' => 'Chủ nhật',
    ];

    $normalizedSchedules = collect();

    if ($doctor->schedules && $doctor->schedules->count()) {
        $normalizedSchedules = $doctor->schedules->flatMap(function ($schedule) {
            $days = collect(explode(',', $schedule->days))
                ->map(fn($day) => trim($day))
                ->filter();

            if ($days->isEmpty()) {
                return collect([$schedule]);
            }

            return $days->map(function ($day) use ($schedule) {
                $clone = clone $schedule;
                $clone->display_day = $day;
                return $clone;
            });
        });

        $dayOrder = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        $normalizedSchedules = $normalizedSchedules
            ->sortBy(function ($schedule) use ($dayOrder) {
                $day = $schedule->display_day ?? trim($schedule->days);
                $dayIndex = array_search($day, $dayOrder);
                $timeValue = strtotime($schedule->start_time);

                return sprintf('%02d-%010d', $dayIndex !== false ? $dayIndex : 99, $timeValue);
            })
            ->groupBy(function ($schedule) {
                return $schedule->display_day ?? trim($schedule->days);
            });
    }
@endphp

<div class="booking-page">
    <div class="booking-container">

        <div class="booking-header">
            <h2>Đặt lịch khám với bác sĩ</h2>
            <p>Chọn khung giờ phù hợp với nhu cầu của bạn trước khi tiến hành đặt hẹn.</p>
        </div>

        <div class="booking-grid">
            <div class="booking-card">
                <div class="doctor-profile">
                    <img src="{{ $avatar }}" alt="{{ $doctor->name }}" class="doctor-avatar">

                    <div class="doctor-name">{{ $doctor->name ?? 'Chưa cập nhật tên' }}</div>
                    <div class="doctor-meta">{{ $doctor->degree ?? 'Bác sĩ' }}</div>
                    <div class="doctor-meta">{{ optional($doctor->specialty)->name ?? 'Chưa cập nhật chuyên khoa' }}</div>
                </div>

                <div class="doctor-info-list">
                    <div class="doctor-info-item">
                        <strong>Kinh nghiệm</strong>
                        {{ $doctor->experience_years ? $doctor->experience_years . ' năm kinh nghiệm' : 'Chưa cập nhật' }}
                    </div>

                    <div class="doctor-info-item">
                        <strong>Thành phố</strong>
                        {{ $doctor->city ?? 'Chưa cập nhật' }}
                    </div>

                    <div class="doctor-info-item">
                        <strong>Email</strong>
                        {{ $doctor->user->email ?? 'Chưa cập nhật' }}
                    </div>

                    <div class="doctor-info-item">
                        <strong>Số điện thoại</strong>
                        {{ $doctor->user->phone ?? 'Chưa cập nhật' }}
                    </div>
                </div>
            </div>

            <div class="booking-card">
                <div class="schedule-section-title">Chọn khung giờ khám</div>
                <div class="schedule-note">
                    Bệnh nhân chọn 1 khung giờ phù hợp từ lịch làm việc mà bác sĩ đã công bố.
                </div>

                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    @forelse($normalizedSchedules as $day => $daySchedules)
                        <div class="schedule-day-block">
                            <div class="schedule-day-header">
                                <div class="schedule-day-name">
                                    {{ $dayLabels[$day] ?? $day }}
                                </div>

                                @php
                                    $first = $daySchedules->sortBy('start_date')->first();
                                    $last = $daySchedules->sortByDesc('end_date')->first();
                                @endphp

                                <div class="schedule-day-date">
                                    Áp dụng:
                                    {{ \Carbon\Carbon::parse($first->start_date)->format('d/m/Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($last->end_date)->format('d/m/Y') }}
                                </div>
                            </div>

                            <div class="slot-list">
                                @foreach($daySchedules->take(3) as $slot)
                                    @php
                                        $slotValue = $slot->id . '|' . ($slot->display_day ?? $slot->days);
                                    @endphp

                                    <div class="slot-option">
                                        <input
                                            type="radio"
                                            name="selected_slot"
                                            id="slot_{{ $slot->id }}_{{ $loop->parent->index ?? 0 }}_{{ $loop->index }}"
                                            value="{{ $slotValue }}"
                                            required
                                        >
                                        <label for="slot_{{ $slot->id }}_{{ $loop->parent->index ?? 0 }}_{{ $loop->index }}">
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}

                                            <span class="slot-sub">
                                                {{ $slot->type === 'online' ? 'Khám online' : 'Khám trực tiếp' }}
                                                @if($slot->location)
                                                    • {{ $slot->location }}
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="empty-slot">
                            Bác sĩ chưa cập nhật lịch làm việc nên hiện chưa thể đặt hẹn.
                        </div>
                    @endforelse

                    <div class="booking-submit-box">
                        <button type="submit" class="booking-submit-btn">
                            Đặt lịch hẹn
                        </button>
                        <div class="booking-warning">
                            Vui lòng chọn 1 khung giờ trước khi đặt lịch.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
