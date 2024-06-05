<?php

namespace Tests\Feature;

use App\Models\Shift;
use App\Models\ShiftDetail;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Response;

class AdminSchedulingTest extends TestCase
{
    // ------------------ ADMIN SCHEDULING -------------------
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    /**
     * Admin providers-on-call page can be rendered.
     */
    public function test_admin_providers_on_call_page_can_be_rendered(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/providers-on-call');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.scheduling.providerOnCall')
            ->assertViewHasAll([
                'regions', 'onCallPhysicians', 'offDutyPhysicians'
            ]);
    }

    /**
     * filter providers on call based on region
     */
    public function test_filter_providers_on_call_based_on_region(): void
    {
        $admin = $this->admin();

        $id = '1';

        $response = $this->actingAs($admin)->get("/filterProvidersByRegion/{$id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Admin shifts for review page can be rendered.
     */
    public function test_admin_shifts_for_review_page_can_be_rendered(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->get('/shifts-review');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Admin Scheduling page can be rendered.
     */
    public function test_admin_scheduling_page_can_be_rendered(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/scheduling');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Admin create single shift with valid data.
     */
    public function test_admin_create_single_shift_with_valid_data(): void
    {
        $admin = $this->admin();

        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');


        $response = $this->actingAs($admin)
            ->postJson('/create-shift', [
                'region' => 1,
                'physician' => 1,
                'shiftDate' => date('Y-m-d'),
                'shiftStartTime' => $startTime,
                'shiftEndTime' => $endTimeString,
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('shiftAdded', 'Shift Added Successfully');
    }

    /**
     * Admin create single shift with invalid data.
     */
    public function test_admin_create_single_shift_with_invalid_data(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/create-shift', [
                'region' => 7,
                'physician' => '',
                'shiftDate' => '2024-01-01',
                'shiftStartTime' => '12:00:00',
                'shiftEndTime' => '11:00:00',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'region' => 'The selected region is invalid.',
                'physician' => 'The physician field is required.',
                'shiftDate' => 'The shift date field must be a date after yesterday.',
                'shiftEndTime' => 'The shift end time field must be a date after shift start time.'
            ]);
    }

    /**
     * Admin create single shift with empty data.
     */
    public function test_admin_create_single_shift_with_no_data(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/create-shift', [
                'region' => '',
                'physician' => '',
                'shiftDate' => '',
                'shiftStartTime' => '',
                'shiftEndTime' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'region' => 'The region field is required.',
                'physician' => 'The physician field is required.',
                'shiftDate' => 'The shift date field is required.',
                'shiftStartTime' => 'The shift start time field is required.',
                'shiftEndTime' => 'The shift end time field is required.'
            ]);
    }

    /**
     * Admin create repeating shifts.
     */
    public function test_admin_create_repeating_shifts(): void
    {
        $admin = $this->admin();

        $startTime = Carbon::parse(date('H:i:00'))->addMinutes(30);
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');

        $response = $this->actingAs($admin)
            ->postJson('/create-shift', [
                'region' => '1',
                'physician' => '1',
                'shiftDate' => date('Y-m-d'),
                'shiftStartTime' => $startTime->toTimeString(),
                'shiftEndTime' => $endTimeString,
                'is_repeat' => 'on',
                'checkbox' => [2, 3],
                'repeatEnd' => '2',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftAdded', 'Shift Added Successfully');
    }

    /**
     * Admin create overlapping shift.
     */
    public function test_admin_create_overlapping_shift(): void
    {
        $admin = $this->admin();

        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');

        $response = $this->actingAs($admin)
            ->postJson('/create-shift', [
                'region' => '1',
                'physician' => '1',
                'shiftDate' => date('Y-m-d'),
                'shiftStartTime' => $startTime,
                'shiftEndTime' => $endTimeString,
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('shiftOverlap', 'Provider you selected have an shift during the time period you provided');
    }

    // admin edit shift with valid data
    public function test_admin_edit_shift_with_valid_data()
    {
        $shiftId = Shift::first()->id;
        $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');
        $admin = $this->admin();
        $response = $this->actingAs($admin)->postJson('/admin-edit-shift', [
            'shiftId' => $shiftId,
            'shiftDetailId' => $shiftDetailId,
            'shiftDate' => Date('Y-m-d'),
            'shiftTimeStart' => '22:00:00',
            'shiftTimeEnd' => '23:00:00',
            'action' => 'save',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftEdited', 'Shift Edited Successfully!');
    }

    // admin delete shift
    public function test_admin_delete_shift()
    {
        $shiftId = Shift::first()->id;
        $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');

        $admin = $this->admin();
        $response = $this->actingAs($admin)->postJson('/admin-edit-shift', [
            'shiftId' => $shiftId,
            'shiftDetailId' => $shiftDetailId,
            'action' => 'delete',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftDeleted', 'Shift Deleted Successfully!');
    }

    // admin change shift status
    public function test_admin_change_shift_status()
    {
        $shiftDetailId = ShiftDetail::where('status', 1)->value('id');

        $admin = $this->admin();
        $response = $this->actingAs($admin)->postJson('/admin-edit-shift', [
            'shiftDetailId' => $shiftDetailId,
            'action' => 'return',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('shiftApproved', 'Shift Status changed from Pending to Approved');
    }
}
