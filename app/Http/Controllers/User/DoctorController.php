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
        $query = Doctor::with(['specialty', 'user']);

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
            'pageTitle' => 'Doctor List',
            'doctors' => $doctors,
            'specialties' => Specialty::orderBy('name')->get(),
            'stats' => $this->stats(),
        ]);
    }

    public function approvals(Request $request)
    {
        $query = Doctor::with(['specialty', 'user']);

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
            'pageTitle' => 'Doctor approvals',
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
            'verification_status' => 'approved',
            'status' => 'active',
        ]);

        if ($doctor->user) {
            $doctor->user->update([
                'role' => 'doctor',
                'doctor_verification_status' => 'approved',
                'doctor_rejection_reason' => null,
                'doctor_verified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Doctor approved successfully.');
    }

    public function reject(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'approval_note' => 'nullable|string|max:500',
        ]);

        $doctor->update([
            'approval_status' => 'rejected',
            'approval_note' => $validated['approval_note'] ?? null,
            'verification_status' => 'rejected',
            'rejected_at' => now(),
        ]);

        if ($doctor->user) {
            $doctor->user->update([
                'role' => 'user',
                'doctor_verification_status' => 'rejected',
                'doctor_rejection_reason' => $validated['approval_note'] ?? null,
                'doctor_verified_at' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Doctor application rejected.');
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
