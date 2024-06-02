<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class FamilySubmitRequestTest extends TestCase
{
    public function test_family_create_request_page_can_be_rendered()
    {
        $response = $this->get('/submit-requests/family');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * create family request with no data
     *
     * @return void
     */
    public function test_create_family_request_with_no_data(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => '',
            'family_last_name' => '',
            'family_phone_number' => '',
            'family_email' => '',
            'family_relation' => '',
            'symptoms' => '',
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'family_first_name' => 'Please enter First Name',
            'family_last_name' => 'Please enter Last Name',
            'family_phone_number' => 'Please enter Phone Number',
            'family_email' => 'Please enter Email',
            'family_relation' => 'Please enter a relation with patient',
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
     * create family request with invalid data
     *
     * @return void
     */
    public function test_create_family_request_with_invalid_data(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => '12',
            'family_last_name' => '!@#$@#4',
            'family_phone_number' => '1234',
            'family_email' => 'asdf@123.234',
            'family_relation' => 'as!@#$',
            'symptoms' => '!@#$!@3',
            'first_name' => '!@#$@',
            'last_name' => 'AS',
            'date_of_birth' => '12321',
            'email' => 'asdf@312.as',
            'phone_number' => '12342',
            'street' => '!#$%@',
            'city' => '$%^',
            'state' => '*^%',
            'zipcode' => '213',
            'room' => '34215',
            'docs' => '&',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'family_first_name' => 'Please enter more than 3 Alphabets',
            'family_last_name' => 'Please enter only Alphabets in Last name',
            'family_email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'family_relation' => 'Please enter valid relation (Format : alphabets-alphabets).',
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter more than 3 Alphabets',
            'date_of_birth' => 'The date of birth field must be a date after Jan 01 1900.',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'phone_number' => 'The phone number field must have at least 10 digits.',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'Please enter 6 digits zipcode',
            'docs' => 'The docs field must be a file.',
            'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ]);
    }

    /**
     * create family request with valid data and existing email
     *
     * @return void
     */
    public function test_family_create_request_with_valid_data_and_existing_email(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => 'Olivia',
            'family_last_name' => 'Oliver',
            'request_type_id' => '2',
            'family_phone_number' => '1234567890',
            'family_email' => 'asdf@new.com',
            'family_relation' => 'cousin',
            'symptoms' => 'skin problem',
            'first_name' => 'otto',
            'last_name' => 'garrate',
            'date_of_birth' => '2004-12-12',
            'email' => 'otto@new.com',
            'phone_number' => '1234567880',
            'street' => 'Street 1',
            'city' => 'new city',
            'state' => 'states',
            'zipcode' => '123456',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    }

    /**
     * create family request with valid data and new email
     *
     * @return void
     */
    public function test_family_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => 'Olivia',
            'family_last_name' => 'Oliver',
            'request_type_id' => '2',
            'family_phone_number' => '1234567890',
            'family_email' => 'asdf@new.com',
            'family_relation' => 'cousin',
            'symptoms' => 'skin problem',
            'first_name' => 'otto',
            'last_name' => 'garrate',
            'date_of_birth' => '2004-12-12',
            'email' => fake()->unique()->email(),
            'phone_number' => '1234567880',
            'street' => 'Street 1',
            'city' => 'new city',
            'state' => 'states',
            'zipcode' => '123456',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }
}
