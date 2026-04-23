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
        $validated = $this->validateMedication($request);

        Medication::create($validated);

        return back()->with('success', 'New medication added to the catalog.');
    }

    public function update(Request $request, Medication $medication): RedirectResponse
    {
        $validated = $this->validateMedication($request, $medication);

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

    protected function validateMedication(Request $request, ?Medication $medication = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'dosage' => trim((string) $request->input('dosage')),
            'category' => $request->filled('category') ? trim((string) $request->input('category')) : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dosage' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($request, $medication) {
                    $normalizedName = $this->normalizeText((string) $request->input('name'));
                    $normalizedDosage = $this->normalizeText((string) $value);

                    $exists = Medication::query()
                        ->when($medication, fn ($query) => $query->whereKeyNot($medication->id))
                        ->get()
                        ->contains(function (Medication $item) use ($normalizedName, $normalizedDosage) {
                            return $this->normalizeText($item->name) === $normalizedName
                                && $this->normalizeText($item->dosage) === $normalizedDosage;
                        });

                    if ($exists) {
                        $fail('This medication already exists in the catalog.');
                    }
                },
            ],
            'medicine_type_id' => ['nullable', 'exists:medicine_types,id'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        return [
            'name' => $validated['name'],
            'dosage' => $validated['dosage'],
            'medicine_type_id' => $validated['medicine_type_id'] ?? null,
            'category' => $validated['category'] ?? null,
        ];
    }

    protected function normalizeText(string $value): string
    {
        return mb_strtolower(trim($value));
    }
}
