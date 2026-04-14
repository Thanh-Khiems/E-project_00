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
        $user = Auth::user();

        $locations = config('locations');
        $provinces = array_keys($locations);

        $appointments = Appointment::with(['doctor.user', 'doctor.specialty', 'prescriptions.items.medication.medicineType'])
            ->where('patient_id', $user->id)
            ->latest('appointment_date')
            ->latest('start_time')
            ->get();

        $completedAppointments = $appointments->where('status', 'completed')->values();

        $specialties = Specialty::query()
            ->active()
            ->orderBy('name')
            ->get();

        $degrees = Degree::query()
            ->active()
            ->orderBy('name')
            ->get();

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

        return back()->with('success', 'Cập nhật tài khoản thành công.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update(['password' => bcrypt($request->password)]);

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Cập nhật ảnh đại diện thành công.');
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
            return back()->with('error', 'Bạn đã có yêu cầu xác thực đang chờ duyệt.');
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
            'user_id'             => $user->id,
            'specialty_id'        => $specialty->id,
            'name'                => $validated['doctor_full_name'],
            'email'               => $user->email,
            'phone'               => $validated['doctor_phone'],
            'degree'              => $validated['degree'],
            'doctor_dob'          => $validated['doctor_dob'],
            'citizen_id'          => $validated['citizen_id'],
            'citizen_id_front'    => $citizenFrontPath,
            'citizen_id_back'     => $citizenBackPath,
            'degree_image'        => $degreeImagePath,
            'experience_years'    => $validated['experience_years'],
            'city'                => $validated['doctor_city'] ?? $user->province,
            'status'              => 'active',
            'approval_status'     => 'pending',
            'approval_note'       => null,
            'approved_at'         => null,
            'verification_status' => 'pending',
            'submitted_at'        => now(),
            'rejected_at'         => null,
        ]);

        $doctor->save();

        $user->update([
            'doctor_verification_status' => 'pending',
            'doctor_verified_at' => null,
            'doctor_rejection_reason' => null,
        ]);

        return back()->with('success', 'Đã gửi yêu cầu xác thực bác sĩ, vui lòng chờ admin duyệt.');
    }
}
