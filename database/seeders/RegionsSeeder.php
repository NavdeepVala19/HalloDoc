<?php

namespace Database\Seeders;

use App\Models\Regions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Regions::insert(
            [

                ['id' => 1, 'region_name' => 'Somnath'],
                ['id' => 2, 'region_name' => 'Dwarka'],
                ['id' => 3, 'region_name' => 'Rajkot'],
                ['id' => 4, 'region_name' => 'Bhavnagar'],
                ['id' => 5, 'region_name' => 'Ahmedabad']
            ]
        );
    }
}
