<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Users;
use App\Models\Provider;
use App\Models\UserRoles;
use App\Models\ShiftDetail;
use App\Models\RequestTable;
use Symfony\Component\HttpFoundation\Response;

class ProvidersTest extends TestCase
{

    private function provider(){
        $userId = UserRoles::where('role_id', 2)->value('user_id');
        $provider = User::where('id', $userId)->first();

        return $provider;
    }
    /**
     * Test successful send link form submission with valid data.
     *
     * @return void
     */
    // public function test_send_link_form_with_valid_data()
    // {
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/send-link', [
    //         'first_name' => 'shivesh',
    //         'last_name' => 'surani',
    //         'email' => 'shivesh@mail.com',
    //         'phone_number' => '1234567890',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('successMessage', 'Link Sent Successfully!');
    // }


    /**
     *  Test successful send link form submission with empty_fields
     * @return void
     */

    public function test_send_link_form_with_empty_fields()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/send-link', [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone_number' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'phone_number' => 'The phone number field is required.',
            'email' => 'The email field is required.',
        ]);
    }

    /**
     * Test successful send link form submission with invalid characters.
     *
     * @return void
     */
    public function test_send_link_form_with_invalid_characters()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/send-link', [
            'first_name' => '1bfggbfg23!',
            'last_name' => 'su545rani',
            'email' => 'shivesh@ma444il.com',
            'phone_number' => '1234567890',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'email' => 'The email field format is invalid.',
        ]);
    }


    /**
     * Test successful create request with valid data and existing email
     * @return void 
     */

    // public function test_create_request_form_with_valid_data_and_existing_email()
    // {
    //     $email = Users::first()->email;
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-request', [
    //         'first_name' => 'Xantha',
    //         'last_name' => 'Dorsey',
    //         'date_of_birth' => '2002-02-20',
    //         'email' => $email,
    //         'phone_number' => '+1 265-494-9775',
    //         'street' => 'Reiciendis tempore ',
    //         'city' => 'In aliquid ut quia a',
    //         'state' => 'Provident numquam a',
    //         'zip' => '440723',
    //         'room' => '4',
    //         'note' => 'Rerum quibusdam aute',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('provider.status', 'pending')->assertSessionHas('successMessage','Request is Submitted');
    // }

    /**
     * Test successful create request with valid data
     * @return void 
     */

    // public function test_create_request_form_with_valid_data()
    // {
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-request', [
    //         'first_name' => 'Xantha',
    //         'last_name' => 'Dorsey',
    //         'date_of_birth' => '2002-02-20',
    //         'email' => fake()->unique()->email(),
    //         'phone_number' => '+1 265-494-9775',
    //         'street' => 'Reiciendis tempore ',
    //         'city' => 'In aliquid ut quia a',
    //         'state' => 'Provident numquam a',
    //         'zip' => '440723',
    //         'room' => '4',
    //         'note' => 'Rerum quibusdam aute',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('provider.status', 'pending')->assertSessionHas('successMessage', 'Email for Create Account is Sent and Request is Submitted');
    // }


    /**
     * Test successful create request with empty data
     * @return void
     */
    public function test_create_request_form_with_empty_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-request', [
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter first name.',
            'last_name' => 'Please enter last name.',
            'email' => 'Please enter email.',
            'phone_number' => 'Please enter phone number.',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
        ]);
    }

    /**
     * Test successful create request with invalid data
     * @return void
     */

    public function test_create_request_form_with_invalid_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-request', [
            'first_name' => '21343243',
            'last_name' => '4543',
            'email' => 'shivesh@454mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => 'dcdg%$%#$%f',
            'city' => 'dfgedgf34',
            'state' => 'dfgedgf34',
            'zip' => '',
            'room' => '',
            'note' => '%$#%$%##',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'email' => 'The email field format is invalid.',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'note' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ]);
    }

    /**
     * Test successful create request to admin with valid data
     * @return void
     */
    // public function test_send_request_to_admin_with_valid_data()
    // {
    //     $providerId = Provider::first()->id;
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-edit-profile', [
    //         "message" => 'hello admin',
    //         "providerId" => $providerId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('mailSentToAdmin', 'Email Sent to Admin - to make requested changes!');
    // }


    /**
     * Test successful create request to admin with invalid data
     * @return void
     */
    public function test_send_request_to_admin_with_invalid_data()
    {
        $providerId = Provider::first()->id;
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-edit-profile', [
            "message" => '#$%^^%^%$',
            "providerId" => $providerId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'message' => 'The message field format is invalid.',    
        ]);
    }

    /**
     * Test successful create request to admin with empty data
     * @return void
     */

    public function test_send_request_to_admin_with_empty_data()
    {
        $providerId = Provider::first()->id;
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-edit-profile', [
            "message" => '',
            "providerId" => $providerId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'message' => 'The message field is required.',
        ]);
    }

    /**
     * Test successful transfer case to admin with valid data
     * @return void
     */
    // public function test_transfer_case_to_admin_with_valid_data()
    // {
    //     $requestId = RequestTable::where('status', 3)->value('id');
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/transfer-case', [
    //         "notes" => 'transfer back',
    //         "requestId" => $requestId
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND);
    // }

    /**
     * Test successful transfer case to admin with invalid data
     * @return void
     */
    public function test_transfer_case_to_admin_with_invalid_data()
    {
        $requestId = RequestTable::where('status', 3)->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/transfer-case', [
            "notes" => '$#%$%#%$',
            "requestId" => $requestId
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'notes' => 'The notes field format is invalid.',
        ]);
    }

    /**
     * Test successful create request to admin with empty data
     * @return void
     */

    public function test_transfer_case_to_admin_with_empty_data()
    {
        $requestId = RequestTable::where('status', 3)->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/transfer-case', [
            "notes" => '',
            "requestId" => $requestId
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'notes' => 'The notes field is required.',
        ]);
    }


    /**
     * Test successful encounter form with valid data
     * @return void
     */
    // public function test_encounter_form_valid_data()
    // {
    //     $todayDate = now()->toDateString();
    //     $requestId = RequestTable::whereIn('status', [4,5])->value('id');
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/medical-form', [
    //         "first_name" => 'Xenos',
    //         "last_name" => 'Oneill',
    //         "location" => 'Ut non repellendus ',
    //         "date_of_birth" => '2001-12-12',
    //         "service_date" => $todayDate,
    //         "mobile" => '+1 601-603-4829',
    //         "email" => fake()->unique()->email(),
    //         "present_illness_history" => 'Sint dolores nostru',
    //         "medical_history" => 'A dolorum iusto exce',
    //         "medications" => 'Laboris doloribus te',
    //         "allergies" => 'Maxime nulla non et ',
    //         "temperature" => '40',
    //         "heart_rate" => '48',
    //         "repository_rate" => '25',
    //         "sis_BP" => '68',
    //         "dia_BP" => '51',
    //         "oxygen" => '75',
    //         "pain" => 'Adipisicing repellen',
    //         "heent" => 'Ullam cum qui nulla ',
    //         "cv" => 'Excepturi accusantiu',
    //         "chest" => 'Consequatur volupta',
    //         "abd" => 'Ea aut soluta fugit',
    //         "extr" => 'Esse et aliquid qui',
    //         "skin" => 'Pariatur Rerum iure',
    //         "neuro" => 'Hic ad est veniam e',
    //         "other" => 'Ut id quibusdam quib',
    //         "diagnosis" => 'Sed et veritatis nob',
    //         "treatment_plan" => 'Voluptas cupidatat t',
    //         "medication_dispensed" => 'Aut ut consequuntur ',
    //         "procedure" => 'Ullamco doloribus ip',
    //         "followUp" => 'Ipsum tenetur possim',
    //         "request_id" => $requestId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('encounterChangesSaved', 'Your changes have been Successfully Saved');
    // }


    /**
     * Test successful encounter form with empty data
     * @return void
     */
    public function test_encounter_form_with_empty_data()
    {
        $requestId = RequestTable::whereIn('status', [4, 5])->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/medical-form', [
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
            "request_id" => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'location' => 'The location field is required.',
            'date_of_birth' => 'The date of birth field is required.',
            'service_date' => 'The service date field is required.',
            'mobile' => 'The mobile field is required.',
            'email' => 'The email field is required.',
            'allergies' => 'The allergies field is required.',
            'treatment_plan' => 'The treatment plan field is required.',
            'medication_dispensed' => 'The medication dispensed field is required.',
            'procedure' => 'The procedure field is required.',
            'followUp' => 'The follow up field is required.',
        ]);
    }

    /**
     * Test successful encounter form with valid data
     * @return void
     */
    public function test_encounter_form_invalid_data()
    {
        $todayDate = now()->toDateString();
        $requestId = RequestTable::whereIn('status', [4,5])->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/medical-form', [
            "first_name" => 'Maxine2',
            "last_name" => 'Bowen324',
            "location" => 'Dolorum corporis nos@%$%',
            "date_of_birth" => '2090-12-12',
            "service_date" => $todayDate,
            "mobile" => '+1 882-148-3794',
            "email" => 'wuha@maili4354nator.com',
            "present_illness_history" => 'Repudiandae accusant#$%$',
            "medical_history" => 'Vero anim dolor recu%$#%',
            "medications" => 'Expedita quis rem se%$#%',
            "allergies" => 'Reiciendis sit maxi$%#',
            "temperature" => '300',
            "heart_rate" => '300',
            "repository_rate" => '300',
            "sis_BP" => '66',
            "dia_BP" => '45',
            "oxygen" => '300',
            "pain" => 'Nulla sed eligendi e%$#%3',
            "heent" => 'Aut maxime officia m$#%',
            "cv" => 'Totam nulla et enim 2#@',
            "chest" => 'Et sint sint lorem p==',
            "abd" => 'Anim aperiam asperio======0',
            "extr" => 'Sit itaque pariatur35%$^',
            "skin" => 'Dolore qui fugit es%!@#^',
            "neuro" => 'Beatae temporibus la%$^',
            "other" => 'Id ipsam ea quae por%$^',
            "diagnosis" => 'Expedita ratione con%^46',
            "treatment_plan" => 'Ex sequi autem tenet%$^',
            "medication_dispensed" => 'Velit minim unde at$%^5',
            "procedure" => 'In elit vitae delec%^$%6',
            "followUp" => 'Eos totam temporibu%%$^',
            "request_id" => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'location' => 'The location field format is invalid.',
            'date_of_birth' => 'The date of birth field must be a date before today.',
            'service_date' => 'The service date field must be a date before tommorow.',
            'email' => 'The email field format is invalid.',
            'present_illness_history' => 'The present illness history field format is invalid.',
            'medical_history' => 'The medical history field format is invalid.',
            'medications' => 'The medications field format is invalid.',
            'allergies' => 'The allergies field format is invalid.',
            'temperature' => 'The temperature field must not be greater than 50.',
            'heart_rate' => 'The heart rate field must not be greater than 220.',
            'repository_rate' => 'The repository rate field must not be greater than 40.',
            'oxygen' => 'The oxygen field must not be greater than 100.',
            'pain' => 'The pain field format is invalid.',
            'heent' => 'The heent field format is invalid.',
            'cv' => 'The cv field format is invalid.',
            'chest' => 'The chest field format is invalid.',
            'abd' => 'The abd field format is invalid.',
            'extr' => 'The extr field format is invalid.',
            'skin' => 'The skin field format is invalid.',
            'other' => 'The other field format is invalid.',
            'neuro' => 'The neuro field format is invalid.',
            'diagnosis' => 'The diagnosis field format is invalid.',
            'treatment_plan' => 'The treatment plan field format is invalid.',
            'medication_dispensed' => 'The medication dispensed field format is invalid.',
            'procedure' => 'The procedure field format is invalid.',
            'followUp' => 'The follow up field format is invalid.',
        ]);
    }


    /**
     * Test successful send order form with valid data
     * @return void
     */

    // public function test_send_order_with_valid_data()
    // {
    //     $requestId = RequestTable::whereIn('status', [4,5])->value('id');
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-send-order', [
    //         'prescription' => 'Voluptatum anim elig',
    //         'requestId' => $requestId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    // }

    /**
     * Test successful send order form with invalid data
     * @return void
     */
    public function test_send_order_with_invalid_data()
    {
        $requestId = RequestTable::whereIn('status', [4, 5])->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-send-order', [
            'profession' => '4',
            'vendor_id' => '8',
            'prescription' => 'Molestiae doloribus $%@#$#@434',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'prescription' => 'The prescription field format is invalid.',
        ]);
    }

    /**
     * Test successful send order form with empty data
     * @return void
     */
    public function test_send_order_with_empty_data()
    {
        $requestId = RequestTable::whereIn('status', [4, 5])->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-send-order', [
            'profession' => '',
            'vendor_id' => '',
            'prescription' => '',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'profession' => 'The profession field is required.',
            'vendor_id' => 'The vendor id field is required.',
            'prescription' => 'The prescription field is required.',
        ]);
    }


    /**
     * Test successful view notes form with valid data
     * @return void
     */
    // public function test_view_notes_with_valid_data()
    // {
    //     $requestId = RequestTable::where('status', 3)->value('id');
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider/view/notes/store', [
    //         'physician_note' => 'Physician Notes',
    //         'requestId' => $requestId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND);
    // }

    /**
     * Test successful view notes form with empty data
     * @return void
     */
    public function test_view_notes_with_empty_data()
    {
        $requestId = RequestTable::where('status', 3)->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider/view/notes/store', [
            'physician_note' => '',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'physician_note' => 'The physician note field is required.',
        ]);
    }

    /**
     * Test successful view notes form with invalid data
     * @return void
     */
    public function test_view_notes_with_invalid_data()
    {
        $requestId = RequestTable::where('status', 3)->value('id');
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider/view/notes/store', [
            'physician_note' => '#$%$#%$%',
            'requestId' => $requestId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'physician_note' => 'The physician note field format is invalid.',
        ]);
    }



    /**
     * Test successful reset password in my profile with valid data
     * @return void
     */
    // public function test_reset_password_in_my_profile_with_valid_data()
    // {
    //     $providerId = Provider::first()->id;
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-reset-password', [
    //         'password' => 'doctor@gmail.com',
    //         'providerId' => $providerId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('success', 'Password Reset Successfully');
    // }


    /**
     * Test successful reset password in my profile with invalid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_invalid_data()
    {
        $providerId = Provider::first()->id;
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-reset-password', [
            'password' => 'do54',
            'providerId' => $providerId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'password' => 'The password field must be at least 8 characters.',
        ]);
    }


    /**
     * Test successful reset password in my profile with empty data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_empty_data()
    {
        $providerId = Provider::first()->id;
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-reset-password', [
            'password' => '',
            'providerId' => $providerId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'password' => 'The password field is required.',
        ]);
    }


    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    // public function test_schedule_shift_with_valid_data()
    // {
    //     $faker = Factory::create();
    //     $fakeDate = $faker->dateTimeBetween('+3 week', '+3 month');
    //     $extractedDate = $fakeDate->format('Y-m-d');

    //     $providerId = Provider::first()->id;
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-create-shift', [
    //         'region' => '1',
    //         'shiftDate' => $extractedDate,
    //         'shiftStartTime' => '22:00',
    //         'shiftEndTime' => '23:00',
    //         'checkbox' => [1,2],
    //         'is_repeat' => '1',
    //         'repeatEnd' => '2',
    //         'providerId' => $providerId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftAdded', 'Shift Added Successfully');
    // }


    /**
     * Test successful existing schedule shift with valid data 
     * @return void
     */
    // public function test_schedule_shift_valid_data_existing_shift()
    // {            
    //     $shiftDate = ShiftDetail::where('shift_date','>',now())->value('shift_date');
        
    //     $providerId = Provider::first()->id;
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-create-shift', [
    //         'region' => '1',
    //         'shiftDate' => $shiftDate,
    //         'shiftStartTime' => '22:00',
    //         'shiftEndTime' => '23:00',
    //         'checkbox' => [1,2],
    //         'is_repeat' => '1',
    //         'repeatEnd' => '2',
    //         'providerId' => $providerId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftOverlap', 'You have an shift during the time period you provided');
    // }


    /**
     * Test successful schedule shift with invalid data
     * @return void
     */
    public function test_schedule_shift_with_invalid_data()
    {
        $time = now()->totimeString();
        $beforeNow = date('H:i:s', strtotime($time . '-1 hour'));
        $beforeStartTime = date('H:i:s', strtotime($time . '-2 hour'));

        $providerId = Provider::first()->id;
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-create-shift', [
            'region' => '1',
            'shiftDate' => '11-05-2024',
            'shiftStartTime' => $beforeNow,
            'shiftEndTime' => $beforeStartTime,
            'checkbox' => '',
            'repeatEnd' => '',
            'providerId' => $providerId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'shiftDate' => 'The shift date field must be a date after yesterday.',
            'shiftStartTime' => 'The shift start time field must be a date after now.',
            'shiftEndTime' => 'The shift end time field must be a date after shift start time.',
        ]);
    }


    /**
     * Test successful schedule shift with empty data
     * @return void
     */
    public function test_schedule_shift_with_empty_data()
    {
        $providerId = Provider::first()->id;
        $provider = $this->provider();
        $response = $this->actingAs($provider)->postJson('/provider-create-shift', [
            'region' => '',
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
            'checkbox' => '',
            'repeatEnd' => '',
            'providerId' => $providerId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'shiftDate' => 'The shift date field is required.',
            'shiftEndTime' => 'The shift end time field is required.',
        ]);
    }


    /**
     * Test successful edit scheduled shift with valid data
     * @return void
     */
    // public function test_edit_scheduled_shift_with_valid_data()
    // {
    //     $todayDate = now();
    //     $shiftDate = ShiftDetail::where('shift_date', '>', $todayDate)->value('shift_date');
    //     $shiftId = ShiftDetail::where('shift_date','>', $todayDate)->value('shift_id');
    //     $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');

    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-edit-shift', [
    //         'shiftDate' => $shiftDate,
    //         'shiftTimeStart' => '22:00',
    //         'shiftTimeEnd' => '23:00',
    //         'shiftDetailId' => $shiftDetailId,
    //         'action'=> 'save'
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftEdited', 'Shift Edited Successfully!');
    // }


    /**
     * Test successful edit scheduled shift with invalid data
     * @return void
     */

    //  * enable validation in providerschedulingcontroller  providerEditShift().
    // public function test_edit_scheduled_shift_with_invalid_data()
    // {
    //     $time = now()->totimeString();
    //     $beforeNow = date('H:i:s', strtotime($time . '-1 hour'));
    //     $beforeStartTime = date('H:i:s', strtotime($time . '-2 hour'));

    //     $todayDate = now();
    //     $shiftDate = ShiftDetail::where('shift_date', '>', $todayDate)->value('shift_date');
    //     $shiftId = ShiftDetail::where('shift_date', '>', $todayDate)->value('shift_id');
    //     $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');

    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-edit-shift', [
    //         'shiftDate' => $shiftDate,
    //         'shiftStartTime' => $beforeNow,
    //         'shiftEndTime' => $beforeStartTime,
    //         'shiftDetailId' => $shiftDetailId,
    //         'action' => 'save'
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonValidationErrors([
    //         'shiftStartTime' => 'The shift start time field must be a date after now.',
    //         'shiftEndTime' => 'The shift end time field must be a date after shift start time.',
    //     ]);
    // }

    /**
     * Test successful delete scheduled shift with valid data
     * @return void
     */
    // public function test_delete_scheduled_shift_with_valid_data()
    // {
    //     $todayDate = now();
    //     $shiftId = ShiftDetail::where('shift_date', '>', $todayDate)->value('shift_id');
    //     $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');

    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-edit-shift', [
    //         'shiftDetailId' => $shiftDetailId,
    //         'shiftId' => $shiftId,
    //         'action' => 'delete'
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftDeleted', 'Shift Deleted Successfully!');
    // }


    /**
     * Test successful edit existing schedule shift with valid data 
     * @return void
     */
    // public function test_edit_schedule_shift_valid_data_existing_shift()
    // {
    //     $shiftDate = ShiftDetail::where('shift_date', '>', now())->value('shift_date');

    //     $todayDate = now();
    //     $shiftId = ShiftDetail::where('shift_date', '>', $todayDate)->value('shift_id');
    //     $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');

    //     $providerId = Provider::first()->id;
    //     $provider = $this->provider();
    //     $response = $this->actingAs($provider)->postJson('/provider-edit-shift', [
    //         'shiftDate' => $shiftDate,
    //         'shiftStartTime' => '22:00',
    //         'shiftEndTime' => '23:00',
    //         'action' => 'save',
    //         'shiftDetailId' => $shiftDetailId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftOverlap', 'You have an shift during the time period you provided');
    // }

}
