<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function doctor()
    {
        return view('pages.doctor.doctor-main');
    }

    public function manageAppointments()
    {
        return view('pages.doctor.dashboard');
    }

    public function appointments()
    {
        return view('pages.doctor.appointments');
    }
}
