<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Specialty;

class DoctorListController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'specialty', 'schedules'])
            ->where('approval_status', 'approved');

        if ($request->filled('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('degree', 'like', '%' . $keyword . '%')
                  ->orWhere('city', 'like', '%' . $keyword . '%')
                  ->orWhereHas('specialty', function ($sub) use ($keyword) {
                      $sub->where('name', 'like', '%' . $keyword . '%');
                  });
            });
        }

        $doctors = $query->latest()->get();

        $specialties = Specialty::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('pages.user.doctor-list', compact('doctors', 'specialties'));
    }
}
