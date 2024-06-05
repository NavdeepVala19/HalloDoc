<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class BusinessSubmitRequestTest extends TestCase
{
    /**
     * Details entered are empty
     *
     * @return void
     */
    public function test_business_entered_data_is_empty(): void
    {
        $response = $this->postJson('/business-created', [
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'business_first_name' => '',
            'business_last_name' => '',
            'business_email' => '',
            'business_mobile' => '',
            'business_property_name' => '',
            'symptoms' => '',
            'case_number' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'date_of_birth' => 'Please enter Date of Birth',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
            'zipcode' => 'Please enter 6 digits zipcode',
            'business_first_name' => 'Please enter First Name',
            'business_last_name' => 'Please enter Last Name',
            'business_email' => 'Please enter Email',
            'business_mobile' => 'Please enter Phone Number',
            'business_property_name' => 'Please enter a business/property name',
        ]);
    }


    /**
     * Details entered are invalid
     *
     * @return void
     */
    public function test_business_entered_data_is_invalid(): void
    {
        $response = $this->postJson('/business-created', [
            'first_name' => 'Delilah56465',
            'last_name' => 'Chang5465',
            'date_of_birth' => '',
            'email' => 'huwe@maili54534nator.com',
            'phone_number' => '638',
            'street' => 'Voluptatem Nam beat^%^$%^%^^',
            'city' => 'Odio quia sapiente e56',
            'state' => 'Aut quas aut o56dio do',
            'zipcode' => '96402544',
            'business_first_name' => 'Taylor232',
            'business_last_name' => 'Mclaughlin3243',
            'business_email' => 'xolagorij@mail3243inator.com',
            'business_mobile' => '(457) 581-7784',
            'business_property_name' => 'James Cross$#@%#$%$@^%$&*#^*',
            'symptoms' => 'Ad necessitatibus es^%$^%$#4',
            'case_number' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'phone_number' => 'The phone number field must have at least 10 digits.',
            'date_of_birth' => 'Please enter Date of Birth',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'Please enter 6 digits zipcode',
            'business_first_name' => 'Please enter only Alphabets in First name',
            'business_last_name' => 'Please enter only Alphabets in Last name',
            'business_email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'business_property_name' => 'Please enter a only alphabets,numbers,dash,underscore,fullstop,ampersand in business/property name.',
            'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ]);
    }

    /**
     * Details entered are correct and new request is created with an existing email
     *
     * @return void
     */
    // public function test_business_create_request_with_valid_data_and_existing_email(): void
    // {
    //     $response = $this->postJson('/business-created', [
    //         'first_name' => 'Steve',
    //         'last_name' => 'Smith',
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
     * Details entered are correct and new request is created with an new email
     *
     * @return void
     */
    // public function test_business_create_request_with_valid_data_and_new_email(): void
    // {
    //     $response = $this->postJson('/business-created', [
    //         'first_name' => 'Steve',
    //         'last_name' => 'Smith',
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
