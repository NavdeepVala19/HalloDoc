<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class BusinessSubmitRequestTest extends TestCase
{
    /**
     * Details entered are empty or not in proper valid format
     *
     * @return void
     */
    public function test_business_entered_data_is_not_valid(): void
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
    }

    /**
     * Details entered are correct and new request is created with an existing email
     *
     * @return void
     */
    public function test_business_create_request_with_valid_data_and_existing_email(): void
    {
        $response = $this->postJson('/business-created', [
            'first_name' => 'Steve',
            'last_name' => 'Smith',
            'date_of_birth' => '1994-03-12',
            'email' => 'smith@mail.com',
            'phone_number' => '2345678901',
            'street' => 'new street',
            'city' => 'city',
            'state' => 'states',
            'zipcode' => '123456',
            'business_first_name' => 'Johnson',
            'business_last_name' => 'ponting',
            'business_email' => 'johnson@mail.com',
            'business_mobile' => '3456789012',
            'business_property_name' => 'newMatch',
            'symptoms' => 'test sympotoms',
            'case_number' => '12345',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.re quest')->assertSessionHas('message', 'Request is Submitted');
    }

    /**
     * Details entered are correct and new request is created with an new email
     *
     * @return void
     */
    public function test_business_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/business-created', [
            'first_name' => 'Steve',
            'last_name' => 'Smith',
            'date_of_birth' => '1994-03-12',
            'email' => 'smith123@mail.com',
            'phone_number' => '2345678901',
            'street' => 'new street',
            'city' => 'city',
            'state' => 'states',
            'zipcode' => '123456',
            'business_first_name' => 'Johnson',
            'business_last_name' => 'ponting',
            'business_email' => 'johnson@mail.com',
            'business_mobile' => '3456789012',
            'business_property_name' => 'newMatch',
            'symptoms' => 'test sympotoms',
            'case_number' => '12345',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }
}
