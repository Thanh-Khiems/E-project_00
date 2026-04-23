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
        min-width: 180px;
        text-align: left;
    }

    .slot-option input[type="radio"]:checked + label {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
        box-shadow: 0 8px 20px rgba(29, 78, 216, 0.18);
    }

    .slot-option input[type="radio"]:disabled + label {
        background: #f3f4f6;
        color: #94a3b8;
        border-color: #e5e7eb;
        cursor: not-allowed;
        box-shadow: none;
    }

    .slot-sub {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        font-weight: 500;
        opacity: 0.9;
        line-height: 1.5;
    }

    .slot-capacity {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
    }

    .slot-full {
        background: #fef2f2;
        color: #dc2626;
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

    .booking-submit-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
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
    $avatar = $doctor->user?->avatar_url ?? asset('images/default-avatar.png');

    $dayLabels = [
        'Mon' => 'Monday',
        'Tue' => 'Tuesday',
        'Wed' => 'Wednesday',
        'Thu' => 'Thursday',
        'Fri' => 'Friday',
        'Sat' => 'Saturday',
        'Sun' => 'Sunday',
    ];
    $hasAvailableBookingSlot = $bookingSlots->flatten(1)->contains(fn ($slot) => ! $slot['is_full']);
@endphp

<div class="booking-page">
    <div class="booking-container">

        <div class="booking-header">
            <h2>Book an appointment with a doctor</h2>
            <p>Choose a suitable time slot within the next 7 days before proceeding with the booking.</p>
        </div>

        <div class="booking-grid">
            <div class="booking-card">
                <div class="doctor-profile">
                    <img src="{{ $avatar }}" alt="{{ $doctor->name }}" class="doctor-avatar">

                    <div class="doctor-name">{{ $doctor->name ?? 'Name not updated' }}</div>
                    <div class="doctor-meta">{{ $doctor->degree_display ?? 'Doctor' }}</div>
                    <div class="doctor-meta">{{ optional($doctor->specialty)->name ?? 'Specialty not updated' }}</div>
                </div>

                <div class="doctor-info-list">
                    <div class="doctor-info-item">
                        <strong>Experience</strong>
                        {{ $doctor->experience_years ? $doctor->experience_years . ' years of experience' : 'Not updated' }}
                    </div>

                    <div class="doctor-info-item">
                        <strong>City</strong>
                        {{ $doctor->city ?? 'Not updated' }}
                    </div>

                    <div class="doctor-info-item">
                        <strong>Email</strong>
                        {{ $doctor->user->email ?? 'Not updated' }}
                    </div>

                    <div class="doctor-info-item">
                        <strong>Phone number</strong>
                        {{ $doctor->user->phone ?? 'Not updated' }}
                    </div>
                </div>
            </div>

            <div class="booking-card">
                <div class="schedule-section-title">Choose an appointment time slot</div>
                <div class="schedule-note">
                    The patient selects one suitable time slot from the doctor's published working schedule for the next 7 days.
                </div>

                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    @forelse($bookingSlots as $dateKey => $daySlots)
                        @php
                            $displayDate = \Carbon\Carbon::parse($dateKey);
                        @endphp
                        <div class="schedule-day-block">
                            <div class="schedule-day-header">
                                <div class="schedule-day-name">
                                    {{ $dayLabels[$displayDate->format('D')] ?? $displayDate->translatedFormat('l') }}
                                </div>

                                <div class="schedule-day-date">
                                    {{ $displayDate->format('d/m/Y') }}
                                </div>
                            </div>

                            <div class="slot-list">
                                @foreach($daySlots as $slot)
                                    @php
                                        $inputId = 'slot_' . $slot['schedule_id'] . '_' . $slot['date']->format('Ymd');
                                    @endphp

                                    <div class="slot-option">
                                        <input
                                            type="radio"
                                            name="selected_slot"
                                            id="{{ $inputId }}"
                                            value="{{ $slot['value'] }}"
                                            {{ $slot['is_full'] ? 'disabled' : 'required' }}
                                        >
                                        <label for="{{ $inputId }}">
                                            {{ $slot['start_time']->format('H:i') }} - {{ $slot['end_time']->format('H:i') }}

                                            <span class="slot-sub">
                                                {{ $slot['type'] === 'online' ? 'Online consultation' : 'In-person consultation' }}
                                                @if($slot['location'])
                                                    • {{ $slot['location'] }}
                                                @endif
                                            </span>

                                            @if(! is_null($slot['remaining_slots']))
                                                <span class="slot-capacity {{ $slot['is_full'] ? 'slot-full' : '' }}">
                                                    {{ $slot['is_full'] ? 'Full' : '' . $slot['remaining_slots'] . ' slots left' }}
                                                </span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="empty-slot">
                            The doctor has no valid time slots in the next 7 days, so booking is currently unavailable.
                        </div>
                    @endforelse

                    <div class="booking-submit-box">
                        <button type="submit" class="booking-submit-btn" {{ ! $hasAvailableBookingSlot ? 'disabled' : '' }}>
                            Book appointment
                        </button>
                        <div class="booking-warning">
                            {{ $hasAvailableBookingSlot ? 'Please choose one time slot before booking.' : 'There are no available slots left for booking in the next 7 days.' }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
