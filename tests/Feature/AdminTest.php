<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRoles;
use App\Models\RequestTable;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class AdminTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }
    /**
     * Test successful assign case form with valid data
     * @return void
     */
    // public function test_assign_case_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId =  RequestTable::where('status', 1)->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/assign-case', [
    //             'requestId' => $requestId,
    //             'region' => 1,
    //             'physician' => 1,
    //             'assign_note' => 'Physician Notes',
    //         ]);

    //     $physician = Provider::where('id', 1)->first();
    //     $physicianName = $physician->first_name . ' ' . $physician->last_name;

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', "Case Assigned Successfully to physician - {$physicianName}");
    // }

    /**
     * Test successful assign case form with invalid data
     * @return void
     */
    // public function test_assign_case_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/assign-case', [
    //             'assign_note' => '$#%$^',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'assign_note' => 'The assign note field format is invalid.'
    //         ]);
    // }

    /**
     * Test successful assign case form with empty data
     * @return void
     */
    // public function test_assign_case_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/assign-case', [
    //             'physician' => '',
    //             'assign_note' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'physician' => 'The physician field is required.',
    //             'assign_note' => 'The assign note field is required.'
    //         ]);
    // }

    /**
     * View case form page can be rendered
     * @return void
     */
    // public function test_view_case_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::first()->id;
    //     $id = Crypt::encrypt($requestId);

    //     $response = $this->actingAs($admin)
    //         ->get('admin/view/case/{' . $id . '}');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Test successful view case form with valid data
     * @return void
     */
    // public function test_view_case_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin/view/case/edit', [
    //             'requestId' => $requestId,
    //             'patient_notes' => 'Physician Notes',
    //             'first_name' => 'Denton',
    //             'last_name' => 'Wise',
    //             'dob' => '2022-03-09',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('caseEdited', 'Information updated successfully!');
    // }

    /**
     * Test successful view case form with invalid data
     * @return void
     */
    // public function test_view_case_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin/view/case/edit', [
    //             'first_name' => 'ne',
    //             'last_name' => 'Wise1231',
    //             'dob' => '1885-05-23',
    //             'patient_notes' => 'Physician Notes *^&)&^$',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'first_name' => "The first name field must be at least 3 characters.",
    //             'last_name' => "The last name field must only contain letters.",
    //             'dob' => "The dob field must be a date after Jan 01 1900.",
    //             'patient_notes' => "The patient notes field format is invalid.",
    //         ]);
    // }

    /**
     * Test successful view case form with empty data
     * @return void
     */
    // public function test_view_case_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin/view/case/edit', [
    //             'first_name' => '',
    //             'last_name' => '',
    //             'dob' => '',
    //             'patient_notes' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //     ->assertJsonValidationErrors([
    //         'first_name' => "The first name field is required." ,
    //         'last_name' => "The last name field is required.",
    //         'dob' => "The dob field is required.",
    //     ]);
    // }

    /**
     * View Notes page can be rendered
     * @return void
     */
    // public function test_view_note_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::first()->id;
    //     $id = Crypt::encrypt($requestId);

    //     $response = $this->actingAs($admin)->get('admin/view/notes/{' . $id . '}');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * View Notes -> add note with no text entered
     * @return void
     */
    // public function test_view_note_add_note_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin/view/notes/store', [
    //             'admin_note' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'admin_note' => "The admin note field is required."
    //     ]);
    // }

    /**
     * View Notes -> add note with invalid data format
     * @return void
     */
    // public function test_view_note_add_note_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin/view/notes/store', [
    //             'admin_note' => '+_)*&(',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'admin_note' => "The admin note field format is invalid.",
    //     ]);
    // }

    /**
     * View Notes -> add note with valid data format
     * @return void
     */
    // public function test_view_note_add_note_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin/view/notes/store', [
    //             'requestId' => $requestId,
    //             'admin_note' => 'New note added',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('adminNoteAdded', 'Your Note Successfully Added');
    // }

    /**
     * Test successful block case form with valid data
     * @return void
     */
    // public function test_block_case_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::where('status', 1)->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/block-case', [
    //             'requestId' => $requestId,
    //             'block_reason' => 'confirm block',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Blocked Successfully!');
    // }

    /**
     * Test successful block case form with valid data
     * @return void
     */
    // public function test_block_case_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/block-case', [
    //             'block_reason' => '%$^%$#@',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'block_reason' => 'The block reason field format is invalid.',
    //     ]);
    // }

    /**
     * Test successful block case form with empty data
     * @return void
     */
    // public function test_block_case_with_empty_data()
    // {
    //     $admin = $this->admin();
    //     $response = $this->actingAs($admin)
    //         ->postJson('/block-case', [
    //             'block_reason' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'block_reason' => 'The block reason field is required.',
    //     ]);
    // }


    /**
     * Test successful close case form with valid data
     * @return void
     */
    // public function test_close_case_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::whereIn('status', [2, 7, 11])->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/close-case', [
    //             'closeCaseBtn' => 'Close Case',
    //             'requestId' => $requestId,
    //             'phone_number' => '1478523690',
    //             'email' => 'fehaw@mailinator.com',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Closed Successfully!');
    // }

    // Close case (update data without closing case)
    // public function test_close_case_update_information_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::whereIn('status', [2, 7, 11])->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/close-case', [
    //             'closeCaseBtn' => 'Save',
    //             'requestId' => $requestId,
    //             'phone_number' => '1478523690',
    //             'email' => 'new123@mailinator.com',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Information updated Successfully!');
    // }

    /**
     * Test successful close case form with invalid data
     * @return void
     */
    // public function test_close_case_update_information_with_invalid_data()
    // {
    //     $admin = $this->admin();
    //     $response = $this->actingAs($admin)
    //         ->postJson('/close-case', [
    //             'closeCaseBtn' => 'Save',
    //             'phone_number' => '1478323523',
    //             'email' => 'fehaw@2432mailinator.com',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'email' => 'The email field format is invalid.',
    //         ]);
    // }

    /**
     * Test successful close case form with empty data
     * @return void
     */
    // public function test_close_case_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->postJson('/close-case', [
    //         'closeCaseBtn' => 'Save',
    //         'phone_number' => '',
    //         'email' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'email' => 'The email field is required.',
    //             'phone_number' => 'The phone number field is required.',
    //         ]);
    // }

    // Partners page can be rendered
    // public function test_partners_page_can_be_rendered(){
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('add-business');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Test successful add business form with valid data
     * @return void
     */
    // public function test_add_business_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/add-business', [
    //             'business_name' => 'Garrison',
    //             'profession' => 1,
    //             'fax_number' => '4610',
    //             'mobile' => '+1 776-977-4023',
    //             'email' => 'refe@mailinator.com',
    //             'business_contact' => '3315698752',
    //             'street' => 'Culpa ullam error qu',
    //             'city' => 'Et autem et aperiam ',
    //             'state' => 'Dolor architecto off',
    //             'zip' => '529430',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('businessAdded', 'Business Added Successfully!');
    // }

    /**
     * Test successful add business form with invalid data
     * @return void
     */
    // public function test_add_business_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/add-business', [
    //             'business_name' => 'Garrison 44',
    //             'profession' => 50,
    //             'fax_number' => '4610454455454',
    //             'mobile' => '+1 776-977-402345',
    //             'email' => 'refe@mailinato45r.com',
    //             'business_contact' => '3314545698752',
    //             'street' => 'Culp#$%#$%345a ullam error qu',
    //             'city' => 'Et aut#%$%#$em et aperiam ',
    //             'state' => 'Dolor #$%$%#545architecto off',
    //             'zip' => '529434540',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'business_name' => 'The business name field must only contain letters.',
    //         'fax_number' => 'The fax number field must not have more than 8 digits.',
    //         'email' => 'The email field format is invalid.',
    //         'business_contact' => 'The business contact field must not have more than 10 digits.',
    //         'street' => 'The street field must not be greater than 25 characters.',
    //         'city' => 'The city field must only contain letters.',
    //         'state' => 'The state field must only contain letters.',
    //         'zip' => 'The zip field must not have more than 6 digits.',
    //     ]);
    // }

    /**
     * Test successful add business form with empty data
     * @return void
     */
    // public function test_add_business_with_empty_data()
    // {
    //     $admin = $this->admin();
    //     $response = $this->actingAs($admin)
    //         ->postJson('/add-business', [
    //             'business_name' => '',
    //             'fax_number' => '',
    //             'mobile' => '',
    //             'email' => '',
    //             'business_contact' => '',
    //             'street' => '',
    //             'city' => '',
    //             'state' => '',
    //             'zip' => '',
    //         ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'business_name' => 'The business name field is required.',
    //             'fax_number' => 'The fax number field is required.',
    //             'mobile' => 'The mobile field is required.',
    //             'email' => 'The email field is required.',
    //             'business_contact' => 'The business contact field is required.',
    //             'street' => 'The street field is required.',
    //             'city' => 'The city field is required.',
    //             'state' => 'The state field is required.',
    //             'zip' => 'The zip field is required.',
    //         ]);
    // }

    /**
     * Test successful send mail to patient form with valid data
     * @return void
     */
    // public function test_send_mail_to_patient_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::first()->id;

    //     $response = $this->actingAs($admin)->postJson('/send-mail', [
    //         'requestId' => $requestId,
    //         'message' => 'Test mail sent through unit test case',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Mail sent to patient successfully!');
    // }

    /**
     * Test successful send mail form with invalid data
     * @return void
     */
    // public function test_send_mail_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/send-mail', [
    //             'message' => '$#%$%#%$#^%^',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'message' => 'The message field format is invalid.'
    //         ]);
    // }

    /**
     * Test successful send mail form with empty data
     * @return void
     */
    // public function test_send_mail_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/send-mail', [
    //             'message' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'message' => 'The message field is required.',
    //     ]);
    // }

    /**
     * Test successful cancel case form with valid data
     * @return void
     */
    // public function test_cancel_case_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::where('status', 1)->first()->id;

    //     $response = $this->actingAs($admin)->postJson('/cancel-case-data', [
    //         'requestId' => $requestId,
    //         'case_tag' => 1,
    //         'reason' => 'canceled this case through test case',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Cancelled (Moved to ToClose State)');
    // }

    /**
     * Test successful cancel case form with invalid data
     * @return void
     */
    // public function test_cancel_case_with_invalid_data()
    // {
    //     $admin = $this->admin();
    //     $response = $this->actingAs($admin)
    //         ->postJson('/cancel-case-data', [
    //             'case_tag' => '12',
    //             'reason' => '$#^%^$#%$^&',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'case_tag' => 'The selected case tag is invalid.',
    //         'reason' => 'The reason field format is invalid.',
    //     ]);
    // }

    /**
     * Test successful cancel case form with empty data
     * @return void
     */
    // public function test_cancel_case_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/cancel-case-data', [
    //             'case_tag' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'case_tag' => 'The case tag field is required.',
    //     ]);
    // }

    // Account access page can be rendered
    // public function test_account_access_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('/access');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    // Create role page can be rendered
    // public function test_create_role_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('/create-role');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Test successful create role with valid data
     * @return void
     */
    // public function test_create_role_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->postJson('/create-access', [
    //         'role_name' => '1',
    //         'role' => 'newAccount',
    //         'menu_checkbox' => [1, 2, 3, 4],
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirectToRoute('admin.access.view')
    //         ->assertSessionHas('accessOperation', 'New access created successfully!');
    // }

    /**
     * Test successful create role with valid data
     * @return void
     */
    // public function test_create_role_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/create-access', [
    //             'role' => 'test account name new created',
    //             'role_name' => '3',
    //             'menu_checkbox' => [40, 41]
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertJsonValidationErrors([
    //         'role' => 'The role field must not be greater than 20 characters.',
    //         'role_name' => 'The selected role name is invalid.',
    //     ]);
    // }

    /**
     * Test successful create role with valid data
     * @return void
     */
    // public function test_create_role_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/create-access', [
    //             'role' => '',
    //             'role_name' => '',
    //             'menu_checkbox' => ''
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'role' => 'The role field is required.',
    //         'role_name' => 'The role name field is required.',
    //         'menu_checkbox' => 'The menu checkbox field is required.',
    //     ]);
    // }

    // Admin Create request page can be rendered
    // public function test_admin_create_request_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('/admin-submit-requests');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Admin submit request with empty data
     * @return void
     */
    // public function test_admin_create_request_with_empty_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-submitted-requests', [
    //             'first_name' => '',
    //             'last_name' => '',
    //             'request_type_id' => '',
    //             'phone_number' => '',
    //             'email' => '',
    //             'date_of_birth' => '',
    //             'street' => '',
    //             'city' => '',
    //             'state' => '',
    //             'zipcode' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'Please enter First Name',
    //         'last_name' => 'Please enter Last Name',
    //         'request_type_id' => 'Request Type Id is required',
    //         'phone_number' => 'Please enter Phone Number',
    //         'email' => 'Please enter Email',
    //         'street' => 'Please enter a street',
    //         'city' => 'Please enter a city',
    //         'state' => 'Please enter a state',
    //     ]);
    // }

    /**
     * Admin submit request with invalid data
     * @return void
     */
    // public function test_admin_create_request_with_invalid_data()
    // {
    //     $admin = $this->admin();
    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-submitted-requests', [
    //             'first_name' => '123423',
    //             'last_name' => '1234231',
    //             'email' => 'asdf@fasd',
    //             'date_of_birth' => '12/14/2025',
    //             'city' => 'asfd =-1234',
    //             'state' => '1234',
    //             'zipcode' => '123',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'Please enter only Alphabets in First name',
    //         'last_name' => 'Please enter only Alphabets in Last name',
    //         'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
    //         'date_of_birth' => 'Please enter Date of Birth not greater than today',
    //         'city' => 'Please enter alpbabets in city name.',
    //         'state' => 'Please enter alpbabets in state name.',
    //         'zipcode' => 'The zipcode field must be 6 digits.',
    //     ]);
    // }

    /**
     * Admin submit request with valid data and existing email
     * @return void
     */
    // public function test_admin_create_request_with_valid_data_and_existing_email()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-submitted-requests', [
    //             'first_name' => 'newPatient',
    //             'last_name' => 'newData',
    //             'request_type_id' => 1,
    //             'phone_number' => '1234567890',
    //             'email' => 'pusib@mailinator.com',
    //             'date_of_birth' => '1995-08-12',
    //             'street' => 'newStreet',
    //             'city' => 'asfd',
    //             'state' => 'newState',
    //             'zipcode' => '123456',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirectToRoute('admin.status', 'new')
    //         ->assertSessionHas('successMessage', 'Request Created Successfully!');
    // }

    /**
     * Admin submit request with valid data and new email
     * @return void
     */
    // public function test_admin_create_request_with_valid_data_and_new_email()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-submitted-requests', [
    //             'first_name' => 'newPatient',
    //             'last_name' => 'newData',
    //             'request_type_id' => 1,
    //             'phone_number' => '1234567890',
    //             'email' => fake()->unique()->email(),
    //             'date_of_birth' => '1995-08-12',
    //             'street' => 'newStreet',
    //             'city' => 'asfd',
    //             'state' => 'newState',
    //             'zipcode' => '123456',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirectToRoute('admin.status', 'new')
    //         ->assertSessionHas('successMessage', 'Email for create account is sent & request created successfully!');
    // }

    /**
     * Admin transfer request with no data (i.e. no physician selected)
     *
     * @return void
     */
    // public function test_admin_transfer_request_with_no_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/transfer-case-admin', [
    //             'physician' => '',
    //             'notes' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'physician' => 'The physician field is required.',
    //             'notes' => 'The notes field is required.',
    //         ]);
    // }

    /**
     * Admin transfer request with invalid data
     *
     * @return void
     */
    // public function test_admin_transfer_request_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/transfer-case-admin', [
    //             'physician' => 'asd',
    //             'notes' => '^*&^*)',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'physician' => 'The physician field must be a number.',
    //         'notes' => 'The notes field format is invalid.',
    //     ]);
    // }

    /**
     * Admin transfer request with valid data
     *
     * @return void
     */
    // public function test_admin_transfer_request_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::where('status', 3)->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/transfer-case-admin', [
    //             'requestId' => $requestId,
    //             'physician' => 1,
    //             'notes' => 'Transfered case from admin through unit test case',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertSessionHas('successMessage', 'Case Transferred to Another Physician');
    // }

    /**
     * Admin send order -> with no data
     *
     * @return void
     */
    // public function test_admin_send_order_with_no_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-send-order', [
    //             'profession' => '',
    //             'vendor_id' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'profession' => 'The profession field is required.',
    //         'vendor_id' => 'The vendor id field is required.',
    //     ]);
    // }

    /**
     * Admin send order -> with invalid data
     *
     * @return void
     */
    // public function test_admin_send_order_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-send-order', [
    //             'profession' => 10,
    //             'vendor_id' => 20,
    //             'prescription' => '&*(^^*(',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'prescription' => 'The prescription field format is invalid.'
    //         ]);
    // }

    /**
     * Admin send order -> with valid data
     *
     * @return void
     */
    // public function test_admin_send_order_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::whereIn('status', [2, 4, 5, 6, 7, 11])->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-send-order', [
    //             'requestId' => $requestId,
    //             'profession' => 1,
    //             'vendor_id' => 1,
    //             'prescription' => 'Prescription added through unit test case.'
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertSessionHas('successMessage', 'Order Created Successfully!');
    // }

    // Admin encounter form can be rendered
    // public function test_admin_encounter_form_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $id = RequestTable::whereIn('status', [2, 4, 5, 6, 7, 11])->first()->id;

    //     $requestId = Crypt::encrypt($id);

    //     $response = $this->actingAs($admin)->get('/admin-encounter-form/{' . $requestId . '}');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Admin encounter form (Medical Form) -> submit with no data
     *
     * @return void
     */
    // public function test_admin_encounter_form_submit_with_no_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-medical-form', [
    //             'first_name' => '',
    //             'last_name' => '',
    //             'location' => '',
    //             'date_of_birth' => '',
    //             'service_date' => '',
    //             'mobile' => '',
    //             'allergies' => '',
    //             'treatment_plan' => '',
    //             'medication_dispensed' => '',
    //             'procedure' => '',
    //             'followUp' => '',
    //             'email' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'The first name field is required.',
    //         'last_name' => 'The last name field is required.',
    //         'location' => 'The location field is required.',
    //         'date_of_birth' => 'The date of birth field is required.',
    //         'service_date' => 'The service date field is required.',
    //         'mobile' => 'The mobile field is required.',
    //         'allergies' => 'The allergies field is required.',
    //         'treatment_plan' => 'The treatment plan field is required.',
    //         'medication_dispensed' => 'The medication dispensed field is required.',
    //         'procedure' => 'The procedure field is required.',
    //         'followUp' => 'The follow up field is required.',
    //         'email' => 'The email field is required.',
    //     ]);
    // }

    /**
     * Admin encounter form (Medical Form) -> submit with invalid data
     *
     * @return void
     */
    // public function test_admin_encounter_form_submit_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-medical-form', [
    //             'first_name' => '123421',
    //             'last_name' => '41234123',
    //             'location' => '1242',
    //             'date_of_birth' => '12432',
    //             'service_date' => '123',
    //             'mobile' => '12342',
    //             'allergies' => 'asd',
    //             'treatment_plan' => '(&%$$$&',
    //             'medication_dispensed' => '*^^',
    //             'procedure' => '%$^%%',
    //             'followUp' => '$^**&',
    //             'email' => '*^&%',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'The first name field must only contain letters.',
    //         'last_name' => 'The last name field must only contain letters.',
    //         'location' => 'The location field must be at least 5 characters.',
    //         'date_of_birth' => 'The date of birth field must be a valid date.',
    //         'service_date' => 'The service date field must be a valid date.',
    //         'allergies' => 'The allergies field must be at least 5 characters.',
    //         'treatment_plan' => 'The treatment plan field format is invalid.',
    //         'medication_dispensed' => 'The medication dispensed field must be at least 5 characters.',
    //         'procedure' => 'The procedure field format is invalid.',
    //         'followUp' => 'The follow up field format is invalid.',
    //         'email' => 'The email field must be a valid email address.',
    //     ]);
    // }

    /**
     * Admin encounter form (Medical Form) -> submit with valid data
     *
     * @return void
     */
    // public function test_admin_encounter_form_submit_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $requestId = RequestTable::whereIn('status', [2, 4, 5, 6, 7, 11])->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-medical-form', [
    //             'request_id' => $requestId,
    //             'first_name' => 'firstName',
    //             'last_name' => 'lastName',
    //             'location' => 'new building, near hospital',
    //             'date_of_birth' => '1990-02-10',
    //             'service_date' => '2024-02-12',
    //             'mobile' => '1234567890',
    //             'allergies' => 'dust particles',
    //             'treatment_plan' => 'new plan for testing',
    //             'medication_dispensed' => 'given',
    //             'procedure' => 'completed',
    //             'followUp' => 'required',
    //             'email' => 'new@new.com',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('encounterChangesSaved', 'Your changes have been Successfully Saved');
    // }

    /**
     * Admin MyProfile page can be rendered.
     */
    // public function test_admin_profile_page_can_be_rendered(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('/admin-profile-edit');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Admin Provider page can be rendered.
     */
    // public function test_admin_provider_page_can_be_rendered(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('/admin-providers');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Admin create new Provider page can be rendered.
     */
    // public function test_admin_create_new_provider_page_can_be_rendered(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)->get('/admin-new-provider');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Admin create new Provider with no data.
     */
    // public function test_admin_create_new_provider_with_no_data(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-create-new-provider', [
    //             'user_name' => '',
    //             'password' => '',
    //             'role' => '',
    //             'first_name' => '',
    //             'last_name' => '',
    //             'email' => '',
    //             'phone_number' => '',
    //             'medical_license' => '',
    //             'npi_number' => '',
    //             'region_id' => '',
    //             'address1' => '',
    //             'address2' => '',
    //             'city' => '',
    //             'select_state' => '',
    //             'zip' => '',
    //             'phone_number_alt' => '',
    //             'business_name' => '',
    //             'business_website' => '',
    //             'admin_notes' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'user_name' => 'Please enter User Name',
    //         'password' => 'Please enter Password',
    //         'first_name' => 'Please enter First Name',
    //         'last_name' => 'Please enter Last Name',
    //         'role' => 'Please select a Role',
    //         'email' => 'Please enter Email',
    //         'phone_number' => 'Please enter Phone Number',
    //         'medical_license' => 'Please enter medical license',
    //         'npi_number' => 'Please enter NPI number',
    //         'region_id' => 'Please select atleast one Region',
    //         'address1' => 'Please enter a address1',
    //         'address2' => 'Please enter a address2',
    //         'city' => 'Please enter a city',
    //         'select_state' => 'Please select state',
    //         'zip' => 'Please enter 6 digits zipcode',
    //         'phone_number_alt' => 'Please enter Alternate Phone Number',
    //         'business_name' => 'Please enter Business Name',
    //         'business_website' => 'Please enter Business Website Url',
    //         'admin_notes' => 'Please enter Admin Notes',
    //     ]);
    // }

    /**
     * Admin create new Provider with invalid data.
     */
    // public function test_admin_create_new_provider_with_invalid_data(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-create-new-provider', [
    //             'user_name' => '1234',
    //             'password' => '12',
    //             'role' => 9,
    //             'first_name' => '2345()*)(',
    //             'last_name' => '*)&*',
    //             'email' => 'asdf1234@123.12',
    //             'phone_number' => '&%^$$',
    //             'medical_license' => 'asdfasdf',
    //             'npi_number' => 'asdasd',
    //             'region_id[]' => '12',
    //             'address1' => '1234&^(^',
    //             'address2' => '!@#$@3',
    //             'city' => '1234',
    //             'select_state' => '&%$',
    //             'zip' => '1@#4',
    //             'phone_number_alt' => '!@#$',
    //             'business_name' => '!%#@4',
    //             'business_website' => 'ASDF123423',
    //             'admin_notes' => '1234',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'user_name' => 'Please enter only Alphabets in User name',
    //         'password' => 'Please enter more than 8 characters',
    //         'first_name' => 'Please enter only Alphabets in First name',
    //         'last_name' => 'Please enter only Alphabets in Last name',
    //         'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
    //         'medical_license' => 'Please enter only numbers',
    //         'npi_number' => 'Please enter only numbers',
    //         'address1' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in address1.',
    //         'address2' => 'Please enter alphabets in address2 name.',
    //         'city' => 'Please enter alphabets in city.',
    //         'zip' => 'Please enter 6 digits zipcode',
    //         'business_name' => 'Please enter alphabets in business name.',
    //         'business_website' => 'Please enter a valid business website URL starting with https://www.',
    //         'admin_notes' => 'Please enter more than 5 character',
    //     ]);
    // }

    /**
     * Admin create new Provider with valid data and existing email.
     */
    // public function test_admin_create_new_provider_with_valid_data_and_existing_email(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-create-new-provider', [
    //             'user_name' => 'desegoqa',
    //             'password' => 'anything',
    //             'role' => 1,
    //             'first_name' => 'Ethan',
    //             'last_name' => 'Noel',
    //             'email' => 'nohovezazi@mailinator.com',
    //             'phone_number' => '+11751349589',
    //             'medical_license' => '8831212111',
    //             'npi_number' => '3272345345',
    //             'region_id' => [1, 2],
    //             'address1' => '46 Fabien Drive',
    //             'address2' => 'Non velit mollit ip',
    //             'city' => 'Ipsum reprehenderit',
    //             'select_state' => 2,
    //             'zip' => 884615,
    //             'phone_number_alt' => '3231234123',
    //             'business_name' => 'McKenzie Kent',
    //             'business_website' => 'https://www.nodus.org.au',
    //             'admin_notes' => 'Porro ullamco magna',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'email' => 'The email has already been taken.'
    //     ]);
    // }

    /**
     * Admin create new Provider with valid data and new email.
     */
    // public function test_admin_create_new_provider_with_valid_data_and_new_email(): void
    // {
    //     $admin = $this->admin();

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-create-new-provider', [
    //             'user_name' => 'desegoqa',
    //             'password' => 'anything',
    //             'role' => 16,
    //             'first_name' => 'Ethan',
    //             'last_name' => 'Noel',
    //             'email' => fake()->unique()->email(),
    //             'phone_number' => '+11751349589',
    //             'medical_license' => '8831212111',
    //             'npi_number' => '3272345345',
    //             'region_id' => [1, 2],
    //             'address1' => '46 Fabien Drive',
    //             'address2' => 'Non velit mollit ip',
    //             'city' => 'Ipsum reprehenderit',
    //             'select_state' => 2,
    //             'zip' => 884615,
    //             'phone_number_alt' => '3231234123',
    //             'business_name' => 'McKenzie Kent',
    //             'business_website' => 'https://www.nodus.org.au',
    //             'admin_notes' => 'Porro ullamco magna',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'account is created');
    // }

    // Admin edit provider page can be rendered
    // public function test_admin_edit_proivder_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $providerId = Provider::orderBy('id', 'desc')->first()->id;

    //     $id = Crypt::encrypt($providerId);

    //     $response = $this->actingAs($admin)->get('/admin-edit-provider/{' . $id . '}');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    // // Admin edit proivders password with no data
    // public function test_admin_edit_providers_password_with_no_data()
    // {
    //     $admin = $this->admin();

    //     $providerId = Provider::orderBy('id', 'desc')->first()->id;

    //     $id = Crypt::encrypt($providerId);

    //     $response = $this->actingAs($admin)->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
    //         'password' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'password' => 'The password field is required.',
    //         ]);
    // }

    // Admin edit proivders password with invalid data
    // public function test_admin_edit_providers_password_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $providerId = Provider::orderBy('id', 'desc')->first()->id;

    //     $id = Crypt::encrypt($providerId);

    //     $response = $this->actingAs($admin)->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
    //         'password' => 'asdfas',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'password' => 'The password field must be at least 8 characters.',
    //         ]);
    // }

    // ------------------------------ (Not Working) ---------------------------------
    // Admin edit proivders password with valid data 
    // public function test_admin_edit_providers_password_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $providerId = Provider::orderBy('id', 'desc')->first()->id;

    //     $id = Crypt::encrypt($providerId);

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
    //             'password' => 'newPassword',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertSessionHas('message', 'account information is updated');
    // }
    // ----------------------------------------------------------------------------------

    // // Admin edit proivders username with no data
    // public function test_admin_edit_providers_username_with_no_data()
    // {
    //     $admin = $this->admin();

    //     $providerId = Provider::orderBy('id', 'desc')->first()->id;

    //     $id = Crypt::encrypt($providerId);

    //     $response = $this->actingAs($admin)->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
    //         'user_name' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'user_name' => 'The user name field is required.',
    //         ]);
    // }

    // Admin edit proivders username with invalid data
    // public function test_admin_edit_providers_username_with_invalid_data()
    // {
    //     $admin = $this->admin();

    //     $providerId = Provider::orderBy('id', 'desc')->first()->id;

    //     $id = Crypt::encrypt($providerId);

    //     $response = $this->actingAs($admin)->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
    //         'user_name' => '*^^(*',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'user_name' => 'The user name field must only contain letters.',
    //         ]);
    // }

    // ------------------------------ (Not Working) ---------------------------------
    // Admin edit proivders username with valid data
    // public function test_admin_edit_providers_username_with_valid_data()
    // {
    //     $admin = $this->admin();

    //     $id = Provider::orderBy('id', 'desc')->first()->id;

    //     $response = $this->actingAs($admin)
    //         ->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
    //             'user_name' => 'newName',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertSessionHas('message', 'account information is updated');
    // }
    // --------------------------------------------------------------------------------
}
