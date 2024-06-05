<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\UserRoles;
use Illuminate\Http\Response;

class ProviderSchedulingTest extends TestCase
{
    public function provider()
    {
        $providerId = UserRoles::where('role_id', 2)->first()->user_id;
        return User::where('id', $providerId)->first();
    }

    // provider scheduling page can be rendered
    public function test_provider_scheduling_page_can_be_rendered()
    {
        $provider = $this->provider();

        $response = $this->actingAs($provider)->get('/provider-scheduling');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * provider add single shift with valid data
     * @return void
     */
    public function test_provider_add_single_shift_with_valid_data()
    {
        $provider = $this->provider();
        $providerId = Provider::where('user_id', $provider->id)->value('id');

        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');

        $response = $this->actingAs($provider)
            ->postJson('/provider-create-shift', [
                'providerId' => $providerId,
                'region' => '1',
                'shiftDate' => now()->toDateString(),
                'shiftStartTime' => $startTime,
                'shiftEndTime' => $endTimeString,
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftAdded', 'Shift Added Successfully');
    }

    /**
     * provider add overlapping shift with valid data
     * @return void
     */
    public function test_provider_add_overlapping_shift_with_valid_data()
    {
        $provider = $this->provider();
        $providerId = Provider::where('user_id', $provider->id)->value('id');

        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');

        $response = $this->actingAs($provider)
            ->postJson('/provider-create-shift', [
                'providerId' => $providerId,
                'region' => '1',
                'shiftDate' => now()->toDateString(),
                'shiftStartTime' => $startTime,
                'shiftEndTime' => $endTimeString,
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftAdded', 'Shift Added Successfully');
    }

    /**
     * provider add repeating shift with valid data
     * @return void
     */
    public function test_provider_add_repeating_shift_with_valid_data()
    {
        $provider = $this->provider();
        $providerId = Provider::where('user_id', $provider->id)->value('id');

        $startTime = Carbon::parse(date('H:i:00'))->addMinutes(30);
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');

        $response = $this->actingAs($provider)
            ->postJson('/provider-create-shift', [
                'providerId' => $providerId,
                'region' => '1',
                'shiftDate' => now()->toDateString(),
                'shiftStartTime' => $startTime->toTimeString(),
                'shiftEndTime' => $endTimeString,
                'is_repeat' => 'on',
                'checkbox' => [0, 1],
                'repeatEnd' => '2'
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftOverlap', 'You have an shift during the time period you provided');
    }

    /**
     * Test successful schedule shift with invalid data
     * @return void
     */
    public function test_provider_add_shift_with_invalid_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)
            ->postJson('/provider-create-shift', [
                'region' => 'somnath',
                'shiftDate' => '11-05-2024',
                'shiftStartTime' => '07:00:00',
                'shiftEndTime' => '06:00:00',
                'checkbox' => '7',
                'repeatEnd' => '5',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'region' => 'The selected region is invalid.',
            'shiftDate' => 'The shift date field must be a date after yesterday.',
            'shiftEndTime' => 'The shift end time field must be a date after shift start time.',
        ]);
    }


    /**
     * Test successful schedule shift with empty data
     * @return void
     */
    public function test_provider_add_shift_with_empty_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)
            ->postJson('/provider-create-shift', [
                'region' => '',
                'shiftDate' => '',
                'shiftStartTime' => '',
                'shiftEndTime' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'region' => 'The region field is required.',
            'shiftDate' => 'The shift date field is required.',
            'shiftStartTime' => 'The shift start time field is required.',
            'shiftEndTime' => 'The shift end time field is required.',
        ]);
    }


    /**
     * Test successful edit scheduled shift with valid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_valid_data()
    {
        $provider = $this->provider();
        $providerId = Provider::where('user_id', $provider->id)->value('id');

        $shift = Shift::where('start_date', '<', now()->toDateString())->value('id');
        $shiftDetailId = ShiftDetail::where('shift_id', $shift)->value('id');

        $response = $this->actingAs($provider)
            ->postJson('/provider-edit-shift', [
                'providerId' => $providerId,
                'shiftId' => $shift,
                'shiftDetailId' => $shiftDetailId,
                'shiftDate' => now()->toDateString(),
                'shiftTimeStart' => '22:00:00',
                'shiftTimeEnd' => '23:00:00',
                'action' => 'save'
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftEdited', 'Shift Edited Successfully!');
    }


    /**
     * Test successful edit scheduled shift with invalid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_invalid_data()
    {
        $provider = $this->provider();
        $response = $this->actingAs($provider)
            ->postJson('/provider-edit-shift', [
                'shiftDate' => '2024-01-01',
                'shiftTimeStart' => '15:00',
                'shiftTimeEnd' => '13:00',
                'action' => 'save',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'shiftDate' => 'The shift date field must be a date after yesterday.',
            'shiftTimeEnd' => 'The shift time end field must be a date after shift time start.',
        ]);
    }


    /**
     * delete scheduled shift
     * @return void
     */
    public function test_delete_scheduled_shift()
    {
        $provider = $this->provider();

        $providerId = Provider::where('user_id', $provider->id)->value('id');

        $shiftId = Shift::orderBy('id', 'desc')->first()->id;
        $shiftDetailId = ShiftDetail::where('shift_id', $shiftId)->value('id');

        $response = $this->actingAs($provider)
            ->postJson('/provider-edit-shift', [
                'shiftId' => $shiftId,
                'shiftDetailId' => $shiftDetailId,
                'action' => 'delete',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('shiftDeleted', 'Shift Deleted Successfully!');
    }
}
