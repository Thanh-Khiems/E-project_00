<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'System Admin',
                'phone' => '0123456789',
                'gender' => 'male',
                'province' => 'Ho Chi Minh',
                'district' => 'District 1',
                'ward' => 'Ben Nghe',
                'address_detail' => 'Default Admin Account',
                'dob' => '1990-01-01',
                'role' => 'admin',
                'password' => Hash::make('Admin@123456'),
            ]
        );

        Staff::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'name' => $admin->full_name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'role' => 'admin',
                'department' => 'Administration',
                'shift' => 'Full-time',
                'status' => 'working',
            ]
        );
    }
}
