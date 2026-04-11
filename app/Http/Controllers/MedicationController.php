<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\MedicineType;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    // 📌 Hiển thị
    public function index()
    {
        $medications = Medication::with('type')->get();
        $types = MedicineType::all();

        return view('pages.doctor.medications', compact('medications', 'types'));
    }

    // 📌 Thêm thuốc
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'dosage' => 'required',
            'medicine_type_id' => 'required',
            'category' => 'required',
        ]);

        Medication::create($request->all());

        return redirect('/medications')->with('success', 'Thêm thành công');
    }

    // 📌 Update
    public function update(Request $request, $id)
    {
        $med = Medication::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'dosage' => 'required',
            'medicine_type_id' => 'required',
            'category' => 'required',
        ]);

        $med->update($request->all());

        return redirect('/medications')->with('success', 'Cập nhật thành công');
    }

    // 📌 Xóa
    public function destroy($id)
    {
        $med = Medication::findOrFail($id);
        $med->delete();

        return redirect('/medications')->with('success', 'Xóa thành công');
    }
}