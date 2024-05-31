<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientSubmitRequestTest extends TestCase
{
    // public function test_patient_create_request_page_can_be_rendered()
    // {
    //     $response = $this->get('/submit-requests/patient');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * create patient request with no data
     *
     * @return void
     */
    // public function test_create_patient_request_with_no_data(): void
    // {
    //     $response = $this->postJson('/patient-created', [
    //         'first_name' => '',
    //         'last_name' => '',
    //         'date_of_birth' => '',
    //         'email' => '',
    //         'phone_number' => '',
    //         'street' => '',
    //         'city' => '',
    //         'state' => '',
    //         'zipcode' => '',
    //         'docs' => '',
    //         'symptoms' => '',
    //         'room' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'first_name' => 'Please enter First Name',
    //             'last_name' => 'Please enter Last Name',
    //             'date_of_birth' => 'Please enter Date of Birth',
    //             'email' => 'Please enter Email',
    //             'phone_number' => 'Please enter Phone Number',
    //             'street' => 'Please enter a street',
    //             'city' => 'Please enter a city',
    //             'state' => 'Please enter a state',
    //             'zipcode' => 'Please enter 6 digits zipcode',
    //         ]);
    // }

    // create patient request with invalid data
    // public function test_create_patient_request_with_invalid_data(): void
    // {
    //     $response = $this->postJson('/patient-created', [
    //         'first_name' => 'as',
    //         'last_name' => '123423',
    //         'date_of_birth' => '12\12\2122',
    //         'email' => 'asd@123.12',
    //         'phone_number' => '12342423',
    //         'street' => '!@#$23',
    //         'city' => '!@#$@3',
    //         'state' => '!@$#@3',
    //         'zipcode' => '123',
    //         'docs' => '!@#4',
    //         'symptoms' => '1!@#$@#4',
    //         'room' => '123',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'first_name' => 'Please enter more than 3 Alphabets',
    //             'last_name' => 'Please enter only Alphabets in Last name',
    //             'date_of_birth' => 'The date of birth field must be a valid date.',
    //             'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
    //             'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
    //             'city' => 'Please enter alpbabets in city name.',
    //             'state' => 'Please enter alpbabets in state name.',
    //             'zipcode' => 'Please enter 6 digits zipcode',
    //             'docs' => 'The docs field must be a file.',
    //             'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
    //         ]);
    // }

    /**
     * create patient request with valid data and existing email
     *
     * @return void
     */
    // public function test_patient_create_request_with_valid_data_and_existing_email(): void
    // {
    //     $response = $this->postJson('/patient-created', [
    //         'first_name' => 'new',
    //         'last_name' => 'user',
    //         'request_type_id' => '1',
    //         'date_of_birth' => '2023-12-12',
    //         'email' => 'lejig@mailinator.com',
    //         'phone_number' => '1234567890',
    //         'street' => '21, new street',
    //         'city' => 'new city',
    //         'state' => 'state',
    //         'zipcode' => '123456',
    //         'docs' => '',
    //         'symptoms' => '',
    //         'room' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    // }

    /**
     * create patient request with valid data and new email
     *
     * @return void
     */
    // public function test_patient_create_request_with_valid_data_and_new_email(): void
    // {
    //     $response = $this->postJson('/patient-created', [
    //         'first_name' => 'new',
    //         'last_name' => 'user',
    //         'request_type_id' => '1',
    //         'date_of_birth' => '2023-12-12',
    //         'email' => fake()->unique()->email(),
    //         'phone_number' => '1234567890',
    //         'street' => '21, new street',
    //         'city' => 'new city',
    //         'state' => 'state',
    //         'zipcode' => '123456',
    //         'docs' => '',
    //         'symptoms' => '',
    //         'room' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    // }
}
