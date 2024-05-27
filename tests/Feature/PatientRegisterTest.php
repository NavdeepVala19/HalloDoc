<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Users;
use Illuminate\Http\Response;

class PatientRegisterTest extends TestCase
{
    /**
     * Patient register page can be rendered.
     */
    public function test_patient_register_page_can_be_rendered(): void
    {
        $response = $this->get('/patient-register');

        $response->assertStatus(200);
    }

    /**
     * No data entered in fields.
     */
    public function test_patient_create_account_with_no_data_entered(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => '',
            'password' => '',
            'confirm_password' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Patient creating account with already created account email and password.
     * Account with the entered email already exists
     */
    public function test_patient_account_aleardy_exists_with_entered_email(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => 'nera1@mailinator.com',
            'password' => 'nera@mailinator.com',
            'confirm_password' => 'nera@mailinator.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.login.view')->assertSessionHas('message', 'account with this email already exist');
    }

    /**
     * Patient create account with valid email and password.
     */
    public function test_patient_create_account_with_valid_email_and_password(): void
    {

        $response = $this->postJson('/patient-registered', [
            'email' => 'cyho@mailinator.com',
            'password' => 'newPassword',
            'confirm_password' => 'newPassword',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.login.view')->assertSessionHas('success', 'login with your registered credentials');

        Users::where('email', 'cyho@mailinator.com')->update(['password' => null]);
    }
}
