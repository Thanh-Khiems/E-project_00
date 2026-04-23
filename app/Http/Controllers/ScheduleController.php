<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index()
    {
        return redirect()->route('doctor.manage', ['tab' => 'schedule']);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $doctor = Doctor::where('user_id', $user->id)
            ->where('approval_status', 'approved')
            ->first();

        if (! $doctor) {
            return back()->with('error', 'Your doctor account has not been approved yet, so you cannot create a schedule.');
        }

        $validated = $this->validateSchedule($request);
        $payload = $this->buildSchedulePayload($validated);

        if ($this->hasScheduleConflict($doctor, $payload)) {
            return back()
                ->withInput()
                ->with('error', 'A work schedule already exists for this day and time. Please choose another date, time, or working day.');
        }

        Schedule::create([
            'doctor_id' => $doctor->id,
            ...$payload,
        ]);

        return back()->with('success', 'Doctor working schedule saved successfully.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $schedule = Schedule::where('doctor_id', $doctor->id)->findOrFail($id);

        return view('pages.doctor.edit-schedule', compact('schedule'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $schedule = Schedule::where('doctor_id', $doctor->id)->findOrFail($id);

        $validated = $this->validateSchedule($request);
        $payload = $this->buildSchedulePayload($validated);

        if ($this->hasScheduleConflict($doctor, $payload, $schedule->id)) {
            return back()
                ->withInput()
                ->with('error', 'A work schedule already exists for this day and time. Please choose another date, time, or working day.');
        }

        $schedule->update($payload);

        return redirect()->route('doctor.manage')->with('success', 'Working schedule updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $schedule = Schedule::where('doctor_id', $doctor->id)->findOrFail($id);
        $schedule->delete();

        return redirect()->route('doctor.manage')
            ->with('success', 'Working schedule deleted successfully.');
    }

    protected function validateSchedule(Request $request): array
    {
        $request->merge([
            'type' => trim((string) $request->input('type')),
            'location' => $request->filled('location') ? trim((string) $request->input('location')) : null,
            'notes' => $request->filled('notes') ? trim((string) $request->input('notes')) : null,
            'days' => collect((array) $request->input('days', []))
                ->map(fn ($day) => trim((string) $day))
                ->filter()
                ->unique()
                ->values()
                ->all(),
        ]);

        return $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'type' => ['required', 'string', 'max:255'],
            'days' => ['required', 'array', 'min:1'],
            'days.*' => ['required', 'string', Rule::in(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'max_patients' => ['nullable', 'integer', 'min:1'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    protected function buildSchedulePayload(array $validated): array
    {
        $days = collect($validated['days'])
            ->map(fn ($day) => trim((string) $day))
            ->filter()
            ->unique()
            ->sortBy(fn ($day) => array_search($day, ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], true))
            ->values()
            ->all();

        return [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => trim($validated['type']),
            'days' => implode(',', $days),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_patients' => $validated['max_patients'] ?? 10,
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];
    }

    protected function hasScheduleConflict(Doctor $doctor, array $payload, ?int $ignoreScheduleId = null): bool
    {
        $newDays = collect(explode(',', (string) $payload['days']))
            ->map(fn ($day) => trim($day))
            ->filter();

        return Schedule::query()
            ->where('doctor_id', $doctor->id)
            ->when($ignoreScheduleId, fn ($query) => $query->whereKeyNot($ignoreScheduleId))
            ->whereDate('start_date', '<=', $payload['end_date'])
            ->whereDate('end_date', '>=', $payload['start_date'])
            ->get()
            ->contains(function (Schedule $schedule) use ($newDays, $payload) {
                $existingDays = collect(explode(',', (string) $schedule->days))
                    ->map(fn ($day) => trim($day))
                    ->filter();

                $sharesWorkingDay = $existingDays->intersect($newDays)->isNotEmpty();

                if (! $sharesWorkingDay) {
                    return false;
                }

                return $payload['start_time'] < $schedule->end_time
                    && $payload['end_time'] > $schedule->start_time;
            });
    }
}
