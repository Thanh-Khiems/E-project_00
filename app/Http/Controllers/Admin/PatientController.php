<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\User;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $this->syncMissingPatients();

        $query = Patient::query()
            ->with(['user'])
            ->withCount('appointments')
            ->where(function ($q) {
                $q->whereNull('user_id')
                  ->orWhereHas('user', fn ($userQuery) => $userQuery
                      ->where('role', 'user')
                      ->whereDoesntHave('doctorProfile'));
            });

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('patient_code', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('admin.patients.index', [
            'pageTitle' => 'Patient management',
            'patients' => $patients,
            'stats' => $this->stats(),
        ]);
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'appointments']);

        return view('admin.patients.show', [
            'pageTitle' => 'Patient details',
            'patient' => $patient,
        ]);
    }

    public function edit(Patient $patient, LocationService $locationService)
    {
        $patient->load('user');

        return view('admin.patients.edit', [
            'pageTitle' => 'Update patient',
            'patient' => $patient,
            'locations' => $locationService->getStructuredLocations(),
            'provinces' => array_keys($locationService->getStructuredLocations()),
        ]);
    }

    public function update(Request $request, Patient $patient, LocationService $locationService)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . optional($patient->user)->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address_detail' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin',
        ]);

        if (($validated['province'] ?? null) || ($validated['district'] ?? null) || ($validated['ward'] ?? null)) {
            if (! ($validated['province'] ?? null) || ! ($validated['district'] ?? null) || ! ($validated['ward'] ?? null)) {
                return back()->withErrors([
                    'province' => 'Please select the full province/city, district, and ward/commune.',
                ])->withInput();
            }

            if (! $locationService->isValidSelection($validated['province'], $validated['district'], $validated['ward'])) {
                return back()->withErrors([
                    'province' => 'The selected location is invalid or has changed. Please choose again.',
                ])->withInput();
            }
        }

        DB::transaction(function () use ($patient, $validated) {
            $user = $patient->user;

            if ($user) {
                $newRole = $validated['role'] ?? $user->role ?? 'user';

                $user->update([
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'] ?? $user->email,
                    'phone' => $validated['phone'] ?? null,
                    'gender' => ($validated['gender'] ?? null) !== 'other' ? ($validated['gender'] ?? null) : null,
                    'dob' => $validated['dob'] ?? null,
                    'province' => $validated['province'] ?? null,
                    'district' => $validated['district'] ?? null,
                    'ward' => $validated['ward'] ?? null,
                    'address_detail' => $validated['address_detail'] ?? null,
                    'role' => $newRole,
                ]);

                if ($newRole === 'admin') {
                    Staff::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'name' => $user->full_name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'role' => 'admin',
                            'department' => 'Administrator',
                            'shift' => 'Office hours',
                            'status' => 'working',
                        ]
                    );

                    Patient::where('user_id', $user->id)->delete();
                    return;
                }

                Staff::where('user_id', $user->id)->delete();
                Patient::syncFromUser($user->fresh());
                return;
            }

            $patient->update([
                'name' => $validated['full_name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? 'other',
                'date_of_birth' => $validated['dob'] ?? null,
                'address' => collect([
                    $validated['address_detail'] ?? null,
                    $validated['ward'] ?? null,
                    $validated['district'] ?? null,
                    $validated['province'] ?? null,
                ])->filter()->implode(', '),
            ]);
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient information updated.');
    }

    public function destroy(Patient $patient)
    {
        DB::transaction(function () use ($patient) {
            if ($patient->user) {
                Staff::where('user_id', $patient->user->id)->delete();
                $patient->user->delete();
            }

            $patient->delete();
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient account deleted.');
    }

    protected function syncMissingPatients(): void
    {
        Patient::query()
            ->whereHas('user', fn ($query) => $query->where('role', '!=', 'user')->orWhereHas('doctorProfile'))
            ->delete();

        User::query()
            ->where('role', 'user')
            ->whereDoesntHave('doctorProfile')
            ->whereDoesntHave('patientProfile')
            ->get()
            ->each(fn (User $user) => Patient::syncFromUser($user));

        User::query()
            ->where('role', 'admin')
            ->get()
            ->each(function (User $user) {
                Staff::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->full_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => 'admin',
                        'department' => 'Administrator',
                        'shift' => 'Office hours',
                        'status' => 'working',
                    ]
                );
            });
    }

    protected function stats(): array
    {
        $visiblePatients = Patient::query()->where(function ($q) {
            $q->whereNull('user_id')
              ->orWhereHas('user', fn ($userQuery) => $userQuery
                  ->where('role', 'user')
                  ->whereDoesntHave('doctorProfile'));
        });

        return [
            'total' => (clone $visiblePatients)->count(),
            'male' => (clone $visiblePatients)->where('gender', 'male')->count(),
            'female' => (clone $visiblePatients)->where('gender', 'female')->count(),
            'new_this_month' => (clone $visiblePatients)->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }
}
