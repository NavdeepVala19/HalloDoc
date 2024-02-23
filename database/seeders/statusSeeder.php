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
        // DB::table('status')->insert([
        //     ['status_type' => 'New'],
        //     ['status_type' => 'Pending'],
        //     ['status_type' => 'Active'],
        //     ['status_type' => 'Conclude'],
        //     ['status_type' => 'Toclose'],
        //     ['status_type' => 'Unpaid']
        // ]);

        Status::insert([
            ['id' => 1, 'status_type' => 'Unassigned'], // New state for both provider and admin
            ['id' => 2, 'status_type' => 'Cancelled'], // to close state
            ['id' => 3, 'status_type' => 'Accepted'], // when accepted by provider, the case will be in pending state
            ['id' => 4, 'status_type' => 'MDEnRoute'], // agreement sent and accepted by patient - active state
            ['id' => 5, 'status_type' => 'MDOnSite'], // call type selected by physician(provider) - active state
            ['id' => 6, 'status_type' => 'conclude'],
            ['id' => 7, 'status_type' => 'closed'], // request send from conclude -> toclose state
            ['id' => 8, 'status_type' => 'clear'], // admin clear case
            ['id' => 9, 'status_type' => 'unpaid'],
            // ['id' => 2, 'status_type' => 'Pending'],
            // ['id' => 11, 'status_type' => 'CancelledByProvider'],
        ]);

        // to close case -> cancelled and closed
    }
}
