@extends('layouts.app')

@section('content')

<style>
.doctors-section {
    padding: 40px 20px;
    background: #f9f9f9;
}
.doctors-container {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
}

/* Filter */
.filter-card {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    flex: 0 0 280px;
    height: fit-content;
}
.filter-card h4 {
    font-weight: 700;
    margin-bottom: 20px;
    color: #007bff;
}
.filter-card input,
.filter-card select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    margin-bottom: 15px;
    outline: none;
}
.filter-card input:focus,
.filter-card select:focus {
    border-color: #007bff;
}
.filter-card button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: #007bff;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s ease;
}
.filter-card button:hover {
    opacity: 0.92;
}

/* Doctor list */
.doctors-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    flex: 1;
}
.doctor-card {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    flex: 0 0 calc(50% - 10px);
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.doctor-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.10);
}

.doctor-card-top {
    display: flex;
    gap: 18px;
    align-items: flex-start;
    margin-bottom: 15px;
}
.doctor-avatar img {
    width: 96px;
    height: 96px;
    border-radius: 14px;
    object-fit: cover;
    display: block;
    background: #f2f2f2;
}
.doctor-info {
    flex: 1;
}
.doctor-info h5 {
    font-size: 18px;
    font-weight: 700;
    color: #007bff;
    margin-bottom: 8px;
}
.doctor-info p {
    font-size: 14px;
    color: #555;
    display: flex;
    align-items: center;
    margin-bottom: 7px;
}
.doctor-info p i {
    margin-right: 8px;
    color: #007bff;
    min-width: 18px;
}

.doctor-card-actions .btn-book {
    flex: 1;
    padding: 10px 0;
    border: none;
    border-radius: 10px;
    background: #007bff;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    display: inline-block;
}

/* Schedule */
.doctor-schedule {
    margin-top: 12px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.doctor-schedule h6 {
    font-size: 15px;
    font-weight: 700;
    color: #333;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.schedule-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
    padding: 10px 0;
    border-bottom: 1px dashed #eee;
}
.schedule-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}
.schedule-row-day {
    min-width: 75px;
    font-size: 14px;
    font-weight: 700;
    color: #007bff;
    padding-top: 4px;
}
.schedule-row-slots {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    flex: 1;
}
.time-slot-btn {
    border: 1px solid #b9dcff;
    background: #eaf4ff;
    color: #007bff;
    border-radius: 999px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 600;
    cursor: default;
    transition: all 0.2s ease;
}
.time-slot-btn:hover {
    background: #007bff;
    color: #fff;
}
.schedule-note {
    margin-top: 10px;
    font-size: 12px;
    color: #777;
}
.schedule-empty {
    font-size: 13px;
    color: #999;
    font-style: italic;
}

/* Actions */
.doctor-card-actions {
    display: flex;
    gap: 10px;
    margin-top: 18px;
}
.doctor-card-actions .btn-detail {
    flex: 1;
    padding: 10px 0;
    border: 1px solid #007bff;
    border-radius: 10px;
    background: #fff;
    color: #007bff;
    font-weight: 600;
    cursor: pointer;
}
.doctor-card-actions .btn-book {
    flex: 1;
    padding: 10px 0;
    border: none;
    border-radius: 10px;
    background: #007bff;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
}

@media (max-width: 992px) {
    .doctors-container {
        flex-direction: column;
    }
    .filter-card {
        flex: 1;
        margin-bottom: 20px;
    }
    .doctor-card {
        flex: 1 1 100%;
    }
}
</style>

