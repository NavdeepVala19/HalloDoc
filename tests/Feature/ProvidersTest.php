<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvidersTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }


    /**
     * Test successful send link form submission with valid data.
     *
     * @return void
     */
    public function test_send_link_form_with_valid_data()
    {
            $response = $this->postJson('/provider-send-mail', [
            'first_name' => 'shivesh',
            'last_name' =>'surani' ,
            'email' => 'shivesh@mail.com',
            'mobile_number' => '+1 403-288-7577' ,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    public function test_send_link_form_with_empty_fields()
    {
        $response = $this->postJson('/provider-send-mail', [
            'first_name' => '',
            'last_name' => 'surani',
            'email' => 'shivesh@mail.com',
            'mobile_number' => '+1 403-288-7577',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
    }

    /**
     * Test form submission with invalid characters.
     *
     * @return void
     */
    public function test_send_link_form_with_invalid_characters()
    {
        $response = $this->postJson('/provider-send-mail', [
            'first_name' => '123!@#',
            'last_name' => 'surani',
            'email' => 'invalid_email',
            'mobile_number' => 'invalid_number',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_create_request_form_with_valid_data()
    {
        $response = $this->postJson('/provider-request', [
            'first_name' => 'shivesh',
            'last_name' => 'surani',
            'email' => 'shivesh@mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => 'fgfgef',
            'city' => 'dfgedgf',
            'state' => 'gfdgfd',
            'zip' => '147852',
            'room' => '4',
            'note' => 'sddfsfd',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }
    public function test_create_request_form_with_empty_data()
    {
        $response = $this->postJson('/provider-request', [
            'first_name' => '',
            'last_name' => 'surani',
            'email' => 'shivesh@mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => '',
            'city' => 'dfgedgf',
            'state' => '',
            'zip' => '',
            'room' => '4',
            'note' => 'sddfsfd',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function test_create_request_form_with_invalid_data()
    {
        $response = $this->postJson('/provider-request', [
            'first_name' => '21343243',
            'last_name' => '4543',
            'email' => 'shivesh@454mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => 'dcdgf',
            'city' => 'dfgedgf34',
            'state' => 'dfgfg45',
            'zip' => 'f1478523',
            'room' => '4ff',
            'note' => 'sddfsfd',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
