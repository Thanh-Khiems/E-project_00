<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        $degrees = Degree::fixedCollection();

        if ($request->filled('keyword')) {
            $keyword = mb_strtolower(trim((string) $request->keyword));

            $degrees = $degrees->filter(function ($degree) use ($keyword) {
                return str_contains(mb_strtolower((string) $degree->name), $keyword)
                    || str_contains(mb_strtolower((string) ($degree->description ?? '')), $keyword);
            })->values();
        }

        return view('admin.degrees.index', [
            'pageTitle' => 'Degree management',
            'degrees' => $degrees,
            'stats' => [
                'total' => $degrees->count(),
                'visible' => $degrees->where('status', Degree::STATUS_ACTIVE)->count(),
                'hidden' => $degrees->where('status', Degree::STATUS_INACTIVE)->count(),
                'used' => Doctor::query()->whereNotNull('degree')->where('degree', '!=', '')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.degrees.index')
            ->with('error', 'Degree list is hard-coded. Please update App\Models\Degree::FIXED_DEGREES if you want to change it.');
    }

    public function update(Request $request, Degree $degree)
    {
        return redirect()->route('admin.degrees.index')
            ->with('error', 'Degree list is hard-coded. Please update App\Models\Degree::FIXED_DEGREES if you want to change it.');
    }

    public function destroy(Degree $degree)
    {
        return redirect()->route('admin.degrees.index')
            ->with('error', 'Degree list is hard-coded. Please update App\Models\Degree::FIXED_DEGREES if you want to change it.');
    }
}
