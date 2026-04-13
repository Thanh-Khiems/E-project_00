<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Doctor;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        return redirect()->route('doctor.manage', ['tab' => 'schedule']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'type'         => 'required|string|max:255',
            'days'         => 'required|array|min:1',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'max_patients' => 'nullable|integer|min:1',
            'location'     => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $user = Auth::user();

        $doctor = Doctor::where('user_id', $user->id)
            ->where('approval_status', 'approved')
            ->first();

        if (!$doctor) {
            return back()->with('error', 'Bạn chưa được duyệt tài khoản bác sĩ nên chưa thể tạo lịch.');
        }

        Schedule::create([
            'doctor_id'    => $doctor->id,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'type'         => $request->type,
            'days'         => implode(',', $request->days),
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'max_patients' => $request->max_patients ?? 10,
            'location'     => $request->location,
            'notes'        => $request->notes,
        ]);

        return back()->with('success', 'Bác sĩ đã lưu lịch làm việc thành công.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $schedule = Schedule::where('doctor_id', $doctor->id)->findOrFail($id);

        return view('pages.doctor.edit-schedule', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'type'         => 'required|string|max:255',
            'days'         => 'required|array|min:1',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'max_patients' => 'nullable|integer|min:1',
            'location'     => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $schedule = Schedule::where('doctor_id', $doctor->id)->findOrFail($id);

        $schedule->update([
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'type'         => $request->type,
            'days'         => implode(',', $request->days),
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'max_patients' => $request->max_patients ?? 10,
            'location'     => $request->location,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('doctor.manage')->with('success', 'Cập nhật lịch làm việc thành công.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $schedule = Schedule::where('doctor_id', $doctor->id)->findOrFail($id);
        $schedule->delete();

        return redirect()->route('doctor.manage')
            ->with('success', 'Xóa lịch làm việc thành công.');
    }
}
