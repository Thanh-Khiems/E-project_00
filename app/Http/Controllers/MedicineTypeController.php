<?php

namespace App\Http\Controllers;

use App\Models\MedicineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicineTypeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMedicineType($request);

        MedicineType::create($validated);

        return back()->with('success', 'New medication group added.');
    }

    public function update(Request $request, MedicineType $medicineType): RedirectResponse
    {
        $validated = $this->validateMedicineType($request, $medicineType);

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

    protected function validateMedicineType(Request $request, ?MedicineType $medicineType = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'description' => $request->filled('description') ? trim((string) $request->input('description')) : null,
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($medicineType) {
                    $normalized = $this->normalizeText((string) $value);

                    $exists = MedicineType::query()
                        ->when($medicineType, fn ($query) => $query->whereKeyNot($medicineType->id))
                        ->get()
                        ->contains(fn (MedicineType $type) => $this->normalizeText($type->name) === $normalized);

                    if ($exists) {
                        $fail('This medication group already exists.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        return [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];
    }

    protected function normalizeText(string $value): string
    {
        return mb_strtolower(trim($value));
    }
}
