<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultDemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            'Cardiology' => [
                'description' => 'Diagnosis and treatment for heart and cardiovascular conditions.',
                'is_featured' => true,
            ],
            'Dermatology' => [
                'description' => 'Skin, hair, and nail consultation and treatment.',
                'is_featured' => true,
            ],
            'Pediatrics' => [
                'description' => 'Healthcare services for infants, children, and adolescents.',
                'is_featured' => false,
            ],
        ];

        $specialtyMap = [];

        foreach ($specialties as $name => $specialtyData) {
            $specialtyMap[$name] = Specialty::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $specialtyData['description'],
                    'status' => 'active',
                    'is_featured' => $specialtyData['is_featured'],
                ]
            );
        }

        $doctorAccounts = [
            [
                'full_name' => 'Dr. Nguyen Minh Tam',
                'email' => 'doctor1@gmail.com',
                'phone' => '0901000001',
                'gender' => 'male',
                'province' => 'Ho Chi Minh',
                'district' => 'District 1',
                'ward' => 'Ben Nghe',
                'address_detail' => 'Default Doctor Account 1',
                'dob' => '1985-03-15',
                'specialty' => 'Cardiology',
                'degree' => 'MD, Cardiology',
                'license_number' => 'DOC-CARD-0001',
                'experience_years' => 12,
                'hospital' => 'MediConnect Central Hospital',
                'clinic_address' => '12 Le Loi, District 1',
                'city' => 'Ho Chi Minh',
                'bio' => 'Experienced cardiologist focusing on preventive care, cardiovascular screening, and follow-up treatment.',
                'consultation_fee' => 300000,
                'schedule_text' => 'Mon - Fri | 08:00 - 16:00',
                'is_featured' => true,
            ],
            [
                'full_name' => 'Dr. Tran Ha Linh',
                'email' => 'doctor2@gmail.com',
                'phone' => '0901000002',
                'gender' => 'female',
                'province' => 'Ho Chi Minh',
                'district' => 'District 3',
                'ward' => 'Vo Thi Sau',
                'address_detail' => 'Default Doctor Account 2',
                'dob' => '1988-07-21',
                'specialty' => 'Dermatology',
                'degree' => 'MD, Dermatology',
                'license_number' => 'DOC-DERM-0002',
                'experience_years' => 9,
                'hospital' => 'MediConnect Skin Clinic',
                'clinic_address' => '25 Nguyen Dinh Chieu, District 3',
                'city' => 'Ho Chi Minh',
                'bio' => 'Dermatologist with a focus on acne, eczema, skin infections, and routine skin health consultation.',
                'consultation_fee' => 250000,
                'schedule_text' => 'Tue - Sat | 09:00 - 17:00',
                'is_featured' => true,
            ],
            [
                'full_name' => 'Dr. Le Quoc Bao',
                'email' => 'doctor3@gmail.com',
                'phone' => '0901000003',
                'gender' => 'male',
                'province' => 'Ho Chi Minh',
                'district' => 'District 7',
                'ward' => 'Tan Phu',
                'address_detail' => 'Default Doctor Account 3',
                'dob' => '1983-11-09',
                'specialty' => 'Pediatrics',
                'degree' => 'MD, Pediatrics',
                'license_number' => 'DOC-PED-0003',
                'experience_years' => 14,
                'hospital' => 'MediConnect Children Hospital',
                'clinic_address' => '88 Nguyen Thi Thap, District 7',
                'city' => 'Ho Chi Minh',
                'bio' => 'Pediatric doctor supporting general child health, nutrition consultation, and regular follow-up care.',
                'consultation_fee' => 280000,
                'schedule_text' => 'Mon - Sat | 07:30 - 15:30',
                'is_featured' => false,
            ],
        ];

        foreach ($doctorAccounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'full_name' => $account['full_name'],
                    'phone' => $account['phone'],
                    'gender' => $account['gender'],
                    'province' => $account['province'],
                    'district' => $account['district'],
                    'ward' => $account['ward'],
                    'address_detail' => $account['address_detail'],
                    'dob' => $account['dob'],
                    'role' => 'doctor',
                    'doctor_verification_status' => 'approved',
                    'doctor_verified_at' => now(),
                    'doctor_rejection_reason' => null,
                    'password' => Hash::make('Doctor@123456'),
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty_id' => $specialtyMap[$account['specialty']]->id,
                    'name' => $account['full_name'],
                    'email' => $account['email'],
                    'phone' => $account['phone'],
                    'degree' => $account['degree'],
                    'license_number' => $account['license_number'],
                    'experience_years' => $account['experience_years'],
                    'hospital' => $account['hospital'],
                    'clinic_address' => $account['clinic_address'],
                    'city' => $account['city'],
                    'bio' => $account['bio'],
                    'consultation_fee' => $account['consultation_fee'],
                    'schedule_text' => $account['schedule_text'],
                    'status' => 'active',
                    'is_featured' => $account['is_featured'],
                    'approval_status' => 'approved',
                    'approval_note' => 'Default seeded doctor account approved automatically.',
                    'verification_status' => 'approved',
                    'submitted_at' => now(),
                    'approved_at' => now(),
                    'rejected_at' => null,
                ]
            );
        }

        $patientAccounts = [
            [
                'full_name' => 'Pham Thi An',
                'email' => 'customer1@gmail.com',
                'phone' => '0912000001',
                'gender' => 'female',
                'province' => 'Ho Chi Minh',
                'district' => 'District 10',
                'ward' => 'Ward 12',
                'address_detail' => 'Default Customer Account 1',
                'dob' => '1995-02-10',
            ],
            [
                'full_name' => 'Vo Thanh Nam',
                'email' => 'customer2@gmail.com',
                'phone' => '0912000002',
                'gender' => 'male',
                'province' => 'Ho Chi Minh',
                'district' => 'Binh Thanh',
                'ward' => 'Ward 25',
                'address_detail' => 'Default Customer Account 2',
                'dob' => '1992-08-18',
            ],
        ];

        foreach ($patientAccounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'full_name' => $account['full_name'],
                    'phone' => $account['phone'],
                    'gender' => $account['gender'],
                    'province' => $account['province'],
                    'district' => $account['district'],
                    'ward' => $account['ward'],
                    'address_detail' => $account['address_detail'],
                    'dob' => $account['dob'],
                    'role' => 'user',
                    'doctor_verification_status' => 'none',
                    'doctor_verified_at' => null,
                    'doctor_rejection_reason' => null,
                    'password' => Hash::make('User@123456'),
                ]
            );

            $patient = Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $account['full_name'],
                    'date_of_birth' => $account['dob'],
                    'gender' => $account['gender'],
                    'phone' => $account['phone'],
                    'email' => $account['email'],
                    'address' => implode(', ', array_filter([
                        $account['address_detail'],
                        $account['ward'],
                        $account['district'],
                        $account['province'],
                    ])),
                ]
            );

            if (! $patient->patient_code) {
                $patient->update([
                    'patient_code' => 'BN-' . str_pad((string) $patient->id, 6, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }
}
