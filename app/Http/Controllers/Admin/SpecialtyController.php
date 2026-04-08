<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $query = Specialty::query()->withCount(['doctors', 'appointments']);

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%'.$request->keyword.'%')
                ->orWhere('description', 'like', '%'.$request->keyword.'%');
        }

        $specialties = $query->latest()->paginate(10)->withQueryString();

        return view('admin.specialties.index', [
            'pageTitle' => 'Quản lý chuyên khoa',
            'specialties' => $specialties,
            'stats' => [
                'total' => Specialty::count(),
                'visible' => Specialty::where('status', 'active')->count(),
                'hidden' => Specialty::where('status', 'inactive')->count(),
                'featured' => Specialty::where('is_featured', true)->count(),
            ]
        ]);
    }
}
