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

        $degrees = $query->latest()->paginate(10)->withQueryString();

        return view('admin.degrees.index', [
            'pageTitle' => 'Quản lý bằng cấp',
            'degrees' => $degrees,
            'stats' => [
                'total' => Degree::count(),
                'visible' => Degree::where('status', Degree::STATUS_ACTIVE)->count(),
                'hidden' => Degree::where('status', Degree::STATUS_INACTIVE)->count(),
                'used' => Doctor::query()
                    ->whereNotNull('degree')
                    ->whereIn('degree', Degree::query()->pluck('name'))
                    ->distinct('degree')
                    ->count('degree'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateDegree($request);

        Degree::create($validated);

        return redirect()->route('admin.degrees.index')->with('success', 'Đã thêm bằng cấp mới.');
    }

    public function update(Request $request, Degree $degree)
    {
        $oldName = $degree->name;
        $validated = $this->validateDegree($request, $degree);

        $degree->update($validated);

        if ($oldName !== $validated['name']) {
            Doctor::query()
                ->where('degree', $oldName)
                ->update(['degree' => $validated['name']]);
        }

        return redirect()->route('admin.degrees.index')->with('success', 'Đã cập nhật bằng cấp thành công.');
    }

    public function destroy(Degree $degree)
    {
        $inUseCount = Doctor::query()->where('degree', $degree->name)->count();

        if ($inUseCount > 0) {
            return redirect()->route('admin.degrees.index')
                ->with('error', "Không thể xóa bằng cấp {$degree->name} vì đang có {$inUseCount} bác sĩ sử dụng.");
        }

        $degreeName = $degree->name;
        $degree->delete();

        return redirect()->route('admin.degrees.index')->with('success', "Đã xóa bằng cấp: {$degreeName}.");
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
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];
    }
}
