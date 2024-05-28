<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\RequestTable;
use Symfony\Component\HttpFoundation\Response;

class AdminTest extends TestCase
{
    /**
     * Test successful assign case form with valid data
     * @return void
     */
    public function test_assign_case_with_valid_data()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        $admin = User::where('id', $adminId)->first();

        $requestId =  RequestTable::where('status', 1)->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/assign-case', [
                'requestId' => $requestId,
                'region' => 1,
                'physician' => 1,
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
            'assign_note' => '$#%$^',
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
            'assign_note' => '',
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
            'first_name' => 'ne',
            'last_name' => 'Wise1231',
            'dob' => '03-09-20221',
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
     * View Notes -> add note with no text entered
     * @return void
     */
    public function test_view_note_add_note_with_empty_data()
    {
        $response = $this->postJson('/admin/view/notes/store', [
            'admin_note' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * View Notes -> add note with invalid data format
     * @return void
     */
    public function test_view_note_add_note_with_invalid_data()
    {
        $response = $this->postJson('/admin/view/notes/store', [
            'admin_note' => '+_)*&(',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * View Notes -> add note with valid data format
     * @return void
     */
    public function test_view_note_add_note_with_valid_data()
    {
        $response = $this->postJson('/admin/view/notes/store', [
            'admin_note' => 'New note added',
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
            'case_tag' => 'cost_issue',
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
            'case_tag' => '',
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
            'menu_checkbox' => '1'
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
            'menu_checkbox' => '40'
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
            'menu_checkbox' => ''
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin submit request with empty data
     * @return void
     */
    public function test_admin_create_request_with_empty_data()
    {
        $response = $this->postJson('/admin-submitted-requests', [
            'first_name' => '',
            'last_name' => '',
            'request_type_id' => 1,
            'phone_number' => '',
            'email' => '',
            'date_of_birth' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin submit request with invalid data
     * @return void
     */
    public function test_admin_create_request_with_invalid_data()
    {
        $response = $this->postJson('/admin-submitted-requests', [
            'first_name' => '123423',
            'last_name' => '1234231',
            'request_type_id' => 1,
            'phone_number' => '-==1242',
            'email' => 'asdf@fasd',
            'date_of_birth' => '12/14/2025',
            'street' => 'asdfa a1234 1234',
            'city' => 'asfd =-1234',
            'state' => '1234',
            'zipcode' => 'asdf',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin submit request with valid data
     * @return void
     */
    public function test_admin_create_request_with_valid_data()
    {
        $response = $this->postJson('/admin-submitted-requests', [
            'first_name' => 'newPatient',
            'last_name' => 'newData',
            'request_type_id' => 1,
            'phone_number' => '1234567890',
            'email' => 'asdf@fasd.cc',
            'date_of_birth' => '12/08/1995',
            'street' => 'newStreet',
            'city' => 'asfd',
            'state' => 'newState',
            'zipcode' => '123456',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin transfer request with no data (i.e. no physician selected)
     *
     * @return void
     */
    public function test_admin_transfer_request_with_no_data()
    {
        $response = $this->postJson('/transfer-case-admin', [
            'physician' => '',
            'notes' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin transfer request with invalid data
     *
     * @return void
     */
    public function test_admin_transfer_request_with_invalid_data()
    {
        $response = $this->postJson('/transfer-case-admin', [
            'physician' => '',
            'notes' => '^*&^*)',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin transfer request with valid data
     *
     * @return void
     */
    public function test_admin_transfer_request_with_valid_data()
    {
        $response = $this->postJson('/transfer-case-admin', [
            'physician' => 1,
            'notes' => '^*&^*)',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin send order -> with no data
     *
     * @return void
     */
    public function test_admin_send_order_with_no_data()
    {
        $response = $this->postJson('/admin-send-order', [
            'profession' => '',
            'vendor_id' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin send order -> with invalid data
     *
     * @return void
     */
    public function test_admin_send_order_with_invalid_data()
    {
        $response = $this->postJson('/admin-send-order', [
            'profession' => 1,
            'vendor_id' => 1,
            'prescription' => '&*(^^*(',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin send order -> with valid data
     *
     * @return void
     */
    public function test_admin_send_order_with_valid_data()
    {
        $response = $this->postJson('/admin-send-order', [
            'profession' => 1,
            'vendor_id' => 1,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin encounter form (Medical Form) -> submit with no data
     *
     * @return void
     */
    public function test_admin_encounter_form_submit_with_no_data()
    {
        $response = $this->postJson('/admin-medical-form', [
            'first_name' => '',
            'last_name' => '',
            'location' => '',
            'date_of_birth' => '',
            'service_date' => '',
            'mobile' => '',
            'allergies' => '',
            'treatment_plan' => '',
            'medication_dispensed' => '',
            'procedure' => '',
            'followUp' => '',
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin encounter form (Medical Form) -> submit with invalid data
     *
     * @return void
     */
    public function test_admin_encounter_form_submit_with_invalid_data()
    {
        $response = $this->postJson('/admin-medical-form', [
            'first_name' => '123421',
            'last_name' => '41234123',
            'location' => '12423',
            'date_of_birth' => '12432',
            'service_date' => '123',
            'mobile' => '12342',
            'allergies' => '1234',
            'treatment_plan' => '(&',
            'medication_dispensed' => '*^^',
            'procedure' => '%$^%%',
            'followUp' => '$^**&',
            'email' => '*^&%',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin encounter form (Medical Form) -> submit with valid data
     *
     * @return void
     */
    public function test_admin_encounter_form_submit_with_valid_data()
    {
        $response = $this->postJson('/admin-medical-form', [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'location' => 'new building, near hospital',
            'date_of_birth' => '10/12/1990',
            'service_date' => '19/02/2024',
            'mobile' => '1234567890',
            'allergies' => 'dust',
            'treatment_plan' => 'new plan for testing',
            'medication_dispensed' => 'given',
            'procedure' => 'completed',
            'followUp' => 'no',
            'email' => 'new@new.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin MyProfile page can be rendered.
     */
    public function test_admin_profile_page_can_be_rendered(): void
    {
        $response = $this->get('/admin-profile-edit');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin Provider page can be rendered.
     */
    public function test_admin_provider_page_can_be_rendered(): void
    {
        $response = $this->get('/admin-providers');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin create new Provider page can be rendered.
     */
    public function test_admin_create_new_provider_page_can_be_rendered(): void
    {
        $response = $this->get('/admin-new-provider');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin create new Provider with no data.
     */
    public function test_admin_create_new_provider_with_no_data(): void
    {
        $response = $this->postJson('/admin-create-new-provider', [
            'user_name' => '',
            'password' => '',
            'role' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone_number' => '',
            'medical_license' => '',
            'npi_number' => '',
            'region_id[]' => '',
            'address1' => '',
            'address2' => '',
            'city' => '',
            'select_state' => '',
            'zip' => '',
            'phone_number_alt' => '',
            'business_name' => '',
            'business_website' => '',
            'admin_notes' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin create new Provider with invalid data.
     */
    public function test_admin_create_new_provider_with_invalid_data(): void
    {
        $response = $this->postJson('/admin-create-new-provider', [
            'user_name' => '1234',
            'password' => '12',
            'role' => 9,
            'first_name' => '2345()*)(',
            'last_name' => '*)&*',
            'email' => 'asdf1234@123.12',
            'phone_number' => '&%^$$',
            'medical_license' => 'asdfasdf',
            'npi_number' => 'asdasd',
            'region_id[]' => '12',
            'address1' => '1234&^(^',
            'address2' => '!@#$@3',
            'city' => '1234',
            'select_state' => '&%$',
            'zip' => '1@#4',
            'phone_number_alt' => '!@#$',
            'business_name' => '!%#@4',
            'business_website' => 'ASDF123423',
            'admin_notes' => '1234',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin create new Provider with valid data.
     */
    public function test_admin_create_new_provider_with_valid_data(): void
    {
        $response = $this->postJson('/admin-create-new-provider', [
            'user_name' => 'desegoqa',
            'password' => 'anything',
            'role' => 1,
            'first_name' => 'Ethan',
            'last_name' => 'Noel',
            'email' => 'nohovezazi@mailinator.com',
            'phone_number' => '+11751349589',
            'medical_license' => '8831212121',
            'npi_number' => '3272345345',
            'region_id[]' => [1, 2],
            'address1' => '46 Fabien Drive',
            'address2' => 'Non velit mollit ip',
            'city' => 'Ipsum reprehenderit',
            'select_state' => 2,
            'zip' => 884615,
            'phone_number_alt' => '3231234123',
            'business_name' => 'McKenzie Kent',
            'business_website' => 'https://www.nodus.org.au',
            'admin_notes' => 'Porro ullamco magna',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }
}
