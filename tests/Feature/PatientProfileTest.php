<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Symfony\Component\HttpFoundation\Response;

class PatientProfileTest extends TestCase
{
    /**
     * Details entered are correct and patient profile is updated
     *
     * @return void
     */
    public function test_update_patient_profile_with_valid_data(): void
    {
        $userId = UserRoles::where('role_id', 3)->value('user_id');
        $patient = User::where('id', $userId)->first();

        $response = $this->actingAs($patient)->postJson('/patient/profile-updated', [
            'first_name' => 'otto',
            'last_name' => 'garrate',
            'date_of_birth' => '2004-12-12',
            'email' => fake()->unique()->email(),
            'phone_number' => '1234567880',
            'street' => 'billionaires row',
            'city' => 'manhattan',
            'state' => 'new york',
            'zipcode' => '345678',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.dashboard')->assertSessionHas('message', 'profile is updated successfully');
    }
    
    /**
     * Details entered are empty
     *
     * @return void
     */
    public function test_update_patient_profile_with_empty_data(): void
    {
        $userId = UserRoles::where('role_id', 3)->value('user_id');
        $patient = User::where('id', $userId)->first();

        $response = $this->actingAs($patient)->postJson('/patient/profile-updated', [
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'street' => ' ',
            'city' => '',
            'state' => '',
            'zipcode' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'date_of_birth' => 'Please enter Date of Birth',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
            'zipcode' => 'Please enter 6 digits zipcode',
        ]);
    }


    /**
     * Details entered are invalid
     *
     * @return void
     */
    public function test_update_patient_profile_with_invalid_data(): void
    {
        $userId = UserRoles::where('role_id', 3)->value('user_id');
        $patient = User::where('id', $userId)->first();

        $response = $this->actingAs($patient)->postJson('/patient/profile-updated', [
            'first_name' => 'shivesh565',
            'last_name' => 'surani5465',
            'date_of_birth' => '',
            'email' => 'shivesh@m565ail.com',
            'phone_number' => '099780 71802',
            'street' => 'shivanjali heights-2, abrama road,mota varachha%^%',
            'city' => 'surat56565',
            'state' => 'gujarat545',
            'zipcode' => '39410155',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'date_of_birth' => 'Please enter Date of Birth',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'Please enter 6 digits zipcode',
        ]);
    }
}
