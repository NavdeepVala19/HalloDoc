<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Regions;
use App\Models\Provider;
use App\Models\ShiftDetail;
use Illuminate\Http\Request;
use App\Models\PhysicianRegion;
use App\Models\ShiftDetailRegion;
use Illuminate\Support\Facades\Auth;

class ProviderSchedulingController extends Controller
{
    public function providerCalendarView()
    {
        return view('providerPage.scheduling.providerScheduling');
    }
    public function providerInformation()
    {
        $data = Provider::where('user_id', Auth::user()->id)->first();
        $regions = PhysicianRegion::with('regions')->where('provider_id', $data->id)->get();
        // $allRegions = [];
        // foreach ($regions as  $region) {
        //     dump($region->id, $region->regions->region_name);
        //     // $allRegions.push(['region_id' => $region->id, 'region_name' => $region->regions->region_name]);
        // }
        return response()->json(['physicianId' => $data->id, 'allRegions' => $regions]);
    }
    public function providerShiftData(Request $request)
    {
        // dd($request->all());

        if ($request->checkbox) {
            $weekDays = implode(',', $request->checkbox);
        } else {
            $weekDays = null;
        }
        $request->validate(['region' => 'required|in:1,2,3']);
        if ($request['is_repeat'] == true) {
            $is_repeat = 1;
        } else {
            $is_repeat = 0;
        }
        $shift =  Shift::create([
            'physician_id' => $request['providerId'],
            'start_date' => $request['shiftDate'],
            'is_repeat' => $is_repeat,
            'week_days' => $weekDays,
            'repeat_upto' => $request['repeatEnd'],
        ]);

        $shiftDetail = ShiftDetail::create([
            'shift_id' => $shift->id,
            'shift_date' => $request['shiftDate'],
            'region_id' => $request['region'],
            'start_time' => $request['shiftStartTime'],
            'end_time' => $request['shiftEndTime'],
        ]);
        ShiftDetailRegion::create([
            'shift_detail_id' => $shiftDetail->id,
            'region_id' => $request['region']
        ]);
        return redirect()->back();
    }
    public function providerShift()
    {
        // Get all the shifts from database and convert it into json format to be used by FullCalendar
        $physician = Provider::where('user_id', Auth::user()->id)->first();
        $shifts = Shift::with('shiftDetail')->where('physician_id', $physician->id)->get();
        $formattedShift = $shifts->map(function ($event) {
            return [
                // 'id' => $event->id,
                'title' => $event->provider->first_name . " " . $event->provider->last_name,
                'shiftDate' => $event->shiftDetail->shift_date,
                'startTime' => $event->shiftDetail->start_time,
                'endTime' => $event->shiftDetail->end_time,
                'resourceId' => $event->physician_id,
                'physician_id' => $event->physician_id,
                'is_repeat' => $event->is_repeat,
                'week_days' => explode(',', $event->week_days),
                'repeat_upto' => $event->repeat_upto
            ];
        });

        return response()->json($formattedShift->toArray());
    }
}
