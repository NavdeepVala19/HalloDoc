<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Regions;
use App\Models\Provider;
use App\Models\ShiftDetail;
use App\Models\ShiftDetailRegion;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{
    public function schedulingCalendarView()
    {
        $regions = Regions::get();
        return view('adminPage.scheduling.scheduling', compact('regions'));
    }
    public function providerData()
    {
        $providers = Provider::get();
        $formattedData = [];
        foreach ($providers as $provider) {
            $formattedData[] = [
                'id' => $provider->id,
                'physician' => $provider->first_name .  " " . $provider->last_name,
                'photo' => $provider->photo,
            ];
        }
        return response()->json($formattedData);
    }
    public function providersOnCall()
    {
        return view('adminPage.scheduling.providerOnCall');
    }
    public function shiftsReviewView()
    {
        return view('adminPage.scheduling.shiftsForReview');
    }
    public function createShiftData(Request $request)
    {
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
        $shiftId =  Shift::insertGetId([
            'physician_id' => $request['physician'],
            'start_date' => $request['shiftDate'],
            'is_repeat' => $is_repeat,
            'week_days' => $weekDays,
            'repeat_upto' => $request['repeatEnd'],
        ]);
        $shiftDetailId = ShiftDetail::insertGetId([
            'shift_id' => $shiftId,
            'shift_date' => $request['shiftDate'],
            'region_id' => $request['region'],
            'start_time' => $request['shiftStartTime'],
            'end_time' => $request['shiftEndTime'],
        ]);
        ShiftDetailRegion::insert([
            'shift_detail_id' => $shiftDetailId,
            'region_id' => $request['region']
        ]);
        return redirect()->back();
    }

    public function eventsData()
    {
        // Get all the shifts from database and convert it into json format to be used by FullCalendar
        $shifts = Shift::with('shiftDetail')->get();

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
