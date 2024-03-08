@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
@endsection

{{--
@section('username')
    {{ $userData->username }}
@endsection
--}}

@section('nav-links')
<a href="" class="active-link">Dashboard</a>
<a href="{{route('providerLocation')}}">Provider Location</a>
<a href="">My Profile</a>
<a href="">Providers</a>
<a href="{{ route('admin.partners') }}">Partners</a>
<a href="{{ route('admin.access.view') }}">Access</a>
<div class="dropdown record-navigation ">
    <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Records
    </button>
    <ul class="dropdown-menu records-menu">
        <li><a class="dropdown-item " href="{{ route('admin.search.records.view') }}">Search Records</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
    </ul>
</div>
@endsection

@section('content')
{{-- Patient requests that have been accepted by providers or are still pending the acceptance of the service agreement
by patients. --}}
{{-- When providers accept a patient request, they are required to send an agreement video link via email and SMS to the
patient's email address and phone number. Once the patient accepts the agreement, their request will transition from the
"Pending" state to the "Active" state. --}}

<div class="overlay"></div>

{{-- Send Agreement Pop-up --}}
{{-- This pop-up will open when admin/provider will click on “Send agreement” link from Actions menu. From the
pending state, providers need to send an agreement link to patients. --}}
<div class="pop-up send-agreement">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Send Agreement</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="p-3">
        <div>
            <span class="request-detail">Show the name and color of request (i.e. patinet, family, business,
                concierge)</span>
            <p class="m-2">To send Agreement please make sure you are updating the correct contact information below
                for
                the
                responsible party.
            </p>
        </div>
        <form action="{{ route('send.agreement') }}" method="POST">
            @csrf
            <input type="text" class="send-agreement-id" name="request_id" value="" hidden>
            <div>
                <div class="form-floating ">
                    <input type="text" name="phone_number" class="form-control" id="floatingInput" placeholder="Phone Number">
                    <label for="floatingInput">Phone Number</label>
                </div>
                <div class="form-floating ">
                    <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email</label>
                </div>
            </div>
    </div>
    <div class="p-2 d-flex align-items-center justify-content-end gap-2">
        <input type="submit" value="Send" class="primary-fill send-case">
        <button class="primary-empty hide-popup-btn">Cancel</button>
    </div>
    </form>
</div>

