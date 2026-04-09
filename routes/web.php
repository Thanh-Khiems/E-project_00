<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;

use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about');
Route::view('/services', 'pages.services');
Route::view('/contact', 'pages.contact');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Doctor (Frontend)
|--------------------------------------------------------------------------
*/
Route::get('/doctor', [DashboardController::class, 'doctor'])->name('doctor.dashboard');

Route::get('/appointments', [AppointmentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Admin (TẠM TẮT để tránh lỗi SQL)
|--------------------------------------------------------------------------
*/
// ❌ COMMENT lại để không bị lỗi DB khi chỉ làm frontend
/*
Route::redirect('/admin', '/admin/doctors');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
});
*/

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/
Route::get('/user/dashboard', function () {
    return view('pages.user.dashboard');
<<<<<<< HEAD
})->middleware('auth')->name('user.dashboard');
=======
})->middleware('auth')->name('user.dashboard');

Route::get('/doctor', function () {
    return view('pages.user.doctor');
});
>>>>>>> 38a0084b1cb3dab481ab048f2ecfac282df6017a
