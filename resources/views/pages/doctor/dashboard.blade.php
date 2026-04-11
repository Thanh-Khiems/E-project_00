@extends('layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .doctor-manage-page {
        background: #f5f7fb;
        min-height: 100vh;
        padding: 24px;
    }

    .doctor-manage-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
    }

    .doctor-manage-sidebar {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 780px;
    }

    .doctor-manage-brand h2 {
        font-size: 22px;
        font-weight: 800;
        color: #1d4ed8;
        margin-bottom: 8px;
    }

    .doctor-manage-brand p {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 24px;
    }

    .doctor-manage-menu {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .doctor-manage-menu a,
    .doctor-manage-menu button,
    .doctor-manage-menu .menu-static {
        width: 100%;
        display: block;
        text-align: left;
        text-decoration: none;
        border: none;
        background: transparent;
        padding: 12px 14px;
        border-radius: 12px;
        color: #374151;
        font-weight: 600;
        transition: 0.2s ease;
        cursor: pointer;
    }

    .doctor-manage-menu a:hover,
    .doctor-manage-menu button:hover,
    .doctor-manage-menu .menu-static:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .doctor-manage-menu a.active {
        background: #1d4ed8;
        color: #fff;
    }

    .doctor-manage-sidebar-bottom {
        margin-top: 24px;
        border-top: 1px solid #e5e7eb;
        padding-top: 18px;
    }

    .doctor-manage-main {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .doctor-manage-header {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        border-radius: 22px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.25);
    }

    .doctor-manage-header h2 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .doctor-manage-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 15px;
    }

    .doctor-manage-header a {
        display: inline-block;
        text-decoration: none;
        background: #fff;
        color: #1d4ed8;
        font-weight: 700;
        padding: 12px 18px;
        border-radius: 12px;
        white-space: nowrap;
    }

    .doctor-manage-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .doctor-stat-card {
        background: #fff;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .doctor-stat-card h4 {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .doctor-stat-card .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
    }

    .doctor-stat-card .stat-note {
        font-size: 13px;
        color: #10b981;
    }

    .doctor-manage-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .doctor-left-column,
    .doctor-right-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .doctor-card {
        background: #fff;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.05);
    }

    .doctor-card h3,
    .doctor-card h4 {
        margin-bottom: 16px;
        color: #111827;
        font-weight: 800;
    }

    .schedule-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .schedule-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #f9fafb;
    }

    .schedule-time {
        min-width: 120px;
        font-weight: 700;
        color: #1d4ed8;
    }

    .schedule-info {
        flex: 1;
    }

    .schedule-info strong {
        display: block;
        color: #111827;
        margin-bottom: 4px;
    }

    .schedule-info span {
        font-size: 13px;
        color: #6b7280;
    }

    .schedule-status {
        font-size: 13px;
        font-weight: 700;
        padding: 8px 12px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .status-confirmed {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .doctor-manage-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .mini-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .mini-item {
        padding: 12px 14px;
        border-radius: 12px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #374151;
        font-weight: 600;
    }

    .capacity-card {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
    }

    .capacity-card h3 {
        color: #fff;
    }

    .capacity-card p {
        opacity: 0.95;
        margin-bottom: 18px;
    }

    .capacity-card button {
        border: none;
        background: #fff;
        color: #1d4ed8;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 12px;
        cursor: pointer;
    }

    .activity-list {
        margin: 0;
        padding-left: 18px;
        color: #374151;
    }

    .activity-list li {
        margin-bottom: 10px;
    }

    .calendar-box {
        min-height: 220px;
        border-radius: 16px;
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-weight: 600;
        text-align: center;
        padding: 20px;
    }

    @media (max-width: 1200px) {
        .doctor-manage-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .doctor-manage-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .doctor-manage-wrapper {
            grid-template-columns: 1fr;
        }

        .doctor-manage-sidebar {
            min-height: auto;
        }

        .doctor-manage-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .doctor-manage-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .doctor-manage-page {
            padding: 16px;
        }

        .doctor-manage-stats {
            grid-template-columns: 1fr;
        }

        .schedule-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .schedule-time {
            min-width: unset;
        }
    }
</style>

<div class="doctor-manage-page">
    <div class="doctor-manage-wrapper">
        <aside class="doctor-manage-sidebar">
            <div>
                <div class="doctor-manage-brand">
                    <h2>MediConnect</h2>
                    <p>Doctor Appointment Management</p>
                </div>

                <nav class="doctor-manage-menu">
                    <a href="{{ route('doctor.dashboard') }}">Doctor Main</a>
                    <a href="{{ route('doctor.manage') }}" class="active">Manage Appointments</a>
                    <a href="{{ url('/appointments') }}">Appointments</a>
                    <a href="#">Patients</a>
                </nav>
            </div>

            <div class="doctor-manage-sidebar-bottom">
                <div class="doctor-manage-menu">
                    <div class="menu-static">Help Center</div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <section class="doctor-manage-main">
            <div class="doctor-manage-header">
                <div>
                    <h2>Morning, Dr. {{ Auth::user()->full_name }}</h2>
                    <p>You have 8 appointments today and 3 open slots for next week.</p>
                </div>
                <a href="{{ url('/schedule') }}">Schedule</a>
            </div>

            <div class="doctor-manage-stats">
                <div class="doctor-stat-card">
                    <h4>Appointments Today</h4>
                    <div class="stat-number">8</div>
                    <div class="stat-note">+2 from yesterday</div>
                </div>

                <div class="doctor-stat-card">
                    <h4>New Patients</h4>
                    <div class="stat-number">3</div>
                    <div class="stat-note">Updated recently</div>
                </div>

                <div class="doctor-stat-card">
                    <h4>Open Slots</h4>
                    <div class="stat-number">5</div>
                    <div class="stat-note">Still available</div>
                </div>

                <div class="doctor-stat-card">
                    <h4>Weekly Capacity</h4>
                    <div class="stat-number">94%</div>
                    <div class="stat-note">Operating well</div>
                </div>
            </div>

            <div class="doctor-manage-content">
                <div class="doctor-left-column">
                    <div class="doctor-card">
                        <h3>Today's Schedule</h3>

                        <div class="schedule-list">
                            <div class="schedule-item">
                                <div class="schedule-time">08:00 - 08:30</div>
                                <div class="schedule-info">
                                    <strong>Nguyễn Văn A</strong>
                                    <span>Tim mạch - Tái khám định kỳ</span>
                                </div>
                                <div class="schedule-status status-confirmed">Confirmed</div>
                            </div>

                            <div class="schedule-item">
                                <div class="schedule-time">09:00 - 09:30</div>
                                <div class="schedule-info">
                                    <strong>Trần Thị B</strong>
                                    <span>Da liễu - Tư vấn ban đầu</span>
                                </div>
                                <div class="schedule-status status-confirmed">Confirmed</div>
                            </div>

                            <div class="schedule-item">
                                <div class="schedule-time">10:00 - 10:30</div>
                                <div class="schedule-info">
                                    <strong>Lê Minh C</strong>
                                    <span>Khám tổng quát - Theo dõi kết quả</span>
                                </div>
                                <div class="schedule-status status-pending">Pending</div>
                            </div>

                            <div class="schedule-item">
                                <div class="schedule-time">14:00 - 14:30</div>
                                <div class="schedule-info">
                                    <strong>Phạm Thảo D</strong>
                                    <span>Hô hấp - Kiểm tra triệu chứng</span>
                                </div>
                                <div class="schedule-status status-confirmed">Confirmed</div>
                            </div>
                        </div>
                    </div>

                    <div class="doctor-manage-row">
                        <div class="doctor-card">
                            <h3>Requests</h3>
                            <div class="mini-list">
                                <div class="mini-item">Jason Mendoza</div>
                                <div class="mini-item">Maya Lewis</div>
                                <div class="mini-item">Hoàng Minh Tuấn</div>
                            </div>
                        </div>

                        <div class="doctor-card capacity-card">
                            <h3>94% Capacity</h3>
                            <p>3 slots open for next week. Update your availability to optimize bookings.</p>
                            <button type="button">View Slots</button>
                        </div>
                    </div>
                </div>

                <div class="doctor-right-column">
                    <div class="doctor-card">
                        <h4>Recent Activity</h4>
                        <ul class="activity-list">
                            <li>Lab reports updated</li>
                            <li>New message received</li>
                            <li>Prescription signed</li>
                            <li>Schedule adjusted for tomorrow</li>
                        </ul>
                    </div>

                    <div class="doctor-card">
                        <h4>Calendar</h4>
                        <div class="calendar-box">
                            Weekly / monthly doctor calendar area.<br>
                            You can connect real calendar data later.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
