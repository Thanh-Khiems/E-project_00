<?php

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
Route::get('/admin/dashboard', function () {
    return 'Admin dashboard';
});
//User Dashboard
Route::get('/user/dashboard', function () {
    return view('pages.user.dashboard');
})->middleware('auth')->name('user.dashboard');
