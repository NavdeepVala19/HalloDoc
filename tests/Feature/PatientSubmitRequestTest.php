<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientSubmitRequestTest extends TestCase
{

    /**
     * Details entered are correct and new request is created with an new email
     *
     * @return void
     */
    public function test_patient_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/patient-created', [
            'first_name' => 'new',
            'last_name' => 'user',
            'date_of_birth' => '2010-10-10',
            'email' => fake()->unique()->email(),
            'phone_number' => '1234567890',
            'street' => '21, new street',
            'city' => 'new city',
            'state' => 'state',
            'zipcode' => '123456',
            'docs' => '',
            'symptoms' => '',
            'room' => '23',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }

    /**
     * Details entered are empty or not in proper valid format
     *
     * @return void
     */
    public function test_patient_entered_data_is_empty(): void
    {
        $response = $this->postJson('/patient-created', [
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'docs' => '',
            'symptoms' => '',
            'room' => '',
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
     * Details entered are correct and new request is created with an existing email
     *
     * @return void
     */
    public function test_patient_create_request_with_valid_data_and_existing_email(): void
    {
        $response = $this->postJson('/patient-created', [
            'first_name' => 'new',
            'last_name' => 'user',
            'date_of_birth' => '2023-12-12',
            'email' => 'shivesh@mail.com',
            'phone_number' => '1234567890',
            'street' => '21, new street',
            'city' => 'new city',
            'state' => 'state',
            'zipcode' => '123456',
            'docs' => '',
            'symptoms' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    }


    /**
     * Details entered are incorrect
     *
     * @return void
     */
    public function test_patient_create_request_with_invalid_data(): void
    {
        $response = $this->postJson('/patient-created', [
            'first_name' => 'new5',
            'last_name' => 'use56r',
            'date_of_birth' => '2030-12-12',
            'email' => 'lejig@m454rfhtgrailinator.com',
            'phone_number' => '12345trtrg67890',
            'street' => '21, new street$%4',
            'city' => 'new city5465%$%',
            'state' => 'state$%^%^',
            'zipcode' => '1234516',
            'docs' => '',
            'symptoms' => '%$^%^%6',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'date_of_birth' => 'Please enter Date of Birth Before Today',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'Please enter 6 digits zipcode',
            'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ]);
    }
}
