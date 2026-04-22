<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\DoctorListController;
use App\Http\Controllers\User\DoctorBookingController;
use App\Http\Controllers\AppointmentController;
// Admin Controllers
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DegreeController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\MedicineTypeController;
use App\Http\Controllers\Admin\MedicationController as AdminMedicationController;
use App\Http\Controllers\Doctor\PrescriptionController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/services', 'pages.services')->name('services');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->middleware('auth')->name('blog.show');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email');
Route::get('/forgot-password/otp', [AuthController::class, 'showOtpForm'])->name('password.otp');
Route::post('/forgot-password/otp', [AuthController::class, 'verifyResetCode'])->name('password.otp.verify');
Route::get('/forgot-password/reset', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])->name('password.update.custom');

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



/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});
/*
|--------------------------------------------------------------------------
| Doctor List
|--------------------------------------------------------------------------
*/
Route::get('/doctor', [DoctorListController::class, 'index'])->name('doctors.index');
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
Route::get('/user/dashboard', [DashboardController::class, 'user'])
    ->middleware('auth')
    ->name('user.dashboard');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
    Route::get('/', fn () => redirect()->route('admin.doctors.index'))->name('index');
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctor-approvals', [DoctorController::class, 'approvals'])->name('doctors.approvals');
    Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])->name('doctors.approve');
    Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])->name('doctors.reject');

    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::post('/doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('doctors.toggleStatus');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');

    Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::post('/specialties', [SpecialtyController::class, 'store'])->name('specialties.store');
    Route::put('/specialties/{specialty}', [SpecialtyController::class, 'update'])->name('specialties.update');
    Route::delete('/specialties/{specialty}', [SpecialtyController::class, 'destroy'])->name('specialties.destroy');

    Route::get('/degrees', [DegreeController::class, 'index'])->name('degrees.index');
    Route::post('/degrees', [DegreeController::class, 'store'])->name('degrees.store');
    Route::put('/degrees/{degree}', [DegreeController::class, 'update'])->name('degrees.update');
    Route::delete('/degrees/{degree}', [DegreeController::class, 'destroy'])->name('degrees.destroy');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');

    Route::get('/medications', [AdminMedicationController::class, 'index'])->name('medications.index');
    Route::post('/medications', [AdminMedicationController::class, 'store'])->name('medications.store');
    Route::put('/medications/{medication}', [AdminMedicationController::class, 'update'])->name('medications.update');
    Route::delete('/medications/{medication}', [AdminMedicationController::class, 'destroy'])->name('medications.destroy');
    Route::post('/medicine-types', [MedicineTypeController::class, 'store'])->name('medicine-types.store');
    Route::put('/medicine-types/{medicineType}', [MedicineTypeController::class, 'update'])->name('medicine-types.update');
    Route::delete('/medicine-types/{medicineType}', [MedicineTypeController::class, 'destroy'])->name('medicine-types.destroy');

    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
    Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');
});

/*
|--------------------------------------------------------------------------
| Medication
|--------------------------------------------------------------------------
*/


Route::get('/doctor-booking/{doctor}', [DoctorBookingController::class, 'show'])->middleware('auth')->name('doctor.booking');




Route::middleware('auth')->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/my-appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.patient');
    Route::get('/doctor-appointments', [AppointmentController::class, 'doctorIndex'])->name('doctor.appointments');
});


Route::middleware(['auth'])->group(function () {
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])
        ->name('appointments.confirm');

    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');

    Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])
        ->name('appointments.complete');

    Route::post('/appointments/{appointment}/review', [AppointmentController::class, 'storeReview'])
        ->name('appointments.review');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/appointments/{appointment}/prescriptions/create', [PrescriptionController::class, 'create'])
        ->name('doctor.appointments.prescriptions.create');
    Route::post('/appointments/{appointment}/prescriptions', [PrescriptionController::class, 'store'])
        ->name('doctor.appointments.prescriptions.store');
});
