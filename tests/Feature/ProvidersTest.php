<?php

namespace Tests\Feature;

use App\Models\Provider;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestType;
use Illuminate\Support\Facades\Crypt;
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
    public function test_send_link_form_with_empty_fields()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-send-mail', [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'email' => 'The email field is required.',
            'phone_number' => 'The phone number field is required.',
        ]);
    }

    /**
     * Test successful send link form submission with invalid data.
     *
     * @return void
     */
    public function test_send_link_form_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-send-mail', [
                'first_name' => '123!@#',
                'last_name' => 'as',
                'email' => 'invalid_email',
                'phone_number' => '12342',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field must only contain letters.',
                'last_name' => 'The last name field must be at least 3 characters.',
                'email' => 'The email field must be a valid email address.',
            ]);
    }

    // provider create request page can be rendered
    public function test_provider_create_request_page_can_be_rendered()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)->get('/create-request-provider');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test successful create request with empty data
     * @return void
     */
    public function test_create_request_form_with_empty_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)
            ->postJson('/provider-request', [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
                'street' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'room' => '',
                'note' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'Please enter first name.',
                'last_name' => 'Please enter last name.',
                'email' => 'Please enter email.',
                'phone_number' => 'Please enter phone number.',
                'street' => 'Please enter street.',
                'city' => 'Please enter city.',
                'state' => 'Please enter state.',
            ]);
    }

    /**
     * Test successful create request with invalid data
     * @return void
     */
    public function test_create_request_form_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-request', [
                'first_name' => '21343243',
                'last_name' => 'sd',
                'email' => 'navdeep@454mail.com',
                'phone_number' => '+1 403-288-7577',
                'street' => 'dcdgf !@#$',
                'city' => 'dfgedgf34 !@#4',
                'state' => 'dfgfg45 !@#4',
                'zipcode' => '8523',
                'room' => '4 !@# ff',
                'symptoms' => 'sddfsfd !@34',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must be at least 3 characters.',
            'email' => 'The email field format is invalid.',
            'street' => 'The street field format is invalid.',
            'city' => 'The city field format is invalid.',
            'state' => 'The state field format is invalid.',
            'zipcode' => 'The zipcode field must have at least 6 digits.',
            'room' => 'The room field must be greater than or equal to 1 characters.',
            'symptoms' => 'The symptoms field format is invalid.',
        ]);
    }

    /**
     * Test successful create request with valid data and existing email
     * @return void
     */
    public function test_create_request_form_with_valid_data_and_existing_email()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-request', [
                'first_name' => 'Navdeep',
                'last_name' => 'Vala',
                'request_type_id' => 1,
                'email' => 'munuv@mailinator.com',
                'date_of_birth' => '2001-10-19',
                'phone_number' => '+1 403-288-7577',
                'street' => 'fgfgef',
                'city' => 'dfgedgf',
                'state' => 'gfdgfd',
                'zip' => '147852',
                'room' => '4',
                'note' => 'sddfsfd',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('provider.status', 'pending')
            ->assertSessionHas('successMessage', 'Request Created Successfully!');
    }

    /**
     * Test successful create request with valid data and new email
     * @return void
     */
    public function test_create_request_form_with_valid_data_and_new_email()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-request', [
                'first_name' => 'Navdeep',
                'last_name' => 'Vala',
                'request_type_id' => 1,
                'email' => fake()->unique()->email(),
                'date_of_birth' => '2001-10-19',
                'phone_number' => '+1 403-288-7577',
                'street' => 'fgfgef',
                'city' => 'dfgedgf',
                'state' => 'gfdgfd',
                'zip' => '147852',
                'room' => '4',
                'note' => 'sddfsfd',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('provider.status', 'pending')
            ->assertSessionHas('successMessage', 'Email for create account is sent & request created successfully!');
    }

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
    public function test_send_request_to_admin_with_empty_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-edit-profile', [
                "message" => ''
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'message' => 'The message field is required.',
            ]);
    }

    /**
     * Test successful create request to admin with invalid data
     * @return void
     */
    public function test_send_request_to_admin_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-edit-profile', [
                "message" => '!@#$!@#$'
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'message' => 'The message field format is invalid.',
            ]);
    }

    /**
     * Test successful transfer case to admin with valid data
     * @return void
     */
    public function test_transfer_case_to_admin_with_valid_data()
    {
        $provider = $this->provider();

        $providerId = $provider->id;

        $requestId = RequestTable::where('status', 3)->value('id');

        $response = $this->actingAs($provider)
            ->postJson('/transfer-case', [
                'requestId' => $requestId,
                'providerId' => $providerId,
                "notes" => 'transfer back'
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Case Transferred to Admin');
    }

    /**
     * Test successful transfer case to admin with invalid data
     * @return void
     */
    public function test_transfer_case_to_admin_with_invalid_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)
            ->postJson('/transfer-case', [
                "notes" => '!@#$!@#$'
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'notes' => 'The notes field format is invalid'
        ]);
    }

    /**
     * Test successful create request to admin with empty data
     * @return void
     */
    public function test_transfer_case_to_admin_with_empty_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/transfer-case', [
                "notes" => ''
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'notes' => 'The notes field is required.'
        ]);
    }

    // provider encounter form page can be rendered
    public function test_provider_encounter_form_can_be_rendered()
    {
        $provider = $this->provider();

        $id = RequestTable::whereIn('status', [4, 5, 6])->value('id');

        $response = $this->actingAs($provider)->get('/encounter-form/{' . $id . '}');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test successful encounter form with valid data
     * @return void
     */
    public function test_provider_encounter_form_with_valid_data()
    {
        $provider = $this->provider();

        $id = RequestTable::whereIn('status', [4, 5, 6])->value('id');

        $response = $this->actingAs($provider)
            ->postJson('/medical-form', [
                'requestId' => $id,
                "first_name" => 'Xenos',
                "last_name" => 'Oneill',
                "location" => 'Ut non repellendus ',
                "date_of_birth" => '2017-01-25',
                "service_date" => '2024-05-15',
                "mobile" => '+1 601-603-4829',
                "email" => 'ryhobeluxe@mailinator.com',
                "present_illness_history" => 'Sint dolores nostru',
                "medical_history" => 'A dolorum iusto exce',
                "medications" => 'Laboris doloribus te',
                "allergies" => 'Maxime nulla non et ',
                "temperature" => '40',
                "heart_rate" => '48',
                "repository_rate" => '25',
                "sis_BP" => '68',
                "dia_BP" => '51',
                "oxygen" => '75',
                "pain" => 'Adipisicing repellen',
                "heent" => 'Ullam cum qui nulla ',
                "cv" => 'Excepturi accusantiu',
                "chest" => 'Consequatur volupta',
                "abd" => 'Ea aut soluta fugit',
                "extr" => 'Esse et aliquid qui',
                "skin" => 'Pariatur Rerum iure',
                "neuro" => 'Hic ad est veniam e',
                "other" => 'Ut id quibusdam quib',
                "diagnosis" => 'Sed et veritatis nob',
                "treatment_plan" => 'Voluptas cupidatat t',
                "medication_dispensed" => 'Aut ut consequuntur',
                "procedure" => 'Ullamco doloribus ip',
                "followUp" => 'Ipsum tenetur possim',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('encounterChangesSaved', 'Your changes have been Successfully Saved');
    }


    /**
     * Test successful encounter form with empty data
     * @return void
     */
    public function test_provider_encounter_form_with_empty_data()
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
     * Test successful encounter form with invalid data
     * @return void
     */
    public function test_provider_encounter_form_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/medical-form', [
                "first_name" => 'as',
                "last_name" => '1234!@',
                "location" => '1234 !@#$',
                "date_of_birth" => '1889-10-12',
                "service_date" => '2025-03-15',
                "mobile" => '123456789',
                "email" => 'asdf!@#$@1234.1234',
                "present_illness_history" => '!@#$@#4',
                "medical_history" => '123',
                "medications" => '!@#$@#4',
                "allergies" => '!@#$@',
                "temperature" => '!!@#$',
                "heart_rate" => '123',
                "repository_rate" => '12312',
                "sis_BP" => '12312',
                "dia_BP" => '24234',
                "oxygen" => '23',
                "pain" => '123 1@#$@',
                "heent" => '12312 !@$@#',
                "cv" => '!@$@#',
                "chest" => '!@#$@',
                "abd" => '!@$@#4',
                "extr" => '!@$@#',
                "skin" => '!@$2',
                "neuro" => '!@#$2',
                "other" => '!@$@!34',
                "diagnosis" => '!@$@#',
                "treatment_plan" => '!@#$@',
                "medication_dispensed" => '!@$@3',
                "procedure" => '!@#$@',
                "followUp" => '!@$2',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                "first_name" => 'The first name field must be at least 3 characters.',
                "last_name" => 'The last name field must only contain letters.',
                "location" => 'The location field format is invalid.',
                "date_of_birth" => 'The date of birth field must be a date after Jan 01 1900.',
                "service_date" => 'The service date field must be a date before tomorrow.',
                "email" => 'The email field must be a valid email address.',
                "treatment_plan" => 'The treatment plan field format is invalid.',
                "medication_dispensed" => 'The medication dispensed field format is invalid.',
                "procedure" => 'The procedure field format is invalid.',
                "followUp" => 'The follow up field must be at least 5 characters.',
                "present_illness_history" => 'The present illness history field format is invalid.',
                "medical_history" => 'The medical history field must be at least 5 characters.',
                "medications" => 'The medications field format is invalid.',
                "temperature" => 'The temperature field must be a number.',
                "repository_rate" => 'The repository rate field must not be greater than 40.',
                "sis_BP" => 'The sis  b p field must not be greater than 250.',
                "dia_BP" => 'The dia  b p field must not be greater than 250.',
                'oxygen' => 'The oxygen field must be at least 70.',
                'pain' => 'The pain field format is invalid.',
                'heent' => 'The heent field format is invalid.',
                'cv' => 'The cv field format is invalid.',
                'chest' => 'The chest field format is invalid.',
                'abd' => 'The abd field format is invalid.',
                'extr' => 'The extr field format is invalid.',
                'skin' => 'The skin field must be at least 5 characters.',
                'neuro' => 'The neuro field format is invalid.',
                'other' => 'The other field format is invalid.',
                'diagnosis' => 'The diagnosis field format is invalid.',
            ]);
    }

    // provider send order page can be rendered
    public function test_provider_send_order_page_can_be_rendered()
    {
        $provider = $this->provider();

        $requestId = RequestTable::whereIn('status', [4, 5])->value('id');

        $id = Crypt::encrypt($requestId);
        $response = $this->actingAs($provider)->get('/view-order/{' . $id . '}');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test successful send order form with valid data
     * @return void
     */
    public function test_send_order_with_valid_data()
    {
        $provider = $this->provider();

        $requestId = RequestTable::whereIn('status', [4, 5])->value('id');

        $response = $this->actingAs($provider)
            ->postJson('/provider-send-order', [
                'request_id' => $requestId,
                'profession' => 1,
                'vendor_id' => 7,
                'prescription' => 'Voluptatum anim elig',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('provider.status', 'active')
            ->assertSessionHas('successMessage', 'Order Created Successfully!');
    }

    /**
     * Test successful send order form with invalid data
     * @return void
     */
    public function test_send_order_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-send-order', [
                'profession' => 'asd',
                'vendor_id' => 'asdf',
                'prescription' => 'Molestiae doloribus $%@#$#@434',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'profession' => 'The profession field must be a number.',
                'vendor_id' => 'The vendor id field must be a number.',
                'prescription' => 'The prescription field format is invalid.',
            ]);
    }

    /**
     * Test successful send order form with empty data
     * @return void
     */
    public function test_send_order_with_empty_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-send-order', [
                'profession' => '',
                'vendor_id' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'profession' => 'The profession field is required.',
            'vendor_id' => 'The vendor id field is required.',
        ]);
    }

    // provider view case page can be rendered
    public function test_provider_view_case_page_can_be_rendered()
    {
        $provider = $this->provider();
        $providerId = Provider::where('user_id', $provider->id)->value('id');
        $requestId = RequestTable::where('physician_id', $providerId)->whereIn('status', [1, 3, 4, 5, 6])->value('id');
        $id = Crypt::encrypt($requestId);

        $response = $this->actingAs($provider)->get('provider/view/case/{' . $id . '}');
        $response->assertStatus(Response::HTTP_OK);
    }

    // provider view notes page can be rendered
    public function test_provider_view_notes_page_can_be_rendered()
    {
        $provider = $this->provider();

        $providerId = Provider::where('user_id', $provider->id)->value('id');
        $requestId = RequestTable::where('physician_id', $providerId)->value('id');
        $id = Crypt::encrypt($requestId);

        $response = $this->actingAs($provider)->get('/provider/view/notes/{' . $id . '}');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test successful view notes form with valid data
     * @return void
     */
    public function test_view_notes_with_valid_data()
    {
        $provider = $this->provider();

        $providerId = Provider::where('user_id', $provider->id)->value('id');
        $requestId = RequestTable::where('physician_id', $providerId)->value('id');

        $response = $this->actingAs($provider)
            ->postJson('/provider/view/notes/store', [
                'requestId' => $requestId,
                'physician_note' => 'Physician Notes',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('providerNoteAdded', 'Your Note Successfully Added');
    }

    /**
     * Test successful view notes form with empty data
     * @return void
     */
    public function test_view_notes_with_empty_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider/view/notes/store', [
                'physician_note' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'physician_note' => 'The physician note field is required.'
            ]);
    }

    /**
     * Test successful view notes form with invalid data
     * @return void
     */
    public function test_view_notes_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider/view/notes/store', [
                'physician_note' => '#$%$%',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'physician_note' => 'The physician note field format is invalid.'
            ]);
    }

    // provider profile page can be rendered
    public function test_provider_profile_page_can_be_rendered()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)->get('/profile');

        $response->assertStatus(Response::HTTP_OK);
    }


    /**
     * Test successful reset password in my profile with valid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_valid_data()
    {
        $provider = $this->provider();

        $providerId = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($provider)
            ->postJson('/provider-reset-password', [
                'providerId' => $providerId,
                'password' => 'physician1@mail.com',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('success', 'Password Reset Successfully');
    }


    /**
     * Test successful reset password in my profile with invalid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_invalid_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-reset-password', [
                'password' => 'do54',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password' => 'The password field must be at least 5 characters.',
            ]);
    }


    /**
     * Test successful reset password in my profile with empty data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_empty_data()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)
            ->postJson('/provider-reset-password', [
                'password' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password' => 'The password field is required.',
            ]);
    }
}
