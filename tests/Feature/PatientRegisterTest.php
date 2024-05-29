<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Users;
use Illuminate\Http\Response;

class PatientRegisterTest extends TestCase
{
    /**
     * Email and password entered is in proper format with existing email and not existing password
     *
     * @return void
     */
    public function test_email_and_password_entered_for_patient_register_in_proper_format_with_existing_email_and_not_existing_password(): void
    {
        $email = Users::where('password',null)->value('email');

        $response = $this->postJson('/patient-registered', [
            'email' => $email,
            'password' => $email,
            'confirm_password' => $email,
        ]);

         $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.login.view')->assertSessionHas('message', 'login with your registered credentials');
    }
    
    
    /**
     * Email and password entered is in empty format
     *
     * @return void
     */
    public function test_email_and_password_with_empty_data(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => '',
            'password' => '',
            'confirm_password' => '',
        ]);
        
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
            'confirm_password' => 'The confirm password field is required.'
        ]);
    }

    
    /**
     * Email and password entered is in proper format and existing email and password
     *
     * @return void
     */
    public function test_email_and_password_entered_for_patient_register_existing_email_and_password(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => 'shivesh@mail.com',
            'password' => 'shivesh@mail.com',
            'confirm_password' => 'shivesh@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.login.view')->assertSessionHas('message', 'account with this email already exist');
    }


    /**
     * Email and password entered is in proper format and both are not exist
     *
     * @return void
     */
    public function test_email_and_password_entered_for_patient_register_not_existing_email(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => 'patient@mail.com',
            'password' => 'patient@mail.com',
            'confirm_password' => 'patient@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'no single request was created from this email To create account first submit request');

    }
    /**
     * Email and password entered is in invalid format
     *
     * @return void
     */
    public function test_email_and_password_entered_for_patient_register_invalid_format(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => 'patient@3245mail.com',
            'password' => 'patient@maigwjtgnw',
            'confirm_password' => 'patient@maigwjtgnw',
        ]);
        
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field format is invalid.',
        ]);
    }

    /**
     * password and confirm password is not same
     *
     * @return void
     */
    public function test_password_and_confirm_password_entered_for_patient_register_is_not_same(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => 'patient@mail.com',
            'password' => 'patient@maigwjtgnwtw',
            'confirm_password' => 'patiennfibnr',
        ]);
        
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'confirm_password' => 'The confirm password field must match password.',
        ]);
    }


    /**
     * password and confirm password is not same and email format is invalid
     *
     * @return void
     */
    public function test_password_and_confirm_password_entered_for_patient_register_is_not_same_and_email_is_invalid(): void
    {
        $response = $this->postJson('/patient-registered', [
            'email' => 'patient@m23ail.com',
            'password' => 'patient@maigwjtgnwtw',
            'confirm_password' => 'patiennfibnr',
        ]);
        
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field format is invalid.',
            'confirm_password' => 'The confirm password field must match password.',
        ]);
    }
}   
