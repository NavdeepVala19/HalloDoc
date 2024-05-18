<?php

namespace App\Services;

use App\Models\SMSLogs;
use App\Models\BlockRequest;
use App\Models\RequestClient;
use Illuminate\Support\Facades\DB;

class RecordsService
{
    public function searchRecordsListing()
    {
        // This combinedData is the combination of data from RequestClient,Request,RequestNotes,Provider,RequestClosed
        $combinedData = RequestClient::distinct()->select([
            'request.request_type_id',
            'request_client.first_name',
            'request_client.id',
            'request_client.email',
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request_client.notes',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request.status',
            'provider.first_name as physician_first_name',
            DB::raw('DATE(request_client.created_at) as created_date'),
            DB::raw('DATE(request_closed.created_at) as closed_date'),
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('request_closed', 'request_closed.request_id', '=', 'request_client.request_id')
            ->orderByDesc('id')
            ->paginate(10);

        return $combinedData;
    }

    public function filterSearchRecords($request)
    {

        $todayDate = now();
        $combinedData = RequestClient::distinct()->select([
            'request_client.first_name',
            'request.request_type_id',
            'request_client.email',
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request.status',
            'provider.first_name as physician_first_name',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request_notes.patient_notes',
            'request_client.id',
            DB::raw('DATE(request_client.created_at) as created_date'),
            DB::raw('DATE(request_closed.created_at) as closed_date'),
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('request_closed', 'request_closed.request_id', '=', 'request_client.request_id')
            ->orderByDesc('id');

        // if (!empty($request->patient_name)) {
        if ($request->patient_name) {
            $combinedData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        // if (!empty($request->email)) {
        if ($request->email) {
            $combinedData->where('request_client.email', "like", "%" . $request->email . "%");
        }
        // if (!empty($request->phone_number)) {
        if ($request->phone_number) {
            $combinedData->where('request_client.phone_number', "like", "%" . $request->phone_number . "%");
        }
        // if (!empty($request->request_type)) {
        if ($request->request_type) {
            $combinedData->where('request.request_type_id', $request->request_type);
        }
        // if (!empty($request->provider_name)) {
        if ($request->provider_name) {
            $combinedData->where('provider.first_name', "like", "%" . $request->provider_name . "%");
        }
        // if (!empty($request->request_status)) {
        if ($request->request_status) {
            $combinedData->where('request.status', $request->request_status);
        }
        // if (!empty($request->from_date_of_service)) {
        if ($request->from_date_of_service) {
            $combinedData->whereBetween('request_client.created_at', [$request->from_date_of_service, $todayDate]);
        }
        // if (!empty($request->to_date_of_service)) {
        if ($request->to_date_of_service) {
            $combinedData->where('request_client.created_at', "<", $request->to_date_of_service);
        }
        // if (!empty($request->from_date_of_service) && !empty($request->to_date_of_service)) {
        if ($request->from_date_of_service && $request->to_date_of_service) {
            $combinedData->whereBetween('request_client.created_at', [$request->from_date_of_service, $request->to_date_of_service,]);
        }
        return $combinedData;
    }

    public function searchRecords($request)
    {
        // Retrieve pagination parameters from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $combinedData = $this->filterSearchRecords($request)->paginate($perPage, ['*'], 'page', $page);

        return $combinedData;
    }

    public function exportFilteredDataToExcel($request)
    {
        $data = $this->filterSearchRecords($request);
        return $data;
    }

    public function filterSMSLogs($request)
    {
        // Retrieve pagination parameters from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $sms = SMSLogs::select();

        // if (!empty($request->receiver_name)) {
        if ($request->receiver_name) {
            $sms->where('sms_log.recipient_name', 'like', '%' . $request->receiver_name . '%');
        }
        // if (!empty($request->phone_number)) {
        if ($request->phone_number) {
            $sms->where('sms_log.mobile_number', "like", "%" . $request->phone_number . "%");
        }
        // if (!empty($request->created_date)) {
        if ($request->created_date) {
            $sms->where('sms_log.created_date', "like", "%" . $request->created_date . "%");
        }
        // if (!empty($request->sent_date)) {
        if ($request->sent_date) {
            $sms->where('sms_log.sent_date', "like", "%" . $request->sent_date . "%");
        }
        // if (!empty($request->role_type)) {
        if ($request->role_type) {
            $sms->where('sms_log.role_id', "like", "%" . $request->role_type . "%");
        }
        $sms = $sms->paginate($perPage, ['*'], 'page', $page);

        return $sms;
    }

    public function blockHistory(){
        $blockData = BlockRequest::select(
            'block_request.phone_number',
            'block_request.email',
            'block_request.id',
            'block_request.is_active',
            'block_request.request_id',
            'block_request.reason',
            'request_client.first_name as patient_name',
            DB::raw('DATE(block_request.created_at) as created_date'),
        )
            ->leftJoin('request_client', 'block_request.request_id', 'request_client.request_id')
            ->orderByDesc('id')
            ->paginate(10);

            return $blockData;
    }

    public function filterBlockHistoryData($request){
        $blockData = BlockRequest::select(
            'request_client.first_name as patient_name',
            'block_request.id',
            'block_request.phone_number',
            'block_request.email',
            'block_request.is_active',
            'block_request.reason',
            'block_request.request_id',
            DB::raw('DATE(block_request.created_at) as created_date'),
        )
            ->leftJoin('request_client', 'block_request.request_id', 'request_client.request_id');

        // if (!empty($request->patient_name)) {
        if ($request->patient_name) {
            $blockData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        // if (!empty($request->email)) {
        if ($request->email) {
            $blockData->where('block_request.email', "like", "%" . $request->email . "%");
        }
        // if (!empty($request->phone_number)) {
        if ($request->phone_number) {
            $blockData->where('block_request.phone_number', "like", "%" . $request->phone_number . "%");
        }
        // if (!empty($request->date)) {
        if ($request->date) {
            $blockData->where('block_request.created_at', "like", "%" . $request->date . "%");
        }
        $blockData = $blockData->paginate(10);

        return $blockData;
    }
}
