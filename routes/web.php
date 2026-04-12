<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\DoctorListController;

// Admin Controllers
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\MedicineTypeController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
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
| Doctor
|--------------------------------------------------------------------------
*/
Route::get('/doctor-main', [DashboardController::class, 'doctor'])
    ->middleware('auth')
    ->name('doctor.dashboard');

Route::get('/doctor-manage', [DashboardController::class, 'manageAppointments'])
    ->middleware('auth')
    ->name('doctor.manage');

Route::get('/doctor-appointments', [DashboardController::class, 'appointments'])
    ->middleware('auth')
    ->name('doctor.appointments');

/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});
/*
|--------------------------------------------------------------------------
| Doctor List
|--------------------------------------------------------------------------
*/
Route::get('/doctor-list', [DoctorListController::class, 'index'])->name('doctor-list');

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/doctor-list', [DoctorListController::class, 'index'])->name('doctor-list');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/verify-doctor', [ProfileController::class, 'verifyDoctor'])->name('profile.verifyDoctor');
});

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/
Route::get('/user/dashboard', function () {
    return view('pages.user.dashboard');
})->middleware('auth')->name('user.dashboard');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::redirect('/admin', '/admin/doctors');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctor-approvals', [DoctorController::class, 'approvals'])->name('doctors.approvals');
    Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])->name('doctors.approve');
    Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])->name('doctors.reject');

    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::post('/doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('doctors.toggleStatus');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');

    Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
});

/*
|--------------------------------------------------------------------------
| Medication
|--------------------------------------------------------------------------
*/
Route::get('/medications', [MedicationController::class, 'index']);
Route::post('/medications', [MedicationController::class, 'store']);
Route::delete('/medications/{id}', [MedicationController::class, 'destroy']);
Route::post('/medicine-types', [MedicineTypeController::class, 'store']);
