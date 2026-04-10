<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    // 📌 Lấy danh sách thuốc
    public function index()
    {
        return response()->json(Medication::all());
    }

    // 📌 Thêm thuốc
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $med = Medication::create($data);

        return response()->json($med);
    }

    // ✏️ Update thuốc
    public function update(Request $request, $id)
    {
        $med = Medication::find($id);

        if (!$med) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $med->update([
            'name' => $request->name
        ]);

        return response()->json($med);
    }

    // ❌ Xoá thuốc
    public function destroy($id)
    {
        $med = Medication::find($id);

        if (!$med) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $med->delete();

        return response()->json(['message' => 'Deleted']);
    }
}