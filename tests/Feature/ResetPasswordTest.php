<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class ResetPasswordTest extends TestCase
{
    /**
     * No email entered, then return back and show error
     *
     * @return void
     */
    public function test_no_email_entered_in_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Invalid email entered (which is not in database, or which is not admin or provider)
     *
     * @return void
     */
    public function test_invalid_email_entered_for_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => 'invalid@mails.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error');
    }

    /**
     * valid email entered (which is either admin or provider emails)
     *
     * @return void
     */
    public function test_valid_email_entered_for_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => 'admin@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login')->assertSessionHas('message');
    }
}
