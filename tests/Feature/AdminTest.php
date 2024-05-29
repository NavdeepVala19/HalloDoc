<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRoles;
use App\Models\RequestTable;
use Symfony\Component\HttpFoundation\Response;

class AdminTest extends TestCase
{
    private function adminLoggedIn()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        return $admin;
    }
    /**
     * Test successful assign case form with valid data
     * @return void
     */
    public function test_assign_case_with_valid_data()
    {
        $admin = $this->adminLoggedIn();
        $requestId = RequestTable::where('status', 1)->value('id');
        $response = $this->actingAs($admin)->postJson('/assign-case', [
            'region' => '1',
            'physician' => '1',
            'assign_note' => 'Physician Notes',
            'requestId' => $requestId,
        ]);
  
        $physician = Provider::where('id', 1)->first();
        $physicianName = $physician->first_name . ' ' . $physician->last_name;
        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', "Case Assigned Successfully to physician - {$physicianName}");
    }

    /**
     * Test successful assign case form with invalid data
     * @return void
     */
    public function test_assign_case_with_invalid_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/assign-case', [
            'region' => '2',
            'physician' => '1',
            'assign_note' => '$#%',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'assign_note' => 'The assign note field format is invalid.',
        ]);
    }

    /**
     * Test successful assign case form with empty data
     * @return void
     */
    public function test_assign_case_with_empty_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/assign-case', [
            'region' => '',
            'physician' => '',
            'assign_note' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'region' => 'The region field is required.',
            'physician' => 'The physician field is required.',
            'assign_note' => 'The assign note field is required.',
        ]);
    }


    /**
     * Test successful view case form with valid data
     * @return void
     */
    public function test_view_case_with_valid_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin/view/case/edit', [
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
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin/view/case/edit', [
            'patient_notes' => 'Physician N%#$^%^%otes',
            'first_name' => 'Denton43566',
            'last_name' => 'Wise5465',
            'dob' => '03-09-2035',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'dob' => 'The dob field must be a date before tomorrow.',
            'patient_notes' => 'The patient notes field format is invalid.',
        ]);
    }

    /**
     * Test successful view case form with empty data
     * @return void
     */
    public function test_view_case_with_empty_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin/view/case/edit', [
            'patient_notes' => '',
            'first_name' => '',
            'last_name' => '',
            'dob' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'dob' => 'The dob field is required.',
        ]);
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
            'case_tag'=>'1',
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
            'region' => '1',
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
            'region' => '1',
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


    /**
     * admin submit request with valid data
     * @return void
     */
    public function test_admin_submit_request_with_valid_data(): void
    {
        $response = $this->postJson('/admin-submitted-requests', [
            'first_name' => 'otto',
            'last_name' => 'garrate',
            'date_of_birth' => '2004-12-12',
            'email' => fake()->unique()->email(),
            'phone_number' => '1234567880',
            'street' => 'billionaires row',
            'city' => 'manhattan',
            'state' => 'new york',
            'zip' => '345678',
            'room' => '1',
            'adminNote' => 'efgijhfnj',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * admin submit request with invalid data
     * @return void
     */
    public function test_admin_submit_request_with_invalid_data(): void
    {
        $response = $this->postJson('/admin-submitted-requests', [
            'first_name' => 'ot454to',
            'last_name' => 'garrate43534',
            'date_of_birth' => '2030-12-12',
            'email' => 'otto@657new.com',
            'phone_number' => '123455hgf67880',
            'street' => 'billionaires#%$ row',
            'city' => 'manha434ttan',
            'state' => 'new york34',
            'zip' => '3456784',
            'room' => '1cd',
            'adminNote' => 'efgijhf43534nj',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * admin submit request with empty data
     * @return void
     */
    public function test_admin_submit_request_with_empty_data(): void
    {
        $response = $this->postJson('/admin-submitted-requests', [
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'room' => '',
            'adminNote' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

}
