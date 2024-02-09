<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class requestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('request_type')->insert([
            ['name' => 'Patient'],
            ['name' => 'Family'],
            ['name' => 'Concierge'],
            ['name' => 'Buisness']
        ]);
    }
}
