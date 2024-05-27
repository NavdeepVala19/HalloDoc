<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthProfessionalType;

class HealthProfessionalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        HealthProfessionalType::insert([
            ['id' => 1, 'profession_name' => 'Physicians'],
            ['id' => 2, 'profession_name' => 'Denstist'],
            ['id' => 3, 'profession_name' => 'Pharmacist'],
            ['id' => 4, 'profession_name' => 'Cardiologists'],
            ['id' => 5, 'profession_name' => 'Neurologists'],
            ['id' => 6, 'profession_name' => 'Dietitian'],
            ['id' => 7, 'profession_name' => 'Diagnostic Professionals'],
            ['id' => 8, 'profession_name' => 'Surgeon'],
        ]);
    }
}
