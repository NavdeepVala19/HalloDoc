<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CaseTag;

class caseTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CaseTag::insert([
            ['id' => 1, 'case_name' => 'Cost Issue'],
            ['id' => 2, 'case_name' => 'Inappropriate for service'],
            ['id' => 3, 'case_name' => 'Provider not available'],
            ['id' => 4, 'case_name' => 'Location problem'],
        ]);
    }
}
