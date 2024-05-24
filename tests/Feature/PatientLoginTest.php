<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientLoginTest extends TestCase
{
    public function test_patient_login_page_can_be_rendered()
    {
        $response = $this->get('/patient/login');

        $response->assertStatus(Response::HTTP_OK);
    }
    
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
    }

    /**
     * No user with email and password entered exists
     *
     * @return void
     */
    public function test_no_user_with_entered_credentials_exists_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'lejifasg@mailinator.com',
            'password' => 'passwordTest',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'We could not find an account associated with that email address');
    }

    /**
     * Either admin or provider is trying to login on patient page
     *
     * @return void
     */
    public function test_invalid_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'admin@mail.com',
            'password' => 'admin12345',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Invalid credentials');
    }

    /**
     * correct credentials entered
     *
     * @return void
     */
    public function test_valid_credentials_entered_for_patient_login(): void
    {
        $response = $this->postJson('/patient/logged-in', [
            'email' => 'lejig@mailinator.com',
            'password' => 'lejig@mailinator.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.dashboard');
    }
}
