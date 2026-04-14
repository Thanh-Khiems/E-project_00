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

        return back()->with('success', 'Đã thêm nhóm thuốc mới.');
    }
}
