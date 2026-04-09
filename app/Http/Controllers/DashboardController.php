<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
   public function doctor()
{
    return view('pages.doctor.dashboard');
}
}