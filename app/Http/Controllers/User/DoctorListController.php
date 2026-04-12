<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorListController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'specialty', 'schedules'])
            ->where('approval_status', 'approved');

        if ($request->filled('specialty')) {
            $query->whereHas('specialty', function ($q) use ($request) {
                $q->where('name', $request->specialty);
            });
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

        return view('pages.user.doctor-list', compact('doctors'));
    }
}
