<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class ConceirgeSubmitRequestTest extends TestCase
{
    public function test_concierge_create_request_page_can_be_rendered()
    {
        $response = $this->get('/submit-requests/concierge');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * create concierge request with no data
     *
     * @return void
     */
    public function test_create_concierge_request_with_no_data(): void
    {
        $response = $this->postJson('/concierge-created', [
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'concierge_first_name' => '',
            'concierge_last_name' => '',
            'concierge_email' => '',
            'concierge_mobile' => '',
            'concierge_hotel_name' => '',
            'concierge_street' => '',
            'concierge_state' => '',
            'concierge_city' => '',
            'concierge_zip_code' => '',
            'symptoms' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'date_of_birth' => 'Please enter Date of Birth',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'concierge_first_name' => 'Please enter First Name',
            'concierge_last_name' => 'Please enter Last Name',
            'concierge_email' => 'Please enter Email',
            'concierge_mobile' => 'Please enter Phone Number',
            'concierge_hotel_name' => 'Please enter a hotel name',
            'concierge_street' => 'Please enter a street',
            'concierge_state' => 'Please enter a state',
            'concierge_city' => 'Please enter a city',
            'concierge_zip_code' => 'Please enter 6 digits zipcode',
        ]);
    }

    /**
     * create concierge request with invalid data
     *
     * @return void
     */
    public function test_create_concierge_request_with_invalid_data(): void
    {
        $response = $this->postJson(
            '/concierge-created',
            [
                'first_name' => 'as',
                'last_name' => '12342',
                'date_of_birth' => '1234',
                'email' => 'asdf@1234.123',
                'phone_number' => '1234242342',
                'concierge_first_name' => '1234',
                'concierge_last_name' => '!@#4',
                'concierge_email' => '!@#4',
                'concierge_mobile' => '12341234',
                'concierge_hotel_name' => '1234 &^(*',
                'concierge_street' => '1234 *&(^',
                'concierge_city' => '1234 !@#4',
                'concierge_state' => '@!#$',
                'concierge_zip_code' => '123',
                'symptoms' => '123 !@#4',
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'Please enter more than 3 Alphabets',
            'last_name' => 'Please enter only Alphabets in Last name',
            'date_of_birth' => 'The date of birth field must be a valid date.',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'concierge_first_name' => 'Please enter only Alphabets in First name',
            'concierge_last_name' => 'Please enter only Alphabets in Last name',
            'concierge_email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'concierge_hotel_name' => 'Please enter alphabets,number,dash,underscore,ampersand,fullstop,comma in hotel/property name.',
            'concierge_street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'concierge_city' => 'Please enter alpbabets in city name.',
            'concierge_state' => 'Please enter alpbabets in state name.',
            'concierge_zip_code' => 'Please enter 6 digits zipcode',
            'symptoms' => "Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.",
        ]);
    }

    /**
     * create concierge request with valid data and existing email
     *
     * @return void
     */
    public function test_concierge_create_request_with_valid_data_and_existing_email(): void
    {
        $response = $this->postJson('/concierge-created', [
            'first_name' => 'Glenn',
            'last_name' => 'McGrath',
            'date_of_birth' => '1954-04-17',
            'request_type_id' => '3',
            'email' => 'glenn@mail.com',
            'phone_number' => '5678901234',
            'concierge_first_name' => 'Ricky',
            'concierge_last_name' => 'Ponting',
            'concierge_email' => 'ricky@mail.com',
            'concierge_mobile' => '6789012345',
            'concierge_hotel_name' => 'kangaroos',
            'concierge_street' => 'streets',
            'concierge_state' => 'states',
            'concierge_city' => 'city',
            'concierge_zip_code' => '234567',
            'symptoms' => 'Symptoms test',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    }

    /**
     * create concierge request with valid data and new email
     *
     * @return void
     */
    public function test_concierge_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/concierge-created', [
            'first_name' => 'Glenn',
            'last_name' => 'McGrath',
            'request_type_id' => '3',
            'date_of_birth' => '1954-04-17',
            'email' => fake()->unique()->email(),
            'phone_number' => '5678901234',
            'concierge_first_name' => 'Ricky',
            'concierge_last_name' => 'Ponting',
            'concierge_email' => 'ricky@mail.com',
            'concierge_mobile' => '6789012345',
            'concierge_hotel_name' => 'kangaroos',
            'concierge_street' => 'streets',
            'concierge_state' => 'states',
            'concierge_city' => 'city',
            'concierge_zip_code' => '234567',
            'symptoms' => 'Symptoms test',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }
}
