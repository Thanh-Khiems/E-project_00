<?php

namespace App\Http\Controllers;

use App\Models\MedicineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicineTypeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        MedicineType::create($validated);

        return back()->with('success', 'New medication group added.');
    }

    public function update(Request $request, MedicineType $medicineType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $medicineType->update($validated);

        return back()->with('success', 'Medication group updated.');
    }

    public function destroy(MedicineType $medicineType): RedirectResponse
    {
        if ($medicineType->medications()->exists()) {
            return back()->with('error', 'This medication group still contains medications, so it cannot be deleted.');
        }

        $medicineType->delete();

        return back()->with('success', 'Medication group deleted.');
    }
}
