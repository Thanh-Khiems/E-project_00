<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Working Schedule Management - MediConnect</title>
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
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --warning-color: #f59e0b;
            --warning-hover: #d97706;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --success-border: #bbf7d0;
            --error-bg: #fee2e2;
            --error-text: #991b1b;
            --error-border: #fecaca;
            --secondary-bg: #e2e8f0;
            --secondary-text: #334155;
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
            max-width: 1100px;
            margin: 0 auto;
        }

        .main h1 {
            font-size: 1.75rem;
            margin-bottom: 24px;
            color: var(--text-main);
        }

        .alert-success,
        .alert-danger {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            transition: opacity 0.4s ease;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: var(--success-text);
            border: 1px solid var(--success-border);
        }

        .alert-danger {
            background-color: var(--error-bg);
            color: var(--error-text);
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

        button[type="submit"],
        .btn {
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, opacity 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary,
        button[type="submit"] {
            background-color: var(--primary-color);
        }

        .btn-primary:hover,
        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }

        .btn-warning {
            background-color: var(--warning-color);
        }

        .btn-warning:hover {
            background-color: var(--warning-hover);
        }

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        .btn-secondary {
            background-color: var(--secondary-bg);
            color: var(--secondary-text);
        }

        .btn-secondary:hover {
            opacity: 0.9;
        }

        .submit-btn {
            width: 100%;
            margin-top: 10px;
        }

        .action-group,
        .form-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .inline-form {
            display: inline-block;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
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
            white-space: nowrap;
        }

        .badge.online { background-color: #dbeafe; color: #1e40af; }
        .badge.in-person { background-color: #fef3c7; color: #92400e; }

        .empty-text {
            color: var(--text-muted);
            font-style: italic;
        }

        .edit-mode-banner {
            display: none;
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #9a3412;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-weight: 600;
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

            table {
                display: block;
                overflow-x: auto;
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

        <h1>Schedule Management</h1>

        @if(session('success'))
            <div class="alert-success flash-alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-danger flash-alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-danger flash-alert">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="editModeBanner" class="edit-mode-banner">
            You are editing a working schedule. After finishing, click "Update Schedule" to save.
        </div>

        <form method="POST" action="{{ route('schedule.store') }}" id="scheduleForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="schedule_id" id="scheduleId">

            <div class="card">
                <h3 id="formTitle">Create Working Schedule</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="start_date" required value="{{ old('start_date') }}">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="end_date" required value="{{ old('end_date') }}">
                    </div>

                    <div class="form-group">
                        <label>Consultation Type</label>
                        <select name="type" id="type">
                            <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online Video Call</option>
                            <option value="in-person" {{ old('type') == 'in-person' ? 'selected' : '' }}>In-person (Clinic)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Max Patients (per day/slot)</label>
                        <input type="number" name="max_patients" id="max_patients" placeholder="e.g. 20" value="{{ old('max_patients') }}">
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
                        <input type="time" name="start_time" id="start_time" required value="{{ old('start_time') }}">
                    </div>
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="end_time" id="end_time" required value="{{ old('end_time') }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Location (for In-person)</label>
                        <input type="text" name="location" id="location" placeholder="Enter clinic room or address" value="{{ old('location') }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Internal Notes</label>
                        <textarea name="notes" id="notes" placeholder="Any specific notes for this schedule block?">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary submit-btn" id="submitBtn">Save Schedule</button>
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display:none;">Cancel Edit</button>
                </div>
            </div>
        </form>

        <div class="card">
            <h3>Upcoming Published Schedules (Today)</h3>

            <table>
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
                            {{ \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') }}
                            -
                            {{ \Carbon\Carbon::parse($s->end_date)->format('d/m/Y') }}
                        </td>
                        <td>{{ $s->days }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</strong>
                            -
                            <strong>{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $s->type === 'online' ? 'online' : 'in-person' }}">
                                {{ ucfirst($s->type) }}
                            </span>
                        </td>
                        <td>{{ $s->location ?? 'N/A' }}</td>
                        <td>{{ $s->max_patients ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-text">No appointment schedule applies today yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>All Created Schedules</h3>

            <table>
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
                            {{ \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') }}
                            -
                            {{ \Carbon\Carbon::parse($s->end_date)->format('d/m/Y') }}
                        </td>
                        <td>{{ $s->days }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</strong>
                            -
                            <strong>{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $s->type === 'online' ? 'online' : 'in-person' }}">
                                {{ ucfirst($s->type) }}
                            </span>
                        </td>
                        <td>{{ $s->location ?? 'N/A' }}</td>
                        <td>{{ $s->max_patients ?? 'N/A' }}</td>
                        <td>{{ $s->notes ?? '---' }}</td>
                        <td>
                            <div class="action-group">
                                <button
                                    type="button"
                                    class="btn btn-warning edit-btn"
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
                                    Edit
                                </button>

                                <form action="{{ route('schedule.destroy', $s->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-text">The doctor has not created any working schedules yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.flash-alert').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        });
    }, 3000);

    const scheduleForm = document.getElementById('scheduleForm');
    const formMethod = document.getElementById('formMethod');
    const scheduleId = document.getElementById('scheduleId');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editModeBanner = document.getElementById('editModeBanner');

    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const type = document.getElementById('type');
    const maxPatients = document.getElementById('max_patients');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const location = document.getElementById('location');
    const notes = document.getElementById('notes');
    const dayCheckboxes = document.querySelectorAll('.day-checkbox');

    function resetFormToCreateMode() {
        scheduleForm.action = "{{ route('schedule.store') }}";
        formMethod.value = 'POST';
        scheduleId.value = '';
        formTitle.textContent = 'Create Working Schedule';
        submitBtn.textContent = 'Save Schedule';
        cancelEditBtn.style.display = 'none';
        editModeBanner.style.display = 'none';

        scheduleForm.reset();
        dayCheckboxes.forEach(cb => cb.checked = false);
    }

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const days = (this.dataset.days || '').split(',').map(day => day.trim());

            scheduleForm.action = `/schedule/${id}`;
            formMethod.value = 'PUT';
            scheduleId.value = id;

            formTitle.textContent = 'Update Working Schedule';
            submitBtn.textContent = 'Update Schedule';
            cancelEditBtn.style.display = 'inline-block';
            editModeBanner.style.display = 'block';

            startDate.value = this.dataset.start_date || '';
            endDate.value = this.dataset.end_date || '';
            type.value = this.dataset.type || 'online';
            maxPatients.value = this.dataset.max_patients || '';
            startTime.value = this.dataset.start_time || '';
            endTime.value = this.dataset.end_time || '';
            location.value = this.dataset.location || '';
            notes.value = this.dataset.notes || '';

            dayCheckboxes.forEach(cb => {
                cb.checked = days.includes(cb.value);
            });

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    cancelEditBtn.addEventListener('click', function () {
        resetFormToCreateMode();
    });
</script>

</body>
</html>
