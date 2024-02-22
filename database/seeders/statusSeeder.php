<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

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

        Status::insert([
            ['id' => 1, 'status_type' => 'Unassigned'],
            ['id' => 2, 'status_type' => 'Pending'],
            ['id' => 3, 'status_type' => 'Cancelled'],
            ['id' => 4, 'status_type' => 'Accepted'],
            ['id' => 6, 'status_type' => 'MDEnRoute'], 
            ['id' => 7, 'status_type' => 'MDOnSite'],
            ['id' => 9, 'status_type' => 'close'],
            ['id' => 10, 'status_type' => 'clear'],
            ['id' => 11, 'status_type' => 'unpaid'],
            ['id' => 11, 'status_type' => 'conclude'],
            ['id' => 15, 'status_type' => 'CancelledByProvider'],
        ]);



        // 1. Unassigned
        // 2. Pending
        // 3. Cancelled
        // 4. Accepted
        // 5. MDEnRoute - service agreement sent by admin // 6. MDOnSite - Call type accepted by physician - both used in active stage
        // 7. close - conclude -> to close state
        // 8. clear - admin clear case
        // 9. unpaid

        // 11-Consult 
        // 13-CancelledByProvider 
        // 14-CCUploadedByClient 
        // 15-CCApprovedByAdmin

        // to close case -> cancelled and closed
    }
}
