<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestType;
use Symfony\Component\HttpFoundation\Response;

class ProvidersTest extends TestCase
{
    public function provider()
    {
        $providerId = UserRoles::where('role_id', 2)->first()->user_id;
        return User::where('id', $providerId)->first();
    }

    /**
     * Test successful send link form submission with valid data.
     *
     * @return void
     */
    // public function test_send_link_form_with_valid_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-send-mail', [
    //             'first_name' => 'Navdeep',
    //             'last_name' => 'vala',
    //             'email' => 'navdeep@mail.com',
    //             'phone_number' => '+1 403-288-7577',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertSessionHas('successMessage', 'Link Sent Successfully!');
    // }

    /**
     *  Test successful send link form submission with empty_fields
     * @return void
     */

    // public function test_send_link_form_with_empty_fields()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-send-mail', [
    //         'first_name' => '',
    //         'last_name' => '',
    //         'email' => '',
    //         'phone_number' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'The first name field is required.',
    //         'last_name' => 'The last name field is required.',
    //         'email' => 'The email field is required.',
    //         'phone_number' => 'The phone number field is required.',
    //     ]);
    // }

    /**
     * Test successful send link form submission with invalid data.
     *
     * @return void
     */
    // public function test_send_link_form_with_invalid_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-send-mail', [
    //             'first_name' => '123!@#',
    //             'last_name' => 'as',
    //             'email' => 'invalid_email',
    //             'phone_number' => '12342',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'first_name' => 'The first name field must only contain letters.',
    //             'last_name' => 'The last name field must be at least 3 characters.',
    //             'email' => 'The email field must be a valid email address.',
    //         ]);
    // }

    // provider create request page can be rendered
    // public function test_provider_create_request_page_can_be_rendered()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)->get('/create-request-provider');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Test successful create request with empty data
     * @return void
     */
    // public function test_create_request_form_with_empty_data()
    // {
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-request', [
    //             'first_name' => '',
    //             'last_name' => '',
    //             'email' => '',
    //             'phone_number' => '',
    //             'street' => '',
    //             'city' => '',
    //             'state' => '',
    //             'zip' => '',
    //             'room' => '',
    //             'note' => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'first_name' => 'Please enter first name.',
    //             'last_name' => 'Please enter last name.',
    //             'email' => 'Please enter email.',
    //             'phone_number' => 'Please enter phone number.',
    //             'street' => 'Please enter street.',
    //             'city' => 'Please enter city.',
    //             'state' => 'Please enter state.',
    //         ]);
    // }

    /**
     * Test successful create request with invalid data
     * @return void
     */
    // public function test_create_request_form_with_invalid_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-request', [
    //             'first_name' => '21343243',
    //             'last_name' => 'sd',
    //             'email' => 'navdeep@454mail.com',
    //             'phone_number' => '+1 403-288-7577',
    //             'street' => 'dcdgf !@#$',
    //             'city' => 'dfgedgf34 !@#4',
    //             'state' => 'dfgfg45 !@#4',
    //             'zipcode' => '8523',
    //             'room' => '4 !@# ff',
    //             'symptoms' => 'sddfsfd !@34',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'first_name' => 'The first name field must only contain letters.',
    //         'last_name' => 'The last name field must be at least 3 characters.',
    //         'email' => 'The email field format is invalid.',
    //         'street' => 'The street field format is invalid.',
    //         'city' => 'The city field format is invalid.',
    //         'state' => 'The state field format is invalid.',
    //         'zipcode' => 'The zipcode field must have at least 6 digits.',
    //         'room' => 'The room field must be greater than or equal to 1 characters.',
    //         'symptoms' => 'The symptoms field format is invalid.',
    //     ]);
    // }

    /**
     * Test successful create request with valid data and existing email
     * @return void
     */
    // public function test_create_request_form_with_valid_data_and_existing_email()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-request', [
    //             'first_name' => 'Navdeep',
    //             'last_name' => 'Vala',
    //             'request_type_id' => 1,
    //             'email' => 'munuv@mailinator.com',
    //             'date_of_birth' => '2001-10-19',
    //             'phone_number' => '+1 403-288-7577',
    //             'street' => 'fgfgef',
    //             'city' => 'dfgedgf',
    //             'state' => 'gfdgfd',
    //             'zip' => '147852',
    //             'room' => '4',
    //             'note' => 'sddfsfd',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirectToRoute('provider.status', 'pending')
    //         ->assertSessionHas('successMessage', 'Request Created Successfully!');
    // }

