<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provider::create([
            // 'user_id' => ,
            'first_name' => "Navdeep",
            'last_name' => "Vala",
            'email' => "navdeepvala99@gmail.com",
            'mobile' => "9016499707",
            'medical_license' => 2,
            'IsAgreementDoc' => 1,
            'IsBackgroundDoc' => 1,
            'IsTrainingDoc' => 1,
            'IsNonDisclosureDoc' => 1,
            'address1' => "MH-25, North Colony, AmbujaNagar",
            'address2' => "Solaj",
            'city' => "Kodinar",
            'zip' => 362715,
        ]);
    }
}
