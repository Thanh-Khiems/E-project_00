<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home');
Route::view('/about', 'pages.about');
Route::view('/services', 'pages.services');
Route::view('/contact', 'pages.contact');

Route::view('/register', 'pages.register');