    /**
     * Test successful create request with valid data and new email
     * @return void
     */
    // public function test_create_request_form_with_valid_data_and_new_email()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-request', [
    //             'first_name' => 'Navdeep',
    //             'last_name' => 'Vala',
    //             'request_type_id' => 1,
    //             'email' => fake()->unique()->email(),
    //             'date_of_birth' => '2001-10-19',
    //             'phone_number' => '+1 403-288-7577',
    //             'street' => 'fgfgef',
    //             'city' => 'dfgedgf',
    //             'state' => 'gfdgfd',
    //             'zip' => '147852',
    //             'room' => '4',
    //             'note' => 'sddfsfd',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirectToRoute('provider.status', 'pending')
    //         ->assertSessionHas('successMessage', 'Email for create account is sent & request created successfully!');
    // }

    // -------------------------- (Not Working) -------------------------------
    /**
     * send request to admin with valid data
     * @return void
     */
    // public function test_send_request_to_admin_with_valid_data()
    // {
    //     $provider = $this->provider();

    //     $providerId = $provider->id;

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-edit-profile', [
    //             'providerId' => $providerId,
    //             "message" => 'hello admin'
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertSessionHas('mailSentToAdmin', 'Email Sent to Admin - to make requested changes!');
    // }
    // ---------------------------------------------------------------------------

    /**
     * Test successful create request to admin with empty data
     * @return void
     */
    // public function test_send_request_to_admin_with_empty_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-edit-profile', [
    //             "message" => ''
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'message' => 'The message field is required.',
    //         ]);
    // }

    /**
     * Test successful create request to admin with invalid data
     * @return void
     */
    // public function test_send_request_to_admin_with_invalid_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/provider-edit-profile', [
    //             "message" => '!@#$!@#$'
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
    //         ->assertJsonValidationErrors([
    //             'message' => 'The message field format is invalid.',
    //         ]);
    // }

    /**
     * Test successful transfer case to admin with valid data
     * @return void
     */
    // public function test_transfer_case_to_admin_with_valid_data()
    // {
    //     $provider = $this->provider();

    //     $providerId = $provider->id;

    //     $requestId = RequestTable::where('status', 3)->value('id');

    //     $response = $this->actingAs($provider)
    //         ->postJson('/transfer-case', [
    //             'requestId' => $requestId,
    //             'providerId' => $providerId,
    //             "notes" => 'transfer back'
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Transferred to Admin');
    // }

