<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            ['status_type' => 'Unassigned'],
            ['status_type' => 'Accepted'],
            ['status_type' => 'Cancelled'],
            ['status_type' => 'Reserving'],
            ['status_type' => 'MDEnRoute'],
            ['status_type' => 'MDOnSite'],
            ['status_type' => 'FollowUp'],
            ['status_type' => 'Closed'],
            ['status_type' => 'Locked'],
            ['status_type' => 'Declined'],
            ['status_type' => 'Consult'],
            ['status_type' => 'Clear'],
            ['status_type' => 'CancelledByProvider'],
            ['status_type' => 'CCUploadedByClient'],
            ['status_type' => 'CCApprovedByAdmin']
        ]);
    }
}
