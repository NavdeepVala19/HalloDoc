<?php

namespace App\Http\Controllers;

use DatePeriod;
use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Regions;
use App\Models\Provider;
use Carbon\CarbonInterval;
use App\Models\ShiftDetail;
use Illuminate\Http\Request;
use App\Models\PhysicianRegion;
use App\Models\ShiftDetailRegion;
use Illuminate\Support\Facades\Auth;

class ProviderSchedulingController extends Controller
{
    /**
     * Display the provider scheduling page.
     *
     * @return \Illuminate\View\View
     */
    public function providerCalendarView()
    {
        return view('providerPage.scheduling.providerScheduling');
    }

    /**
     * Fetch details of the logged-in provider.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function providerInformation()
    {
        $data = Provider::where('user_id', Auth::user()->id)->first();
        $regions = PhysicianRegion::with('regions')->where('provider_id', $data->id)->get();
        return response()->json(['physicianId' => $data->id, 'allRegions' => $regions]);
    }

    /**
     * Add a new shift to the calendar.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerShiftData(Request $request)
    {
        $request->validate([
            'shiftDate' => 'required',
            'shiftStartTime' => 'required',
            'shiftEndTime' => 'required|after:shiftStartTime',
        ]);

        // Check whether the shift created for provider is already having shift for that time period
        $shifts = Shift::with('shiftDetail')->get();
        $currentShifts = $shifts->whereIn("start_date", $request->shiftDate);
        $userId = Auth::user()->id;
        $providerId = Provider::where('user_id', $userId)->first()->id;

        // check for each shifts, whether it have the same time period or in-between time period
        foreach ($currentShifts as $currentShift) {
            if ($currentShift->physician_id == $providerId) {
                // for the currentShift if the physician_id matches requested physician check for the time period
                $shiftStartTimeCurrent = $currentShift->shiftDetail->start_time;
                $shiftEndTimeCurrent = $currentShift->shiftDetail->end_time;

                if (
                    $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                    $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                ) {
                    return redirect()->back()->with('shiftOverlap', "You have an shift during the time period you provided");
                }
            }
        };

        if ($request->checkbox) {
            $weekDays = implode(',', $request->checkbox);
        } else {
            $weekDays = null;
        }
        $request->validate(['region' => 'required|in:1,2,3,4,5']);
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
            'start_time' => $request['shiftStartTime'],
            'end_time' => $request['shiftEndTime'],
            'status' => 1
        ]);
        $shiftDetailRegion = ShiftDetailRegion::create([
            'shift_detail_id' => $shiftDetail->id,
            'region_id' => $request['region']
        ]);

        ShiftDetail::where('shift_id', $shift->id)->update(['region_id' => $shiftDetailRegion->id]);

        if ($is_repeat == 1) {
            $startDate = Carbon::parse($request['shiftDate']);
            $endDate = Carbon::parse($request['shiftDate']);

            // Set the end date based on the value of repeatEnd
            switch ($request['repeatEnd']) {
                case 2:
                    $endDate->addDays(14);
                    break;
                case 3:
                    $endDate->addDays(21);
                    break;
                case 4:
                    $endDate->addDays(28);
                    break;
                default:
                    // Set the end date to the start date if repeatEnd is not 2, 3, or 4
                    $endDate = $startDate;
                    break;
            }

            // Create a DatePeriod object to generate a range of dates between the start and end dates
            $interval = CarbonInterval::day();
            $dateRange = new DatePeriod($startDate, $interval, $endDate);

            // Loop through the range of dates and create a ShiftDetail record for each date that is selected
            foreach ($dateRange as $date) {
                if (in_array($date->format('w'), $request->checkbox)) {
                    $shiftDetail = ShiftDetail::create([
                        'shift_id' => $shift->id,
                        'shift_date' => $date->format('Y-m-d'),
                        'start_time' => $request['shiftStartTime'],
                        'end_time' => $request['shiftEndTime'],
                        'status' => 1
                    ]);

                    $shiftDetailRegion = ShiftDetailRegion::create([
                        'shift_detail_id' => $shiftDetail->id,
                        'region_id' => $request['region']
                    ]);

                    ShiftDetail::where('id', $shiftDetail->id)->update(['region_id' => $shiftDetailRegion->id]);
                }
            }
        }

        return redirect()->back()->with('shiftAdded', "Shift Added Successfully");
    }

    /**
     * Get all shifts from the database and convert them into JSON format for use by FullCalendar.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function providerShift()
    {
        $physician = Provider::where('user_id', Auth::user()->id)->first();

        $shiftDetails = ShiftDetail::with('getShiftData')->whereHas('getShiftData', function ($query) use ($physician) {
            $query->where('physician_id', $physician->id);
        })->get();

        $formattedShift = $shiftDetails->map(function ($event) {
            return [
                'shiftId' => $event->getShiftData->id,
                'shiftDetailId' => $event->id,
                'title' => $event->getShiftData->provider->first_name . " " . $event->getShiftData->provider->last_name,
                'shiftDate' => $event->shift_date,
                'startTime' => $event->start_time,
                'endTime' => $event->end_time,
                'resourceId' => $event->getShiftData->physician_id,
                'physician_id' => $event->getShiftData->physician_id,
                'region_id' => $event->shiftDetailRegion->region_id,
                'region_name' => $event->shiftDetailRegion->region->region_name,
                'is_repeat' => $event->getShiftData->is_repeat,
                'week_days' => explode(',', $event->getShiftData->week_days),
                'repeat_upto' => $event->getShiftData->repeat_upto,
                'status' => $event->status
            ];
        });

        return response()->json($formattedShift->toArray());
    }

    /**
     * Edit already existing shifts.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerEditShift(Request $request)
    {
        if ($request['action'] == 'save') {
            // Check whether the shift created for provider is already having shift for that time period
            $shifts = Shift::with('shiftDetail')->get();
            $currentShifts = $shifts->whereIn("start_date", $request->shiftDate);

            $userId = Auth::user()->id;
            $providerId = Provider::where('user_id', $userId)->first()->id;

            // check for each shifts, whether it have the same time period or in-between time period
            foreach ($currentShifts as $currentShift) {
                if ($currentShift->physician_id == $providerId) {
                    // for the currentShift if the physician_id matches requested physician check for the time period
                    $shiftStartTimeCurrent = $currentShift->shiftDetail->start_time;
                    $shiftEndTimeCurrent = $currentShift->shiftDetail->end_time;

                    if (
                        $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                        $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                    ) {
                        return redirect()->back()->with('shiftOverlap', "You have an shift during the time period you provided");
                    }
                }
            };

            ShiftDetail::where('id', $request->shiftDetailId)->update([
                'shift_date' => $request->shiftDate,
                'start_time' => $request->shiftTimeStart,
                'end_time' => $request->shiftTimeEnd,
                'modified_by' => "Provider"
            ]);

            return redirect()->back()->with('shiftEdited', 'Shift Edited Successfully!');
        } else if ($request['action'] == 'delete') {
            ShiftDetail::where('id', $request->shiftDetailId)->delete();
            ShiftDetailRegion::where('shift_detail_id', $request->shiftDetailId)->delete();

            $data = ShiftDetail::where('shift_id', $request->shiftId)->get();

            if ($data->isEmpty()) {
                Shift::where('id', $request->shiftId)->delete();
            }
            return redirect()->back()->with("shiftDeleted", "Shift Deleted Successfully!");
        }
    }
}
