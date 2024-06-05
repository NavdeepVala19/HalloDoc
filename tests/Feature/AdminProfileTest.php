<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Response;

class AdminProfileTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    /**
     * Admin MyProfile page can be rendered.
     */
    public function test_admin_profile_page_can_be_rendered(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin-profile-edit');

        $response->assertStatus(Response::HTTP_OK);
    }
}
