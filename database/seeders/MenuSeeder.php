<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            ['id' => 1, 'name' => 'Regions', 'account_type' => 'admin'],
            ['id' => 2, 'name' => 'Scheduling', 'account_type' => 'admin'],
            ['id' => 3, 'name' => 'History', 'account_type' => 'admin'],
            ['id' => 4, 'name' => 'Accounts', 'account_type' => 'admin'],
            ['id' => 5, 'name' => 'MyProfile', 'account_type' => 'admin'],
            ['id' => 6, 'name' => 'Dashboard', 'account_type' => 'admin'],
            ['id' => 7, 'name' => 'Role', 'account_type' => 'admin'],
            ['id' => 8, 'name' => 'Provider', 'account_type' => 'admin'],
            ['id' => 9, 'name' => 'RequestData', 'account_type' => 'admin'],
            ['id' => 10, 'name' => 'VendorsInfo', 'account_type' => 'admin'],
            ['id' => 11, 'name' => 'Profession', 'account_type' => 'admin'],
            ['id' => 12, 'name' => 'SendOrder', 'account_type' => 'admin'],
            ['id' => 13, 'name' => 'HaloAdministrators', 'account_type' => 'admin'],
            ['id' => 14, 'name' => 'HaloUsers', 'account_type' => 'admin'],
            ['id' => 15, 'name' => 'CancelledHistory', 'account_type' => 'admin'],
            ['id' => 16, 'name' => 'ProviderLocation', 'account_type' => 'admin'],
            ['id' => 17, 'name' => 'HaloEmployee', 'account_type' => 'admin'],
            ['id' => 18, 'name' => 'HaloWorkPlace', 'account_type' => 'admin'],
            ['id' => 19, 'name' => 'Chat', 'account_type' => 'admin'],
            ['id' => 20, 'name' => 'PatientRecords', 'account_type' => 'admin'],
            ['id' => 21, 'name' => 'BlockedHistory', 'account_type' => 'admin'],
            ['id' => 22, 'name' => 'Invoicing', 'account_type' => 'admin'],
            ['id' => 23, 'name' => 'SMSLogs', 'account_type' => 'admin'],
            ['id' => 24, 'name' => 'Dashboard', 'account_type' => 'Physician'],
            ['id' => 25, 'name' => 'MySchedule', 'account_type' => 'Physician'],
            ['id' => 26, 'name' => 'MyProfile', 'account_type' => 'Physician'],
            ['id' => 27, 'name' => 'SendOrder', 'account_type' => 'Physician'],
            ['id' => 28, 'name' => 'Chat', 'account_type' => 'Physician'],
            ['id' => 29, 'name' => 'Invoicing', 'account_type' => 'Physician'],
        ]);
    }
}
