<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Schedule</title>

<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
</head>

<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Clinical Sanctuary</h2>
    <ul>
        <li><a href="/doctor">Dashboard</a></li>
        <li><a href="/appointments">Appointments</a></li>
        <li class="active">Schedule</li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

<h1>Schedule Management</h1>

@if(session('success'))
<p style="color:green;">{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('schedule.store') }}">
@csrf

<div class="card">

<h3>Create Working Schedule</h3>

<input type="date" name="start_date" required>
<input type="date" name="end_date" required>

<select name="type">
    <option value="online">Online</option>
    <option value="in-person">In-person</option>
</select>

<div class="days">
@foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
    <label>
        <input type="checkbox" name="days[]" value="{{ $day }}">
        {{ $day }}
    </label>
@endforeach
</div>

<input type="time" name="start_time">
<input type="time" name="end_time">

<input type="number" name="max_patients" placeholder="Max Patients">

<input type="text" name="location" placeholder="Clinic Location">

<textarea name="notes" placeholder="Notes..."></textarea>

<button type="submit">Save Schedule</button>

</div>

</form>

<div class="card">
<h3>Upcoming Schedules</h3>

<table>
<tr>
<th>Date</th>
<th>Time</th>
<th>Type</th>
</tr>

@foreach($schedules as $s)
<tr>
<td>{{ $s->start_date }}</td>
<td>{{ $s->start_time }} - {{ $s->end_time }}</td>
<td>{{ $s->type }}</td>
</tr>
@endforeach

</table>
</div>

</div>
</div>

</body>
</html>