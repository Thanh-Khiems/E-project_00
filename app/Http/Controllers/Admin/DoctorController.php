<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query()->with('specialty');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $doctors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.doctors.index', [
            'pageTitle' => 'Quản lý bác sĩ',
            'doctors' => $doctors,
            'specialties' => Specialty::orderBy('name')->get(),
            'stats' => [
                'total' => Doctor::count(),
                'active' => Doctor::where('status', 'active')->count(),
                'inactive' => Doctor::where('status', 'inactive')->count(),
                'featured' => Doctor::where('is_featured', true)->count(),
            ]
        ]);
    }
}
