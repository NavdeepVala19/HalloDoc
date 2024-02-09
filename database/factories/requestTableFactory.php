<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\requestTable;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\requestTable>
 */
class requestTableFactory extends Factory
{
    protected $model = requestTable::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_type_id' => $this->faker->numberBetween(1, 4),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'status' => $this->faker->numberBetween(1, 6),
            'is_urgent_email_sent' => 0,
            'is_mobile' => 0,
            'case_tag_physician' => 0,
            'patient_account_id' => 0,
            'created_user_id' => 0,
        ];
    }
}
