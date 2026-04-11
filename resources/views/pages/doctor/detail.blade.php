<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Doctor Detail</title>

<link rel="stylesheet" href="{{ asset('css/doctor-detail.css') }}">
</head>

<body>

<div class="container">

<div class="left">

<div class="card">
<h2>{{ $doctor['name'] }}</h2>
<p>{{ $doctor['specialty'] }}</p>
<p>$ {{ $doctor['price'] }}</p>
</div>

<div class="card">
<h3>Available Schedule</h3>

<div class="times">
@foreach($times as $time)
    <button onclick="selectTime(this, '{{ $time }}')">{{ $time }}</button>
@endforeach
</div>
</div>

<div class="card">
<h3>Reason</h3>
<textarea id="reason" style="width:100%;height:80px;"></textarea>
</div>

</div>

<div class="right">

<div class="card">
<h3>Booking Summary</h3>

<p>Doctor: {{ $doctor['name'] }}</p>
<p>Time: <span id="timeText">None</span></p>
<p>Total: ${{ $doctor['price'] }}</p>

<form method="POST" action="{{ route('book.store') }}">
@csrf

<input type="hidden" name="doctor_id" value="{{ $doctor['id'] }}">
<input type="hidden" name="time" id="timeInput">
<input type="hidden" name="reason" id="reasonInput">

<button type="submit" class="btn" onclick="submitForm()">Confirm</button>

</form>

</div>

</div>

</div>

<script src="{{ asset('js/doctor-detail.js') }}"></script>

</body>
</html>