<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Different Models used in these Controller
use App\Models\requestTable;
use App\Models\request_Client;
use App\Models\MedicalReport;
use App\Models\RequestNotes;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\DB;
use App\Models\caseTag;
use App\Models\RequestStatus;

// For sending Mails
use App\Mail\SendMail;
use App\Mail\SendAgreement;
use Illuminate\Support\Facades\Mail;

// DomPDF package used for the creation of pdf from the form
use Barryvdh\DomPDF\Facade\Pdf;


// To create zip, used to download multiple documents at once
use ZipArchive;


class AdminController extends Controller
{
    
    // Display Admin Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        // Total count of cases as per the status (displayed in all listing pages)
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();
        $tocloseCasesCount = requestTable::with(['requestClient'])->where('status', 5)->count();
        $unpaidCasesCount = requestTable::with(['requestClient'])->where('status', 6)->count();

        // $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);

        $cases = DB::table('request')
                ->join('request_client','request_client.request_id','=','request.id')
                ->select(
                'request.id as id',
                'request_client.first_name as client_first_name',
                'request_client.date_of_birth as date_of_birth',
                'request.first_name as request_first_name',
                'request_client.phone_number as mobile',
                'request.email',
                'request.created_at',
                'request_client.street as street',
                'request.request_type_id',
                'request.last_name as request_last_name')
                ->where('status', $this->getStatusId($status))
                ->paginate(10);

     
    
        // As per the selected Tab, different view (listing pages) are rendered
        if ($this->getStatusId($status) == '1') {
            return view('adminPage.adminTabs.adminNewListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount', 'tocloseCasesCount', 'unpaidCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount', 'tocloseCasesCount', 'unpaidCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount', 'tocloseCasesCount', 'unpaidCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount', 'tocloseCasesCount', 'unpaidCasesCount'));
        } else if ($this->getStatusId($status) == '5') {
            return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount', 'tocloseCasesCount', 'unpaidCasesCount'));
        } else if ($this->getStatusId($status) == '6') {
            return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount', 'tocloseCasesCount', 'unpaidCasesCount'));
        }
    }

    public function totalCasesCount()
    {
        // Total count of cases as per the status (displayed in all listing pages)
     
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count(); // unassigned case, assigned to provider but not accepted
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count(); //accepted by provider, pending state
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count(); //MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider)
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();
        $toCloseCaseCount = requestTable::with(['requestClient'])->where('status', 5)->count();
        $unPaidCasesCount = requestTable::with(['requestClient'])->where('status', 6)->count();

        return [
            'newCase' => $newCasesCount,
            'pendingCase' => $pendingCasesCount,
            'activeCase' => $activeCasesCount,
            'concludeCase' => $concludeCasesCount,
            'toCloseCount'=>$toCloseCaseCount,
            'unPaidCount'=>$unPaidCasesCount
        ];
    }

    
    public function adminFilter(Request $request, $status = 'new', $category = 'all')
    {
        $count = $this->totalCasesCount();

        // dd($count);

        // By default, category is all, and when any other button is clicked for filter that data will be passed to the view.
        if ($category == 'all') {
            // Retrieve data for all request type
            return $this->cases($status, $count);
        } else {
            // Retrieve data for specific request type using request_type_id
            // Provides data as per the status and required category
            if ($status == 'new') {
                $cases = requestTable::with(['requestClient'])->where('status', 1)->where('request_type_id', $this->getCategoryId($category))->paginate(10);
                return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count'));
            } else if ($status == 'pending') {
                $cases = requestTable::with(['requestClient'])->where('status', 2)->where('request_type_id', $this->getCategoryId($category))->paginate(10);
                return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count'));
            }else if ($status == 'active') {
                $cases = requestTable::with(['requestClient'])->where('status', 3)->orWhere('status', 5)->where('request_type_id', $this->getCategoryId($category))->paginate(10);
                return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count'));
            } 
            else if ($status == 'conclude') {
                $cases = requestTable::with(['requestClient'])->where('status', 4)->where('request_type_id', $this->getCategoryId($category))->paginate(10);
                return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count'));
            }
            else if ($status == 'close') {
                $cases = requestTable::with(['requestClient'])->where('status', 5)->orWhere('status', 5)->where('request_type_id', $this->getCategoryId($category))->paginate(10);
                return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'count'));
            } 
            else if ($status == 'unpaid') {
                $cases = requestTable::with(['requestClient'])->where('status', 6)->orWhere('status', 5)->where('request_type_id', $this->getCategoryId($category))->paginate(10);
                return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count'));
            } 
        }
    }
    
    //Get category id from the name of category
    private function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => 1,
            'family' => 2,
            'business' => 3,
            'concierge' => 4,
        ];
        return $categoryMapping[$category] ?? null;
    }

    //Get status id from the name of status
    private function getStatusId($status)
    {
        // mapping of status names to status
        $statusMapping = [
            'new' => 1,
            'pending' => 2,
            'active' => 3,
            'conclude' => 4,
            'toclose' => 5,
            'unpaid' => 6,
        ];
        return $statusMapping[$status] ?? null;
    }



    public function cancelCaseOptions()
    {
        $reasons = caseTag::all();
        return response()->json($reasons);
    }
    // Store cancel case request_id, status(cancelled), adminId, & Notes(reason) in requestStatusLog
    public function cancelCase(Request $request)
    {
        // Got request_id and reason(note)
       dd($request->requestId, $request->reason);

       

       // change status for these case
    }
}
