<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\MedicineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        $medications = Medication::with('medicineType')
            ->withCount('prescriptionItems')
            ->latest()
            ->paginate(12);

        $medicineTypes = MedicineType::withCount('medications')
            ->orderBy('name')
            ->get();

        return view('admin.medications.index', compact('medications', 'medicineTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dosage' => ['required', 'string', 'max:255'],
            'medicine_type_id' => ['nullable', 'exists:medicine_types,id'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        Medication::create($validated);

        return back()->with('success', 'New medication added to the catalog.');
    }

    public function update(Request $request, Medication $medication): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dosage' => ['required', 'string', 'max:255'],
            'medicine_type_id' => ['nullable', 'exists:medicine_types,id'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $medication->update($validated);

        return back()->with('success', 'Medication information updated.');
    }

    public function destroy(Medication $medication): RedirectResponse
    {
        if ($medication->prescriptionItems()->exists()) {
            return back()->with('error', 'This medication has already been used in prescriptions, so it cannot be deleted.');
        }

        $medication->delete();

        return back()->with('success', 'Medication removed from the catalog.');
    }
}
