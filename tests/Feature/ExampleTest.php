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

    public function test_provider_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'physician1@mail.com',
            'password' => 'physician1',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('provider.dashboard');
    }
}
