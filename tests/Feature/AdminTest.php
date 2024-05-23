<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * Test successful assign case form with valid data
     * @return void
     */
    public function test_assign_case_with_valid_data()
    {
        $response = $this->postJson('/assign-case', [
            'assign_note' => 'Physician Notes',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful assign case form with invalid data
     * @return void
     */
    public function test_assign_case_with_invalid_data()
    {
        $response = $this->postJson('/assign-case', [
            'assign_note' => '$#%',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful assign case form with empty data
     * @return void
     */
    public function test_assign_case_with_empty_data()
    {
        $response = $this->postJson('/assign-case', [
            'assign_note' => ' ',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful view case form with valid data
     * @return void
     */
    public function test_view_case_with_valid_data()
    {
        $response = $this->postJson('/admin/view/case/edit', [
            'patient_notes' => 'Physician Notes',
            'first_name' => 'Denton',
            'last_name' => 'Wise',
            'dob' => '03-09-2022',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful view case form with invalid data
     * @return void
     */
    public function test_view_case_with_invalid_data()
    {
        $response = $this->postJson('/admin/view/case/edit', [
            'patient_notes' => 'Physician Notes',
            'first_name' => 'Denton',
            'last_name' => 'Wise',
            'dob' => '03-09-2022',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful view case form with empty data
     * @return void
     */
    public function test_view_case_with_empty_data()
    {
        $response = $this->postJson('/admin/view/case/edit', [
            'patient_notes' => '',
            'first_name' => '',
            'last_name' => '',
            'dob' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful block case form with valid data
     * @return void
     */
    public function test_block_case_with_valid_data()
    {
        $response = $this->postJson('/block-case', [
            'block_reason' => 'confirm block',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful block case form with valid data
     * @return void
     */
    public function test_block_case_with_invalid_data()
    {
        $response = $this->postJson('/block-case', [
            'block_reason' => '%$^%$#@',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful block case form with empty data
     * @return void
     */
    public function test_block_case_with_empty_data()
    {
        $response = $this->postJson('/block-case', [
            'block_reason' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful close case form with valid data
     * @return void
     */
    public function test_close_case_with_valid_data()
    {
        $response = $this->postJson('/close-case', [
            'phone_number' => '1478523690',
            'email' => 'fehaw@mailinator.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful close case form with invalid data
     * @return void
     */
    public function test_close_case_with_invalid_data()
    {
        $response = $this->postJson('/close-case', [
            'phone_number' => '1478323523690',
            'email' => 'fehaw@2432mailinator.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful close case form with empty data
     * @return void
     */
    public function test_close_case_with_empty_data()
    {
        $response = $this->postJson('/close-case', [
            'phone_number' => '',
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful add business form with valid data
     * @return void
     */
    public function test_add_business_with_valid_data()
    {
        $response = $this->postJson('/add-business', [
            'business_name' => 'Garrison',
            'fax_number' => '4610',
            'mobile' => '+1 776-977-4023',
            'email' => 'refe@mailinator.com',
            'business_contact' => '3315698752',
            'street' => 'Culpa ullam error qu',
            'city' => 'Et autem et aperiam ',
            'state' => 'Dolor architecto off',
            'zip' => '529430',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful add business form with invalid data
     * @return void
     */
    public function test_add_business_with_invalid_data()
    {
        $response = $this->postJson('/add-business', [
            'business_name' => 'Garrison 44',
            'fax_number' => '4610454455454',
            'mobile' => '+1 776-977-402345',
            'email' => 'refe@mailinato45r.com',
            'business_contact' => '3314545698752',
            'street' => 'Culp#$%#$%345a ullam error qu',
            'city' => 'Et aut#%$%#$em et aperiam ',
            'state' => 'Dolor #$%$%#545architecto off',
            'zip' => '529434540',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful add business form with empty data
     * @return void
     */
    public function test_add_business_with_empty_data()
    {
        $response = $this->postJson('/add-business', [
            'business_name' => '',
            'fax_number' => '',
            'mobile' => '',
            'email' => '',
            'business_contact' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
        ]);
        $response->assertStatus(Response::HTTP_FOUND);
    }



    /**
     * Test successful send mail form with valid data
     * @return void
     */
    public function test_send_mail_with_valid_data()
    {
        $response = $this->postJson('/send-mail', [
            'message' => 'Garrison',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful send mail form with invalid data
     * @return void
     */
    public function test_send_mail_with_invalid_data()
    {
        $response = $this->postJson('/send-mail', [
            'message' => '$#%$%#%$#^%^',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful send mail form with empty data
     * @return void
     */
    public function test_send_mail_with_empty_data()
    {
        $response = $this->postJson('/send-mail', [
            'message' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful cancel case form with valid data
     * @return void
     */
    public function test_cancel_case_with_valid_data()
    {
        $response = $this->postJson('/cancel-case-data', [
            'case_tag'=>'cost_issue',
            'reason' => 'cancel this case',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful cancel case form with invalid data
     * @return void
     */
    public function test_cancel_case_with_invalid_data()
    {
        $response = $this->postJson('/cancel-case-data', [
            'case_tag'=>'',
            'reason' => '$#^%^$#%$^&',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful cancel case form with empty data
     * @return void
     */
    public function test_cancel_case_with_empty_data()
    {
        $response = $this->postJson('/cancel-case-data', [
            'case_tag' => '',
            'reason' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    public function test_scheduled_shift_with_valid_data()
    {
        $response = $this->postJson('/create-shift', [
            'region' => 'somnath',
            'physician' => 'doctor_don',
            'shiftDate' => '20-05-2024',
            'shiftStartTime' => '10:00',
            'shiftEndTime' => '11:00',
            'checkbox' => '1',
            'repeatEnd' => '2',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful schedule shift with invalid data
     * @return void
     */
    public function test_scheduled_shift_with_invalid_data()
    {
        $response = $this->postJson('/create-shift', [
            'region' => 'somnath',
            'physician' => 'doctor_don',
            'shiftDate' => '1-05-2024',
            'shiftStartTime' => '10:00',
            'shiftEndTime' => '9:00',
            'checkbox' => '1',
            'repeatEnd' => '2',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    public function test_scheduled_shift_with_empty_data()
    {
        $response = $this->postJson('/create-shift', [
            'region' => '',
            'physician' => '',
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
            'checkbox' => '',
            'repeatEnd' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful create role with valid data
     * @return void
     */
    public function test_create_role_with_valid_data()
    {
        $response = $this->postJson('/create-shift', [
            'role_name' => '1',
            'menu_checkbox'=> '1'
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful create role with valid data
     * @return void
     */
    public function test_create_role_with_invalid_data()
    {
        $response = $this->postJson('/create-shift', [
            'role_name' => '3',
            'menu_checkbox'=> '40'
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful create role with valid data
     * @return void
     */
    public function test_create_role_with_empty_data()
    {
        $response = $this->postJson('/create-shift', [
            'role_name' => '',
            'menu_checkbox'=> ''
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

}
