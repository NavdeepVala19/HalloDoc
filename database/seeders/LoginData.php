<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoginData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'testing',
            'email' => 'johnss@gmail.com',
            // 'mobile' => '911234567891',
            'password' => Hash::make('john@123')
        ]);
    }
}
