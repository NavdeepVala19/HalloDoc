<?php

namespace Database\Seeders;

use App\Models\SMSLogs as ModelsSMSLogs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SMSLogs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsSMSLogs::create([
            'mobile_number'=>'1234567890',
            'created_date'=>'2024-03-07',
            'sent_Date'=>'2024-03-07',
            'sent_tries'=>'1',
        ]);   
        ModelsSMSLogs::create([
            'mobile_number'=>'9978071802',
            'created_date'=>'2024-03-08',
            'sent_Date'=>'2024-03-08',
            'sent_tries'=>'1',
        ]);   
        ModelsSMSLogs::create([
            'mobile_number'=>'9484999636',
            'created_date'=>'2024-03-14',
            'sent_Date'=>'2024-03-14',
            'sent_tries'=>'2',
        ]);   
    }
}
