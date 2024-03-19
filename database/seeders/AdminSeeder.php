<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\UserRoles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'HalloDoc-Admin',
            'password' => Hash::make('admin123'),
            'email' => 'admin@mail.com',
            'phone_number' => 7778889999,
        ]);

        $userRoles = UserRoles::create([
            'id' => 1,
            'role_id' => 1,
            'user_id' => 1
        ]);

        $admin = Admin::create([
            'id' => 1,
            'user_id' => 1,
            'status' => 2,
            'first_name' => 'HalloDoc',
            'last_name' => 'Admin',
            'email' => 'admin@mail.com',
            'mobile' => 7778889999,
            'address1' => 'Address Line 1',
            'address2' => 'Address Line 2',
            'city' => 'NewCity',
            // 'region_id' => ,
            'zip' => 332211,
            'alt_phone' => 1112223333,
            'role_id' => 1,
        ]);



        $admin->users()->save($user);
    }
}
