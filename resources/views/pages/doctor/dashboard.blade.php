@extends('layouts.app')

@section('content')
<style>
    /* ----- RESET & BASE ----- */
    * {
        box-sizing: border-box;
    }

    .doctor-manage-page {
        background: #f5f7fb;
        min-height: 100vh;
        padding: 24px;
        font-family: 'Inter', sans-serif;
    }

    .doctor-manage-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
    }

    /* ----- SIDEBAR ----- */
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
        font-size: 15px;
    }

    .doctor-manage-menu button:hover,
    .doctor-manage-menu .menu-static:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .doctor-manage-menu button.active {
        background: #1d4ed8;
        color: #fff;
    }

    .doctor-manage-sidebar-bottom {
        margin-top: 24px;
        border-top: 1px solid #e5e7eb;
        padding-top: 18px;
    }

    /* ----- MAIN AREA ----- */
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

    .doctor-manage-header button.header-btn {
        display: inline-block;
        border: none;
        background: #fff;
        color: #1d4ed8;
        font-weight: 700;
        padding: 12px 18px;
        border-radius: 12px;
        white-space: nowrap;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.2s;
    }

    .doctor-manage-header button.header-btn:hover {
        background: #f8fafc;
        /* Đã tắt hiệu ứng nảy lên */
        /* transform: translateY(-1px); */
    }
    /* ----- DASHBOARD VIEW CSS ----- */
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
        padding: 24px;
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.05);
    }

    .doctor-card h3,
    .doctor-card h4 {
        margin-bottom: 16px;
        color: #111827;
        font-weight: 800;
        font-size: 18px;
    }

    .schedule-list, .mini-list {
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

    .schedule-info flex: 1; }
    .schedule-info strong { display: block; color: #111827; margin-bottom: 4px; }
    .schedule-info span { font-size: 13px; color: #6b7280; }

    .schedule-status {
        font-size: 13px;
        font-weight: 700;
        padding: 8px 12px;
        border-radius: 999px;
    }
    .status-confirmed { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef3c7; color: #92400e; }

    .doctor-manage-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
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
    .capacity-card h3, .capacity-card p { color: #fff; }
    .capacity-card p { opacity: 0.95; margin-bottom: 18px; font-size: 14px; line-height: 1.5; }
    .capacity-card button {
        border: none; background: #fff; color: #1d4ed8; font-weight: 700; padding: 10px 16px; border-radius: 12px; cursor: pointer;
    }

    .activity-list { padding-left: 18px; color: #374151; font-size: 14px; line-height: 1.6; }
    .calendar-box {
        min-height: 220px; border-radius: 16px; border: 1px dashed #cbd5e1; background: #f8fafc;
        display: flex; align-items: center; justify-content: center; color: #64748b; font-weight: 600; text-align: center; padding: 20px;
    }


    /* ----- SCHEDULE VIEW CSS (Tạo lịch) ----- */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { font-size: 14px; font-weight: 600; color: #374151; }

    .form-control {
        width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;
    }
    .form-control:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15); }
    textarea.form-control { resize: vertical; min-height: 100px; }

    /* Pill buttons chọn ngày */
    .days-wrapper { display: flex; flex-wrap: wrap; gap: 10px; }
    .day-btn { cursor: pointer; }
    .day-btn input { display: none; }
    .day-btn span {
        display: inline-block; padding: 10px 18px; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 20px; font-size: 14px; font-weight: 600; color: #4b5563; transition: 0.2s; user-select: none;
    }
    .day-btn input:checked + span { background-color: #1d4ed8; color: white; border-color: #1d4ed8; }
    .day-btn:hover span { border-color: #1d4ed8; }

    .btn-submit {
        background-color: #1d4ed8; color: white; border: none; padding: 14px 24px; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.2s; width: 100%;
    }
    .btn-submit:hover { background-color: #1e40af; }

    /* Bảng lịch đã tạo */
    .schedule-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .schedule-table th, .schedule-table td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    .schedule-table th { background-color: #f8fafc; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 8px 8px 0 0;}
    .schedule-table tr:last-child td { border-bottom: none; }
    .badge-type { padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; }
    .badge-online { background-color: #dbeafe; color: #1e40af; }
    .badge-inperson { background-color: #fef3c7; color: #92400e; }


    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .doctor-manage-stats { grid-template-columns: repeat(2, 1fr); }
        .doctor-manage-content { grid-template-columns: 1fr; }
    }
    @media (max-width: 900px) {
        .doctor-manage-wrapper { grid-template-columns: 1fr; }
        .doctor-manage-sidebar { min-height: auto; }
        .doctor-manage-header { flex-direction: column; align-items: flex-start; }
        .doctor-manage-row { grid-template-columns: 1fr; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="doctor-manage-page">
    <div class="doctor-manage-wrapper">

        <aside class="doctor-manage-sidebar">
            <div>
                <div class="doctor-manage-brand">
                    <h2>MediConnect</h2>
                    <p>Doctor Appointment</p>
                </div>

                <nav class="doctor-manage-menu">
                    <button type="button" id="btn-tab-dashboard" class="active" onclick="switchTab('dashboard')">Manage Appointments</button>
                    <button type="button" id="btn-tab-schedule" onclick="switchTab('schedule')">Schedule Settings</button>

                    <button type="button">Doctor Main</button>
                    <button type="button">Appointments</button>
                    <button type="button">Patients</button>
                </nav>
            </div>

            <div class="doctor-manage-sidebar-bottom">
                <div class="doctor-manage-menu">
                    <div class="menu-static">Help Center</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="color: #dc2626;">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <section class="doctor-manage-main">

            <div class="doctor-manage-header">
                <div>
                    <h2>Morning, Dr. Phạm Đắc Phú</h2>
                    <p id="header-desc">You have 8 appointments today and 3 open slots for next week.</p>
                </div>
                <button class="header-btn" onclick="switchTab('schedule')">Create Schedule</button>
            </div>

            <div id="view-dashboard">
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
                            </div>
                        </div>

                        <div class="doctor-manage-row">
                            <div class="doctor-card">
                                <h3>Requests</h3>
                                <div class="mini-list">
                                    <div class="mini-item">Jason Mendoza</div>
                                    <div class="mini-item">Maya Lewis</div>
                                </div>
                            </div>

                            <div class="doctor-card capacity-card">
                                <h3>94% Capacity</h3>
                                <p>3 slots open for next week. Update your availability to optimize bookings.</p>
                                <button type="button" onclick="switchTab('schedule')">Update Slots</button>
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
            </div>

            <div id="view-schedule" style="display: none;">

                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="doctor-card">
                    <h3>Create New Working Schedule</h3>

                    <form method="POST" action="{{ route('schedule.store') }}">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Consultation Type</label>
                                <select name="type" class="form-control">
                                    <option value="online">Online Video Call</option>
                                    <option value="in-person">In-person (Clinic)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Max Patients (per day/slot)</label>
                                <input type="number" name="max_patients" class="form-control" placeholder="e.g. 20">
                            </div>

                            <div class="form-group full-width">
                                <label>Working Days</label>
                                <div class="days-wrapper">
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Mon"><span>Mon</span></label>
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Tue"><span>Tue</span></label>
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Wed"><span>Wed</span></label>
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Thu"><span>Thu</span></label>
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Fri"><span>Fri</span></label>
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Sat"><span>Sat</span></label>
                                    <label class="day-btn"><input type="checkbox" name="days[]" value="Sun"><span>Sun</span></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Start Time</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>End Time</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>

                            <div class="form-group full-width">
                                <label>Location (Khu vực làm việc)</label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ Auth::user()->province ?? 'Chưa cập nhật địa điểm' }}"
                                    readonly
                                    style="background-color: #f3f4f6; cursor: not-allowed; color: #6b7280; border-color: #e5e7eb;">

                                <input type="hidden" name="location" value="{{ Auth::user()->province }}">
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">Save & Publish Schedule</button>
                    </form>
                </div>

                <div class="doctor-card" style="margin-top: 24px;">
                    <h3>Upcoming Published Schedules</h3>
                    <div style="overflow-x: auto;">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Date Range</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>15/04/2026</strong> to <strong>30/04/2026</strong></td>
                                    <td>08:00 - 11:30</td>
                                    <td><span class="badge-type badge-inperson">In-person</span></td>
                                    <td>Room 302, Floor 3</td>
                                </tr>
                                <tr>
                                    <td><strong>20/04/2026</strong> to <strong>20/05/2026</strong></td>
                                    <td>14:00 - 16:00</td>
                                    <td><span class="badge-type badge-online">Online</span></td>
                                    <td>Zoom / Meet</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </section>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // 1. Ẩn tất cả các view
        document.getElementById('view-dashboard').style.display = 'none';
        document.getElementById('view-schedule').style.display = 'none';

        // 2. Xóa class 'active' khỏi tất cả các nút ở menu
        document.getElementById('btn-tab-dashboard').classList.remove('active');
        document.getElementById('btn-tab-schedule').classList.remove('active');

        // 3. Hiện view được chọn và thêm 'active' cho nút tương ứng
        if(tabName === 'dashboard') {
            document.getElementById('view-dashboard').style.display = 'block';
            document.getElementById('btn-tab-dashboard').classList.add('active');
            document.getElementById('header-desc').innerText = "You have 8 appointments today and 3 open slots for next week.";
        }
        else if(tabName === 'schedule') {
            document.getElementById('view-schedule').style.display = 'block';
            document.getElementById('btn-tab-schedule').classList.add('active');
            document.getElementById('header-desc').innerText = "Manage your working slots and availability setup.";
        }
    }
</script>
@endsection
