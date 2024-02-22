<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Different Models used in these Controller
use App\Models\requestTable;
use App\Models\request_Client;
use App\Models\MedicalReport;
use App\Models\RequestNotes;
use App\Models\RequestWiseFile;

use App\Models\caseTag;

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

        $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);

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
