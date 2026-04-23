<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        $query = Degree::query();

        if ($request->filled('keyword')) {
            $query->where(function ($builder) use ($request) {
                $builder->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        $degrees = $query->latest()->paginate(12)->withQueryString();

        return view('admin.degrees.index', [
            'pageTitle' => 'Degree management',
            'degrees' => $degrees,
            'stats' => [
                'total' => Degree::count(),
                'visible' => Degree::where('status', Degree::STATUS_ACTIVE)->count(),
                'hidden' => Degree::where('status', Degree::STATUS_INACTIVE)->count(),
                'used' => Doctor::query()->whereNotNull('degree')->where('degree', '!=', '')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateDegree($request);

        Degree::create($validated);

        return redirect()->route('admin.degrees.index')->with('success', 'New degree added.');
    }

    public function update(Request $request, Degree $degree)
    {
        $oldName = $degree->name;
        $validated = $this->validateDegree($request, $degree);

        $degree->update($validated);

        if ($oldName !== $degree->name) {
            $this->renameDegreeForDoctors($oldName, $degree->name);
        }

        return redirect()->route('admin.degrees.index')->with('success', 'Degree updated successfully.');
    }

    public function destroy(Degree $degree)
    {
        $degreeName = $degree->name;

        $this->removeDegreeFromDoctors($degreeName);
        $degree->delete();

        return redirect()->route('admin.degrees.index')->with('success', "Deleted degree: {$degreeName}.");
    }

    protected function validateDegree(Request $request, ?Degree $degree = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('degrees', 'name')->ignore($degree?->id),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in([Degree::STATUS_ACTIVE, Degree::STATUS_INACTIVE])],
        ]);

        return [
            'name' => trim($validated['name']),
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];
    }

    protected function renameDegreeForDoctors(string $oldName, string $newName): void
    {
        Doctor::query()
            ->where('degree', 'like', '%' . $oldName . '%')
            ->get()
            ->each(function (Doctor $doctor) use ($oldName, $newName) {
                $updatedDegrees = collect($doctor->degree_list)
                    ->map(fn (string $value) => $value === $oldName ? $newName : $value)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $doctor->update([
                    'degree' => empty($updatedDegrees) ? null : implode(', ', $updatedDegrees),
                ]);
            });
    }

    protected function removeDegreeFromDoctors(string $degreeName): void
    {
        Doctor::query()
            ->where('degree', 'like', '%' . $degreeName . '%')
            ->get()
            ->each(function (Doctor $doctor) use ($degreeName) {
                $updatedDegrees = collect($doctor->degree_list)
                    ->reject(fn (string $value) => $value === $degreeName)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $doctor->update([
                    'degree' => empty($updatedDegrees) ? null : implode(', ', $updatedDegrees),
                ]);
            });
    }
}
