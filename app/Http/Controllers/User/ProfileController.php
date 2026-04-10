<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Hiển thị trang profile
    public function index()
    {
        $user = Auth::user();
        $locations = config('locations'); // load config locations.php
        $provinces = array_keys($locations); // danh sách tỉnh/thành phố

        return view('pages.user.profile', compact('user', 'locations', 'provinces'));
    }

    // Cập nhật thông tin cá nhân
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address_detail' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'address_detail' => $request->address_detail,
        ]);

        return redirect()->back()->with('success', 'Thông tin cá nhân đã được cập nhật!');
    }

    // Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('password_error', 'Mật khẩu hiện tại không đúng!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('password_success', 'Mật khẩu đã được cập nhật!');
    }

        public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time().'_'.$user->id.'.'.$file->getClientOriginalExtension();

            // Lưu file vào disk public
            $file->storeAs('avatars', $filename, 'public'); // <--- chú ý 'public' disk

            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                \Storage::disk('public')->delete('avatars/'.$user->avatar);
            }

            $user->avatar = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Avatar đã được cập nhật!');
    }
}
