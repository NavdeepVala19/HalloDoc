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
     * Details entered are empty or not in proper valid format
     *
     * @return void
     */
    public function test_family_entered_data_is_not_valid(): void
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Details entered are correct and new request is created with an existing email
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
     * Details entered are correct and new request is created with an new email
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
            // 'email' => 'otto1234@new.com',
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
