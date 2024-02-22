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

        // 1. Unassigned
        // 2. Pending
        // 3. Cancelled
        // 4. Accepted
        // 5. MDEnRoute - service agreement sent by admin
        // 6. MDOnSite - Call type accepted by physician
        // 7. close - conclude -> to close state
        // 8. clear - admin clear case
        // 9. unpaid

        // 4-Reserving 
        // 7-FollowUp 
        // 9-Locked 
        // 10-Declined 
        // 11-Consult 
        // 13-CancelledByProvider 
        // 14-CCUploadedByClient 
        // 15-CCApprovedByAdmin
    }
}
