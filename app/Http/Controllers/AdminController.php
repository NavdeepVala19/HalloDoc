<?php

namespace App\Http\Controllers;

use ZipArchive;
use Carbon\Carbon;

// Different Models used in these Controller
use App\Models\Roles;
use App\Mail\SendLink;
use App\Mail\SendMail;

// Different Models used in these Controller
use App\Models\Orders;
use App\Models\caseTag;
use App\Models\Regions;
use App\Models\allusers;
use App\Models\EmailLog;

use App\Models\Provider;

// For sending Mails
use App\Models\UserRoles;
use App\Mail\SendAgreement;
use App\Models\BlockRequest;
use App\Models\RequestNotes;
use App\Models\requestTable;
use Illuminate\Http\Request;
use App\Models\MedicalReport;

// DomPDF package used for the creation of pdf from the form
use App\Models\RequestStatus;
// To create zip, used to download multiple documents at once
use App\Models\request_Client;
use App\Models\PhysicianRegion;
use App\Models\RequestWiseFile;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HealthProfessional;
use Illuminate\Support\Facades\DB;
use App\Exports\SearchRecordExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\RequestSupportMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\HealthProfessionalType;
use App\Models\Menu;
use App\Models\RequestClosed;
use App\Models\Role;
use App\Models\RoleMenu;
use Symfony\Component\HttpFoundation\RequestStack;


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
    public function cases($status, $count, $userData)
    {
        if ($status == 'new') {
            $cases = RequestStatus::with('request')->where('status', 1)->paginate(10);
            return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'pending') {
            $cases = RequestStatus::with('request')->where('status', 3)->paginate(10);
            return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'active') {
            $cases = RequestStatus::with('request')->where('status', 4)->orWhere('status', 5)->paginate(10);
            return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'conclude') {
            $cases = RequestStatus::with('request')->where('status', 6)->paginate(10);
            return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'toclose') {
            $cases = RequestStatus::with('request')->where('status', 2)->orWhere('status', 7)->paginate(10);
            return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'unpaid') {
            $cases = RequestStatus::with('request')->where('status', 9)->paginate(10);
            return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count', 'userData'));
        }
    }

    // Admin dashboard
    public function adminDashboard()
    {
        return redirect('/admin/new');
    }

    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        $userData = Auth::user();
        // dd($userData);
        $count = $this->totalCasesCount();
        return $this->cases($status, $count, $userData);
    }



    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)

    public function adminFilter(Request $request, $status = 'new', $category = 'all')
    {
        $userData = Auth::user();
        $count = $this->totalCasesCount();


        // By default, category is all, and when any other button is clicked for filter that data will be passed to the view.
        if ($category == 'all') {
            // Retrieve data for all request type
            return $this->cases($status, $count, $userData);
        } else {
            // Retrieve data for specific request type using request_type_id
            // Provides data as per the status and required category
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'pending') {
                $cases = RequestStatus::where('status', 3)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'active') {
                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'conclude') {
                $cases = RequestStatus::where('status', 6)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'toclose') {
                $cases = RequestStatus::where('status', 7)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'unpaid') {
                $cases = RequestStatus::where('status', 9)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count', 'userData'));
            }
        }
    }



    // Search for specific keyword in first_name of requestTable 
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        $userData = Auth::user();
        $count = $this->totalCasesCount();


        // check for both status & category and fetch data for only the searched term  
        if ($category == 'all') {
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)
                    ->whereHas('request', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%');
                        $q->orWhereHas('requestClient', function ($query) use ($request) {
                            $query->where('first_name', 'like', "%$request->search%");
                        });
                    })->paginate(10);
                return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'pending') {
                $cases = RequestStatus::where('status', 3)
                    ->whereHas('request', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%');
                        $q->orWhereHas('requestClient', function ($query) use ($request) {
                            $query->where('first_name', 'like', "%$request->search%");
                        });
                    })->paginate(10);
                return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'active') {
                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)
                    ->whereHas('request', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%');
                        $q->orWhereHas('requestClient', function ($query) use ($request) {
                            $query->where('first_name', 'like', "%$request->search%");
                        });
                    })->paginate(10);
                return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'conclude') {
                $cases = RequestStatus::where('status', 6)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                    $q->orWhereHas('requestClient', function ($query) use ($request) {
                        $query->where('first_name', 'like', "%$request->search%");
                    });
                })->paginate(10);
                return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'toclose') {
                $cases = RequestStatus::where('status', 2)->orWhere('status', 7)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                    $q->orWhereHas('requestClient', function ($query) use ($request) {
                        $query->where('first_name', 'like', "%$request->search%");
                    });
                })->paginate(10);
                return view('adminPage.adminTabs.adminTocloseListing', compact('cases', 'count'));
            } else if ($status == 'unpaid') {
                $cases = RequestStatus::where('status', 9)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                    $q->orWhereHas('requestClient', function ($query) use ($request) {
                        $query->where('first_name', 'like', "%$request->search%");
                    });
                })->paginate(10);
                return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count'));
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

                return view('adminPage.adminTabs.adminNewListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'pending') {

                $cases = RequestStatus::where('status', 3)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('adminPage.adminTabs.adminPendingListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'active') {

                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('adminPage.adminTabs.adminActiveListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'conclude') {

                $cases = RequestStatus::where('status', 6)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('adminPage.adminTabs.adminConcludeListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'toclose') {

                $cases = RequestStatus::where('status', 7)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('adminPage.adminTabs.adminToCloseListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'unpaid') {

                $cases = RequestStatus::where('status', 9)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('adminPage.adminTabs.adminUnpaidListing', compact('cases', 'count', 'userData'));
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

    // Assign case - All physician Regions
    public function physicianRegions()
    {
        $regions = Regions::get();
        return response()->json($regions);
    }

    public function getPhysicians($id = null)
    {
        $physiciansId = PhysicianRegion::where('region_id', $id)->pluck('provider_id')->toArray();
        $physicians = Provider::whereIn('id', $physiciansId)->get()->toArray();
        return response()->json($physicians);
    }
    // assign Case login 
    public function assignCase(Request $request)
    {
        RequestStatus::where('request_id', $request->requestId)->update(['TransToPhysicianId' => $request->physician]);
        return redirect()->back();
    }

    public function transferCase(Request $request)
    {
        RequestStatus::where('request_id', $request->requestId)->where('TransToAdmin', true)
            ->update([
                'TransToPhysicianId' => $request->physician,
                'TransToAdmin' => null,
                'notes' => $request->notes,
                'status' => 1
            ]);
        return redirect()->back();
    }

    // fetch all caseTag data from its table and show in cancelCase PopUp
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
            // Entry exists with 'notes' as null, update in RequestStatus
            RequestStatus::where('request_id', $request->requestId)
                ->update(['notes' => $request->reason]);
        } else {
            // Entry doesn't exist or 'notes' is not null, perform insert in RequestStatus
            RequestStatus::where('request_id', $request->requestId)->update(['notes' => $request->reason]);
        }
        return redirect()->back();
    }

    // Admin Blocks patient
    public function blockCase(Request $request)
    {
        // Block patient phone number, email, requestId and reason given by admin stored in block_request table
        $client = request_Client::where('request_id', $request->requestId)->first();
        BlockRequest::create([
            'request_id' => $request->requestId,
            'reason' => $request->block_reason,
            'phone_number' => $client->phone_number,
            'email' => $client->email
        ]);
        RequestStatus::where('request_id', $request->requestId)->update(['status' => 10]);

        return redirect()->back();
    }

    // View case
    public function viewCase($id)
    {
        $data = RequestTable::where('id', $id)->first();
        return view('adminPage.pages.viewCase', compact('data'));
    }

    // View Notes
    public function viewNote($id)
    {
        return view('adminPage.pages.viewNotes');
    }

    public function viewUpload($id)
    {
        $data  = requestTable::where('id', $id)->first();
        $documents = RequestWiseFile::get();
        return view('adminPage.pages.viewUploads', compact('data', 'documents'));
    }

    // ****************** This code is for Sending Link ************************

    public function sendMail(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
        ]);

        Mail::to($request->email)->send(new SendLink($request->all()));

        EmailLog::create([
            'role_id' => 1,
            // 'provider_id' => specify provider id
            // 'email_template' =>,
            // 'subject_name' =>,
            'is_email_sent' => true,
            'sent_tries' => 1,
            'sent_date' => now(),
            'email_template' => 'mail.blade.php',
            'subject_name' => 'Create Request Link',
            'email' => $request->email,
        ]);

        return redirect()->back();
    }


    public function clearCase(Request $request)
    {
        RequestStatus::where('request_id', $request->requestId)->update(['status' => 8]);
        return redirect()->back();
    }

    public function closeCase(Request $request, $id = null)
    {
        $data = RequestTable::where('id', $id)->first();
        $files = RequestWiseFile::where('id', $id)->get();
        return view('adminPage.pages.closeCase', compact('data', 'files'));
    }
    public function closeCaseData(Request $request)
    {
        if ($request->input('closeCaseBtn') == 'Save') {
            request_Client::where('request_id', $request->requestId)->update([
                'phone_number' => $request->phone_number,
                'email' => $request->email
            ]);
        } else if ($request->input('closeCaseBtn') == 'Close Case') {
            RequestStatus::where('request_id', $request->requestId)->update(['status' => 9]);
            $statusId = RequestStatus::where('request_id', $request->requestId)->pluck('id')->first();
            RequestClosed::insert([
                'request_id' => $request->requestId,
                'request_status_id' => $statusId
            ]);
            return redirect()->route('admin.status', 'unpaid');
        }
        return redirect()->back();
    }

    // Show Partners page in Admin
    public function viewPartners($id = null)
    {
        if ($id == null || $id == '0') {
            $vendors = HealthProfessional::with('healthProfessionalType')->get();
        } else if ($id) {
            $vendors = HealthProfessional::with('healthProfessionalType')->where('profession', $id)->get();
        }
        $professions = HealthProfessionalType::get();

        // dd($id);
        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id'));
    }
    // Search Partner as per the input 
    public function searchPartners(Request $request)
    {
        $id = $request->profession;
        // dd($id);
        if ($id == null || $id == '0') {
            $vendors = HealthProfessional::with('healthProfessionalType')->where('vendor_name', 'LIKE', "%{$request->search}%")->get();
        } else if ($id) {
            $vendors = HealthProfessional::with('healthProfessionalType')->where('profession', $id)->where('vendor_name', 'LIKE', "%{$request->search}%")->get();
            // dd($vendors);
        }
        $professions = HealthProfessionalType::get();
        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id'));
    }

    // Add Business page
    public function addBusinessView()
    {

        $types = HealthProfessionalType::get();
        return view('adminPage.partners.addBusiness', compact('types'));
    }

    // Add Business Logic
    public function addBusiness(Request $request)
    {
        HealthProfessional::insert([
            'vendor_name' => $request->buisness_name,
            'profession' => $request->profession,
            'fax_number' => $request->fax_number,
            'phone_number' => $request->mobile,
            'email' => $request->email,
            'business_contact' => $request->business_contact,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'address' => $request->street,
        ]);

        return redirect()->route('admin.partners');
    }

    // update Business Page
    public function updateBusinessView($id)
    {
        // HealthProfessional Id whose value need to be updated
        $vendor = HealthProfessional::where('id', $id)->first();
        $professions = HealthProfessionalType::get();
        return view('adminPage.partners.updateBusiness', compact("vendor", 'professions'));
    }
    public function updateBusiness(Request $request)
    {
        HealthProfessional::where('id', $request->vendor_id)->update([
            'vendor_name' => $request->buisness_name,
            'profession' => $request->profession,
            'fax_number' => $request->fax_number,
            'phone_number' => $request->mobile,
            'email' => $request->email,
            'business_contact' => $request->business_contact,
            'address' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip
        ]);
        return redirect()->back();
    }

    public function viewOrder($id = null)
    {
        $types = HealthProfessionalType::get();
        return view('adminPage.pages.sendOrder', compact('id', 'types'));
    }

    // Send orders from action menu 
    public function sendOrder(Request $request)
    {
        Orders::insert([
            'vendor_id' => $request->vendor_id,
            'request_id' => $request->requestId,
            'fax_number' => $request->fax_number,
            'business_contact' => $request->business_contact,
            'email' => $request->email,
            'prescription' => $request->prescription,
            'no_of_refill' => $request->refills,
        ]);

        return redirect()->route('admin.status', 'active');
    }
    public function deleteBusiness($id = null)
    {
        HealthProfessional::where('id', $id)->delete();
        return redirect()->back();
    }

    // Fetch business values (health_professional values) as per the profession selected in Send Orders page
    public function fetchBusiness(Request $request, $id)
    {
        $business = HealthProfessional::where('profession', $id)->get();

        return response()->json($business);
    }
    public function fetchBusinessData($id)
    {
        $businessData = HealthProfessional::where('id', $id)->first();

        return response()->json($businessData);
    }

    // Access Page
    public function accessView()
    {
        $roles = Role::get();
        return view('adminPage.access.access', compact('roles'));
    }
    public function createRoleView()
    {
        $menus = Menu::get();
        return view('adminPage.access.createRole', compact('menus'));
    }

    public function fetchRoles($id = null)
    {
        if ($id == 0) {
            $menus = Menu::get();
            return response()->json($menus);
        } else if ($id == 1) {
            $menus = Menu::where('account_type', 'Admin')->get();
            return response()->json($menus);
        } else if ($id == 2) {
            $menus = Menu::where('account_type', 'Physician')->get();
            return response()->json($menus);
        }
    }

    public function createAccess(Request $request)
    {
        $this->validate($request, [
            'role_name' => 'required|in:1,2,3', [
                'role_name.required' => 'Please select a menu from the dropdown list.',
                'role_name.in' => 'Invalid menu selection. Please choose a valid role option.'
            ],
            'role' => 'required'
        ]);
        if ($request->role_name == 1) {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'admin']);
        } else if ($request->role_name == 2) {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'physician']);
        }

        foreach ($request->input('menu_checkbox') as $key => $value) {
            RoleMenu::insert([
                'role_id' => $roleId,
                'menu_id' => $value
            ]);
        }
        return redirect()->route('admin.access.view');
    }

    public function deleteAccess($id = null)
    {
        Role::where('id', $id)->delete();
        return redirect()->back();
    }

    public function editAccess($id = null)
    {
        $role = Role::where('id', $id)->first();
        $roleMenus = RoleMenu::where('role_id', $id)->get();
        $menus = Menu::get();
        // dd($role, $menus);
        return view('adminPage.access.editAccess', compact('role', 'roleMenus', 'menus'));
    }



    // Records Page
    public function searchRecordsView()
    {

        // Getting Providers Name and status type

        // $searchRecordsData2 = RequestTable::select(
        //     'provider.first_name',
        //     'request_status.status',
        //     'status.status_type'
        // )
        //     ->leftJoin('provider', 'request.physician_id', 'provider.id')
        //     ->leftJoin('request_status', 'request.id', 'request_status.request_id')
        //     ->leftJoin('status', 'status.id', 'request_status.status')
        //     ->get();


        // Getting Patient Name.email,mobile,address,notes(admin,patient,physician) and request_Type

        // $searchRecordsData = request_Client::select(
        //     'request.request_type_id',
        //     'request_client.first_name',
        //     'request_client.email',
        //     'request_client.phone_number',
        //     'request_client.street',
        //     'request_client.city',
        //     'request_client.state',
        //     'request_client.zipcode',
        //     'request_notes.patient_notes',
        //     'request_notes.physician_notes',
        //     'request_notes.admin_notes'
        // )
        //     ->leftJoin('request', 'request.id', 'request_client.request_id')
        //     ->leftJoin('request_notes', 'request_notes.request_id', 'request_client.request_id')
        //     ->paginate(10);

        // This combinedData is the combination of data from RequestClient,Request,RequestNotes,Provider,RequestStatus and Status

        $combinedData = request_Client::distinct()->select([
            'request.request_type_id',
            'request_client.first_name',
            'request_client.id',
            'request_client.email',
            DB::raw('DATE(request_client.created_at) as created_date'),
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request_notes.patient_notes',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request_status.status',
            'provider.first_name as physician_first_name',
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('request_status', 'request_status.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('status', 'status.id', '=', 'request_status.status')
            ->paginate(10);



        return view('adminPage.records.searchRecords', compact('combinedData'));
    }


    public function searchRecordSearching(Request $request)
    {
        $combinedData = $this->exportFilteredSearchRecord($request);
        $combinedData = $combinedData->paginate(10);

        $session = session(
            [
                'request_status' => $request->input('request_status'),
                'patient_name' => $request->input('patient_name'),
                'request_type' => $request->input('request_type'),
                'from_date_of_service' => $request->input('from_date_of_service'),
                'to_date_of_service' => $request->input('to_date_of_service'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'provider_name' => $request->input('provider_name'),
            ]
        );

        return view('adminPage.records.searchRecords', compact('combinedData'));
    }

    public function exportFilteredSearchRecord($request)
    {

        $combinedData = request_Client::distinct()->select([
            'request_client.first_name',
            'request.request_type_id',
            DB::raw('DATE(request_client.created_at) as created_date'),
            'request_client.email',
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request_status.status',
            'provider.first_name as physician_first_name',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request_notes.patient_notes',
            'request_client.id',
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('request_status', 'request_status.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('status', 'status.id', '=', 'request_status.status');

        if (!empty($request->patient_name)) {
            $combinedData = $combinedData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        if (!empty($request->email)) {
            $combinedData = $combinedData->orWhere('request_client.email', "like", "%" . $request->email . "%");
        }
        if (!empty($request->phone_number)) {
            $combinedData = $combinedData->orWhere('request_client.phone_number', "like", "%" . $request->phone_number . "%");
        }
        if (!empty($request->request_type)) {
            $combinedData = $combinedData->orWhere('request.request_type_id', "like", "%" . $request->request_type . "%");
        }
        if (!empty($request->provider_name)) {
            $combinedData = $combinedData->orWhere('provider.first_name', "like", "%" . $request->provider_name . "%");
        }
        if (!empty($request->request_status)) {
            $combinedData = $combinedData->orWhere('request_status.status', "like", "%" . $request->request_status . "%");
        }
        if (!empty($request->from_date_of_service)) {
            $combinedData = $combinedData->orWhere('request_client.created_at', "like", "%" . $request->from_date_of_service . "%");
        }

        return $combinedData;
    }


    public function downloadFilteredData(Request $request)
    {

        $data = $this->exportFilteredSearchRecord($request);
        $export = new SearchRecordExport($data);

        return Excel::download($export, 'filtered_data.xls');
    }



    public function deleteSearchRecordData($id)
    {
        $deleteData = request_Client::where('id', $id)->forceDelete();
        return redirect()->back();
    }



    public function emailRecordsView()
    {
        $emails = EmailLog::with(['roles'])->get();
        return view('adminPage.records.emailLogs', compact('emails'));
    }
    public function searchEmail(Request $request)
    {
        // if ($request->role_id == 0) {
        //     return redirect()->route('admin.email.records.view');
        // }

        $emails = EmailLog::when($request->role_id, function ($query) use ($request) {
            $query->where('role_id', $request->role_id);
        })
            ->when($request->email, function ($query) use ($request) {
                $query->where('email', 'LIKE',  "%$request->email%");
            })
            ->when($request->created_date, function ($query) use ($request) {
                $query->where('created_at', "LIKE", "%$request->created_date%");
                // Carbon::parse($request->created_at)->format('Y-m-d')
            })->when($request->sent_date, function ($query) use ($request) {
                $query->where('sent_date', $request->sent_date);
            })->get();

        // dd($emails);
        // dd(EmailLog::where('email', 'LIKE', "%$request->email%")->get(   ));
        // ->when($request->receiver_name, function ($query) use ($request) {
        //     $query->where('last_name', 'LIKE', "%$request->last_name%");
        // })

        return view('adminPage.records.emailLogs', compact('emails'));
    }
    public function smsRecordsView()
    {
        return view('adminPage.records.smsLogs');
    }
    public function blockHistoryView()
    {
        return view('adminPage.records.blockHistory');
    }
    public function patientHistoryView()
    {
        $patients = request_Client::paginate(10);
        return view('adminPage.records.patientHistory', compact('patients'));
    }
    public function searchPatientData(Request $request)
    {
        $patients = request_Client::where('first_name', 'LIKE',  "%$request->first_name%")
            ->when($request->last_name, function ($query) use ($request) {
                $query->where('last_name', 'LIKE', "%$request->last_name%");
            })
            ->when($request->email, function ($query) use ($request) {
                $query->where('email', 'LIKE',  "%$request->email%");
            })
            ->when($request->phone_number, function ($query) use ($request) {
                $query->where('phone_number', 'LIKE', "%$request->phone_number%");
            })->paginate(10);

        return view('adminPage.records.patientHistory', compact('patients'));
    }
    public function patientRecordsView($id = null)
    {

        $email = request_Client::where('id', $id)->pluck('email')->first();
        $data = request_Client::where('email', $email)->get();
        $status = RequestStatus::with(['statusTable', 'provider'])->where('request_id', $id)->first();
        // dd($status);
        return view('adminPage.records.patientRecords', compact('data', 'status'));
    }

    // Cancel History Page
    public function viewCancelHistory()
    {
        $cancelCases = RequestStatus::with('request')->where('status', 2)->get();
        // dd($cancelCases);
        return view('adminPage.records.cancelHistory', compact('cancelCases'));
    }
    // search cancel case in Cancel History Page
    public function searchCancelCase(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $date = $request->date;
        $phone_number = $request->phone_number;
        // dd($request->name);
        $query = RequestStatus::where('status', 2);

        $query->whereHas('request', function ($query) use ($request) {
            $query->whereHas('requestClient', function ($clientQuery) use ($request) {
                $clientQuery->where(function ($subQuery) use ($request) {
                    $subQuery->where('first_name', 'LIKE', "%$request->name%")->orWhere('last_name', 'LIKE', "%$request->name%");
                })->when($request->email, function ($query) use ($request) {
                    return $query->where('email', 'LIKE', "%$request->email%");
                })->when($request->phone_number, function ($query) use ($request) {
                    return $query->where('phone_number', 'LIKE', "%$request->phone_number%");
                })->when($request->date, function ($query) use ($request) {
                    return $query->where('request.updated_at', $request->date);
                });
            });
        });

        $cancelCases = $query->get();
        // dd($cancelCases);

        return view('adminPage.records.cancelHistory', compact('cancelCases'));
    }
    public function patientViews()
    {
        return view('adminPage.records.patientRecords');
    }


    public function UserAccess()
    {

        $userAccessData = allusers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('user_roles.id', '>', '13')
            ->paginate(10);

        return view('adminPage.access.userAccess', compact('userAccessData'));
    }

    public function UserAccessEdit($id)
    {

        $UserAccessRoleName = Roles::select('name')
            ->leftJoin('user_roles', 'user_roles.role_id', 'roles.id')
            ->where('user_roles.user_id', $id)
            ->whereBetween('user_roles.id', [14, 25])
            ->get();

        if ($UserAccessRoleName->first()->name == 'admin') {
            return redirect()->route('adminProfile', ['id' => $id]);
        } else if ($UserAccessRoleName->first()->name == 'physician') {
            $getProviderId = Provider::where('user_id', $id);
            return redirect()->route('adminEditProvider', ['id' => $getProviderId->first()->id]);
        }
    }

    public function sendRequestSupport(Request $request)
    {

        $requestMessage = $request->contact_msg;
        Mail::to('recipient@example.com')->send(new RequestSupportMessage($requestMessage));
        return redirect()->back();
    }


    // fetching regions from regions table and show in All Regions drop-down button
    public function fetchRegions()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

    // *****  fetching only that data which is filter-by All-Regions drop-down button  ****
    public function filterPatientByRegion(Request $request)
    {
        $status = $request->status;
        $regionId = $request->regionId;
        $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();

        $cases = [];
        if ($status == 'new') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 1)->get();
        } else if ($status == 'pending') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 3)->get();
        } else if ($status == 'active') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 5)->get();
        } else if ($status == 'conclude') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 6)->get();
        } else if ($status == 'toclose') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 7)->get();
        } else if ($status == 'unpaid') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 9)->get();
        }

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($cases as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->requestClient->first_name,
                'last_name' => $patient->requestClient->last_name,
                'date_of_birth' => $patient->requestClient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'created_at' => $patient->requestClient->created_at,
                'phone_number' => $patient->requestClient->phone_number,
                'street' => $patient->requestClient->street,
                'city' => $patient->requestClient->city,
                'state' => $patient->requestClient->state,
            ];
        }
        $data = view('adminPage.adminTabs.regions-filter-new')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }


    public function FilterUserAccessAccountTypeWise(Request $request)
    {
        $account = $request->accountType == "all" ? '' : $request->accountType;

        $userAccessDataFiltering = allusers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id');

        if (!empty($account) && isset($account)) {
            $userAccessDataFiltering = $userAccessDataFiltering->where('roles.name', '=', $account);
        }
        $userAccessDataFiltering = $userAccessDataFiltering->get();


        $data = view('adminPage.access.userAccessFiltering')->with('userAccessData', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }


    public function filterPatientByRegionActiveState($selectedId)
    {
        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-active')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }


    public function filterPatientByRegionConcludeState($selectedId)
    {

        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-conclude')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }
    public function filterPatientByRegionToCloseState($selectedId)
    {

        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-to-close')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }

    public function filterPatientByRegionUnPaidState($selectedId)
    {

        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-unpaid')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }
}