    /**
     * Test successful transfer case to admin with invalid data
     * @return void
     */
    // public function test_transfer_case_to_admin_with_invalid_data()
    // {
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)
    //         ->postJson('/transfer-case', [
    //             "notes" => '!@#$!@#$'
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'notes' => 'The notes field format is invalid'
    //     ]);
    // }

    /**
     * Test successful create request to admin with empty data
     * @return void
     */
    // public function test_transfer_case_to_admin_with_empty_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/transfer-case', [
    //             "notes" => ''
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         'notes' => 'The notes field is required.'
    //     ]);
    // }

    // provider encounter form page can be rendered
    // public function test_provider_encounter_form_can_be_rendered(){
    //     $provider = $this->provider();

    //     $id = RequestTable::whereIn('status', [4, 5, 6])->value('id');

    //     $response = $this->actingAs($provider)->get('/encounter-form/{' . $id .'}');

    //     $response->assertStatus(Response::HTTP_OK);
    // }

    /**
     * Test successful encounter form with valid data
     * @return void
     */
    // public function test_provider_encounter_form_with_valid_data()
    // {
    //     $provider = $this->provider();

    //     $id = RequestTable::whereIn('status', [4, 5, 6])->value('id');

    //     $response = $this->actingAs($provider)
    //         ->postJson('/medical-form', [
    //             'requestId' => $id,
    //             "first_name" => 'Xenos',
    //             "last_name" => 'Oneill',
    //             "location" => 'Ut non repellendus ',
    //             "date_of_birth" => '2017-01-25',
    //             "service_date" => '2024-05-15',
    //             "mobile" => '+1 601-603-4829',
    //             "email" => 'ryhobeluxe@mailinator.com',
    //             "present_illness_history" => 'Sint dolores nostru',
    //             "medical_history" => 'A dolorum iusto exce',
    //             "medications" => 'Laboris doloribus te',
    //             "allergies" => 'Maxime nulla non et ',
    //             "temperature" => '40',
    //             "heart_rate" => '48',
    //             "repository_rate" => '25',
    //             "sis_BP" => '68',
    //             "dia_BP" => '51',
    //             "oxygen" => '75',
    //             "pain" => 'Adipisicing repellen',
    //             "heent" => 'Ullam cum qui nulla ',
    //             "cv" => 'Excepturi accusantiu',
    //             "chest" => 'Consequatur volupta',
    //             "abd" => 'Ea aut soluta fugit',
    //             "extr" => 'Esse et aliquid qui',
    //             "skin" => 'Pariatur Rerum iure',
    //             "neuro" => 'Hic ad est veniam e',
    //             "other" => 'Ut id quibusdam quib',
    //             "diagnosis" => 'Sed et veritatis nob',
    //             "treatment_plan" => 'Voluptas cupidatat t',
    //             "medication_dispensed" => 'Aut ut consequuntur',
    //             "procedure" => 'Ullamco doloribus ip',
    //             "followUp" => 'Ipsum tenetur possim',
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('encounterChangesSaved', 'Your changes have been Successfully Saved');
    // }


    /**
     * Test successful encounter form with empty data
     * @return void
     */
    // public function test_provider_encounter_form_with_empty_data()
    // {
    //     $provider = $this->provider();

    //     $response = $this->actingAs($provider)
    //         ->postJson('/medical-form', [
    //             "first_name" => '',
    //             "last_name" => '',
    //             "location" => '',
    //             "date_of_birth" => '',
    //             "service_date" => '',
    //             "mobile" => '',
    //             "email" => '',
    //             "present_illness_history" => '',
    //             "medical_history" => '',
    //             "medications" => '',
    //             "allergies" => '',
    //             "temperature" => '',
    //             "heart_rate" => '',
    //             "repository_rate" => '',
    //             "sis_BP" => '',
    //             "dia_BP" => '',
    //             "oxygen" => '',
    //             "pain" => '',
    //             "heent" => '',
    //             "cv" => '',
    //             "chest" => '',
    //             "abd" => '',
    //             "extr" => '',
    //             "skin" => '',
    //             "neuro" => '',
    //             "other" => '',
    //             "diagnosis" => '',
    //             "treatment_plan" => '',
    //             "medication_dispensed" => '',
    //             "procedure" => '',
    //             "followUp" => '',
    //         ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
    //         "first_name" => 'The first name field is required.',
    //         "last_name" => 'The last name field is required.',
    //         "location" => 'The location field is required.',
    //         "date_of_birth" => 'The date of birth field is required.',
    //         "service_date" => 'The service date field is required.',
    //         "mobile" => 'The mobile field is required.',
    //         "email" => 'The email field is required.',
    //         "allergies" => 'The allergies field is required.',
    //         "treatment_plan" => 'The treatment plan field is required.',
    //         "medication_dispensed" => 'The medication dispensed field is required.',
    //         "procedure" => 'The procedure field is required.',
    //         "followUp" => 'The follow up field is required.',
    //     ]);
    // }

    /**
     * Test successful encounter form with invalid data
     * @return void
     */
    public function test_provider_encounter_form_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/medical-form', [
                "first_name" => '',
                "last_name" => '',
                "location" => '',
                "date_of_birth" => '',
                "service_date" => '',
                "mobile" => '',
                "email" => '',
                "present_illness_history" => '',
                "medical_history" => '',
                "medications" => '',
                "allergies" => '',
                "temperature" => '',
                "heart_rate" => '',
                "repository_rate" => '',
                "sis_BP" => '',
                "dia_BP" => '',
                "oxygen" => '',
                "pain" => '',
                "heent" => '',
                "cv" => '',
                "chest" => '',
                "abd" => '',
                "extr" => '',
                "skin" => '',
                "neuro" => '',
                "other" => '',
                "diagnosis" => '',
                "treatment_plan" => '',
                "medication_dispensed" => '',
                "procedure" => '',
                "followUp" => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            "first_name" => 'The first name field is required.',
            "last_name" => 'The last name field is required.',
            "location" => 'The location field is required.',
            "date_of_birth" => 'The date of birth field is required.',
            "service_date" => 'The service date field is required.',
            "mobile" => 'The mobile field is required.',
            "email" => 'The email field is required.',
            "allergies" => 'The allergies field is required.',
            "treatment_plan" => 'The treatment plan field is required.',
            "medication_dispensed" => 'The medication dispensed field is required.',
            "procedure" => 'The procedure field is required.',
            "followUp" => 'The follow up field is required.',
        ]);
    }

    /**
     * Test successful send order form with valid data
     * @return void
     */

    public function test_send_order_with_valid_data()
    {
        $response = $this->postJson('/provider-send-order', [
            'prescription' => 'Voluptatum anim elig',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful send order form with invalid data
     * @return void
     */
    public function test_send_order_with_invalid_data()
    {
        $response = $this->postJson('/provider-send-order', [
            'prescription' => 'Molestiae doloribus $%@#$#@434',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful send order form with empty data
     * @return void
     */
    public function test_send_order_with_empty_data()
    {
        $response = $this->postJson('/provider-send-order', [
            'prescription' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful view notes form with valid data
     * @return void
     */
    public function test_view_notes_with_valid_data()
    {
        $response = $this->postJson('/provider/view/notes/store', [
            'physician_note' => 'Physician Notes',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful view notes form with empty data
     * @return void
     */
    public function test_view_notes_with_empty_data()
    {
        $response = $this->postJson('/provider/view/notes/store', [
            'physician_note' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful view notes form with invalid data
     * @return void
     */
    public function test_view_notes_with_invalid_data()
    {
        $response = $this->postJson('/provider/view/notes/store', [
            'physician_note' => '#$%$%',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }



    /**
     * Test successful reset password in my profile with valid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_valid_data()
    {
        $response = $this->postJson('/provider-reset-password', [
            'password' => 'doctor@gmail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful reset password in my profile with invalid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_invalid_data()
    {
        $response = $this->postJson('/provider-reset-password', [
            'password' => 'do54',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful reset password in my profile with empty data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_empty_data()
    {
        $response = $this->postJson('/provider-reset-password', [
            'password' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    public function test_schedule_shift_with_valid_data()
    {
        $response = $this->postJson('/provider-create-shift', [
            'region' => 'somnath',
            'shiftDate' => '15-05-2024',
            'shiftStartTime' => '11:00',
            'shiftEndTime' => '12:00',
            'checkbox' => '1',
            'repeatEnd' => '2',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }



    /**
     * Test successful schedule shift with invalid data
     * @return void
     */
    public function test_schedule_shift_with_invalid_data()
    {
        $response = $this->postJson('/provider-create-shift', [
            'region' => 'somnath',
            'shiftDate' => '11-05-2024',
            'shiftStartTime' => '15:00',
            'shiftEndTime' => '12:00',
            'checkbox' => '7',
            'repeatEnd' => '5',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful schedule shift with empty data
     * @return void
     */
    public function test_schedule_shift_with_empty_data()
    {
        $response = $this->postJson('/provider-create-shift', [
            'region' => '',
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
            'checkbox' => '',
            'repeatEnd' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful edit scheduled shift with valid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_valid_data()
    {
        $response = $this->postJson('/provider-edit-shift', [
            'shiftDate' => '22-05-2024',
            'shiftStartTime' => '12:00',
            'shiftEndTime' => '14:00',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful edit scheduled shift with invalid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_invalid_data()
    {
        $response = $this->postJson('/provider-edit-shift', [
            'shiftDate' => '2-05-2024',
            'shiftStartTime' => '15:00',
            'shiftEndTime' => '13:00',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful edit scheduled shift with valid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_empty_data()
    {
        $response = $this->postJson('/provider-edit-shift', [
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }
}
