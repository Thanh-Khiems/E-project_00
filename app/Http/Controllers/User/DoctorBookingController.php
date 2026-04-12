<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorBookingController extends Controller
{
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialty', 'schedules']);

        return view('pages.user.doctor-booking', compact('doctor'));
    }
}
