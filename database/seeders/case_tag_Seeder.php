<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class case_tag_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('case_tag')->insert([
            ['case_name' => 'New'],
            ['case_name' => 'Pending'],
            ['case_name' => 'Active'],
            ['case_name' => 'Conclude'],
            ['case_name' => 'Toclose'],
            ['case_name' => 'Unpaid']
        ]);
    }
}
