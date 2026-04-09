<?php
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about');
Route::view('/services', 'pages.services');
Route::view('/contact', 'pages.contact');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Doctor Dashboard
Route::get('/doctor/dashboard', function () {
    return 'Doctor dashboard';
});

//Admin Dashboard
Route::redirect('/admin', '/admin/doctors');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
});
//User Dashboard
Route::get('/user/dashboard', function () {
    return view('pages.user.dashboard');
})->middleware('auth')->name('user.dashboard');

Route::get('/doctor', function () {
    return view('pages.user.doctor');
});