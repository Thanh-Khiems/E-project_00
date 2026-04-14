<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $this->syncAdminUsersToStaff();

        $query = Staff::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('department', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staffs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.staffs.index', [
            'pageTitle' => 'Quản lý nhân viên',
            'staffs' => $staffs,
            'stats' => [
                'total' => Staff::count(),
                'working' => Staff::where('status', 'working')->count(),
                'leave' => Staff::where('status', 'leave')->count(),
                'admin' => Staff::where('role', 'admin')->count(),
            ]
        ]);
    }

    protected function syncAdminUsersToStaff(): void
    {
        User::query()
            ->where('role', 'admin')
            ->get()
            ->each(function (User $user) {
                Staff::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->full_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => 'admin',
                        'department' => 'Quản trị viên',
                        'shift' => 'Hành chính',
                        'status' => 'working',
                    ]
                );
            });
    }
}
