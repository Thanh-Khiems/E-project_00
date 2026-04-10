<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::latest()->get();
        return view('pages.doctor.schedule', compact('schedules'));
    }

    public function store(Request $request)
    {
        Schedule::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'days' => implode(',', $request->days ?? []),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_patients' => $request->max_patients,
            'location' => $request->location,
            'notes' => $request->notes,
            
        ]);

        return back()->with('success', 'Saved!');
    }
}