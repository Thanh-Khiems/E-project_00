<?php

namespace Database\Seeders;

use App\Models\Medication;
use App\Models\MedicineType;
use Illuminate\Database\Seeder;

class MedicationCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $medicineTypes = [
            'Analgesic' => 'Medicines commonly used to relieve pain and reduce fever.',
            'Antibiotic' => 'Medicines used to treat bacterial infections.',
            'Antihistamine' => 'Medicines used to relieve allergy symptoms.',
        ];

        $typeMap = [];

        foreach ($medicineTypes as $name => $description) {
            $typeMap[$name] = MedicineType::updateOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        $medications = [
            [
                'name' => 'Paracetamol',
                'dosage' => '500 mg tablet',
                'medicine_type' => 'Analgesic',
                'category' => 'Pain reliever',
            ],
            [
                'name' => 'Amoxicillin',
                'dosage' => '500 mg capsule',
                'medicine_type' => 'Antibiotic',
                'category' => 'Bacterial infection treatment',
            ],
            [
                'name' => 'Cetirizine',
                'dosage' => '10 mg tablet',
                'medicine_type' => 'Antihistamine',
                'category' => 'Allergy relief',
            ],
        ];

        foreach ($medications as $item) {
            Medication::updateOrCreate(
                [
                    'name' => $item['name'],
                    'dosage' => $item['dosage'],
                ],
                [
                    'medicine_type_id' => $typeMap[$item['medicine_type']]->id,
                    'category' => $item['category'],
                ]
            );
        }
    }
}
