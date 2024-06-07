<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\RequestTable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommonOperationTest extends TestCase
{
    private function adminLoggedIn()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        return $admin;
    }

    /**
     * test successfl of view case page render
     * @return void
     */

    public function test_view_case_page_render()
    {
        $admin = $this->adminLoggedIn();
        $requestId = Crypt::encrypt(RequestTable::first()->id);
        $response = $this->actingAs($admin)->get("/admin/view/case/$requestId");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.pages.viewCase');

        $confirmationNo = $response->getOriginalContent()->getData()['data']->getAttributes();
        $patient = $response->getOriginalContent()->getData()['data']->getRelations()['requestClient']->getAttributes();

        $this->assertTrue(array_key_exists('first_name', $patient));
        $this->assertTrue(array_key_exists('last_name', $patient));
        $this->assertTrue(array_key_exists('email', $patient));
        $this->assertTrue(array_key_exists('phone_number', $patient));
        $this->assertTrue(array_key_exists('date_of_birth', $patient));
        $this->assertTrue(array_key_exists('street', $patient));
        $this->assertTrue(array_key_exists('city', $patient));
        $this->assertTrue(array_key_exists('state', $patient));
        $this->assertTrue(array_key_exists('room', $patient));
        $this->assertTrue(array_key_exists('state', $patient));
        $this->assertTrue(array_key_exists('state', $patient));
        $this->assertTrue(array_key_exists('confirmation_no', $confirmationNo));
    }

       /**
     * Test successful view case form with valid data
     * @return void
     */
    // public function test_view_case_with_valid_data()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/admin/view/case/edit', [
    //         'patient_notes' => 'Physician Notes',
    //         'first_name' => 'Denton',
    //         'last_name' => 'Wise',
    //         'dob' => '03-09-2022',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND);
    // }

    /**
     * Test successful view case form with invalid data
     * @return void
     */
    public function test_view_case_with_invalid_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin/view/case/edit', [
            'patient_notes' => 'Physician N%#$^%^%otes',
            'first_name' => 'Denton43566',
            'last_name' => 'Wise5465',
            'dob' => '03-09-2035',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'dob' => 'The dob field must be a date before tomorrow.',
            'patient_notes' => 'The patient notes field format is invalid.',
        ]);
    }

    /**
     * Test successful view case form with empty data
     * @return void
     */
    public function test_view_case_with_empty_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/admin/view/case/edit', [
            'patient_notes' => '',
            'first_name' => '',
            'last_name' => '',
            'dob' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'dob' => 'The dob field is required.',
        ]);
    }

    public function test_view_notes_rendered(){
        $admin = $this->adminLoggedIn();
        $requestId = Crypt::encrypt(RequestTable::first()->id);
        $response = $this->actingAs($admin)->get("/admin/view/notes/$requestId");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.pages.viewNotes');

        dd($response->getOriginalContent()->getData());
        
        $notes = $response->getOriginalContent()->getData()['note']->getAttributes();
        $transferedPhysician = $response->getOriginalContent()->getData()->getRelations()['transferedPhysician']->getAttributes();
        dd($transferedPhysician);
        $providerTransferedCase = $response->getOriginalContent()->getData()['note']->getRelations()['providerTransferedCase']->getAttributes();
        $provider = $response->getOriginalContent()->getData()['note']->getRelations()['provider']->getAttributes();
        dd($notes);

        // $this->assertTrue(array_key_exists('patient_notes', $notes));
        // $this->assertTrue(array_key_exists('physician_notes', $notes));
        // $this->assertTrue(array_key_exists('admin_notes', $notes));

    }
}
