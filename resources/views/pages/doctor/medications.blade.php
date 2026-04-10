<!DOCTYPE html>
<html>
<head>
    <title>Manage Medications</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/medications.css') }}">
</head>
<body>

<div class="container">
    <h2>Manage Medications</h2>

    <input id="name" placeholder="Medicine name">
    <button onclick="addMedication()">+ Add Medicine</button>

    <div id="list"></div>
</div>

<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>

<script src="{{ asset('js/medications.js') }}"></script>

</body>
</html>