<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Response;

class PatientDashboardTest extends TestCase
{

   /**
    * test someone else request form and not existing email in patient dashboard
    * @return void
    */
   public function test_some_one_else_request_with_valid_data(): void
   {
      $userId = UserRoles::where('role_id', 3)->value('user_id');
      $patient = User::where('id', $userId)->first();

      $response = $this->actingAs($patient)->postJson('/patient/submitted-someone-requests', [
         'first_name' => 'new',
         'last_name' => 'patient',
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

      $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.dashboard')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
   }

   /**
    * test someone else request form with existing email in patient dashboard 
    * @return void
    */
   public function test_some_one_else_request_with_valid_data_and_existing_email(): void
   {
      $userId = UserRoles::where('role_id', 3)->value('user_id');
      $patient = User::where('id', $userId)->first();
      $response = $this->actingAs($patient)->postJson('/patient/submitted-someone-requests', [
         'first_name' => 'new',
         'last_name' => 'patient',
         'email' => 'shivesh@mail.com',
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

      $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.dashboard')->assertSessionHas('message', 'Request is Submitted');
   }


   /**
    * test me request form in patient dashboard with empty data
    * @return void
    */
   public function test_some_one_else_request_with_empty_data(): void
   {
      $userId = UserRoles::where('role_id', 3)->value('user_id');
      $patient = User::where('id', $userId)->first();
      $response = $this->actingAs($patient)->postJson('/patient/submitted-someone-requests', [
         'first_name' => '',
         'last_name' => '',
         'date_of_birth' => '',
         'email' => '',
         'phone_number' => '',
         'street' => '',
         'city' => '',
         'state' => '',
         'zipcode' => '',
         'docs' => '',
         'symptoms' => '',
         'room' => '',
         'relation' => '',
      ]);

      $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
      $response->assertJsonValidationErrors([
         'first_name' => 'Please enter First Name',
         'last_name' => 'Please enter Last Name',
         'date_of_birth' => 'Please enter Date of Birth',
         'email' => 'Please enter Email',
         'phone_number' => 'Please enter Phone Number',
         'street' => 'Please enter a street',
         'city' => 'Please enter a city',
         'state' => 'Please enter a state',
         'zipcode' => 'Please enter 6 digits zipcode',
      ]);

   }


   /**
    * test me request form in patient dashboard with invalid data
    * @return void
    */
   public function test_some_one_else_request_with_invalid_data(): void
   {
      $userId = UserRoles::where('role_id', 3)->value('user_id');
      $patient = User::where('id', $userId)->first();
      $response = $this->actingAs($patient)->postJson('/patient/submitted-someone-requests', [
         'first_name' => 'hgjj566',
         'last_name' => '54656',
         'date_of_birth' => '',
         'email' => 'hg4354@gfhnf34.chg',
         'phone_number' => '35454545543',
         'street' => 'fbfghhnh645$#%#$4',
         'city' => 'fhgh5654',
         'state' => 'rhgtrh5465',
         'zipcode' => 'rtrth545',
         'docs' => 'frght454',
         'symptoms' => 'rtgt^%^$%r5',
         'room' => '',
         'relation' => '',
      ]);

      $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
      $response->assertJsonValidationErrors([
         'first_name' => 'Please enter only Alphabets in First name',
         'last_name' => 'Please enter only Alphabets in Last name',
         'date_of_birth' => 'Please enter Date of Birth',
         'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
         'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
         'city' => 'Please enter alpbabets in city name.',
         'state' => 'Please enter alpbabets in state name.',
         'zipcode' => 'Please enter 6 digits zipcode',
         'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
      ]);
   }
}
