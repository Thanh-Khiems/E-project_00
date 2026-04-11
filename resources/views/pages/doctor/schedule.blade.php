<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý lịch làm việc - MediConnect</title>
    <style>
        /* CSS Reset & Variables */
        :root {
            --primary-color: #0ea5e9; /* Màu xanh y tế hiện đại */
            --primary-hover: #0284c7;
            --bg-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* --- Bố cục chính --- */
        .container {
            display: flex;
            width: 100%;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            padding: 24px 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            font-size: 1.25rem;
            color: var(--primary-color);
            padding: 0 24px 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 16px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li a, .sidebar li.active {
            display: block;
            padding: 12px 24px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar li a:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        .sidebar li.active {
            background-color: #f0f9ff;
            color: var(--primary-color);
            border-right: 3px solid var(--primary-color);
        }

        /* --- Vùng Nội dung chính --- */
        .main {
            flex: 1;
            padding: 32px;
            max-width: 1000px; /* Giới hạn chiều rộng để form không bị bè ra quá dài */
            margin: 0 auto;
        }

        .main h1 {
            font-size: 1.75rem;
            margin-bottom: 24px;
            color: var(--text-main);
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #bbf7d0;
        }

        /* --- Cards --- */
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 32px;
        }

        .card h3 {
            font-size: 1.125rem;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        /* --- Forms & Inputs --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-main);
        }

        input[type="date"],
        input[type="time"],
        input[type="number"],
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            color: var(--text-main);
            outline: none;
            transition: border-color 0.2s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* --- Nút chọn Ngày (Pill Buttons) --- */
        .days-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .day-btn {
            cursor: pointer;
        }

        .day-btn input {
            display: none; /* Ẩn checkbox mặc định */
        }

        .day-btn span {
            display: inline-block;
            padding: 8px 16px;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: all 0.2s ease;
            user-select: none;
        }

        /* Khi checkbox được check, thẻ span thay đổi style */
        .day-btn input:checked + span {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .day-btn:hover span {
            border-color: var(--primary-color);
        }

        /* --- Buttons --- */
        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            width: 100%;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }

        /* --- Table --- */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            font-size: 0.95rem;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge.online { background-color: #dbeafe; color: #1e40af; }
        .badge.in-person { background-color: #fef3c7; color: #92400e; }
    </style>
</head>

<body>

<div class="container">

    <div class="sidebar">
        <h2>MediConnect</h2>
        <ul>
            <li><a href="/doctor">Dashboard</a></li>
            <li><a href="/appointments">Appointments</a></li>
            <li class="active">Schedule</li>
        </ul>
    </div>

    <div class="main">

        <h1>Schedule Management</h1>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('schedule.store') }}">
            @csrf

            <div class="card">
                <h3>Create Working Schedule</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label>Consultation Type</label>
                        <select name="type">
                            <option value="online">Online Video Call</option>
                            <option value="in-person">In-person (Clinic)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Max Patients (per day/slot)</label>
                        <input type="number" name="max_patients" placeholder="e.g. 20">
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
                        <input type="time" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="end_time" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Location (for In-person)</label>
                        <input type="text" name="location" placeholder="Enter clinic room or address">
                    </div>

                    <div class="form-group full-width">
                        <label>Internal Notes</label>
                        <textarea name="notes" placeholder="Any specific notes for this schedule block?"></textarea>
                    </div>
                </div>

                <button type="submit">Save Schedule</button>
            </div>
        </form>

        <div class="card">
            <h3>Upcoming Schedules</h3>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $s)
                    <tr>
                        <td>{{ $s->start_date }}</td>
                        <td><strong>{{ $s->start_time }}</strong> - <strong>{{ $s->end_time }}</strong></td>
                        <td>
                            <span class="badge {{ $s->type === 'online' ? 'online' : 'in-person' }}">
                                {{ ucfirst($s->type) }}
                            </span>
                        </td>
                        <td>{{ $s->location ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
