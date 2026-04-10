<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Schedule Management</title>

<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Clinical Sanctuary</h2>

        <ul>
            <li><a href="{{ url('/doctor') }}">Dashboard</a></li>
            <li><a href="{{ url('/appointments') }}">Appointments</a></li>
            <li class="active">Schedule</li>
            <li>Patients</li>
            <li>Profile</li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="main">

        <h1>Schedule Management</h1>

        <div class="card">

            <h2>Create Working Schedule</h2>

            <!-- DATE -->
            <div class="row">
                <input type="date">
                <input type="date">
            </div>

            <!-- TYPE -->
            <div class="row">
                <button class="active">Online</button>
                <button>In-person</button>
            </div>

            <!-- DAYS -->
            <div class="row days">
                <span>Mon</span>
                <span class="active">Tue</span>
                <span class="active">Wed</span>
                <span>Thu</span>
                <span class="active">Fri</span>
                <span>Sat</span>
                <span>Sun</span>
            </div>

            <!-- TIME -->
            <div class="row">
                <input type="time"> to <input type="time">
            </div>

            <!-- MAX -->
            <div class="row">
                <input type="number" placeholder="Max Patients">
            </div>

            <!-- LOCATION -->
            <div class="row">
                <input type="text" placeholder="Clinic Name">
            </div>

            <!-- NOTES -->
            <textarea placeholder="Notes..."></textarea>

            <div class="actions">
                <button class="cancel">Cancel</button>
                <button class="save">Save</button>
            </div>

        </div>

        <!-- TABLE -->
        <div class="card">
            <h2>Existing Schedules</h2>

            <table>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>

                <tr>
                    <td>Oct 24</td>
                    <td>09:00</td>
                    <td>Online</td>
                    <td class="green">Available</td>
                </tr>

                <tr>
                    <td>Oct 25</td>
                    <td>14:00</td>
                    <td>In-person</td>
                    <td class="red">Booked</td>
                </tr>

            </table>
        </div>

    </div>
</div>

</body>
</html>