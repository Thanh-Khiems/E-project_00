<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Prescription Management</title>

<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Clinical Curator</h2>
  <ul class="menu">

    <li class="menu-item {{ request()->is('doctor') ? 'active' : '' }}">
      <a href="{{ url('/doctor') }}">Dashboard</a>
    </li>

    <li class="menu-item {{ request()->is('appointments') ? 'active' : '' }}">
      <a href="{{ url('/appointments') }}">Appointments</a>
    </li>

    <li class="menu-item">
      <a href="#">Patients</a>
    </li>

    <li class="menu-item">
      <a href="#">Settings</a>
    </li>

  </ul>
</div>

<!-- Main -->
<div class="main">

  <div class="header">
    <h1>Prescription Management</h1>
    <span class="badge">IN PROGRESS</span>
  </div>

  <div class="info">
    <div><b>Patient:</b> Alex Johnson</div>
    <div><b>Date:</b> October 24, 2023</div>
    <div><b>Reason:</b> Routine Follow-up</div>
  </div>

  <div class="content">

    <div class="notes">
      <h3>Clinical Notes</h3>
      <textarea placeholder="Enter notes..."></textarea>
    </div>

    <div class="prescription">
      <h3>Prescription List</h3>

      <table id="table">
        <tr>
          <th>Medicine</th>
          <th>Dosage</th>
          <th>Frequency</th>
          <th>Duration</th>
        </tr>

        <tr>
          <td>Metformin</td>
          <td>500mg</td>
          <td>Once Daily</td>
          <td>90 Days</td>
        </tr>

        <tr>
          <td>Lisinopril</td>
          <td>10mg</td>
          <td>Once Daily</td>
          <td>30 Days</td>
        </tr>
      </table>

      <button class="add-btn" onclick="addRow()">+ Add Medication</button>
    </div>

  </div>

  <div class="actions">
    <button class="save">Save Draft</button>
    <button class="submit" onclick="submitForm()">Submit</button>
  </div>

</div>

<script src="{{ asset('js/appointment.js') }}"></script>

</body>
</html>