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
     * Details entered are empty or not in proper valid format
     *
     * @return void
     */
    public function test_concierge_entered_data_is_not_valid(): void
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
            'request_type_id' => '3',
            'date_of_birth' => '1954-04-17',
            'email' => 'glenn123@mail.com',
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
