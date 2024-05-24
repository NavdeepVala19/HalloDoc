<?php

namespace App\Helpers;

use App\Models\Provider;
use App\Models\ShiftDetail;
use App\Models\ShiftDetailRegion;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;

class Helper
{
    public const CATEGORY_PATIENT = 1;
    public const CATEGORY_FAMILY = 2;
    public const CATEGORY_CONCIERGE = 3;
    public const CATEGORY_BUSINESS = 4;

    public const STATUS_NEW = 1;
    public const STATUS_PENDING = 3;
    public const STATUS_ACTIVE = [4, 5];
    public const STATUS_CONCLUDE = 6;
    public const STATUS_TOCLOSE = [2, 7, 11];
    public const STATUS_UNPAID = 9;

    /**
     * Get category id from the name of category
     *
     * @param string $category different category names.
     *
     * @return int different types of request_type_id.
     */
    public static function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => self::CATEGORY_PATIENT,
            'family' => self::CATEGORY_FAMILY,
            'concierge' => self::CATEGORY_CONCIERGE,
            'business' => self::CATEGORY_BUSINESS,
        ];
        return $categoryMapping[$category] ?? null;
    }
    /**
     * Get status id from the name of status
     *
     * @param string $status different status names.
     *
     * @return int status in Id.
     */
    public static function getStatusId($status)
    {
        $statusMapping = [
            'new' => self::STATUS_NEW,
            'pending' => self::STATUS_PENDING,
            'active' => self::STATUS_ACTIVE,
            'conclude' => self::STATUS_CONCLUDE,
            'toclose' => self::STATUS_TOCLOSE,
            'unpaid' => self::STATUS_UNPAID,
        ];
        return $statusMapping[$status];
    }

    public static function getPhysicianDutyStatus()
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)
            ->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();

        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();
        $onCallPhysicians = Provider::whereIn('id', $onCallPhysicianIds)->get();

        $offDutyPhysicians = Provider::whereNotIn('id', $onCallPhysicianIds)->get();

        return [
            'onCallPhysicians' => $onCallPhysicians,
            'offDutyPhysicians' => $offDutyPhysicians,
        ];
    }

    public static function formattedShiftData($event)
    {
        return [
            'shiftId' => $event->getShiftData->id,
            'shiftDetailId' => $event->id,
            'title' => $event->getShiftData->provider->first_name . ' ' . $event->getShiftData->provider->last_name,
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
            'status' => $event->status,
        ];
    }

    public static function storeRepeatedShifts($request, $is_repeat, $shift, $status)
    {
        if ($is_repeat === 1) {
            $startDate = Carbon::parse($request->shiftDate);
            $endDate = Carbon::parse($request->shiftDate);

            $repeatingDays = [
                2 => 14,
                3 => 21,
                4 => 28,
            ];

            $days = $repeatingDays[$request->repeatEnd];
            $endDate->addDays($days);

            // Create a DatePeriod object to generate a range of dates between the start and end dates
            $interval = CarbonInterval::day();
            $dateRange = new DatePeriod($startDate, $interval, $endDate);

            // Loop through the range of dates and create a ShiftDetail record for each date that is selected
            foreach ($dateRange as $date) {
                if (in_array($date->format('w'), $request->checkbox)) {
                    $shiftDetail = ShiftDetail::create([
                        'shift_id' => $shift->id,
                        'shift_date' => $date->format('Y-m-d'),
                        'start_time' => $request->shiftStartTime,
                        'end_time' => $request->shiftEndTime,
                        'status' => $status,
                    ]);

                    $shiftDetailRegion = ShiftDetailRegion::create([
                        'shift_detail_id' => $shiftDetail->id,
                        'region_id' => $request->region,
                    ]);

                    ShiftDetail::where('id', $shiftDetail->id)->update(['region_id' => $shiftDetailRegion->id]);
                }
            }
        }
    }
}
