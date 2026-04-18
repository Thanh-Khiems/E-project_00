<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Specialty;
use Carbon\Carbon;

class DoctorListController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $windowEnd = $today->copy()->addDays(7);

        $query = Doctor::with([
                'user',
                'specialty',
                'schedules' => function ($scheduleQuery) use ($today, $windowEnd) {
                    $scheduleQuery->whereDate('end_date', '>=', $today->toDateString())
                        ->whereDate('start_date', '<=', $windowEnd->toDateString())
                        ->orderBy('start_date')
                        ->orderBy('start_time');
                },
            ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('approval_status', 'approved')
            ->where('status', 'active');

        if ($request->filled('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('degree', 'like', '%' . $keyword . '%')
                    ->orWhere('city', 'like', '%' . $keyword . '%')
                    ->orWhere('hospital', 'like', '%' . $keyword . '%')
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('full_name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('specialty', function ($sub) use ($keyword) {
                        $sub->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $doctors = $query
            ->orderByDesc('is_featured')
            ->orderByDesc('experience_years')
            ->latest()
            ->get();

        $specialties = Specialty::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        $cities = Doctor::query()
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('pages.user.doctor-list', compact('doctors', 'specialties', 'cities'));
    }
}
