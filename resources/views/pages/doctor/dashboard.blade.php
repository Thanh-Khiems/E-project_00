<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <h2>The Clinical Atelier</h2>

    <div class="menu">
        <a href="{{ route('doctor.dashboard') }}" class="menu-item active">Dashboard</a>
        <a href="{{ url('/appointments') }}" class="menu-item">Appointments</a>
        <a href="#" class="menu-item">Patients</a>
    </div>

    <div class="bottom">
        <div class="menu-item">Help Center</div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item">Logout</button>
        </form>
    </div>
</div>

<div class="main">
    <div class="header">
        <div>
            <h2>Morning, Dr. Vance</h2>
            <p>You have 8 appointments today</p>
        </div>
        <button class="btn">+ New Appointment</button>
    </div>

    <div class="content">

        <div class="left">

            <div class="card">
                <h3>Today's Schedule</h3>
                <div id="schedule"></div>
            </div>

            <div class="row">
                <div class="card small">
                    <h4>Patient Volume</h4>
                    <div class="bars">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar active"></div>
                        <div class="bar"></div>
                    </div>
                </div>

                <div class="card blue">
                    <h3>94% Capacity</h3>
                    <p>3 slots open for next week</p>
                    <button>View Slots</button>
                </div>
            </div>

        </div>

        <div class="right">

            <div class="card">
                <h4>Requests</h4>
                <div class="request">Jason Mendoza</div>
                <div class="request">Maya Lewis</div>
                <button class="full">Review All</button>
            </div>

            <div class="card">
                <h4>Recent Activity</h4>
                <ul>
                    <li>Lab reports updated</li>
                    <li>New message received</li>
                    <li>Prescription signed</li>
                </ul>
            </div>

            <div class="card">
                <h4>Calendar</h4>
                <div id="calendar"></div>
            </div>

        </div>

    </div>
</div>

<script src="{{ asset('js/doctor_dashboard.js') }}"></script>

</body>
</html>