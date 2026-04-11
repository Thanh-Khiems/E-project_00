@extends('layouts.app')

@section('content')

<style>
/* Section */
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

/* Filter menu */
.filter-card {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    flex: 0 0 280px;
}
.filter-card h4 {
    font-weight: 600;
    margin-bottom: 20px;
    color: #007bff;
}
.filter-card input, .filter-card select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-bottom: 15px;
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
}

/* Doctor Cards */
.doctors-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    flex: 1;
}
.doctor-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    flex: 0 0 calc(50% - 10px);
    display: flex;
    flex-direction: column;
    transition: transform 0.3s;
}
.doctor-card:hover {
    transform: translateY(-5px);
}

.doctor-card-top {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    margin-bottom: 15px;
}
.doctor-avatar img {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    object-fit: cover;
}
.doctor-info h5 {
    font-size: 18px;
    font-weight: 700;
    color: #007bff;
    margin-bottom: 6px;
}
.doctor-info p {
    font-size: 14px;
    color: #555;
    display: flex;
    align-items: center;
    margin-bottom: 6px;
}
.doctor-info p i {
    margin-right: 8px;
    color: #007bff;
    min-width: 18px;
}

/* Working schedule */
.doctor-schedule {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.doctor-schedule h6 {
    font-size: 15px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.schedule-day {
    margin-bottom: 12px;
}
.schedule-day-name {
    font-size: 14px;
    font-weight: 600;
    color: #007bff;
    margin-bottom: 8px;
}
.schedule-slots {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.schedule-slot {
    display: inline-block;
    padding: 6px 12px;
    background: #eaf4ff;
    color: #007bff;
    border: 1px solid #b9dcff;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
}
.schedule-empty {
    font-size: 13px;
    color: #999;
    font-style: italic;
}

/* Buttons */
.doctor-card-actions {
    display: flex;
    gap: 10px;
    margin-top: auto;
}
.doctor-card-actions .btn-detail {
    flex: 1;
    padding: 10px 0;
    border: solid 1px #007bff;
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

        <!-- Filter -->
        <div class="filter-card">
            <h4>Filter Bác sĩ</h4>
            <form id="doctorFilterForm">
                <input type="text" name="name" placeholder="Họ và tên">

                <select name="specialty">
                    <option value="">Chuyên khoa</option>
                    <option value="Tim mạch">Tim mạch</option>
                    <option value="Da liễu">Da liễu</option>
                    <option value="Nhi">Nhi</option>
                    <option value="Ngoại chấn thương chỉnh hình">Ngoại chấn thương chỉnh hình</option>
                    <option value="Gây mê - điều trị đau">Gây mê - điều trị đau</option>
                </select>

                <select name="city">
                    <option value="">Thành phố</option>
                    <option value="Hà Nội">Hà Nội</option>
                    <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                </select>

                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <!-- Doctor List -->
        <div class="doctors-list">
            @php
            $doctors = [
                [
                    'name' => 'Đỗ Tất Cường',
                    'title' => 'Giáo sư, Tiến sĩ, Bác sĩ',
                    'specialty' => 'Tim mạch',
                    'center' => 'Trung tâm hồi sức và cấp cứu - Bệnh viện Vinmec',
                    'city' => 'Hà Nội',
                    'avatar' => 'https://i.pravatar.cc/100?img=14',
                    'working_hours' => [
                        [
                            'day' => 'Thứ 2',
                            'slots' => ['08:00 - 10:00', '14:00 - 16:00', '19:00 - 20:30']
                        ],
                        [
                            'day' => 'Thứ 4',
                            'slots' => ['08:30 - 11:00', '13:30 - 15:30']
                        ],
                        [
                            'day' => 'Thứ 6',
                            'slots' => ['09:00 - 11:30']
                        ],
                    ]
                ],
                [
                    'name' => 'Nguyễn Thanh Liêm',
                    'title' => 'Giáo sư, Tiến sĩ, Bác sĩ',
                    'specialty' => 'Nhi',
                    'center' => 'Trung tâm Y học tái tạo & Trị liệu tế bào',
                    'city' => 'Hồ Chí Minh',
                    'avatar' => 'https://i.pravatar.cc/100?img=10',
                    'working_hours' => [
                        [
                            'day' => 'Thứ 3',
                            'slots' => ['07:30 - 09:30', '10:00 - 11:30']
                        ],
                        [
                            'day' => 'Thứ 5',
                            'slots' => ['13:00 - 15:00', '15:30 - 17:00']
                        ],
                        [
                            'day' => 'Thứ 7',
                            'slots' => ['08:00 - 10:30']
                        ],
                    ]
                ],
                [
                    'name' => 'Trần Trung Dũng',
                    'title' => 'Giáo sư, Tiến sĩ, Bác sĩ',
                    'specialty' => 'Ngoại chấn thương chỉnh hình',
                    'center' => 'Trung tâm Cơ xương khớp & Chấn thương - Bệnh viện Vinmec Times City',
                    'city' => 'Hà Nội',
                    'avatar' => 'https://i.pravatar.cc/100?img=11',
                    'working_hours' => [
                        [
                            'day' => 'Thứ 2',
                            'slots' => ['08:00 - 09:30', '10:00 - 11:30']
                        ],
                        [
                            'day' => 'Thứ 4',
                            'slots' => ['14:00 - 16:00']
                        ],
                        [
                            'day' => 'Chủ nhật',
                            'slots' => ['08:00 - 10:00', '10:30 - 12:00']
                        ],
                    ]
                ],
                [
                    'name' => 'Philippe Macaire',
                    'title' => 'Giáo sư, Tiến sĩ, Bác sĩ',
                    'specialty' => 'Gây mê - điều trị đau',
                    'center' => 'Khoa Gây mê giảm đau - Bệnh viện Vinmec Times City',
                    'city' => 'Hồ Chí Minh',
                    'avatar' => 'https://i.pravatar.cc/100?img=12',
                    'working_hours' => [
                        [
                            'day' => 'Thứ 3',
                            'slots' => ['08:00 - 10:00']
                        ],
                        [
                            'day' => 'Thứ 5',
                            'slots' => ['09:00 - 11:00', '14:00 - 16:30']
                        ],
                        [
                            'day' => 'Thứ 7',
                            'slots' => ['13:30 - 15:30', '16:00 - 18:00']
                        ],
                    ]
                ],
            ];
            @endphp

            @foreach($doctors as $doctor)
            <div class="doctor-card">
                <div class="doctor-card-top">
                    <div class="doctor-avatar">
                        <img src="{{ $doctor['avatar'] }}" alt="{{ $doctor['name'] }}">
                    </div>

                    <div class="doctor-info">
                        <h5>{{ $doctor['name'] }}</h5>
                        <p><i class="fas fa-user-graduate"></i> {{ $doctor['title'] }}</p>
                        <p><i class="fas fa-stethoscope"></i> {{ $doctor['specialty'] }}</p>
                        <p><i class="fas fa-hospital"></i> {{ $doctor['center'] }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $doctor['city'] }}</p>
                    </div>
                </div>

                <div class="doctor-schedule">
                    <h6><i class="fas fa-clock"></i> Khung giờ làm việc</h6>

                    @if(!empty($doctor['working_hours']))
                        @foreach($doctor['working_hours'] as $schedule)
                            <div class="schedule-day">
                                <div class="schedule-day-name">{{ $schedule['day'] }}</div>

                                @if(!empty($schedule['slots']))
                                    <div class="schedule-slots">
                                        @foreach($schedule['slots'] as $slot)
                                            <span class="schedule-slot">{{ $slot }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="schedule-empty">Chưa có khung giờ</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="schedule-empty">Bác sĩ chưa cập nhật lịch làm việc</div>
                    @endif
                </div>

                <div class="doctor-card-actions">
                    <button class="btn-detail">Xem chi tiết</button>
                    <button class="btn-book">Đặt hẹn</button>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
