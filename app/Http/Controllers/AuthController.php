<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
                'province' => 'The selected location is invalid or has changed. Please choose again.',
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

        return redirect()->route('user.dashboard')->with('success', 'Account registered successfully');
    }

    public function showLogin(Request $request)
    {
        $this->storeIntendedUrl($request->query('redirect'));

        return view('pages.home');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->storeIntendedUrl($request->input('redirect'));

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $defaultRedirect = $this->defaultRedirectPathFor($user);

            return redirect()->intended($defaultRedirect)->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Email or password is incorrect.',
        ])->withInput($request->only('email', 'redirect'));
    }

    public function showForgotPassword()
    {
        return view('pages.auth.forgot-password-email');
    }

    public function sendResetCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'This email is not registered in the system.',
        ]);

        $otp = (string) random_int(100000, 999999);

        $request->session()->put('password_reset.email', $validated['email']);
        $request->session()->put('password_reset.otp', $otp);
        $request->session()->put('password_reset.verified', false);
        $request->session()->put('password_reset.sent_at', now()->timestamp);

        return redirect()->route('password.otp')
            ->with('success', 'Verification code created. Demo code: ' . $otp);
    }

    public function showOtpForm(Request $request)
    {
        abort_unless($request->session()->has('password_reset.email'), 403);

        return view('pages.auth.forgot-password-otp', [
            'email' => $request->session()->get('password_reset.email'),
        ]);
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.digits' => 'The verification code must contain exactly 6 digits.',
        ]);

        if (! $request->session()->has('password_reset.otp')) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'The password reset session has expired. Please try again.',
            ]);
        }

        if ($request->otp !== $request->session()->get('password_reset.otp')) {
            return back()->withErrors([
                'otp' => 'The verification code is incorrect.',
            ])->withInput();
        }

        $request->session()->put('password_reset.verified', true);

        return redirect()->route('password.reset.form');
    }

    public function showResetPasswordForm(Request $request)
    {
        abort_unless($request->session()->get('password_reset.verified') === true, 403);

        return view('pages.auth.forgot-password-reset', [
            'email' => $request->session()->get('password_reset.email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        abort_unless($request->session()->get('password_reset.verified') === true, 403);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->session()->get('password_reset.email');
        $user = User::where('email', $email)->firstOrFail();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->forget('password_reset');

        return redirect()->route('login')->with('success', 'The new password has been updated. Please log in again.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function defaultRedirectPathFor(User $user): string
    {
        if ($user->role === 'admin') {
            return '/admin';
        }

        if ($user->role === 'doctor') {
            return route('doctor.dashboard');
        }

        return route('user.dashboard');
    }

    protected function storeIntendedUrl(?string $redirect): void
    {
        $redirect = trim((string) $redirect);

        if ($redirect === '' || ! $this->isSafeRedirectTarget($redirect)) {
            return;
        }

        session(['url.intended' => $redirect]);
    }

    protected function isSafeRedirectTarget(string $redirect): bool
    {
        return Str::startsWith($redirect, ['/']) || Str::startsWith($redirect, url('/'));
    }

    public function doctor()
    {
        return view('pages.doctor.doctor-main');
    }
}
