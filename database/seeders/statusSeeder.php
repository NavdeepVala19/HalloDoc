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
            ['id' => 10, 'status_type' => 'block'],
            ['id' => 11, 'status_type' => 'CancelledByPatient']
        ]);

        // to close case -> cancelled and closed
    }
}


// Request Status		 Dashboard Status
// 1. Unassigned			New
// -------
// 2. Accepted			    Pending
// -------
// 4. MDEnRoute			    Active
// 5. MDONSite			    Active
// -------
// 6. Conclude			    Conclude
// -------
// 3. Cancelled			    To-close
// 7. CancelledByPatient	To-close
// 8. Closed				To-close
// -------
// 9. Unpaid				Unpaid
// -------
// 10. Clear				Will not show in dashboard
