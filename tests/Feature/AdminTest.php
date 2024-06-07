<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestClient;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class AdminTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    // admin listing new state page can be rendered
    public function test_admin_listing_new_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/new');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();


        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminNewListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
    }

    // admin listing pending state page can be rendered
    public function test_admin_listing_pending_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/pending');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();


        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminPendingListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
    }

    // admin listing active state page can be rendered
    public function test_admin_listing_active_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/active');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();


        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminActiveListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
    }

    // admin listing conclude state page can be rendered
    public function test_admin_listing_conclude_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/conclude');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();


        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminConcludeListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
    }

    // admin listing toclose state page can be rendered
    public function test_admin_listing_toclose_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/toclose');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();


        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminTocloseListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
    }

    // admin listing unpaid state page can be rendered
    public function test_admin_listing_unpaid_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/unpaid');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminUnpaidListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
    }

    /**
     * assign case form with valid data
     * @return void
     */
    public function test_assign_case_with_valid_data()
    {
        $admin = $this->admin();

        $requestId =  RequestTable::where('status', 1)->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/assign-case', [
                'requestId' => $requestId,
                'region' => 1,
                'physician' => 1,
                'assign_note' => 'Physician Notes',
            ]);

        $physician = Provider::where('id', 1)->first();
        $physicianName = $physician->first_name . ' ' . $physician->last_name;

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', "Case Assigned Successfully to physician - {$physicianName}");
    }

    /**
     * assign case form with invalid data
     * @return void
     */
    public function test_assign_case_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/assign-case', [
                'assign_note' => '$#%$^',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'assign_note' => 'The assign note field format is invalid.'
            ]);
    }

    /**
     * assign case form with empty data
     * @return void
     */
    public function test_assign_case_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/assign-case', [
                'physician' => '',
                'assign_note' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'physician' => 'The physician field is required.',
                'assign_note' => 'The assign note field is required.'
            ]);
    }

    /**
     * View case page can be rendered
     * @return void
     */
    public function test_view_case_page_can_be_rendered()
    {
        $admin = $this->admin();

        $requestId = RequestTable::first()->id;
        $id = Crypt::encrypt($requestId);


        $response = $this->actingAs($admin)
            ->get('admin/view/case/{' . $id . '}');

        $request = $response->getOriginalContent()->getData()['data']->getAttributes();
        $requestClient = $response->getOriginalContent()->getData()['data']->getRelations()['requestClient']->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.pages.viewCase')
            ->assertViewHas('data');

        $this->assertTrue(array_key_exists('request_type_id', $request));
        $this->assertTrue(array_key_exists('status', $request));
        $this->assertTrue(array_key_exists('confirmation_no', $request));
        $this->assertTrue(array_key_exists('id', $request));
        $this->assertTrue(array_key_exists('notes', $requestClient));
        $this->assertTrue(array_key_exists('first_name', $requestClient));
        $this->assertTrue(array_key_exists('last_name', $requestClient));
        $this->assertTrue(array_key_exists('date_of_birth', $requestClient));
        $this->assertTrue(array_key_exists('phone_number', $requestClient));
        $this->assertTrue(array_key_exists('email', $requestClient));
        $this->assertTrue(array_key_exists('state', $requestClient));
        $this->assertTrue(array_key_exists('street', $requestClient));
        $this->assertTrue(array_key_exists('city', $requestClient));
        $this->assertTrue(array_key_exists('room', $requestClient));
    }

    /**
     * view case form with valid data
     * @return void
     */
    public function test_view_case_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/admin/view/case/edit', [
                'requestId' => $requestId,
                'patient_notes' => 'Physician Notes',
                'first_name' => 'Denton',
                'last_name' => 'Wise',
                'dob' => '2022-03-09',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('caseEdited', 'Information updated successfully!');
    }

    /**
     * view case form with invalid data
     * @return void
     */
    public function test_view_case_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin/view/case/edit', [
                'first_name' => 'ne',
                'last_name' => 'Wise1231',
                'dob' => '1885-05-23',
                'patient_notes' => 'Physician Notes *^&)&^$',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => "The first name field must be at least 3 characters.",
                'last_name' => "The last name field must only contain letters.",
                'dob' => "The dob field must be a date after Jan 01 1900.",
                'patient_notes' => "The patient notes field format is invalid.",
            ]);
    }

    /**
     * view case form with empty data
     * @return void
     */
    public function test_view_case_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin/view/case/edit', [
                'first_name' => '',
                'last_name' => '',
                'dob' => '',
                'patient_notes' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => "The first name field is required.",
                'last_name' => "The last name field is required.",
                'dob' => "The dob field is required.",
            ]);
    }

    /**
     * View Note page can be rendered
     * @return void
     */
    public function test_view_note_page_can_be_rendered()
    {
        $admin = $this->admin();

        $requestId = RequestTable::first()->id;
        $id = Crypt::encrypt($requestId);

        $response = $this->actingAs($admin)->get('admin/view/notes/{' . $id . '}');

        $data = $response->getOriginalContent()->getData()['data']->getAttributes();
        $note = $response->getOriginalContent()->getData()['note']->getAttributes();
        $adminAssignedCase = $response->getOriginalContent()->getData()['adminAssignedCase']->getAttributes();
        $providerTransferedCase = $response->getOriginalContent()->getData()['providerTransferedCase']->getAttributes();
        $adminTransferedCase = $response->getOriginalContent()->getData()['adminTransferedCase']->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.pages.viewNotes')->assertViewHasAll([
                'requestId', 'note', 'adminAssignedCase', 'providerTransferedCase', 'adminTransferedCase', 'data'
            ]);

        $this->assertTrue(array_key_exists('status', $data));
        $this->assertTrue(array_key_exists('created_at', $adminAssignedCase));
        $this->assertTrue(array_key_exists('notes', $adminAssignedCase));
        $this->assertTrue(array_key_exists('created_at', $providerTransferedCase));
        $this->assertTrue(array_key_exists('notes', $providerTransferedCase));
        $this->assertTrue(array_key_exists('created_at', $adminTransferedCase));
        $this->assertTrue(array_key_exists('notes', $adminTransferedCase));
        $this->assertTrue(array_key_exists('physician_notes', $note));
        $this->assertTrue(array_key_exists('admin_notes', $note));
    }

    /**
     * View Note add note with empty data
     * @return void
     */
    public function test_view_note_add_note_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin/view/notes/store', [
                'admin_note' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'admin_note' => "The admin note field is required."
        ]);
    }

    /**
     * View Note add note with invalid data 
     * @return void
     */
    public function test_view_note_add_note_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin/view/notes/store', [
                'admin_note' => '+_)*&(',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'admin_note' => "The admin note field format is invalid.",
        ]);
    }

    /**
     * View Note add note with valid data
     * @return void
     */
    public function test_view_note_add_note_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/admin/view/notes/store', [
                'requestId' => $requestId,
                'admin_note' => 'New note added',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('adminNoteAdded', 'Your Note Successfully Added');
    }

    /**
     * block case with valid data
     * @return void
     */
    public function test_block_case_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::where('status', 1)->first()->id;
        $response = $this->actingAs($admin)
            ->postJson('/block-case', [
                'requestId' => $requestId,
                'block_reason' => 'confirm block',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Blocked Successfully!');
    }

    /**
     * block case with invalid data
     * @return void
     */
    public function test_block_case_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/block-case', [
                'block_reason' => '%$^%$#@',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'block_reason' => 'The block reason field format is invalid.',
        ]);
    }

    /**
     * block case with empty data
     * @return void
     */
    public function test_block_case_with_empty_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->postJson('/block-case', [
                'block_reason' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'block_reason' => 'The block reason field is required.',
            ]);
    }

    /**
     * close case page can be rendered
     * @return void
     */
    public function test_close_case_page_can_be_rendered()
    {
        $admin = $this->admin();

        $requestId = RequestTable::whereIn('status', [2, 7, 11])->first()->id;

        $id = Crypt::encrypt($requestId);

        $response = $this->actingAs($admin)->get('/close-case/{' . $id . '}');

        $data = $response->getOriginalContent()->getData()['data']->getAttributes();
        $requestClient = $response->getOriginalContent()->getData()['data']->getRelations()['requestClient']->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.pages.closeCase')
            ->assertViewHasAll(['data', 'files']);

        $this->assertTrue(array_key_exists('id', $data));
        $this->assertTrue(array_key_exists('confirmation_no', $data));
        $this->assertTrue(array_key_exists('first_name', $requestClient));
        $this->assertTrue(array_key_exists('last_name', $requestClient));
        $this->assertTrue(array_key_exists('date_of_birth', $requestClient));
        $this->assertTrue(array_key_exists('phone_number', $requestClient));
        $this->assertTrue(array_key_exists('email', $requestClient));
    }

    /**
     * close case form with valid data
     * @return void
     */
    public function test_close_case_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::whereIn('status', [2, 7, 11])->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/close-case', [
                'closeCaseBtn' => 'Close Case',
                'requestId' => $requestId,
                'phone_number' => '1478523690',
                'email' => 'fehaw@mailinator.com',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Closed Successfully!');
    }

    // Close case (update data without closing case)
    public function test_close_case_update_information_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::whereIn('status', [2, 7, 11])->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/close-case', [
                'closeCaseBtn' => 'Save',
                'requestId' => $requestId,
                'phone_number' => '1478523690',
                'email' => 'new123@mailinator.com',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Information updated Successfully!');
    }

    /**
     * close case form with invalid data
     * @return void
     */
    public function test_close_case_update_information_with_invalid_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->postJson('/close-case', [
                'closeCaseBtn' => 'Save',
                'phone_number' => '1478323523',
                'email' => 'fehaw@2432mailinator.com',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email' => 'The email field format is invalid.',
            ]);
    }

    /**
     * Test successful close case form with empty data
     * @return void
     */
    public function test_close_case_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->postJson('/close-case', [
            'closeCaseBtn' => 'Save',
            'phone_number' => '',
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email' => 'The email field is required.',
                'phone_number' => 'The phone number field is required.',
            ]);
    }

    /**
     * send mail to patient with valid data (case wise mail)
     * @return void
     */
    public function test_send_mail_to_patient_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::first()->id;

        $response = $this->actingAs($admin)->postJson('/send-mail', [
            'requestId' => $requestId,
            'message' => 'Test mail sent through unit test case',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('successMessage', 'Mail sent to patient successfully!');
    }

    /**
     * send mail form with invalid data
     * @return void
     */
    public function test_send_mail_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/send-mail', [
                'message' => '$#%$%#%$#^%^',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'message' => 'The message field format is invalid.'
            ]);
    }

    /**
     * Test successful send mail form with empty data
     * @return void
     */
    public function test_send_mail_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/send-mail', [
                'message' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'message' => 'The message field is required.',
        ]);
    }

    /**
     * cancel case form with valid data
     * @return void
     */
    public function test_cancel_case_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::where('status', 1)->first()->id;

        $response = $this->actingAs($admin)->postJson('/cancel-case-data', [
            'requestId' => $requestId,
            'case_tag' => 1,
            'reason' => 'canceled this case through test case',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Cancelled (Moved to ToClose State)');
    }

    /**
     * Test successful cancel case form with invalid data
     * @return void
     */
    public function test_cancel_case_with_invalid_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->postJson('/cancel-case-data', [
                'case_tag' => '12',
                'reason' => '$#^%^$#%$^&',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'case_tag' => 'The selected case tag is invalid.',
            'reason' => 'The reason field format is invalid.',
        ]);
    }

    /**
     * Test successful cancel case form with empty data
     * @return void
     */
    public function test_cancel_case_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/cancel-case-data', [
                'case_tag' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'case_tag' => 'The case tag field is required.',
        ]);
    }


    // Admin Create request page can be rendered
    public function test_admin_create_request_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin-submit-requests');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Admin create request with empty data
     * @return void
     */
    public function test_admin_create_request_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-submitted-requests', [
                'first_name' => '',
                'last_name' => '',
                'request_type_id' => '',
                'phone_number' => '',
                'email' => '',
                'date_of_birth' => '',
                'street' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'request_type_id' => 'Request Type Id is required',
            'phone_number' => 'Please enter Phone Number',
            'email' => 'Please enter Email',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
        ]);
    }

    /**
     * Admin submit request with invalid data
     * @return void
     */
    public function test_admin_create_request_with_invalid_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->postJson('/admin-submitted-requests', [
                'first_name' => '123423',
                'last_name' => '1234231',
                'email' => 'asdf@fasd',
                'date_of_birth' => '12/14/2025',
                'city' => 'asfd =-1234',
                'state' => '1234',
                'zipcode' => '123',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'date_of_birth' => 'Please enter Date of Birth not greater than today',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'The zipcode field must be 6 digits.',
        ]);
    }

    /**
     * Admin create request with valid data and existing email
     * @return void
     */
    public function test_admin_create_request_with_valid_data_and_existing_email()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-submitted-requests', [
                'first_name' => 'newPatient',
                'last_name' => 'newData',
                'request_type_id' => 1,
                'phone_number' => '1234567890',
                'email' => 'pusib@mailinator.com',
                'date_of_birth' => '1995-08-12',
                'street' => 'newStreet',
                'city' => 'asfd',
                'state' => 'newState',
                'zipcode' => '123456',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('admin.status', 'new')
            ->assertSessionHas('successMessage', 'Request Created Successfully!');
    }

    /**
     * Admin submit request with valid data and new email
     * @return void
     */
    public function test_admin_create_request_with_valid_data_and_new_email()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-submitted-requests', [
                'first_name' => 'newPatient',
                'last_name' => 'newData',
                'request_type_id' => 1,
                'phone_number' => '1234567890',
                'email' => fake()->unique()->email(),
                'date_of_birth' => '1995-08-12',
                'street' => 'newStreet',
                'city' => 'asfd',
                'state' => 'newState',
                'zipcode' => '123456',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('admin.status', 'new')
            ->assertSessionHas('successMessage', 'Email for create account is sent & request created successfully!');
    }

    /**
     * Admin transfer request with no data (i.e. no physician selected)
     *
     * @return void
     */
    public function test_admin_transfer_request_with_no_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/transfer-case-admin', [
                'physician' => '',
                'notes' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'physician' => 'The physician field is required.',
                'notes' => 'The notes field is required.',
            ]);
    }

    /**
     * Admin transfer request with invalid data
     *
     * @return void
     */
    public function test_admin_transfer_request_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/transfer-case-admin', [
                'physician' => 'asd',
                'notes' => '^*&^*)',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'physician' => 'The physician field must be a number.',
            'notes' => 'The notes field format is invalid.',
        ]);
    }

    /**
     * Admin transfer request with valid data
     *
     * @return void
     */
    public function test_admin_transfer_request_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::where('status', 3)->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/transfer-case-admin', [
                'requestId' => $requestId,
                'physician' => 1,
                'notes' => 'Transfered case from admin through unit test case',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('successMessage', 'Case Transferred to Another Physician');
    }

    /**
     * Admin send order -> with no data
     *
     * @return void
     */
    public function test_admin_send_order_with_no_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-send-order', [
                'profession' => '',
                'vendor_id' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'profession' => 'The profession field is required.',
            'vendor_id' => 'The vendor id field is required.',
        ]);
    }

    /**
     * Admin send order -> with invalid data
     *
     * @return void
     */
    public function test_admin_send_order_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-send-order', [
                'profession' => 10,
                'vendor_id' => 20,
                'prescription' => '&*(^^*(',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'prescription' => 'The prescription field format is invalid.'
            ]);
    }

    /**
     * Admin send order -> with valid data
     *
     * @return void
     */
    public function test_admin_send_order_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::whereIn('status', [2, 4, 5, 6, 7, 11])->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/admin-send-order', [
                'requestId' => $requestId,
                'profession' => 1,
                'vendor_id' => 1,
                'prescription' => 'Prescription added through unit test case.'
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('successMessage', 'Order Created Successfully!');
    }

    // Admin encounter form can be rendered
    public function test_admin_encounter_form_can_be_rendered()
    {
        $admin = $this->admin();

        $id = RequestTable::whereIn('status', [2, 4, 5, 6, 7, 11])->first()->id;

        $requestId = Crypt::encrypt($id);

        $response = $this->actingAs($admin)->get('/admin-encounter-form/{' . $requestId . '}');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Admin encounter form (Medical Form) -> submit with no data
     *
     * @return void
     */
    public function test_admin_encounter_form_submit_with_no_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-medical-form', [
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'location' => 'The location field is required.',
            'date_of_birth' => 'The date of birth field is required.',
            'service_date' => 'The service date field is required.',
            'mobile' => 'The mobile field is required.',
            'allergies' => 'The allergies field is required.',
            'treatment_plan' => 'The treatment plan field is required.',
            'medication_dispensed' => 'The medication dispensed field is required.',
            'procedure' => 'The procedure field is required.',
            'followUp' => 'The follow up field is required.',
            'email' => 'The email field is required.',
        ]);
    }

    /**
     * Admin encounter form (Medical Form) -> submit with invalid data
     *
     * @return void
     */
    public function test_admin_encounter_form_submit_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-medical-form', [
                'first_name' => '123421',
                'last_name' => '41234123',
                'location' => '1242',
                'date_of_birth' => '12432',
                'service_date' => '123',
                'mobile' => '12342',
                'allergies' => 'asd',
                'treatment_plan' => '(&%$$$&',
                'medication_dispensed' => '*^^',
                'procedure' => '%$^%%',
                'followUp' => '$^**&',
                'email' => '*^&%',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'location' => 'The location field must be at least 5 characters.',
            'date_of_birth' => 'The date of birth field must be a valid date.',
            'service_date' => 'The service date field must be a valid date.',
            'allergies' => 'The allergies field must be at least 5 characters.',
            'treatment_plan' => 'The treatment plan field format is invalid.',
            'medication_dispensed' => 'The medication dispensed field must be at least 5 characters.',
            'procedure' => 'The procedure field format is invalid.',
            'followUp' => 'The follow up field format is invalid.',
            'email' => 'The email field must be a valid email address.',
        ]);
    }

    /**
     * Admin encounter form (Medical Form) -> submit with valid data
     *
     * @return void
     */
    public function test_admin_encounter_form_submit_with_valid_data()
    {
        $admin = $this->admin();

        $requestId = RequestTable::whereIn('status', [2, 4, 5, 6, 7, 11])->first()->id;

        $response = $this->actingAs($admin)
            ->postJson('/admin-medical-form', [
                'request_id' => $requestId,
                'first_name' => 'firstName',
                'last_name' => 'lastName',
                'location' => 'new building, near hospital',
                'date_of_birth' => '1990-02-10',
                'service_date' => '2024-02-12',
                'mobile' => '1234567890',
                'allergies' => 'dust particles',
                'treatment_plan' => 'new plan for testing',
                'medication_dispensed' => 'given',
                'procedure' => 'completed',
                'followUp' => 'required',
                'email' => 'new@new.com',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('encounterChangesSaved', 'Your changes have been Successfully Saved');
    }
}
