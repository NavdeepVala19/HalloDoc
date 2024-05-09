<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\AdminRegion;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\UserRoles;
use App\Models\Users;
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
        $user = Users::create([
            'id' => 1,
            'username' => 'HalloDoc-Admin',
            'password' => Hash::make('admin123'),
            'email' => 'admin@mail.com',
            'phone_number' => 7778889999,
        ]);

        $role = Role::create([
            'id' => 1,
            'name' => 'AdminAccess',
            'account_type' => 1
        ]);

        RoleMenu::create([
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 2,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 5,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 6,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 8,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 16,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 20,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 21,
            ],
            [
                'id' => 1,
                'role_id' => 1,
                'menu_id' => 23,
            ]
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
            'region_id' => 2,
            'zip' => 332211,
            'alt_phone' => 1112223333,
            'role_id' => 1,
        ]);

        AdminRegion::create([
            [
                'id' => 1,
                'admin_id' => 1,
                'region_id' => 1
            ], [
                'id' => 2,
                'admin_id' => 1,
                'region_id' => 2
            ], [
                'id' => 3,
                'admin_id' => 1,
                'region_id' => 4
            ]
        ]);

        $admin->users()->save($user);
    }
}
