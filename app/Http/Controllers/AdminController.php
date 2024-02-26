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
use App\Models\BlockRequest;
use Illuminate\Support\Facades\Mail;

// DomPDF package used for the creation of pdf from the form
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\RequestStack;
// To create zip, used to download multiple documents at once
use ZipArchive;


class AdminController extends Controller
{
    public function totalCasesCount()
    {
        // Total count of cases as per the status (displayed in all listing pages)
        $newCasesCount = RequestStatus::where('status', 1)->count(); // unassigned case, assigned to provider but not accepted
        $pendingCasesCount = RequestStatus::where('status', 3)->count(); //accepted by provider, pending state
        $activeCasesCount = RequestStatus::where('status', 4)->orWhere('status', 5)->count(); //MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider)
        $concludeCasesCount = RequestStatus::where('status', 6)->count();
        $tocloseCasesCount = RequestStatus::where('status', 2)->orWhere('status', 7)->count();
        $unpaidCasesCount = RequestStatus::where('status', 9)->count();

        return [
            'newCase' => $newCasesCount,
            'pendingCase' => $pendingCasesCount,
            'activeCase' => $activeCasesCount,
            'concludeCase' => $concludeCasesCount,
            'tocloseCase' => $tocloseCasesCount,
            'unpaidCase' => $unpaidCasesCount,
        ];
    }

    // provides all cases data as per status
    public function cases($status, $count)
    {
        if ($status == 'new') {
            $cases = RequestStatus::with('request')->where('status', 1)->paginate(10);
            return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count'));
        } else if ($status == 'pending') {
            $cases = RequestStatus::with('request')->where('status', 3)->paginate(10);
            return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count'));
        } else if ($status == 'active') {
            $cases = RequestStatus::with('request')->where('status', 4)->orWhere('status', 5)->paginate(10);
            return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count'));
        } else if ($status == 'conclude') {
            $cases = RequestStatus::with('request')->where('status', 6)->paginate(10);
            return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count'));
        } else if ($status == 'toclose') {
            $cases = RequestStatus::with('request')->where('status', 2)->orWhere('status', 7)->paginate(10);
            return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'count'));
        } else if ($status == 'unpaid') {
            $cases = RequestStatus::with('request')->where('status', 9)->paginate(10);
            return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count'));

        }
    }


    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        $count = $this->totalCasesCount();
        return $this->cases($status, $count);
    }


    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)

    public function adminFilter(Request $request, $status = 'new', $category = 'all')
    {
        $count = $this->totalCasesCount();


        // By default, category is all, and when any other button is clicked for filter that data will be passed to the view.
        if ($category == 'all') {
            // Retrieve data for all request type
            return $this->cases($status, $count);
        } else {
            // Retrieve data for specific request type using request_type_id
            // Provides data as per the status and required category
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count'));
            } else if ($status == 'pending') {
                $cases = RequestStatus::where('status', 3)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count'));
            } else if ($status == 'active') {
                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count'));
            } else if ($status == 'conclude') {
                $cases = RequestStatus::where('status', 6)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count'));
            } else if ($status == 'toclose') {
                $cases = RequestStatus::where('status', 7)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'count'));
            } else if ($status == 'unpaid') {
                $cases = RequestStatus::where('status', 9)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count'));
            }
        }
    }

    // Search for specific keyword in first_name of requestTable 
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        $count = $this->totalCasesCount();

        // check for both status & category and fetch data for only the searched term  
        if ($category == 'all') {
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                })->paginate(10);
                return view('providerPage.providerTabs.newListing', compact('cases', 'count'));
            } else if ($status == 'pending') {
                $cases = RequestStatus::where('status', 3)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                })->paginate(10);
                return view('providerPage.providerTabs.pendingListing', compact('cases', 'count'));
            } else if ($status == 'active') {
                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                })->paginate(10);
                return view('providerPage.providerTabs.activeListing', compact('cases', 'count'));
            } else if ($status == 'conclude') {
                $cases = RequestStatus::where('status', 6)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                })->paginate(10);
                return view('providerPage.providerTabs.concludeListing', compact('cases', 'count'));
            }
        } else {
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);
                return view('providerPage.providerTabs.newListing', compact('cases', 'count'));
            } else if ($status == 'pending') {

                $cases = RequestStatus::where('status', 3)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);
                return view('providerPage.providerTabs.pendingListing', compact('cases', 'count'));
            } else if ($status == 'active') {

                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);
                return view('providerPage.providerTabs.activeListing', compact('cases', 'count'));
            } else if ($status == 'conclude') {

                $cases = RequestStatus::where('status', 6)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);
                return view('providerPage.providerTabs.concludeListing', compact('cases', 'count'));
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

    public function cancelCaseOptions()
    {
        $reasons = caseTag::all();
        return response()->json($reasons);
    }
    // Store cancel case request_id, status(cancelled), adminId, & Notes(reason) in requestStatusLog
    public function cancelCase(Request $request)
    {
        RequestTable::where('id', $request->requestId)->update(['case_tag' => $request->case_tag]);
        RequestStatus::where('request_id', $request->requestId)->update(['status' => 2]);

        // Check if there is an entry with the specified 'request_id' and 'notes' is null in RequestStatus
        $statusEntry = RequestStatus::where('request_id', $request->requestId)->first();

        if (!empty($statusEntry->notes)) {
            // Entry exists with 'notes' as null, update in RequestNotes
            RequestNotes::where('request_id', $request->requestId)
                ->update(['AdministrativeNotes' => $request->reason]);
        } else {
            // Entry doesn't exist or 'notes' is not null, perform insert in RequestNotes
            $newNoteId = RequestNotes::insertGetId(['request_id' => $request->requestId, 'AdministrativeNotes' => $request->reason, 'created_by' => "admin"]);
            RequestStatus::where('request_id', $request->requestId)->update(['notes' => $newNoteId]);
            // RequestNotes::updateOrInsert(['request_id' => $request->requestId], ['AdministrativeNotes' => $request->reason]);
        }
        return redirect()->back();
    }

    public function blockCase(Request $request)
    {
        BlockRequest::insert(['request_id' => $request->requestId, 'reason' => $request->block_reason]);
        return redirect()->back();
    }
}
