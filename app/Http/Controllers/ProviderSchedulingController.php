<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\ShiftDetailRegion;
use Illuminate\Http\Request;
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
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerShiftData(Request $request)
    {
        $request->validate([
            'region' => 'required|in:1,2,3,4,5',
            'shiftDate' => 'required|after:yesterday',
            'shiftStartTime' => 'required',
            'shiftEndTime' => 'required|after:shiftStartTime',
        ]);

        // Check whether the shift created for provider is already having shift for that time period
        $currentShifts = ShiftDetail::where('shift_date', $request->shiftDate)->get();

        $userId = Auth::user()->id;
        $providerId = Provider::where('user_id', $userId)->first()->id;

        // check for each shifts, whether it have the same time period or in-between time period
        foreach ($currentShifts as $currentShift) {
            if ($currentShift->getShiftData->physician_id === $providerId) {
                // for the currentShift if the physician_id matches requested physician check for the time period
                $shiftStartTimeCurrent = $currentShift->start_time;
                $shiftEndTimeCurrent = $currentShift->end_time;

                if (
                    $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                    $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                ) {
                    return redirect()->back()->with('shiftOverlap', 'You have an shift during the time period you provided');
                }
            }
        }

        if ($request->checkbox) {
            $weekDays = implode(',', $request->checkbox);
        } else {
            $weekDays = null;
        }

        if ($request['is_repeat'] === 'on') {
            $is_repeat = 1;
        } else {
            $is_repeat = 0;
        }

        $shift = Shift::create([
            'physician_id' => $request['providerId'],
            'start_date' => $request['shiftDate'],
            'is_repeat' => $is_repeat,
            'week_days' => $weekDays,
            'repeat_upto' => $request['repeatEnd'],
            'created_by' => 2,
        ]);

        $shiftDetail = ShiftDetail::create([
            'shift_id' => $shift->id,
            'shift_date' => $request['shiftDate'],
            'start_time' => $request['shiftStartTime'],
            'end_time' => $request['shiftEndTime'],
            'status' => 1,
        ]);

        $shiftDetailRegion = ShiftDetailRegion::create([
            'shift_detail_id' => $shiftDetail->id,
            'region_id' => $request['region'],
        ]);

        ShiftDetail::where('shift_id', $shift->id)->update(['region_id' => $shiftDetailRegion->id]);

        Helper::storeRepeatedShifts($request, $is_repeat, $shift, 1);

        return redirect()->back()->with('shiftAdded', 'Shift Added Successfully');
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
            return Helper::formattedShiftData($event);
        });

        return response()->json($formattedShift->toArray());
    }

    /**
     * Edit already existing shifts.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerEditShift(Request $request)
    {
        if ($request['action'] === 'save') {

            $request->validate([
                'shiftDate' => 'required|after:yesterday',
                'shiftTimeStart' => 'required',
                'shiftTimeEnd' => 'required|after:shiftTimeStart',
            ]);

            // Check whether the shift created for provider is already having shift for that time period
            $currentShifts = ShiftDetail::where('shift_date', $request->shiftDate)->get();

            $userId = Auth::user()->id;
            $providerId = Provider::where('user_id', $userId)->first()->id;

            // check for each shifts, whether it have the same time period or in-between time period
            foreach ($currentShifts as $currentShift) {
                if ($currentShift->getShiftData->physician_id === $providerId) {
                    // for the currentShift if the physician_id matches requested physician check for the time period
                    $shiftStartTimeCurrent = $currentShift->start_time;
                    $shiftEndTimeCurrent = $currentShift->end_time;

                    if (
                        $shiftStartTimeCurrent <= $request->shiftTimeStart && $shiftEndTimeCurrent > $request->shiftTimeStart ||
                        $shiftStartTimeCurrent <= $request->shiftTimeEnd && $shiftEndTimeCurrent > $request->shiftTimeEnd
                    ) {
                        return redirect()->back()->with('shiftOverlap', 'You have an shift during the time period you provided');
                    }
                }
            }

            ShiftDetail::where('id', $request->shiftDetailId)->update([
                'shift_date' => $request->shiftDate,
                'start_time' => $request->shiftTimeStart,
                'end_time' => $request->shiftTimeEnd,
                'modified_by' => 'Provider',
            ]);

            return redirect()->back()->with('shiftEdited', 'Shift Edited Successfully!');
        }
        if ($request['action'] === 'delete') {
            ShiftDetail::where('id', $request->shiftDetailId)->delete();
            ShiftDetailRegion::where('shift_detail_id', $request->shiftDetailId)->delete();

            $data = ShiftDetail::where('shift_id', $request->shiftId)->get();

            if ($data->isEmpty()) {
                Shift::where('id', $request->shiftId)->delete();
            }
            return redirect()->back()->with('shiftDeleted', 'Shift Deleted Successfully!');
        }
    }
}
