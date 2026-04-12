<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa lịch làm việc - MediConnect</title>
    <style>
        :root {
            --primary-color: #0ea5e9;
            --primary-hover: #0284c7;
            --bg-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --error-bg: #fee2e2;
            --error-text: #991b1b;
            --error-border: #fecaca;
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

        .container {
            display: flex;
            width: 100%;
        }

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

        .main {
            flex: 1;
            padding: 32px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .main h1 {
            font-size: 1.75rem;
            margin-bottom: 24px;
            color: var(--text-main);
        }

        .alert-danger {
            background-color: var(--error-bg);
            color: var(--error-text);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            border: 1px solid var(--error-border);
        }

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

        .days-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .day-btn {
            cursor: pointer;
        }

        .day-btn input {
            display: none;
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

        .day-btn input:checked + span {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .day-btn:hover span {
            border-color: var(--primary-color);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s ease, opacity 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .main {
                padding: 20px;
            }

            .sidebar {
                display: none;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="sidebar">
        <h2>MediConnect</h2>
        <ul>
            <li><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('doctor.appointments') }}">Appointments</a></li>
            <li class="active"><a href="{{ route('schedule.index') }}">Schedule</a></li>
        </ul>
    </div>

    <div class="main">
        <h1>Chỉnh sửa lịch làm việc</h1>

        @if($errors->any())
            <div class="alert-danger">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $selectedDays = collect(explode(',', $schedule->days))
                ->map(fn($day) => trim($day))
                ->toArray();
        @endphp

        <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <h3>Update Working Schedule</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" required value="{{ old('start_date', $schedule->start_date) }}">
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" required value="{{ old('end_date', $schedule->end_date) }}">
                    </div>

                    <div class="form-group">
                        <label>Consultation Type</label>
                        <select name="type">
                            <option value="online" {{ old('type', $schedule->type) == 'online' ? 'selected' : '' }}>Online Video Call</option>
                            <option value="in-person" {{ old('type', $schedule->type) == 'in-person' ? 'selected' : '' }}>In-person (Clinic)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Max Patients (per day/slot)</label>
                        <input type="number" name="max_patients" value="{{ old('max_patients', $schedule->max_patients) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Working Days</label>
                        <div class="days-wrapper">
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <label class="day-btn">
                                    <input
                                        type="checkbox"
                                        name="days[]"
                                        value="{{ $day }}"
                                        {{ in_array($day, old('days', $selectedDays)) ? 'checked' : '' }}
                                    >
                                    <span>{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="time" name="start_time" required value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}">
                    </div>

                    <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="end_time" required value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Location (for In-person)</label>
                        <input type="text" name="location" value="{{ old('location', $schedule->location) }}" placeholder="Enter clinic room or address">
                    </div>

                    <div class="form-group full-width">
                        <label>Internal Notes</label>
                        <textarea name="notes" placeholder="Any specific notes for this schedule block?">{{ old('notes', $schedule->notes) }}</textarea>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Cập nhật lịch</button>
                    <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
