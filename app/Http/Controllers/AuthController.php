<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\LocationService;

class AuthController extends Controller
{
    public function showRegister(LocationService $locationService)
    {
        $locations = $locationService->getStructuredLocations();
        $provinces = array_keys($locations);

        return view('pages.register', compact('locations', 'provinces'));
    }

    public function register(Request $request, LocationService $locationService)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! $locationService->isValidSelection($validated['province'], $validated['district'], $validated['ward'])) {
            return back()->withErrors([
                'province' => 'Khu vực đã chọn không hợp lệ hoặc đã thay đổi. Vui lòng chọn lại.',
            ])->withInput();
        }

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'address_detail' => $validated['address_detail'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'role' => 'user',
            'password' => Hash::make($validated['password']),
        ]);

        Patient::syncFromUser($user);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Đăng ký tài khoản thành công');
    }

    public function showLogin()
    {
        return view('pages.home');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin')->with('success', 'Login successful');
            }

            if ($user->role === 'doctor') {
                return redirect()->route('doctor.dashboard')->with('success', 'Login successful');
            }

            return redirect()->route('user.dashboard')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Email or password is incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function doctor()
    {
        return view('pages.doctor.doctor-main');
    }
}
