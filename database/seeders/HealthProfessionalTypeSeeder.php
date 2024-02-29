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
            ['id' => 1, 'profession_name' => 'Profession1'],
            ['id' => 2, 'profession_name' => 'Profession2'],
            ['id' => 3, 'profession_name' => 'Profession3'],
            ['id' => 4, 'profession_name' => 'Profession4'],
            ['id' => 5, 'profession_name' => 'Profession5'],
        ]);
    }
}
