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
        font-family: 'Inter', sans-serif;
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

    .schedule-info { flex: 1; }
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

    .days-wrapper { display: flex; flex-wrap: wrap; gap: 10px; }
    .day-btn { cursor: pointer; }
    .day-btn input { display: none; }
    .day-btn span {
        display: inline-block; padding: 10px 18px; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 20px; font-size: 14px; font-weight: 600; color: #4b5563; transition: 0.2s; user-select: none;
    }
    .day-btn input:checked + span { background-color: #1d4ed8; color: white; border-color: #1d4ed8; }
    .day-btn:hover span { border-color: #1d4ed8; }

    .btn-submit,
    .btn-cancel-edit {
        border: none;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-submit {
        background-color: #1d4ed8;
        color: white;
        width: 100%;
    }
    .btn-submit:hover { background-color: #1e40af; }

    .btn-cancel-edit {
        background: #e5e7eb;
        color: #374151;
        display: none;
        margin-top: 12px;
        width: 100%;
    }

    .schedule-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .schedule-table th, .schedule-table td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; vertical-align: top; }
    .schedule-table th { background-color: #f8fafc; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .schedule-table tr:last-child td { border-bottom: none; }
    .badge-type { padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; }
    .badge-online { background-color: #dbeafe; color: #1e40af; }
    .badge-inperson { background-color: #fef3c7; color: #92400e; }

    .table-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-edit-row,
    .btn-delete-row {
        border: none;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-edit-row {
        background: #f59e0b;
        color: white;
    }

    .btn-delete-row {
        background: #ef4444;
        color: white;
    }

    .alert-success-box,
    .alert-error-box,
    .edit-mode-box {
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        transition: opacity 0.4s ease;
    }

    .alert-success-box {
        background: #dcfce7;
        color: #166534;
    }

    .alert-error-box {
        background: #fee2e2;
        color: #991b1b;
    }

    .edit-mode-box {
        display: none;
        background: #fff7ed;
        color: #9a3412;
        font-weight: 700;
    }

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

    .doctor-card,
    .doctor-stat-card,
    .schedule-item,
    .mini-item,
    .capacity-card,
    .calendar-box,
    .doctor-manage-header,
    .doctor-manage-sidebar,
    .doctor-manage-menu button,
    .doctor-manage-menu .menu-static,
    .header-btn,
    .btn-submit,
    .btn-cancel-edit,
    .btn-edit-row,
    .btn-delete-row,
    .day-btn span {
        transform: none !important;
        transition: none !important;
        animation: none !important;
        will-change: auto !important;
    }

    .doctor-card:hover,
    .doctor-stat-card:hover,
    .schedule-item:hover,
    .mini-item:hover,
    .capacity-card:hover,
    .calendar-box:hover,
    .doctor-manage-header:hover,
    .doctor-manage-sidebar:hover,
    .doctor-manage-menu button:hover,
    .doctor-manage-menu .menu-static:hover,
    .header-btn:hover,
    .btn-submit:hover,
    .btn-cancel-edit:hover,
    .btn-edit-row:hover,
    .btn-delete-row:hover,
    .day-btn:hover span {
        transform: none !important;
        transition: none !important;
        animation: none !important;
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
                    <button type="button" id="btn-tab-dashboard" class="active" onclick="switchTab('dashboard')">Dashboard</button>
                    <button type="button" id="btn-tab-schedule" onclick="switchTab('schedule')">Schedule Settings</button>

                    <button type="button" onclick="window.location.href='{{ route('doctor.appointments') }}'">Appointments</button>
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
                    <h2>Morning, Dr. {{ auth()->user()->full_name ?? 'Doctor' }}</h2>
                    <p id="header-desc">You have {{ $todaySchedules->count() }} active schedule slot(s) for today.</p>
                </div>
                <button class="header-btn" onclick="switchTab('schedule')">Create Schedule</button>
            </div>

            <div id="view-dashboard">
                <div class="doctor-manage-stats">
                    <div class="doctor-stat-card">
                        <h4>Schedules Today</h4>
                        <div class="stat-number">{{ $todaySchedules->count() }}</div>
                        <div class="stat-note">Applied for today</div>
                    </div>
                    <div class="doctor-stat-card">
                        <h4>Total Schedules</h4>
                        <div class="stat-number">{{ $schedules->count() }}</div>
                        <div class="stat-note">Created by doctor</div>
                    </div>
                    <div class="doctor-stat-card">
                        <h4>Online Slots</h4>
                        <div class="stat-number">{{ $schedules->where('type', 'online')->count() }}</div>
                        <div class="stat-note">Available online</div>
                    </div>
                    <div class="doctor-stat-card">
                        <h4>In-person Slots</h4>
                        <div class="stat-number">{{ $schedules->where('type', 'in-person')->count() }}</div>
                        <div class="stat-note">Clinic visits</div>
                    </div>
                </div>

                <div class="doctor-manage-content">
                    <div class="doctor-left-column">
                        <div class="doctor-card">
                            <h3>Today's Schedule</h3>
                            <div class="schedule-list">
                                @forelse($todaySchedules as $s)
                                    <div class="schedule-item">
                                        <div class="schedule-time">
                                            {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                                        </div>
                                        <div class="schedule-info">
                                            <strong>{{ $s->days }}</strong>
                                            <span>
                                                {{ ucfirst($s->type) }} • {{ $s->location ?? 'N/A' }} • Max {{ $s->max_patients ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="schedule-status status-confirmed">Active</div>
                                    </div>
                                @empty
                                    <div class="mini-item">Hôm nay chưa có lịch làm việc nào được áp dụng.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="doctor-manage-row">
                            <div class="doctor-card">
                                <h3>Working Days</h3>
                                <div class="mini-list">
                                    @php
                                        $allDays = $schedules->pluck('days')
                                            ->flatMap(fn($days) => explode(',', $days))
                                            ->map(fn($day) => trim($day))
                                            ->filter()
                                            ->unique()
                                            ->values();
                                    @endphp

                                    @forelse($allDays as $day)
                                        <div class="mini-item">{{ $day }}</div>
                                    @empty
                                        <div class="mini-item">Chưa có ngày làm việc nào được tạo</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="doctor-card capacity-card">
                                <h3>{{ $schedules->count() }} lịch đã tạo</h3>
                                <p>Quản lý lịch làm việc, chỉnh sửa hoặc xóa trực tiếp ngay trong Schedule Settings.</p>
                                <button type="button" onclick="switchTab('schedule')">Update Slots</button>
                            </div>
                        </div>
                    </div>

                    <div class="doctor-right-column">
                        <div class="doctor-card">
                            <h4>Recent Activity</h4>
                            <ul class="activity-list">
                                <li>{{ $schedules->count() }} lịch đã được tạo.</li>
                                <li>{{ $todaySchedules->count() }} lịch đang áp dụng hôm nay.</li>
                                <li>Bạn có thể chỉnh sửa trực tiếp ở mục Schedule Settings.</li>
                                <li>Thông báo sẽ tự ẩn sau 3 giây.</li>
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
                    <div class="alert-success-box flash-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error-box flash-alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error-box flash-alert">
                        <ul style="margin: 0; padding-left: 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="editModeBox" class="edit-mode-box">
                    Bạn đang chỉnh sửa lịch làm việc. Sau khi sửa xong, bấm "Update Schedule".
                </div>

                <div class="doctor-card">
                    <h3 id="scheduleFormTitle">Create New Working Schedule</h3>

                    <form method="POST" action="{{ route('schedule.store') }}" id="scheduleForm">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="fake_method_holder">

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required value="{{ old('start_date') }}">
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required value="{{ old('end_date') }}">
                            </div>

                            <div class="form-group">
                                <label>Consultation Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online Video Call</option>
                                    <option value="in-person" {{ old('type') == 'in-person' ? 'selected' : '' }}>In-person (Clinic)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Max Patients (per day/slot)</label>
                                <input type="number" name="max_patients" id="max_patients" class="form-control" placeholder="e.g. 20" value="{{ old('max_patients') }}">
                            </div>

                            <div class="form-group full-width">
                                <label>Working Days</label>
                                <div class="days-wrapper">
                                    @php $oldDays = old('days', []); @endphp
                                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                        <label class="day-btn">
                                            <input type="checkbox" name="days[]" value="{{ $day }}" class="day-checkbox" {{ in_array($day, $oldDays) ? 'checked' : '' }}>
                                            <span>{{ $day }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Start Time</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" required value="{{ old('start_time') }}">
                            </div>
                            <div class="form-group">
                                <label>End Time</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" required value="{{ old('end_time') }}">
                            </div>

                            <div class="form-group full-width">
                                <label>Location (Khu vực làm việc)</label>
                                <input type="text"
                                    class="form-control"
                                    value="{{ Auth::user()->province ?? 'Chưa cập nhật địa điểm' }}"
                                    readonly
                                    style="background-color: #f3f4f6; cursor: not-allowed; color: #6b7280; border-color: #e5e7eb;">
                                <input type="hidden" name="location" id="location" value="{{ Auth::user()->province }}">
                            </div>

                            <div class="form-group full-width">
                                <label>Internal Notes</label>
                                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="submitScheduleBtn">Save & Publish Schedule</button>
                        <button type="button" class="btn-cancel-edit" id="cancelEditBtn">Cancel Edit</button>
                    </form>
                </div>

                <div class="doctor-card" style="margin-top: 24px;">
                    <h3>Upcoming Published Schedules (Today)</h3>
                    <div style="overflow-x: auto;">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Date Range</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Max Patients</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todaySchedules as $s)
                                    <tr>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') }}</strong>
                                            to
                                            <strong>{{ \Carbon\Carbon::parse($s->end_date)->format('d/m/Y') }}</strong>
                                        </td>
                                        <td>{{ $s->days }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge-type {{ $s->type === 'online' ? 'badge-online' : 'badge-inperson' }}">
                                                {{ ucfirst($s->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $s->location ?? 'N/A' }}</td>
                                        <td>{{ $s->max_patients ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Hôm nay chưa có lịch khám nào được áp dụng.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="doctor-card" style="margin-top: 24px;">
                    <h3>All Created Schedules</h3>
                    <div style="overflow-x: auto;">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Date Range</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Max Patients</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $s)
                                    <tr>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') }}</strong>
                                            to
                                            <strong>{{ \Carbon\Carbon::parse($s->end_date)->format('d/m/Y') }}</strong>
                                        </td>
                                        <td>{{ $s->days }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge-type {{ $s->type === 'online' ? 'badge-online' : 'badge-inperson' }}">
                                                {{ ucfirst($s->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $s->location ?? 'N/A' }}</td>
                                        <td>{{ $s->max_patients ?? 'N/A' }}</td>
                                        <td>{{ $s->notes ?? '---' }}</td>
                                        <td>
                                            <div class="table-actions">
                                                <button
                                                    type="button"
                                                    class="btn-edit-row edit-schedule-btn"
                                                    data-id="{{ $s->id }}"
                                                    data-start_date="{{ $s->start_date }}"
                                                    data-end_date="{{ $s->end_date }}"
                                                    data-type="{{ $s->type }}"
                                                    data-days="{{ $s->days }}"
                                                    data-start_time="{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}"
                                                    data-end_time="{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}"
                                                    data-max_patients="{{ $s->max_patients }}"
                                                    data-location="{{ $s->location }}"
                                                    data-notes="{{ $s->notes }}"
                                                >
                                                    Sửa
                                                </button>

                                                <form action="{{ url('/schedule/' . $s->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?');">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn-delete-row">Xóa</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">Bác sĩ chưa tạo lịch làm việc nào.</td>
                                    </tr>
                                @endforelse
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
        document.getElementById('view-dashboard').style.display = 'none';
        document.getElementById('view-schedule').style.display = 'none';

        document.getElementById('btn-tab-dashboard').classList.remove('active');
        document.getElementById('btn-tab-schedule').classList.remove('active');

        if (tabName === 'dashboard') {
            document.getElementById('view-dashboard').style.display = 'block';
            document.getElementById('btn-tab-dashboard').classList.add('active');
            document.getElementById('header-desc').innerText = "You have {{ $todaySchedules->count() }} active schedule slot(s) for today.";
        } else if (tabName === 'schedule') {
            document.getElementById('view-schedule').style.display = 'block';
            document.getElementById('btn-tab-schedule').classList.add('active');
            document.getElementById('header-desc').innerText = "Manage your working slots and availability setup.";
        }
    }

    setTimeout(() => {
        document.querySelectorAll('.flash-alert').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        });
    }, 3000);

    const scheduleForm = document.getElementById('scheduleForm');
    const scheduleFormTitle = document.getElementById('scheduleFormTitle');
    const submitScheduleBtn = document.getElementById('submitScheduleBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editModeBox = document.getElementById('editModeBox');
    const fakeMethodHolder = document.getElementById('fake_method_holder');

    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const typeInput = document.getElementById('type');
    const maxPatientsInput = document.getElementById('max_patients');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const locationInput = document.getElementById('location');
    const notesInput = document.getElementById('notes');
    const dayCheckboxes = document.querySelectorAll('.day-checkbox');

    function setMethodPut() {
        const oldMethod = scheduleForm.querySelector('input[name="_method"]');
        if (oldMethod) oldMethod.remove();

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        methodInput.id = 'real_method_input';
        scheduleForm.appendChild(methodInput);
    }

    function removeMethodPut() {
        const oldMethod = scheduleForm.querySelector('#real_method_input');
        if (oldMethod) oldMethod.remove();
    }

    function resetScheduleForm() {
        scheduleForm.action = "{{ route('schedule.store') }}";
        removeMethodPut();

        scheduleFormTitle.innerText = 'Create New Working Schedule';
        submitScheduleBtn.innerText = 'Save & Publish Schedule';
        cancelEditBtn.style.display = 'none';
        editModeBox.style.display = 'none';

        startDateInput.value = '';
        endDateInput.value = '';
        typeInput.value = 'online';
        maxPatientsInput.value = '';
        startTimeInput.value = '';
        endTimeInput.value = '';
        locationInput.value = "{{ Auth::user()->province }}";
        notesInput.value = '';

        dayCheckboxes.forEach(cb => cb.checked = false);
    }

    document.querySelectorAll('.edit-schedule-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const days = (this.dataset.days || '').split(',').map(day => day.trim());

            switchTab('schedule');

            scheduleForm.action = `/schedule/${id}`;
            setMethodPut();

            scheduleFormTitle.innerText = 'Update Working Schedule';
            submitScheduleBtn.innerText = 'Update Schedule';
            cancelEditBtn.style.display = 'block';
            editModeBox.style.display = 'block';

            startDateInput.value = this.dataset.start_date || '';
            endDateInput.value = this.dataset.end_date || '';
            typeInput.value = this.dataset.type || 'online';
            maxPatientsInput.value = this.dataset.max_patients || '';
            startTimeInput.value = this.dataset.start_time || '';
            endTimeInput.value = this.dataset.end_time || '';
            locationInput.value = this.dataset.location || "{{ Auth::user()->province }}";
            notesInput.value = this.dataset.notes || '';

            dayCheckboxes.forEach(cb => {
                cb.checked = days.includes(cb.value);
            });

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    cancelEditBtn.addEventListener('click', function () {
        resetScheduleForm();
    });

    @if(request('tab') === 'schedule' || $errors->any() || session('success') || session('error'))
        switchTab('schedule');
    @else
        switchTab('dashboard');
    @endif
</script>
@endsection