<section class="doctors-section">
    <div class="doctors-container">

        <div class="filter-card">
            <h4>Filter Bác sĩ</h4>

            <form method="GET" action="{{ route('doctor-list') }}" id="doctorFilterForm">
                <input
                    type="text"
                    name="keyword"
                    placeholder="Họ tên / học vị / chuyên khoa"
                    value="{{ request('keyword') }}"
                >

                <select name="specialty">
                    <option value="">Chuyên khoa</option>
                    @php
                        $specialties = $doctors->pluck('specialty.name')
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values();
                    @endphp

                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                            {{ $specialty }}
                        </option>
                    @endforeach
                </select>

                <select name="city">
                    <option value="">Thành phố</option>
                    @php
                        $cities = $doctors->pluck('city')
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values();
                    @endphp

                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="doctors-list">
            @forelse($doctors as $doctor)
                @php
                    $avatar = ($doctor->user && $doctor->user->avatar)
                        ? asset('storage/avatars/' . $doctor->user->avatar)
                        : asset('images/default-avatar.png');

                    $doctorName = $doctor->name ?? 'Chưa cập nhật tên';
                    $doctorTitle = $doctor->degree ?? 'Bác sĩ';
                    $doctorSpecialty = optional($doctor->specialty)->name ?? 'Chưa cập nhật chuyên khoa';
                    $doctorExperience = $doctor->experience_years
                        ? $doctor->experience_years . ' năm kinh nghiệm'
                        : 'Chưa cập nhật kinh nghiệm';
                    $doctorCity = $doctor->city ?? 'Chưa cập nhật';

                    $groupedSchedules = collect();

                    if ($doctor->schedules && $doctor->schedules->count()) {
                        $normalized = $doctor->schedules->flatMap(function ($schedule) {
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

                        $groupedSchedules = $normalized
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

                    $dayLabels = [
                        'Mon' => 'Thứ 2',
                        'Tue' => 'Thứ 3',
                        'Wed' => 'Thứ 4',
                        'Thu' => 'Thứ 5',
                        'Fri' => 'Thứ 6',
                        'Sat' => 'Thứ 7',
                        'Sun' => 'Chủ nhật',
                    ];
                @endphp

                <div class="doctor-card">
                    <div class="doctor-card-top">
                        <div class="doctor-avatar">
                            <img src="{{ $avatar }}" alt="{{ $doctorName }}">
                        </div>

                        <div class="doctor-info">
                            <h5>{{ $doctorName }}</h5>
                            <p><i class="fas fa-user-graduate"></i> {{ $doctorTitle }}</p>
                            <p><i class="fas fa-stethoscope"></i> {{ $doctorSpecialty }}</p>
                            <p><i class="fas fa-briefcase"></i> {{ $doctorExperience }}</p>
                            <p><i class="fas fa-map-marker-alt"></i> {{ $doctorCity }}</p>
                        </div>
                    </div>

                    <div class="doctor-schedule">
                        <h6><i class="fas fa-clock"></i> Lịch khám</h6>

                        @if($groupedSchedules->count())
                            @foreach($groupedSchedules as $day => $daySchedules)
                                <div class="schedule-row">
                                    <div class="schedule-row-day">
                                        {{ $dayLabels[$day] ?? $day }}
                                    </div>

                                    <div class="schedule-row-slots">
                                        @foreach($daySchedules->take(3) as $schedule)
                                            <span class="time-slot-btn">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @php
                                $firstSchedule = $doctor->schedules->sortBy('start_date')->first();
                                $lastSchedule = $doctor->schedules->sortByDesc('end_date')->first();
                            @endphp

                            @if($firstSchedule && $lastSchedule)
                                <div class="schedule-note">
                                    Áp dụng:
                                    {{ \Carbon\Carbon::parse($firstSchedule->start_date)->format('d/m/Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($lastSchedule->end_date)->format('d/m/Y') }}
                                </div>
                            @endif
                        @else
                            <div class="schedule-empty">Bác sĩ chưa cập nhật lịch làm việc</div>
                        @endif
                    </div>

                    <div class="doctor-card-actions">
                        <button type="button" class="btn-detail">Xem chi tiết</button>
                        <a href="{{ route('doctor.booking', $doctor->id) }}" class="btn-book">Đặt hẹn</a>
                    </div>
                </div>
            @empty
                <div class="doctor-card" style="flex: 1 1 100%;">
                    <div class="schedule-empty" style="font-size:15px;">
                        Hiện chưa có bác sĩ nào đã được duyệt hoặc chưa có dữ liệu hiển thị.
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
