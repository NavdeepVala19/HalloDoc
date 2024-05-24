<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientSubmitRequestTest extends TestCase
{
    public function test_patient_create_request_page_can_be_rendered()
    {
        $response = $this->get('/submit-requests/patient');

        $response->assertStatus(Response::HTTP_OK);
    }
    /**
     * Details entered are empty or not in proper valid format
     *
     * @return void
     */
    public function test_patient_entered_data_is_not_valid(): void
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
            'request_type_id' => '1',
            'date_of_birth' => '2023-12-12',
            'email' => 'lejig@mailinator.com',
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
     * Details entered are correct and new request is created with an new email
     *
     * @return void
     */
    public function test_patient_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/patient-created', [
            'first_name' => 'new',
            'last_name' => 'user',
            'request_type_id' => '1',
            'date_of_birth' => '2023-12-12',
            'email' => 'newPatientMail@mail.com',
            'phone_number' => '1234567890',
            'street' => '21, new street',
            'city' => 'new city',
            'state' => 'state',
            'zipcode' => '123456',
            'docs' => '',
            'symptoms' => '',
            'room' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }
}
