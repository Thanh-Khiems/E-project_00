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
use App\Services\LocationService;

class ProfileController extends Controller
{
    // Hiển thị trang profile
    public function index()
        {
            $user = Auth::user();

            $locations = config('locations');
            $provinces = array_keys($locations);

            $appointments = Appointment::with(['doctor.user', 'doctor.specialty'])
                ->where('patient_id', $user->id)
                ->latest()
                ->get();

            return view('pages.user.profile', compact(
                'user',
                'locations',
                'provinces',
                'appointments'
            ));
        }

    // Cập nhật thông tin cá nhân
    public function update(Request $request, LocationService $locationService)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address_detail' => 'nullable|string|max:255',
        ]);

        if ($validated['province'] || $validated['district'] || $validated['ward']) {
            if (! $validated['province'] || ! $validated['district'] || ! $validated['ward']) {
                return redirect()->back()->withErrors([
                    'province' => 'Vui lòng chọn đầy đủ tỉnh/thành phố, quận/huyện và phường/xã.',
                ])->withInput();
            }

            if (! $locationService->isValidSelection($validated['province'], $validated['district'], $validated['ward'])) {
                return redirect()->back()->withErrors([
                    'province' => 'Khu vực đã chọn không hợp lệ hoặc đã thay đổi. Vui lòng chọn lại.',
                ])->withInput();
            }
        }

        $user = Auth::user();

        $user->update([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'address_detail' => $validated['address_detail'],
        ]);

        Patient::syncFromUser($user->fresh());

        return redirect()->back()->with('success', 'Thông tin cá nhân đã được cập nhật!');
    }

    // Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('password_error', 'Mật khẩu hiện tại không đúng!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('password_success', 'Mật khẩu đã được cập nhật!');
    }

    // Cập nhật avatar
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            $file->storeAs('avatars', $filename, 'public');

            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $user->avatar = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Avatar đã được cập nhật!');
    }

    // Gửi yêu cầu xác thực bác sĩ
    public function verifyDoctor(Request $request)
    {
        $request->validate([
            'doctor_full_name'   => 'required|string|max:255',
            'doctor_dob'         => 'required|date',
            'citizen_id'         => 'required|string|max:50',
            'citizen_id_front'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'citizen_id_back'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'doctor_phone'       => 'required|string|max:20',
            'degree'             => 'required|string|max:100',
            'degree_image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'specialty'          => 'required|string|max:255',
            'experience_years'   => 'required|integer|min:0|max:100',
            'doctor_city'        => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        // Không cho gửi lại nếu đang chờ duyệt
        $existingPending = Doctor::where('user_id', $user->id)
            ->where('approval_status', 'pending')
            ->first();

        if ($existingPending) {
            return redirect()->back()->with('error', 'Bạn đã có yêu cầu xác thực đang chờ duyệt.');
        }

        // Tìm hoặc tạo chuyên khoa
        $specialty = Specialty::firstOrCreate(
            ['name' => $request->specialty],
            [
                'description' => null,
                'status' => 'active',
                'is_featured' => false,
            ]
        );

        // Lưu file
        $citizenFrontPath = $request->file('citizen_id_front')->store('doctor-verifications', 'public');
        $citizenBackPath  = $request->file('citizen_id_back')->store('doctor-verifications', 'public');
        $degreeImagePath  = $request->file('degree_image')->store('doctor-verifications', 'public');

        // Nếu đã có hồ sơ trước đó thì cập nhật, chưa có thì tạo mới
        $doctor = Doctor::firstOrNew(['user_id' => $user->id]);

        $doctor->fill([
            'user_id'             => $user->id,
            'specialty_id'        => $specialty->id,
            'name'                => $request->doctor_full_name,
            'email'               => $user->email,
            'phone'               => $request->doctor_phone,
            'degree'              => $request->degree,
            'doctor_dob'          => $request->doctor_dob,
            'citizen_id'          => $request->citizen_id,
            'citizen_id_front'    => $citizenFrontPath,
            'citizen_id_back'     => $citizenBackPath,
            'degree_image'        => $degreeImagePath,
            'experience_years'    => $request->experience_years,
            'city'                => $request->doctor_city,
            'status'              => 'active',
            'approval_status'     => 'pending',
            'approval_note'       => null,
            'approved_at'         => null,
            'verification_status' => 'pending',
            'submitted_at'        => now(),
            'rejected_at'         => null,
        ]);

        $doctor->save();

        // Cập nhật trạng thái xác thực bên user
        $user->update([
            'doctor_verification_status' => 'pending',
            'doctor_verified_at' => null,
            'doctor_rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Đã gửi yêu cầu xác thực bác sĩ, vui lòng chờ admin duyệt.');
    }
}
