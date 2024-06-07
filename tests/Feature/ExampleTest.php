<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AllUsers;
use App\Models\Provider;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestClient;
use Illuminate\Http\Response;
use App\Models\HealthProfessional;
use Illuminate\Support\Facades\Crypt;

class ExampleTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    // admin listing unpaid state page can be rendered
    public function test_admin_listing_unpaid_state_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin/unpaid');

        $count = $response->getOriginalContent()->getData()['count'];
        $cases = $response->getOriginalContent()->getData()['cases'][0]->getAttributes();
        $requestClient = $response->getOriginalContent()->getData()['cases'][0]->getRelations()['requestClient']->getAttributes();
        $userData = $response->getOriginalContent()->getData()['userData']->getAttributes();


        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.adminTabs.adminUnpaidListing')
            ->assertViewHasAll([
                'cases', 'count', 'userData'
            ]);

        $this->assertTrue(array_key_exists('newCase', $count));
        $this->assertTrue(array_key_exists('pendingCase', $count));
        $this->assertTrue(array_key_exists('activeCase', $count));
        $this->assertTrue(array_key_exists('concludeCase', $count));
        $this->assertTrue(array_key_exists('tocloseCase', $count));
        $this->assertTrue(array_key_exists('unpaidCase', $count));
        $this->assertTrue(array_key_exists('username', $userData));
        $this->assertTrue(array_key_exists('request_type_id', $cases));
        $this->assertTrue(array_key_exists('first_name', $cases));
        $this->assertTrue(array_key_exists('last_name', $cases));
        $this->assertTrue(array_key_exists('id', $cases));
        $this->assertTrue(array_key_exists('created_at', $cases));
        $this->assertTrue(array_key_exists('phone_number', $cases));
        $this->assertTrue(array_key_exists('first_name', $requestClient));
        $this->assertTrue(array_key_exists('last_name', $requestClient));
        $this->assertTrue(array_key_exists('email', $requestClient));
        $this->assertTrue(array_key_exists('date_of_birth', $requestClient));
        $this->assertTrue(array_key_exists('phone_number', $requestClient));
        $this->assertTrue(array_key_exists('street', $requestClient));
        $this->assertTrue(array_key_exists('city', $requestClient));
        $this->assertTrue(array_key_exists('state', $requestClient));
    }
}
