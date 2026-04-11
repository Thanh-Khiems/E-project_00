<?php

namespace App\Http\Controllers;

use App\Models\MedicineType;
use Illuminate\Http\Request;

class MedicineTypeController extends Controller
{
    // Thêm loại thuốc
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        MedicineType::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect('/medications')->with('success', 'Thêm loại thuốc thành công');
    }
}