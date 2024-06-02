<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Response;

class PatientDashboardTest extends TestCase
{
    public function patient()
    {
        return User::where('email', 'nera1@mailinator.com')->first();
    }

    // patient dashboard homepage can be rendered
    public function test_patient_dashboard_homepage_can_be_rendered()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)->get('/patient/dashboard');

        $response->assertStatus(Response::HTTP_OK);
    }

    // patient profile page can be rendered
    public function test_patient_profile_page_can_be_rendered()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)->get('/patient/profile');

        $response->assertStatus(Response::HTTP_OK);
    }

    // patient map location page can be rendered
    public function test_patient_map_location_page_can_be_rendered()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)->get('/patient/map-location');

        $response->assertStatus(Response::HTTP_OK);
    }

    // patient me request page can be rendered
    public function test_patient_me_request_page_can_be_rendered()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)->get('/patient/submit-requests');

        $response->assertStatus(Response::HTTP_OK);
    }

    // patient someone request page can be rendered
    public function test_patient_somone_request_page_can_be_rendered()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)->get('/patient/submit-someone-requests');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * test patient me request form with valid data
     * @return void
     */
    public function test_patient_me_request_form_with_valid_data()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-patient-requests', [
                'first_name' => 'MeTest',
                'last_name' => 'Case',
                'request_type_id' => '1',
                'date_of_birth' => '1990-02-15',
                'phone_number' => '+1 682-345-7862',
                'street' => 'Culpa vitae vitae e',
                'city' => 'Placeat facilis off',
                'state' => 'Cupiditate voluptas ',
                'zipcode' => '754131',
                'symptoms' => 'Maiores nulla incidi',
                'room' => '93',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('patient.dashboard')
            ->assertSessionHas('message', 'Request is Submitted');
    }

    /**
     * test patient me request form with invalid data
     * @return void
     */
    public function test_patient_me_request_form_with_invalid_data()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-patient-requests', [
                'first_name' => 'as',
                'last_name' => '!@#$!2',
                'request_type_id' => '5',
                'date_of_birth' => '1889-02-15',
                'phone_number' => '+1 682-345-7862',
                'street' => 'Culpa vitae vitae e !@!21',
                'city' => 'Placeat facilis off !@$',
                'state' => 'Cupiditate voluptas !@$#',
                'zipcode' => '75413',
                'symptoms' => 'Maiores nulla incidi !#$',
                'room' => '9312',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field must be at least 3 characters.',
                'last_name' => 'The last name field must only contain letters.',
                'request_type_id' => 'The selected request type id is invalid',
                'date_of_birth' => 'The date of birth field must be a date after Jan 01 1900.',
                'street' => 'The street field format is invalid.',
                'city' => 'The city field format is invalid.',
                'state' => 'The state field format is invalid.',
                'zipcode' => 'The zipcode field must be 6 digits.',
                'symptoms' => 'The symptoms field format is invalid.',
            ]);
    }

    /**
     * test patient me request form with no data
     * @return void
     */
    public function test_patient_me_request_form_with_no_data()
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-patient-requests', [
                'first_name' => '',
                'last_name' => '',
                'request_type_id' => '',
                'date_of_birth' => '',
                'phone_number' => '',
                'street' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
                'symptoms' => '',
                'room' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.',
                'request_type_id' => 'The request type id field is required.',
                'date_of_birth' => 'The date of birth field is required.',
                'phone_number' => 'The phone number field is required.',
                'street' => 'The street field is required.',
                'city' => 'The city field is required.',
                'state' => 'The state field is required.',
                'zipcode' => 'The zipcode field is required.',
            ]);
    }

    /**
     * someone else request with valid data and new email
     * @return void
     */
    public function test_some_one_else_request_with_valid_data_and_new_email(): void
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-someone-requests', [
                'first_name' => 'new',
                'last_name' => 'patient',
                'request_type_id' => '1',
                'email' => fake()->unique()->email(),
                'date_of_birth' => '2010-10-10',
                'phone_number' => '1234567890',
                'street' => '21, new street',
                'city' => 'new city',
                'state' => 'state',
                'zipcode' => '123456',
                'docs' => '',
                'symptoms' => '',
                'room' => '',
                'relation' => 'sister',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('patient.dashboard')
            ->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }

    /**
     * someone else request with valid data and existing email
     * @return void
     */
    public function test_some_one_else_request_with_valid_data_and_existing_email(): void
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-someone-requests', [
                'first_name' => 'new',
                'last_name' => 'patient',
                'request_type_id' => '1',
                'email' => 'nera1@mailinator.com',
                'date_of_birth' => '2010-10-10',
                'phone_number' => '1234567890',
                'street' => '21, new street',
                'city' => 'new city',
                'state' => 'state',
                'zipcode' => '123456',
                'docs' => '',
                'symptoms' => '',
                'room' => '',
                'relation' => 'sister',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('patient.dashboard')
            ->assertSessionHas('message', 'Request is submitted');
    }

    /**
     * someone else request with invalid data
     * @return void
     */
    public function test_some_one_else_request_with_invalid_data(): void
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-someone-requests', [
                'first_name' => 'new123',
                'last_name' => 'patient!@#$!@',
                'request_type_id' => '5',
                'email' => '1234@mail!@#4.com1@#',
                'date_of_birth' => '2050-10-10',
                'phone_number' => '1234567890',
                'street' => '21, new street !@#$',
                'city' => 'new city !@#4',
                'state' => 'state !@#$',
                'zipcode' => '1234',
                'symptoms' => '!@#$!@3',
                'room' => '!@#',
                'relation' => 'sister !@#',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'request_type_id' => 'The selected request type id is invalid.',
            'email' => 'The email field must be a valid email address.',
            'date_of_birth' => 'Date of Birth should not be greater than today',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'Please enter 6 digits zipcode',
            'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
            'room' => 'Please enter room number greater than 0',
            'relation' => 'Please enter relation in valid format(example:alphabets-alphabets or only alphabets)',
        ]);
    }

    /**
     * someone else request with no data
     * @return void
     */
    public function test_some_one_else_request_with_no_data(): void
    {
        $patient = $this->patient();

        $response = $this->actingAs($patient)
            ->postJson('/patient/submitted-someone-requests', [
                'first_name' => '',
                'last_name' => '',
                'request_type_id' => '',
                'email' => '',
                'date_of_birth' => '',
                'phone_number' => '',
                'street' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
                'symptoms' => '',
                'room' => '',
                'relation' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'request_type_id' => 'The request type id field is required.',
            'email' => 'Please enter Email',
            'date_of_birth' => 'Please enter Date of Birth',
            'phone_number' => 'Please enter Phone Number',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
            'zipcode' => 'Please enter 6 digits zipcode',
        ]);
    }
}
