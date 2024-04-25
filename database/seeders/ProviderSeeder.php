<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\users;
use App\Models\Provider;
use App\Models\RoleMenu;
use App\Models\PhysicianRegion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstProviderUser = users::create([
            'id' => 2,
            'username' => 'FirstPhysician',
            'password' => Hash::make('physician1'),
            'email' => 'physician1@mail.com',
            'phone_number' => 1111111111,
        ]);

        Role::create([
            'id' => 2,
            'name' => 'physicianAccess',
            'account_type' => 2
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
        ]);

        $firstProvider = Provider::create([
            'id' => 1,
            'user_id' => 2,
            'first_name' => "First",
            'last_name' => "Physician",
            'email' => "physician1@mail.com",
            'mobile' => "1111111111",
            'alt_phone' => "1212121212",
            'medical_license' => 123456,
            'address1' => "Address Line 1",
            'address2' => "Address Line 2",
            'city' => "North",
            'zip' => 362777,
            'regions_id' => 1,
            'role_id' => 2
        ]);

        $secondProviderUser = users::create([
            'id' => 3,
            'username' => 'SecondPhysician',
            'password' => Hash::make('physician2'),
            'email' => 'physician2@mail.com',
            'phone_number' => 2222222222,
        ]);

        $secondProvider = Provider::create([
            'user_id' => 3,
            'first_name' => "Second",
            'last_name' => "Physician",
            'email' => "physician2@mail.com",
            'mobile' => "2222222222",
            'alt_phone' => "2121212121",
            'medical_license' => 234567,
            'address1' => "Address Line 3",
            'address2' => "Address Line 4",
            'city' => "South",
            'zip' => 362778,
            'regions_id' => 2,
            'role_id' => 2
        ]);

        $thirdProviderUser = users::create([
            'id' => 4,
            'username' => 'ThirdPhysician',
            'password' => Hash::make('physician3'),
            'email' => 'physician3@mail.com',
            'phone_number' => 3333333333,
        ]);

        $thirdProvider = Provider::create([
            'user_id' => 4,
            'first_name' => "Third",
            'last_name' => "Physician",
            'email' => "physician3@mail.com",
            'mobile' => "3333333333",
            'alt_phone' => "3131313131",
            'medical_license' => 345678,
            'address1' => "Address Line 5",
            'address2' => "Address Line 6",
            'city' => "Kodinar",
            'zip' => 362779,
            'regions_id' => 3,
            'role_id' => 2
        ]);

        PhysicianRegion::create([
            [
                'id' => 1,
                'provider_id' => 1,
                'region_id' => 1,
            ],
            [
                'id' => 2,
                'provider_id' => 1,
                'region_id' => 2,
            ],
            [
                'id' => 3,
                'provider_id' => 1,
                'region_id' => 3,
            ],
            [
                'id' => 4,
                'provider_id' => 2,
                'region_id' => 1,
            ],
            [
                'id' => 5,
                'provider_id' => 2,
                'region_id' => 3,
            ],
            [
                'id' => 6,
                'provider_id' => 2,
                'region_id' => 4,
            ],
            [
                'id' => 7,
                'provider_id' => 2,
                'region_id' => 5,
            ],
            [
                'id' => 8,
                'provider_id' => 3,
                'region_id' => 2,
            ],
            [
                'id' => 9,
                'provider_id' => 3,
                'region_id' => 4,
            ]
        ]);
    }
}
