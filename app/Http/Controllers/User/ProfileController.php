<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\Degree;

class ProfileController extends Controller
{
    public function index()
    {
        Appointment::purgeExpired();

        $user = Auth::user();

        $locations = config('locations');
        $provinces = array_keys($locations);

        $appointments = Appointment::with(['doctor.user', 'doctor.specialty', 'prescriptions.items.medication.medicineType', 'review.patient'])
            ->where('patient_id', $user->id)
            ->visibleInCurrentWeek()
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        $completedAppointments = Appointment::with(['doctor.user', 'doctor.specialty', 'prescriptions.items.medication.medicineType', 'review.patient'])
            ->where('patient_id', $user->id)
            ->where('status', 'completed')
            ->latest('appointment_date')
            ->latest('start_time')
            ->get();

        $specialties = Specialty::query()->active()->orderBy('name')->get();
        $degrees = Degree::query()->active()->orderBy('name')->get();

        return view('pages.user.profile', compact(
            'user',
            'locations',
            'provinces',
            'appointments',
            'completedAppointments',
            'specialties',
            'degrees'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address_detail' => 'nullable|string|max:255',
        ]);

        $user->update($validated);
        Patient::syncFromUser($user);

        return back()->with('success', 'Account updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update(['password' => bcrypt($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }

    // ✅ FIX CHUẨN STORAGE
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {

            // Xoá avatar cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu avatar mới vào storage
            $path = $request->file('avatar')->store('uploads/avatars', 'public');

            // Lưu DB
            $user->update([
                'avatar' => $path,
            ]);
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function verifyDoctor(Request $request)
    {
        $validated = $request->validate([
            'doctor_full_name'   => 'required|string|max:255',
            'doctor_dob'         => 'required|date',
            'citizen_id'         => 'required|string|max:50',
            'citizen_id_front'   => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'citizen_id_back'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'doctor_phone'       => 'required|string|max:20',
            'degree'             => 'required|string|max:100',
            'degree_image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'specialty'          => 'required|string|max:255',
            'experience_years'   => 'required|integer|min:0|max:100',
            'doctor_city'        => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        $existingPending = Doctor::where('user_id', $user->id)
            ->where('approval_status', 'pending')
            ->first();

        if ($existingPending) {
            return back()->with('error', 'You already have a pending verification request.');
        }

        $specialty = Specialty::firstOrCreate(
            ['name' => $validated['specialty']],
            [
                'description' => null,
                'status' => 'active',
                'is_featured' => false,
            ]
        );

        $citizenFrontPath = $request->file('citizen_id_front')->store('doctor-verifications', 'public');
        $citizenBackPath = $request->file('citizen_id_back')->store('doctor-verifications', 'public');
        $degreeImagePath = $request->file('degree_image')->store('doctor-verifications', 'public');

        $doctor = Doctor::firstOrNew(['user_id' => $user->id]);

        $doctor->fill([
            'user_id' => $user->id,
            'specialty_id' => $specialty->id,
            'name' => $validated['doctor_full_name'],
            'email' => $user->email,
            'phone' => $validated['doctor_phone'],
            'degree' => $validated['degree'],
            'doctor_dob' => $validated['doctor_dob'],
            'citizen_id' => $validated['citizen_id'],
            'citizen_id_front' => $citizenFrontPath,
            'citizen_id_back' => $citizenBackPath,
            'degree_image' => $degreeImagePath,
            'experience_years' => $validated['experience_years'],
            'city' => $validated['doctor_city'] ?? $user->province,
            'status' => 'active',
            'approval_status' => 'pending',
            'verification_status' => 'pending',
            'submitted_at' => now(),
        ]);

        $doctor->save();

        $user->update([
            'doctor_verification_status' => 'pending',
            'doctor_verified_at' => null,
            'doctor_rejection_reason' => null,
        ]);

        return back()->with('success', 'Doctor verification request submitted.');
    }
}