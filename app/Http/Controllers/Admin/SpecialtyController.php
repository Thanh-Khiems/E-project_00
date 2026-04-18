<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $query = Specialty::query()->withCount(['doctors', 'appointments']);

        if ($request->filled('keyword')) {
            $query->where(function ($builder) use ($request) {
                $builder->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        $specialties = $query->latest()->paginate(10)->withQueryString();

        return view('admin.specialties.index', [
            'pageTitle' => 'Specialty management',
            'specialties' => $specialties,
            'stats' => [
                'total' => Specialty::count(),
                'visible' => Specialty::where('status', 'active')->count(),
                'hidden' => Specialty::where('status', 'inactive')->count(),
                'featured' => Specialty::where('is_featured', true)->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSpecialty($request);

        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'New specialty added.');
    }

    public function update(Request $request, Specialty $specialty)
    {
        $validated = $this->validateSpecialty($request, $specialty);

        $specialty->update($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Specialty updated successfully.');
    }

    public function destroy(Specialty $specialty)
    {
        $specialtyName = $specialty->name;
        $specialty->delete();

        return redirect()->route('admin.specialties.index')->with('success', "Deleted specialty: {$specialtyName}.");
    }

    protected function validateSpecialty(Request $request, ?Specialty $specialty = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('specialties', 'name')->ignore($specialty?->id),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in([Specialty::STATUS_ACTIVE, Specialty::STATUS_INACTIVE])],
            'is_featured' => 'nullable|boolean',
        ]);

        return [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
        ];
    }
}

