<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function doctor()
    {
        return view('pages.doctor.doctor-main');
    }

    /* Lịch hẹn */

    public function manageAppointments()
    {
        return view('pages.doctor.dashboard');
    }
}
