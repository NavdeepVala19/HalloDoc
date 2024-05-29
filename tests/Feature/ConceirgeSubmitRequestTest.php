<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;


class ConceirgeSubmitRequestTest extends TestCase
{
    /**
     * Details entered are empty
     *
     * @return void
     */
    public function test_concierge_entered_data_is_empty(): void
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'date_of_birth' => 'Please enter Date of Birth',
            'concierge_street' => 'Please enter a street',
            'concierge_city' => 'Please enter a city',
            'concierge_state' => 'Please enter a state',
            'concierge_zip_code' => 'Please enter 6 digits zipcode',
            'concierge_first_name' => 'Please enter First Name',
            'concierge_last_name' => 'Please enter Last Name',
            'concierge_email' => 'Please enter Email',
            'concierge_mobile' => 'Please enter Phone Number',
            'concierge_hotel_name' => 'Please enter a hotel name',
        ]);
    }

    /**
     * Details entered are invalid 
     *
     * @return void
     */
    public function test_concierge_entered_data_is_invalid(): void
    {
        $response = $this->postJson('/concierge-created', [
            'first_name' => 'Katell325',
            'last_name' => 'Marshall4543',
            'date_of_birth' => '',
            'email' => 'jumarol@mailina3454tor.com',
            'phone_number' => '98444444444444444444',
            'concierge_first_name' => 'Brennan43534',
            'concierge_last_name' => 'Sharpe534',
            'concierge_email' => 'sanecyjyby@43543mailinator.com',
            'concierge_mobile' => '',
            'concierge_hotel_name' => 'Otto Cervantes3$#%$',
            'concierge_street' => 'Enim sunt voluptatem#$%$',
            'concierge_state' => 'Non accusamus iusto #$%#',
            'concierge_city' => 'Nisi cillum rerum qu$#%4',
            'concierge_zip_code' => '516634354',
            'symptoms' => 'Quaerat nemo enim vo$#%#4',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'phone_number' => 'The phone number field must not have more than 10 digits.',
            'date_of_birth' => 'Please enter Date of Birth',
            'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
            'concierge_street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'concierge_city' => 'Please enter alpbabets in city name.',
            'concierge_state' => 'Please enter alpbabets in state name.',
            'concierge_zip_code' => 'Please enter 6 digits zipcode',
            'concierge_first_name' => 'Please enter only Alphabets in First name',
            'concierge_last_name' => 'Please enter only Alphabets in Last name',
            'concierge_email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'concierge_mobile' => 'Please enter Phone Number',
            'concierge_hotel_name' => 'Please enter alphabets,number,dash,underscore,ampersand,fullstop,comma in hotel/property name.',
        ]);
    }

    /**
     * Details entered are correct and new request is created with an existing email
     *
     * @return void
     */
    public function test_concierge_create_request_with_valid_data_and_existing_email(): void
    {
        $response = $this->postJson('/concierge-created', [
            'first_name' => 'Glenn',
            'last_name' => 'McGrath',
            'date_of_birth' => '1954-04-17',
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
            'symptoms' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    }

    /**
     * Details entered are correct and new request is created with an new email
     *
     * @return void
     */
    public function test_concierge_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/concierge-created', [
            'first_name' => 'Glenn',
            'last_name' => 'McGrath',
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
            'symptoms' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }
}