{{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
<div class="pop-up send-link">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Send mail to patient for submitting request</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="p-4 d-flex flex-column align-items-center justify-content-center gap-2">
        <div class="form-floating ">
            <input type="text" name="first_name" class="form-control" id="floatingInput" placeholder="First Name">
            <label for="floatingInput">First Name</label>
            @error('first_name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating ">
            <input type="text" name="last_name" class="form-control" id="floatingInput" placeholder="Last Name">
            <label for="floatingInput">Last Name</label>
            @error('last_name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number">
        @error('phone_number')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form-floating ">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email</label>
        </div>

    </div>
    <div class="p-2 d-flex align-items-center justify-content-end gap-2">
        <button class="primary-fill">Send</button>
        <button class="primary-empty hide-popup-btn">Cancel</button>
    </div>
</div>
{{-- Transfer Request --}}
<div class="pop-up transfer-request">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Transfer Request</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="p-4 d-flex align-items-center justify-content-center gap-2">
        <div class="form-floating">
            <textarea class="form-control transfer-description" placeholder="injury" id="floatingTextarea2"></textarea>
            <label for="floatingTextarea2">Description</label>
        </div>
    </div>
    <div class="p-2 d-flex align-items-center justify-content-end gap-2">
        <button class="primary-fill">Submit</button>
        <button class="primary-empty hide-popup-btn">Cancel</button>
    </div>
</div>

<nav>
    <div class="nav nav-tabs " id="nav-tab">
        <a href="{{ route('admin.status', ['status' => 'new']) }}" class="nav-link" id="nav-new-tab">
            <div class="case case-new  p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                <span>
                    <i class="bi bi-plus-circle"></i> NEW
                </span>
                <span>
                    {{ $count['newCase'] }}
                </span>
            </div>
        </a>

        <a href="{{ route('admin.status', ['status' => 'pending']) }}" class="nav-link " id="nav-pending-tab">
            <div class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                <span>
                    <i class="bi bi-person-square"></i> PENDING
                </span>
                <span>
                    {{ $count['pendingCase'] }}
                </span>
            </div>
        </a>

        <a href="{{ route('admin.status', ['status' => 'active']) }}" class="nav-link" id="nav-active-tab">
            <div class="case case-active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                <span>
                    <i class="bi bi-check2-circle"></i> ACTIVE
                </span>
                <span>
                    {{ $count['activeCase'] }}
                </span>
            </div>
        </a>

        <a href="{{ route('admin.status', ['status' => 'conclude']) }}" class="nav-link" id="nav-conclude-tab">
            <div class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                <span>
                    <i class="bi bi-clock-history"></i> CONCLUDE
                </span>
                <span>
                    {{ $count['concludeCase'] }}
                </span>
            </div>
        </a>

        <a href="{{ route('admin.status', ['status' => 'toclose']) }}" class="nav-link" id="nav-toclose-tab">
            <div class="case case-toclose p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                <span>
                    <i class="bi bi-person-fill-x"></i> TO CLOSE
                </span>
                <span>
                    {{ $count['tocloseCase'] }}
                </span>
            </div>
        </a>

        <a href="{{ route('admin.status', ['status' => 'unpaid']) }}" class="nav-link active" id="nav-unpaid-tab">
            <div class="case case-unpaid active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                <span>
                    <i class="bi bi-cash-coin"></i> UNPAID
                </span>
                <span>
                    {{ $count['unpaidCase'] }}
                </span>
            </div>
        </a>
    </div>
</nav>

<div class="main">
    <div class="heading-section d-flex align-items-center justify-content-between">
        <div class=" d-flex align-items-center">
            <h3>Patients </h3> <strong class="case-type ps-2 ">(Unpaid)</strong>
        </div>
        <div class="admin-btn  d-flex gap-2">
            <button class="primary-fill send-link-btn">
                <i class="bi bi-send"></i>
                <span class="txt">
                    Send Link
                </span>
            </button>
            <a href="{{route('adminPatientRequest')}}" class="primary-fill">
                <i class="bi bi-pencil-square"></i>
                <span class="txt">
                    Create Requests
                </span>
            </a>
            <a href="{{route('exportUnPaid')}}" class="primary-fill">
                <i class="bi bi-send-arrow-down"></i>
                <span class="txt">
                    Export
                </span>
            </a>
            <a href="" class="primary-fill">
                <i class="bi bi-send-arrow-down-fill"></i>
                <span class="txt">
                    Export All
                </span>
            </a>
            <button class="primary-fill request-support-btn">
                <i class="bi bi-pencil-square"></i>
                <span class="txt">
                    Request DTY Support
                </span>
            </button>
        </div>
    </div>


    <div class="listing">
        <div class="search-section d-flex align-items-center  justify-content-between ">
            <form action="{{ route('searching', ['status' => 'unpaid', 'category' => request('category', 'all')]) }}" method="GET" class="d-flex align-items-center">
                {{-- @csrf --}}
                <div class="input-group mb-3">
                    <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient" placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search">
                    {{-- <input type="submit" class="primary-fill"> --}}
                </div>
                <select class="form-select listing-region">
                    <option name="regions" selected>All Regions</option>
                </select>
            </form>
            <div class="src-category d-flex gap-3 align-items-center">
                <a href="{{ route('admin.listing', ['category' => 'all', 'status' => 'unpaid']) }}" class="btn-all filter-btn">All</a>
                <a href="{{ route('admin.listing', ['category' => 'patient', 'status' => 'unpaid']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                <a href="{{ route('admin.listing', ['category' => 'family', 'status' => 'unpaid']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                <a href="{{ route('admin.listing', ['category' => 'business', 'status' => 'unpaid']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                <a href="{{ route('admin.listing', ['category' => 'concierge', 'status' => 'unpaid']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</a>
            </div>
        </div>
        <div class="table-responsive">



            <table class="table table-hover ">
                <thead class="table-secondary">
                    <tr>
                        <th>Name</th>
                        <th>Physician Name</th>
                        <th>Date Of Service</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Chat With</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="dropdown-data-body">
                    @foreach ($cases as $case)
                    @if (!empty($case->request) && !empty($case->request->requestClient))
                    <tr class="type-{{ $case->request->request_type_id }}">
                        <td>{{ $case->request->requestClient->first_name }}</td>
                        <td>Physician Name</td>
                        <td>{{ $case->request->created_at }}</td>
                        <td>{{ $case->request->phone_number }}</td>
                        <td>
                            {{ $case->request->requestClient->street }},
                            {{ $case->request->requestClient->city }},{{ $case->request->requestClient->state }}
                        </td>
                        <td>
                            <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
                            <button class="table-btn"><i class="bi bi-person-check me-2"></i>Provider</button>
                        </td>
                        <td>
                            <div class="action-container">
                                <button class="table-btn action-btn" data-id={{ $case->request->id }}>Actions</button>
                                <div class="action-menu">
                                    <a href="{{ route('provider.view.case', $case->request->id) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                    <button><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                        Uploads</button>
                                    <button><i class="bi bi-journal-text me-2 ms-3"></i>
                                        View Notes</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mobile-listing">
            @foreach ($cases as $case)
            @if (!empty($case->request) && !empty($case->request->requestClient))
            <div class="mobile-list d-flex justify-content-between">
                <div class="d-flex flex-column">
                    <p>{{ $case->request->first_name }} </p>
                    <span>Address:
                        @if ($case->request->requestClient)
                        {{ $case->request->requestClient->street }},{{ $case->request->requestClient->city }},{{ $case->request->requestClient->state }}
                        @endif
                    </span>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-around">
                    @if ($case->request_type_id == 1)
                    <span>
                        Patient
                        <i class="bi bi-circle-fill ms-1 green"></i>
                    </span>
                    @elseif ($case->request_type_id == 2)
                    <span>
                        Family/Friend
                        <i class="bi bi-circle-fill ms-1 yellow"></i>
                    </span>
                    @elseif ($case->request_type_id == 3)
                    <span>
                        Business
                        <i class="bi bi-circle-fill ms-1 red"></i>
                    </span>
                    @elseif ($case->request_type_id == 4)
                    <span>
                        Concierge
                        <i class="bi bi-circle-fill ms-1 blue"></i>
                    </span>
                    @endif
                    <button class="map-btn">Map Location</button>
                </div>
            </div>
            <div class="more-info ">
                <a href="{{ route('provider.view.case', $case->id) }}" class="view-btn">View Case</a>
                <div>
                    <span>
                        <i class="bi bi-calendar3"></i> Date of birth :
                        {{ $case->request->requestClient->date_of_birth }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-envelope"></i> Email :
                        {{ $case->request->requestClient->email }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-cash"></i> Transfer :Admin transferred to
                        {{ $case->request->requestClient->last_name }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-calendar3"></i> Date of services :
                        {{ $case->request->created_at }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-person-circle"></i> Physician :
                        {{ $case->request->last_name }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-person-plus-fill"></i> Requestor:
                        {{ $case->request->first_name }}
                    </span>
                    <div class="grid-2-listing ">
                        <a href="{{ route('provider.view.notes', $case->id) }}" class="secondary-btn text-center">View
                            Notes</a>
                        <button class="secondary-btn">View Uploads</button>
                        <button class="secondary-btn">Email</button>
                    </div>
                </div>
                <div>
                    Chat With:
                    <button class="more-info-btn"><i class="bi bi-person me-2"></i>Patient</button>
                    <button class="more-info-btn"><i class="bi bi-person-check me-2"></i>Admin</button>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="page">
            {{ $cases->links('pagination::bootstrap-5') }}
        </div>
    </div>


</div>
@endsection