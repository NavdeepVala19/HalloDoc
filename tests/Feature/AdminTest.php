<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Users;
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
    // public function test_assign_case_with_valid_data()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $requestId = RequestTable::where('status', 1)->value('id');
    //     $response = $this->actingAs($admin)->postJson('/assign-case', [
    //         'region' => '1',
    //         'physician' => '1',
    //         'assign_note' => 'Physician Notes',
    //         'requestId' => $requestId,
    //     ]);

    //     $physician = Provider::where('id', 1)->first();
    //     $physicianName = $physician->first_name . ' ' . $physician->last_name;
    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', "Case Assigned Successfully to physician - {$physicianName}");
    // }

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
     * Test successful block case form with valid data
     * @return void
     */
    // public function test_block_case_with_valid_data()
    // {
    //     $requestId = RequestTable::where('status', 1)->value('id');
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/block-case', [
    //         'block_reason' => 'confirm block',
    //         'requestId' => $requestId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Blocked Successfully!');
    // }

    /**
     * Test successful block case form with invalid data
     * @return void
     */
    public function test_block_case_with_invalid_data()
    {
        $requestId = RequestTable::where('status', 1)->value('id');
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/block-case', [
            'block_reason' => '%$^%$#@',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'block_reason' => 'The block reason field format is invalid.',
        ]);
    }

    /**
     * Test successful block case form with empty data
     * @return void
     */
    public function test_block_case_with_empty_data()
    {
        $requestId = RequestTable::where('status', 1)->value('id');
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/block-case', [
            'block_reason' => '',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'block_reason' => 'The block reason field is required.',
        ]);
    }


    /**
     * Test successful close case form with valid data
     * @return void
     */
    // public function test_close_case_with_valid_data()
    // {
    //     $requestId = RequestTable::where('status', 7)->value('id');
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/close-case', [
    //         'phone_number' => '1478523690',
    //         'email' => 'fehaw@mailinator.com',
    //         'requestId' => $requestId,
    //         'closeCaseBtn' => 'Save',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND);
    // }


    /**
     * Test successful close case form with invalid data
     * @return void
     */
    public function test_close_case_with_invalid_data()
    {
        $requestId = RequestTable::where('status', 7)->value('id');
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/close-case', [
            'phone_number' => '1478523690',
            'email' => 'fehaw@2432mailinator.com',
            'requestId' => $requestId,
            'closeCaseBtn' => 'Save',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field format is invalid.',
        ]);
    }

    /**
     * Test successful close case form with empty data
     * @return void
     */
    public function test_close_case_with_empty_data()
    {
        $requestId = RequestTable::where('status', 7)->value('id');
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/close-case', [
            'phone_number' => '',
            'email' => '',
            'requestId' => $requestId,
            'closeCaseBtn' => 'Save',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'phone_number' => 'The phone number field is required.',
            'email' => 'The email field is required.',
        ]);
    }


    

    /**
     * Test successful send mail form with valid data
     * @return void
     */
    // public function test_send_mail_with_valid_data()
    // {
    //     $requestId = RequestTable::first()->id;
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/send-mail', [
    //         'message' => 'Hello Patient',
    //         'requestId' => $requestId ,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Mail sent to patient successfully!');
    // }

    /**
     * Test successful send mail form with invalid data
     * @return void
     */
    public function test_send_mail_with_invalid_data()
    {
        $requestId = RequestTable::first()->id;
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/send-mail', [
            'message' => '$#%$%#%$#^%^',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'message' => 'The message field format is invalid.',
        ]);
    }


    /**
     * Test successful send mail form with empty data
     * @return void
     */
    public function test_send_mail_with_empty_data()
    {
        $requestId = RequestTable::first()->id;
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/send-mail', [
            'message' => '',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'message' => 'The message field is required.',
        ]);
    }


    /**
     * Test successful cancel case form with valid data
     * @return void
     */
    // public function test_cancel_case_with_valid_data()
    // {
    //     $requestId = RequestTable::where('status', 1)->value('id');
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/cancel-case-data', [
    //         'case_tag'=>'1',
    //         'reason' => 'cancel this case',
    //         'requestId' => $requestId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Cancelled (Moved to ToClose State)');
    // }

    /**
     * Test successful cancel case form with invalid data
     * @return void
     */
    public function test_cancel_case_with_invalid_data()
    {
        $requestId = RequestTable::where('status', 1)->value('id');
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/cancel-case-data', [
            'case_tag'=>'2',
            'reason' => '*********',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'reason' => 'The reason field format is invalid.',
        ]);
    }

    /**
     * Test successful cancel case form with empty data
     * @return void
     */
    public function test_cancel_case_with_empty_data()
    {
        $requestId = RequestTable::where('status', 1)->value('id');
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/cancel-case-data', [
            'case_tag' => '',
            'reason' => '',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'case_tag' => 'The case tag field is required.',
        ]);
    }


    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    // public function test_scheduled_shift_with_valid_data()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/create-shift', [
    //         'region' => '1',
    //         'physician' => '1',
    //         'shiftDate' => '2025-05-20',
    //         'shiftStartTime' => '9:00',
    //         'shiftEndTime' => '11:00',
    //         'is_repeat' => '1',
    //         'checkbox' => [3,4],
    //         'repeatEnd' => '3',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftAdded', 'Shift Added Successfully');
    // }

    /**
     * Test successful schedule shift with invalid data
     * @return void
     */
    public function test_scheduled_shift_with_invalid_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/create-shift', [
            'region' => '1',
            'physician' => '1',
            'shiftDate' => '1-04-2024',
            'shiftStartTime' => '10:00',
            'shiftEndTime' => '9:00',
            'is_repeat' => '1',
            'checkbox' => '1',
            'repeatEnd' => '2',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'shiftDate' => 'The shift date field must be a date after yesterday.',
            'shiftEndTime' => 'The shift end time field must be a date after shift start time.',
        ]);
    }

    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    public function test_scheduled_shift_with_empty_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/create-shift', [
            'region' => '',
            'physician' => '',
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'region' => 'The region field is required.',
            'physician' => 'The physician field is required.',
            'shiftEndTime' => 'The shift end time field is required.',
        ]);
    }


    public function test_view_admin_schedule_calendar()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->get("/scheduling");

        $response->assertStatus(Response::HTTP_OK);
    }




    /**
     * admin submit request with valid data
     * @return void
     */
    // public function test_admin_submit_request_with_valid_data(): void
    // {
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/admin-submitted-requests', [
    //         'first_name' => 'otto',
    //         'last_name' => 'garrate',
    //         'date_of_birth' => '2004-12-12',
    //         'email' => fake()->unique()->email(),
    //         'phone_number' => '1234567880',
    //         'street' => 'billionaires row',
    //         'city' => 'manhattan',
    //         'state' => 'new york',
    //         'zip' => '345678',
    //         'room' => '1',
    //         'adminNote' => 'efgijhfnj',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.status', 'new')->assertSessionHas('successMessage', 'Email for Create Account is Sent and Request is Submitted');
    // }


    /**
     * admin submit request with valid data
     * @return void
     */
    // public function test_admin_submit_request_with_valid_data_and_existing_email(): void
    // {
    //     $email = Users::first()->email;
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/admin-submitted-requests', [
    //         'first_name' => 'otto',
    //         'last_name' => 'garrate',
    //         'date_of_birth' => '2004-12-12',
    //         'email' => $email,
    //         'phone_number' => '1234567880',
    //         'street' => 'billionaires row',
    //         'city' => 'manhattan',
    //         'state' => 'new york',
    //         'zip' => '345678',
    //         'room' => '1',
    //         'adminNote' => 'efgijhfnj',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.status', 'new')->assertSessionHas('successMessage', 'Request is Submitted');
    // }


    /**
     * admin submit request with invalid data
     * @return void
     */
    public function test_admin_submit_request_with_invalid_data(): void
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin-submitted-requests', [
            'first_name' => 'Phillip43543',
            'last_name' => 'Ruiz43534',
            'date_of_birth' => '2030-12-12',
            'email' => 'nezyryhy@m3543ailinator.com',
            'phone_number' => '1234567890',
            'street' => 'Molestias a3454lias 345345q#%#$%uis',
            'city' => 'Beatae doloru35453m nobismanha434ttan',
            'state' => 'Commodo incidu54354nt be',
            'zip' => '7709715435',
            'room' => '654654',
            'adminNote' => 'Nemo impedit dolore435435435$#%#$$%$%#$%435',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zip' => 'Please enter 6 digits zipcode',
            'room' => 'Maximum 4 digits are allowed in room number',
            'adminNote' => 'Please enter valid notes. notes should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ]);
    }


    /**
     * admin submit request with empty data
     * @return void
     */
    public function test_admin_submit_request_with_empty_data(): void
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin-submitted-requests', [
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
        ]);
    }
}
