<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query()->withCount('appointments');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('patient_code', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('admin.patients.index', [
            'pageTitle' => 'Quản lý bệnh nhân',
            'patients' => $patients,
            'stats' => [
                'total' => Patient::count(),
                'male' => Patient::where('gender', 'male')->count(),
                'female' => Patient::where('gender', 'female')->count(),
                'new_this_month' => Patient::whereMonth('created_at', now()->month)->count(),
            ]
        ]);
    }
}
