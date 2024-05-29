<?php

namespace App\Http\Controllers;

use App\Exports\SearchRecordExport;
use App\Models\BlockRequest;
use App\Models\EmailLog;
use App\Models\RequestBusiness;
use App\Models\RequestClient;
use App\Models\RequestConcierge;
use App\Models\RequestNotes;
use App\Models\RequestStatus;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\SMSLogs;
use App\Services\RecordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class RecordsController extends Controller
{
    /**
     * list of  search records
     * it list patient name,email,mobile,address,zip,date of service ,close case date,request type,request status,provider name,
     * physician note,admin note,patient note
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function searchRecordsView(RecordsService $recordsService)
    {
        $records = $recordsService->searchRecordsListing();
        Session::forget('request_status');
        Session::forget('request_type');

        return view('adminPage.records.searchRecords', compact('records'));
    }

    /**
     * filter records as per input
     *
     * @param \Illuminate\Http\Request $request (the input which is use to filter data in search records)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function searchRecordSearching(Request $request, RecordsService $recordsService)
    {
        $records = $recordsService->searchRecords($request);
        session([
            'patient_name' => $request->patient_name,
            'from_date_of_service' => $request->from_date_of_service,
            'to_date_of_service' => $request->to_date_of_service,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'provider_name' => $request->provider_name,
        ]);

        if ($request->has('request_status')) {
            Session::put('request_status', $request->request_status);
        } else {
            Session::forget('request_status');
        }

        if ($request->has('request_type')) {
            Session::put('request_type', $request->request_type);
        } else {
            Session::forget('request_type');
        }

        return view('adminPage.records.searchRecords', compact('records'));
    }

    /**
     * export filtered data to excel
     *
     * @param \Illuminate\Http\Request $request ( the input which is use to filter data in search records)
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFilteredData(Request $request, RecordsService $recordsService)
    {
        $data = $recordsService->exportFilteredDataToExcel($request);

        if ($data->get()->isEmpty()) {
            return back()->with('message', 'no records to export to Excel');
        }
        $export = new SearchRecordExport($data);
        return Excel::download($export, 'search_record_filtered_data.xls');
    }

    /**
     * delete record permanently from request client ,request,block_request,request_concierge,request_business,request_status,request_wise_file
     *
     * @param mixed $id  (id of request table)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSearchRecordData($id)
    {
        $getRequestId = RequestClient::where('id', $id)->value('request_id');

        RequestWiseFile::where('request_id', $getRequestId)->forceDelete();
        RequestStatus::where('request_id', $getRequestId)->forceDelete();
        RequestBusiness::where('request_id', $getRequestId)->forceDelete();
        RequestConcierge::where('request_id', $getRequestId)->forceDelete();
        BlockRequest::where('request_id', $getRequestId)->forceDelete();
        RequestNotes::where('request_id', $getRequestId)->forceDelete();
        RequestClient::where('id', $id)->forceDelete();
        RequestTable::where('id', $getRequestId)->forceDelete();

        return redirect()->back()->with('message', 'record is permanently delete');
    }

    // --------- 6.2 : Email Logs ---------
    /**
     * Display EmailLogs pages with all the log data.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function emailRecordsView()
    {
        $emails = EmailLog::with(['roles'])->orderByDesc('id')->paginate(10);

        return view('adminPage.records.emailLogs', compact('emails'));
    }

    /**
     * Search/filter EmailLogs.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchEmail(Request $request)
    {
        $roleId = $request->role_id;
        $receiverName = $request->receiver_name;
        $email = $request->email;
        $createdDate = $request->created_date;
        $sentDate = $request->sent_date;

        $emails = EmailLog::query();

        // Filter based on role_id (if provided)
        if ($roleId) {
            $emails->where('role_id', $roleId);
        }

        // Filter based on receiver_name (like operator)
        if ($receiverName) {
            $emails->where('recipient_name', 'LIKE', "%{$receiverName}%");
        }

        // Filter based on email (like operator)
        if ($email) {
            $emails->where('email', 'LIKE', "%{$email}%");
        }

        // Filter based on created_date (exact match)
        if ($createdDate) {
            $emails->where('created_at', 'Like', "%{$createdDate}%");
        }

        // Filter based on sent_date (exact match)
        if ($sentDate) {
            $emails->where('sent_date', 'Like', "%{$sentDate}%");
        }

        // Retrieve pagination parameters from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // Paginate results (10 items per page)
        $emails = $emails->paginate($perPage, ['*'], 'page', $page);
        $emails->appends(request()->query());

        return view('adminPage.records.emailLogs', compact('emails', 'roleId', 'receiverName', 'email', 'createdDate', 'sentDate'));
    }

    // --------- 6.3 : SMS Logs -----------
    /**
     * list receipient name ,action,role_name,mobile,create_date,sent_date,confirmation_number,is_sent_sent_tries
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function smsRecordsView()
    {
        $smsLogs = SMSLogs::latest('id')->paginate(10);
        Session::forget('role_type');
        return view('adminPage.records.smsLogs', compact('smsLogs'));
    }

    /**
     * filter sms logs
     *
     * @param \Illuminate\Http\Request $request  (the input which is enter by admin to filter data)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function searchSMSLogs(Request $request, RecordsService $recordsService)
    {
        $smsLogs = $recordsService->filterSMSLogs($request);
        session(
            [
                'receiver_name' => $request->input('receiver_name'),
                'phone_number' => $request->input('phone_number'),
                'created_date' => $request->input('created_date'),
                'sent_date' => $request->input('sent_date'),
            ]
        );

        // if (!empty($request->role_type)) {
        if ($request->role_type) {
            Session::put('role_type', $request->role_type);
        } else {
            Session::forget('role_type');
        }

        return view('adminPage.records.smsLogs', compact('smsLogs'));
    }

    // --------- 6.4 : Patient Records ----
    /**
     * Display the patient history page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function patientHistoryView()
    {
        $patients = RequestClient::paginate(10);

        return view('adminPage.records.patientHistory', compact('patients'));
    }

    /**
     * Search patient data based on provided criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchPatientData(Request $request)
    {
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $email = $request->email;
        $phoneNumber = $request->phone_number;

        $patients = RequestClient::query();

        if ($firstName) {
            $patients->where('first_name', 'LIKE', "%{$firstName}%");
        }
        if ($lastName) {
            $patients->where('last_name', 'LIKE', "%{$lastName}%");
        }
        if ($email) {
            $patients->where('email', 'LIKE', "%{$email}%");
        }
        if ($phoneNumber) {
            $patients->where('phone_number', 'LIKE', "%{$phoneNumber}%");
        }

        $patients = $patients->paginate(10);

        return view('adminPage.records.patientHistory', compact('patients', 'firstName', 'lastName', 'email', 'phoneNumber'));
    }

    /**
     * Display patient records view.
     *
     * @param  string|null $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function patientRecordsView($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
            $email = RequestClient::where('id', $id)->pluck('email')->first();
            $data = RequestClient::with(['request'])->where('email', $email)->get();

            $requestId = RequestClient::where('id', $id)->first()->request_id;
            $documentCount = RequestWiseFile::where('request_id', $requestId)->get()->count();
            $isFinalize = RequestWiseFile::where('request_id', $requestId)->where('is_finalize', true)->first();

            return view('adminPage.records.patientRecords', compact('data', 'documentCount', 'isFinalize'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // --------- 6.5 : Blocked History ----

    /**
     * List of block request
     * list patient name,mobile,email,created date and notes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function blockHistoryView(RecordsService $recordsService)
    {
        $blockData = $recordsService->blockHistory();
        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    /**
     * filter data according to request
     *
     * @param \Illuminate\Http\Request $request (input which is enter by admin to filter data)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function blockHistroySearchData(Request $request, RecordsService $recordsService)
    {
        $blockData = $recordsService->filterBlockHistoryData($request);
        session([
            'patient_name' => $request->patient_name,
            'date' => $request->date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    /**
     * it check and uncheck checkbox in is_Active columns of listing through ajax
     *
     * @param \Illuminate\Http\Request $request (input which is check or uncheck by admin)
     *
     * @return void
     */
    public function updateBlockHistoryIsActive(Request $request)
    {
        $block = BlockRequest::find($request->blockId);
        $block->update(['is_active' => $request->is_active]);
    }

    /**
     * unblock patient and set status 1 in request_Status and request table
     *
     * @param mixed $id (id of request table)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unBlockPatientInBlockHistoryPage($id)
    {
        RequestTable::where('id', $id)->update(['status' => 1]);
        RequestStatus::where('request_id', $id)->update(['status' => 1]);
        BlockRequest::where('request_id', $id)->delete();
        return redirect()->back()->with('message', 'patient is unblock');
    }
}
