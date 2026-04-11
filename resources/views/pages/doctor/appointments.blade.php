@extends('layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .doctor-appointments-page {
        background: #f5f7fb;
        min-height: 100vh;
        padding: 24px;
    }

    .doctor-appointments-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
    }

    .doctor-sidebar {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 780px;
    }

    .doctor-brand h2 {
        font-size: 22px;
        font-weight: 800;
        color: #1d4ed8;
        margin-bottom: 8px;
    }

    .doctor-brand p {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 24px;
    }

    .doctor-menu {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .doctor-menu a,
    .doctor-menu button,
    .doctor-menu .menu-static {
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
    }

    .doctor-menu a:hover,
    .doctor-menu button:hover,
    .doctor-menu .menu-static:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .doctor-menu a.active {
        background: #1d4ed8;
        color: #fff;
    }

    .doctor-sidebar-bottom {
        margin-top: 24px;
        border-top: 1px solid #e5e7eb;
        padding-top: 18px;
    }

    .doctor-main {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .doctor-header {
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

    .doctor-header h1 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .doctor-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 15px;
    }

    .doctor-badge {
        display: inline-block;
        background: rgba(255,255,255,0.18);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 10px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .doctor-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .doctor-info-card {
        background: #fff;
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .doctor-info-card h4 {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .doctor-info-card p {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .doctor-content-grid {
        display: grid;
        grid-template-columns: 1.2fr 1.8fr;
        gap: 24px;
    }

    .doctor-card {
        background: #fff;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.05);
    }

    .doctor-card h3 {
        margin-bottom: 16px;
        color: #111827;
        font-weight: 800;
    }

    .doctor-card textarea,
    .doctor-card input,
    .doctor-card select {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        outline: none;
    }

    .doctor-card textarea {
        min-height: 260px;
        resize: vertical;
    }

    .doctor-card textarea:focus,
    .doctor-card input:focus,
    .doctor-card select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .prescription-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
        overflow: hidden;
        border-radius: 14px;
    }

    .prescription-table th {
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 700;
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid #dbeafe;
    }

    .prescription-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }

    .prescription-table td input {
        padding: 10px 12px;
    }

    .add-btn {
        border: none;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 12px;
        cursor: pointer;
    }

    .doctor-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .doctor-actions button {
        border: none;
        padding: 12px 18px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-save {
        background: #e5e7eb;
        color: #111827;
    }

    .btn-submit {
        background: #1d4ed8;
        color: #fff;
    }

    @media (max-width: 1200px) {
        .doctor-info-grid {
            grid-template-columns: 1fr;
        }

        .doctor-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .doctor-appointments-wrapper {
            grid-template-columns: 1fr;
        }

        .doctor-sidebar {
            min-height: auto;
        }

        .doctor-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 700px) {
        .doctor-appointments-page {
            padding: 16px;
        }

        .prescription-table,
        .prescription-table thead,
        .prescription-table tbody,
        .prescription-table th,
        .prescription-table td,
        .prescription-table tr {
            display: block;
            width: 100%;
        }

        .prescription-table thead {
            display: none;
        }

        .prescription-table tr {
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .prescription-table td {
            border-bottom: none;
        }
    }
</style>

<div class="doctor-appointments-page">
    <div class="doctor-appointments-wrapper">
        <aside class="doctor-sidebar">
            <div>
                <div class="doctor-brand">
                    <h2>MediConnect</h2>
                    <p>Doctor Appointment Workspace</p>
                </div>

                <nav class="doctor-menu">
                    <a href="{{ route('doctor.dashboard') }}">Doctor Main</a>
                    <a href="{{ route('doctor.manage') }}">Manage Appointments</a>
                    <a href="{{ route('doctor.appointments') }}" class="active">Appointments</a>
                    <a href="#">Patients</a>
                    <a href="#">Settings</a>
                </nav>
            </div>

            <div class="doctor-sidebar-bottom">
                <div class="doctor-menu">
                    <div class="menu-static">Help Center</div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <section class="doctor-main">
            <div class="doctor-header">
                <div>
                    <h1>Prescription Management</h1>
                    <p>Manage notes, medications, and follow-up instructions for your current appointment.</p>
                </div>
                <span class="doctor-badge">IN PROGRESS</span>
            </div>

            <div class="doctor-info-grid">
                <div class="doctor-info-card">
                    <h4>Patient</h4>
                    <p>Alex Johnson</p>
                </div>

                <div class="doctor-info-card">
                    <h4>Date</h4>
                    <p>October 24, 2023</p>
                </div>

                <div class="doctor-info-card">
                    <h4>Reason</h4>
                    <p>Routine Follow-up</p>
                </div>
            </div>

            <div class="doctor-content-grid">
                <div class="doctor-card">
                    <h3>Clinical Notes</h3>
                    <textarea placeholder="Enter notes..."></textarea>
                </div>

                <div class="doctor-card">
                    <h3>Prescription List</h3>

                    <table class="prescription-table" id="table">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="medicine[]" placeholder="Medicine name"></td>
                                <td><input type="text" name="dosage[]" placeholder="Dosage"></td>
                                <td><input type="text" name="frequency[]" placeholder="Frequency"></td>
                                <td><input type="text" name="duration[]" placeholder="Duration"></td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="add-btn" type="button" onclick="addRow()">+ Add Medication</button>
                </div>
            </div>

            <div class="doctor-actions">
                <button class="btn-save" type="button">Save Draft</button>
                <button class="btn-submit" type="button" onclick="submitForm()">Submit</button>
            </div>
        </section>
    </div>
</div>

<script>
    function addRow() {
        const tableBody = document.querySelector('#table tbody');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td><input type="text" name="medicine[]" placeholder="Medicine name"></td>
            <td><input type="text" name="dosage[]" placeholder="Dosage"></td>
            <td><input type="text" name="frequency[]" placeholder="Frequency"></td>
            <td><input type="text" name="duration[]" placeholder="Duration"></td>
        `;

        tableBody.appendChild(row);
    }

    function submitForm() {
        alert('Prescription submitted successfully.');
    }
</script>
@endsection
