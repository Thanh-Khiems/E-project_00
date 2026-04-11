<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query()->with('specialty');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $doctors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.doctors.index', [
            'pageTitle' => 'Quản lý bác sĩ',
            'doctors' => $doctors,
            'specialties' => Specialty::orderBy('name')->get(),
            'stats' => $this->stats(),
        ]);
    }

    public function approvals(Request $request)
    {
        $query = Doctor::query()->with('specialty');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        $approvalStatus = $request->get('approval_status', 'pending');
        if ($approvalStatus !== 'all') {
            $query->where('approval_status', $approvalStatus);
        }

        $doctors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.doctors.approvals', [
            'pageTitle' => 'Duyệt bác sĩ',
            'doctors' => $doctors,
            'stats' => $this->stats(),
            'approvalStatus' => $approvalStatus,
        ]);
    }

    public function approve(Doctor $doctor)
    {
        $doctor->update([
            'approval_status' => 'approved',
            'approval_note' => null,
            'approved_at' => now(),
            'status' => $doctor->status ?: 'active',
        ]);

        return back()->with('success', 'Đã duyệt bác sĩ thành công.');
    }

    public function reject(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'approval_note' => 'nullable|string|max:500',
        ]);

        $doctor->update([
            'approval_status' => 'rejected',
            'approval_note' => $validated['approval_note'] ?? null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Đã từ chối hồ sơ bác sĩ.');
    }

    protected function stats(): array
    {
        return [
            'total' => Doctor::count(),
            'active' => Doctor::where('status', 'active')->count(),
            'inactive' => Doctor::where('status', 'inactive')->count(),
            'featured' => Doctor::where('is_featured', true)->count(),
            'pending' => Doctor::where('approval_status', 'pending')->count(),
            'approved' => Doctor::where('approval_status', 'approved')->count(),
            'rejected' => Doctor::where('approval_status', 'rejected')->count(),
        ];
    }
}
