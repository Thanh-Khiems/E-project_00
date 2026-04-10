<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // Trang danh sách bác sĩ
    public function index()
    {
        // Fake data demo, sau này lấy từ DB
        $doctors = [
            [
                'id' => 1,
                'name' => 'Dr. John Doe',
                'specialty' => 'Cardiologist',
                'rating' => 4.8,
                'reviews' => 150,
                'city' => 'Hồ Chí Minh',
                'avatar' => 'doctor-placeholder.png',
                'working_hours' => ['09:00-12:00','13:00-17:00']
            ],
            [
                'id' => 2,
                'name' => 'Dr. Jane Smith',
                'specialty' => 'Dermatologist',
                'rating' => 4.7,
                'reviews' => 120,
                'city' => 'Hà Nội',
                'avatar' => 'doctor-placeholder.png',
                'working_hours' => ['08:00-11:00','13:00-16:00']
            ],
        ];

        return view('pages.user.doctor-list', compact('doctors'));
    }

    // Trang chi tiết bác sĩ
    public function show($id)
    {
        $doctor = [
            'id' => $id,
            'name' => 'Dr. John Doe',
            'specialty' => 'Cardiologist',
            'rating' => 4.8,
            'reviews' => 150,
            'city' => 'Hồ Chí Minh',
            'avatar' => 'doctor-placeholder.png',
            'working_hours' => ['09:00-12:00','13:00-17:00']
        ];

        return view('pages.user.doctor-detail', compact('doctor'));
    }

    // Trang đặt lịch hẹn
    public function appointment()
    {
        return view('pages.user.appointment');
    }
}
