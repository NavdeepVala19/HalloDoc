<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientLoginTest extends TestCase
{
    /**
     * Email and password entered is either empty or is not in proper format
     *
     * @return void
     */
    public function test_email_and_password_entered_for_patient_login_is_not_in_proper_format(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }

    /**
     * No user exists and it's email and password are entered 
     *
     * @return void
     */
    public function test_no_user_with_entered_credentials_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'lejifasg@mailinator.com',
            'password' => 'lejig@mailinator.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'We could not find an account associated with that email address');
    }

    /**
     * correct credentials entered
     *
     * @return void
     */
    public function test_valid_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'shivesh@mail.com',
            'password' => 'shivesh@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.dashboard');
    }

    /**
     * Email entered is wrong.
     *
     * @return void
     */
    public function test_invalid_email_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'shivesh3ggf@mail.com',
            'password' => 'shivesh@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'We could not find an account associated with that email address');
    }


    /**
     * password entered is wrong.
     *
     * @return void
     */
    public function test_invalid_password_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'shivesh@mail.com',
            'password' => 'fegfegfdf@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Incorrect Password, Please Enter Correct Password');
    }

    /**
     * enter admin credentials.
     *
     * @return void
     */
    public function test_admin_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'admin@mail.com',
            'password' => 'admin@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Invalid credentials');
    }

    /**
     * enter provider credentials.
     *
     * @return void
     */
    public function test_provider_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'doctor@gmail.com',
            'password' => 'doctor@gmail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Invalid credentials');
    }
}
