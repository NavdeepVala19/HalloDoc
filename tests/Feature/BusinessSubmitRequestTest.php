<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class BusinessSubmitRequestTest extends TestCase
{
    // business create request page can be rendered
    public function test_business_create_request_page_can_be_rendered()
    {
        $response = $this->get('/submit-requests/business');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * create business request with no data
     *
     * @return void
     */
    // public function test_create_business_request_with_no_data(): void
    // {
    //     $response = $this->postJson('/business-created', [
    //         'first_name' => '',
    //         'last_name' => '',
    //         'date_of_birth' => '',
    //         'email' => '',
    //         'phone_number' => '',
    //         'street' => '',
    //         'city' => '',
    //         'state' => '',
    //         'zipcode' => '',
    //         'business_first_name' => '',
    //         'business_last_name' => '',
    //         'business_email' => '',
    //         'business_mobile' => '',
    //         'business_property_name' => '',
    //         'symptoms' => '',
    //         'case_number' => '',
    //         'room' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'Please enter First Name',
    //         'last_name' => 'Please enter Last Name',
    //         'date_of_birth' => 'Please enter Date of Birth',
    //         'email' => 'Please enter Email',
    //         'phone_number' => 'Please enter Phone Number',
    //         'street' => 'Please enter a street',
    //         'city' => 'Please enter a city',
    //         'state' => 'Please enter a state',
    //         'zipcode' => 'Please enter 6 digits zipcode',
    //         'business_first_name' => 'Please enter First Name',
    //         'business_last_name' => 'Please enter Last Name',
    //         'business_email' => 'Please enter Email',
    //         'business_mobile' => 'Please enter Phone Number',
    //         'business_property_name' => 'Please enter a business/property name',
    //     ]);
    // }

    /**
     * create business request with invalid data
     *
     * @return void
     */
    // public function test_create_business_request_with_invalid_data(): void
    // {
    //     $response = $this->postJson(
    //         '/business-created',
    //         [
    //             'first_name' => 'as',
    //             'last_name' => '12342',
    //             'date_of_birth' => '1234',
    //             'email' => 'asdf@1234.123',
    //             'phone_number' => '1234242342',
    //             'street' => '1234 *&(^',
    //             'city' => '1234 !@#4',
    //             'state' => '@!#$',
    //             'zipcode' => '123',
    //             'business_first_name' => '1234',
    //             'business_last_name' => '!@#4',
    //             'business_email' => '!@#4',
    //             'business_mobile' => '12341234',
    //             'business_property_name' => '1234 &^(*',
    //             'symptoms' => '123 !@#4',
    //             'case_number' => '!@#',
    //             'room' => '12343',
    //         ]
    //     );

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'Please enter more than 3 Alphabets',
    //         'last_name' => 'Please enter only Alphabets in Last name',
    //         'date_of_birth' => 'The date of birth field must be a valid date.',
    //         'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
    //         'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
    //         'city' => 'Please enter alpbabets in city name.',
    //         'state' => 'Please enter alpbabets in state name.',
    //         'zipcode' => 'Please enter 6 digits zipcode',
    //         'business_first_name' => 'Please enter only Alphabets in First name',
    //         'business_last_name' => 'Please enter only Alphabets in Last name',
    //         'business_email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
    //         'business_property_name' => 'Please enter a only alphabets,numbers,dash,underscore,fullstop,ampersand in business/property name.',
    //         'symptoms' => "Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.",
    //     ]);
    // }

    /**
     * create business request with valid data and existing email
     *
     * @return void
     */
    // public function test_create_business_request_with_valid_data_and_existing_email(): void
    // {
    //     $response = $this->postJson('/business-created', [
    //         'first_name' => 'Steve',
    //         'last_name' => 'Smith',
    //         'request_type_id' => '4',
    //         'date_of_birth' => '1994-03-12',
    //         'email' => 'smith@mail.com',
    //         'phone_number' => '2345678901',
    //         'street' => 'new street',
    //         'city' => 'city',
    //         'state' => 'states',
    //         'zipcode' => '123456',
    //         'business_first_name' => 'Johnson',
    //         'business_last_name' => 'ponting',
    //         'business_email' => 'johnson@mail.com',
    //         'business_mobile' => '3456789012',
    //         'business_property_name' => 'newMatch',
    //         'symptoms' => 'test sympotoms',
    //         'case_number' => '12345',
    //         'room' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    // }

    /**
     * create business request with valid data and new email
     *
     * @return void
     */
    // public function test_create_business_request_with_valid_data_and_new_email(): void
    // {
    //     $response = $this->postJson('/business-created', [
    //         'first_name' => 'Steve',
    //         'last_name' => 'Smith',
    //         'request_type_id' => '4',
    //         'date_of_birth' => '1994-03-12',
    //         'email' => fake()->unique()->email(),
    //         'phone_number' => '2345678901',
    //         'street' => 'new street',
    //         'city' => 'city',
    //         'state' => 'states',
    //         'zipcode' => '123456',
    //         'business_first_name' => 'Johnson',
    //         'business_last_name' => 'ponting',
    //         'business_email' => 'johnson@mail.com',
    //         'business_mobile' => '3456789012',
    //         'business_property_name' => 'newMatch',
    //         'symptoms' => 'test sympotoms',
    //         'case_number' => '12345',
    //         'room' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    // }
}
