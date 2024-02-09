<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            ['status_type' => 'New'],
            ['status_type' => 'Pending'],
            ['status_type' => 'Active'],
            ['status_type' => 'Conclude'],
            ['status_type' => 'Toclose'],
            ['status_type' => 'Unpaid']
            ]);
    }
}
