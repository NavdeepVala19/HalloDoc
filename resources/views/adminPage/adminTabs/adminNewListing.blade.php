@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
@endsection


@section('username')
    {{ !empty($userData) ? $userData->username : '' }}
@endsection



@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('adminProvidersInfo') }}">Provider</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.scheduling') }}">Scheduling</a></li>
            <li><a class="dropdown-item" href="">Invoicing</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="{{ route('admin.access.view') }}">Access</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.search.records.view') }}">Search Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="overlay"></div>

    {{-- Error or Success Message Alerts/Pop-ups --}}
    {{-- Admin Logged In Successfully --}}
    @if (session('message'))
        <h6 class="alert alert-success popup-message">
            {{ session('message') }}
        </h6>
    @endif


    {{-- Case Assigned Successfully --}}
    @if (session('assigned'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('assigned') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Case Blocked Successfully --}}
    @if (session('CaseBlocked'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('CaseBlocked') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    

    {{-- Physician Not selected in Assign Case Action --}}
    @if ($errors->has('physician'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ $errors->first('physician') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif


    {{-- SendLink Validation Error pop-ups --}}
    @if ($errors->any())
        <div class="alert alert-danger popup-message ">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        <span>{{ $error }}</span>
                        <i class="bi bi-exclamation-circle"></i>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Cancel Case Pop-up --}}
    {{-- This pop-up will open when admin will click on “Cancel case” link from Actions menu. Admin can cancel the request using this pop-up. --}}
    <div class="pop-up cancel-case">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Confirm Cancellation</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="m-3">
            <span>Patient Name: </span> <span class="displayPatientName">patient name</span>
        </div>
        <form action="{{ route('admin.cancel.case') }}" method="POST">
            @csrf
            <input type="text" class="requestId" name="requestId" value="" hidden>
            <div class="m-3">
                <div class="form-floating">
                    <select class="form-select" name="case_tag"
                        class="cancel-options @error('case_tag') is-invalid @enderror" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option selected>Reasons</option>
                    </select>
                    <label for="floatingSelect">Reasons for Cancellation</label>
                    @error('case_tag')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <textarea class="form-control" name="reason" placeholder="notes" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Provide Additional Notes</label>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <input type="submit" value="Confirm" class="primary-fill cancel-case">
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Assign Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Assign case” link from Actions menu. Admin can assign the case
to providers based on patient’s region using this pop-up. --}}
    <div class="pop-up assign-case">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Assign Request</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <p class="m-2">To assign this request, search and select another Physician</p>
        <form action="{{ route('admin.assign.case') }}" method="POST">
            @csrf
            <div class="m-3">
                <input type="text" class="requestId" name="requestId" value="" hidden>
                <div class="form-floating">
                    <select class="form-select physicianRegions" name="region" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option selected>Regions</option>
                    </select>
                    <label for="floatingSelect">Narrow Search by Region</label>
                </div>
                <div class="form-floating">
                    <select
                        class="form-select selectPhysician @error('physician')
                    is-invalid
                    @enderror"
                        name="physician" id="floatingSelect" aria-label="Floating label select example" required>
                        <option>Select Physician</option>
                    </select>
                    <label for="floatingSelect">Select Physician</label>
                    @error('physician')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <textarea class="form-control @error('assign_note')
                        is-invalid
                    @enderror"  name="assign_note" placeholder="Description" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Description</label>
                    @error('assign_note')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="submit" class="primary-fill confirm-case">Submit</button>
                <button class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Block Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Block Case” link from Actions menu. From the new state, admin
can block any case. All blocked cases can be seen in Block history page. --}}
    <div class="pop-up block-case">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Confirm Block</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="m-3">
            <span>Patient Name: </span>
            <span class="displayPatientName"></span>
        </div>
        <form action="{{ route('admin.block.case') }}" method="POST">
            @csrf
            <div class="m-3">
                <input type="text" class="requestId" name="requestId" value="" hidden>
                <div class="form-floating">
                    <textarea class="form-control @error('block_reason') is-invalid @enderror" name="block_reason" placeholder="Reason for block request" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Reason for Block Request</label>
                    @error('block_reason')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <input type="submit" value="Confirm" class="primary-fill">
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
        <form action="{{ route('admin.send.mail') }}" method="POST">
            @csrf
            <div class="p-4 d-flex flex-column align-items-center justify-content-center gap-2">
                <div class="form-floating ">
                    <input type="text" name="first_name"
                        class="form-control @error('first_name') is-invalid @enderror" id="floatingInput"
                        placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                        id="floatingInput" placeholder="Last Name">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <input type="tel" name="phone_number"
                    class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone"
                    placeholder="Phone Number">
                @error('phone_number')
                    <div class="text-danger w-100">{{ $message }}</div>
                @enderror
                <div class="form-floating ">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email</label>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <input type="submit" value="Send" class="primary-fill">
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>


    {{-- Request DTY Support pop-up ->  --}}
    <div class="pop-up request-support">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Request Support</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('sendRequestSupport') }}" method="POST">
            @csrf
            <div class="p-4 d-flex flex-column align-items-center justify-content-center gap-2">

                <p>To all unscheduled Physicians:We are short on coverage and needs additional support On Call to respond to
                    Requests</p>

                <div class="form-floating ">
                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="contact_msg"
                        style="height: 120px"></textarea>
                    <label for="floatingTextarea2">Message</label>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <input type="submit" value="Send" class="primary-fill">
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>

    <nav>
        <div class="nav nav-tabs state-grid-3 " id="nav-tab">
            <a href="{{ route('admin.status', ['status' => 'new']) }}" class="nav-link active" id="nav-new-tab">
                <div class="case case-new active p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                    <span>
                        <i class="bi bi-plus-circle"></i> NEW
                    </span>
                    <span>
                        {{ $count['newCase'] }}
                    </span>
                </div>
            </a>

            <a href="{{ route('admin.status', ['status' => 'pending']) }}" class="nav-link" id="nav-pending-tab">
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

            <a href="{{ route('admin.status', ['status' => 'unpaid']) }}" class="nav-link" id="nav-unpaid-tab">
                <div class="case case-unpaid p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
            <div class="d-flex align-items-center">
                <h3>Patients </h3> <strong class="case-type ps-2">(New)</strong>
            </div>
            <div class="admin-btn d-flex gap-2">
                <button class="primary-fill send-link-btn">
                    <i class="bi bi-send"></i>
                    <span class="txt">
                        Send Link
                    </span>
                </button>
                <a href="{{ route('adminPatientRequest') }}" class="primary-fill">
                    <i class="bi bi-pencil-square"></i>
                    <span class="txt">
                        Create Requests
                    </span>
                </a>
                <a href="{{ route('exportNewData') }}" class="primary-fill" id="filterExportBtnNew">
                    <i class="bi bi-send-arrow-down"></i>
                    <span class="txt">
                        Export
                    </span>
                </a>
                <form action="{{ route('exportNewData') }}" method="POST" id="filterExport">
                    @csrf
                    <input name="filter_search" value="" hidden>
                    <input name="filter_region" value="" hidden>
                    <input name="filter_category" value="" hidden>
                    <button type="submit" hidden>export</button>
                </form>
                <a href="{{ route('exportAll') }}" class="primary-fill">
                    <i class="bi bi-send-arrow-down-fill"></i>
                    <span class="txt">
                        Export All
                    </span>
                </a>
                <button class="primary-fill request-support-btn">
                    <i class="bi bi-person-square"></i>
                    <span class="txt">
                        Request DTY Support
                    </span>
                </button>
            </div>
        </div>
        <div class="listing">
            <div class="search-section d-flex align-items-center  justify-content-between ">
                <form action="{{ route('searching', ['status' => 'new', 'category' => request('category', 'all')]) }}"
                    method="GET" class="d-flex align-items-center filter-section">
                    {{-- @csrf --}}
                    <div class="input-group mb-3">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                            placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search">
                        {{-- <input type="submit" class="primary-fill"> --}}
                    </div>
                    <select class="form-select listing-region">
                        <option name="regions" selected>All Regions</option>
                    </select>
                </form>
                <div class="src-category d-flex gap-3 align-items-center">
                    <a href="{{ route('admin.listing', ['category' => 'all', 'status' => 'new']) }}" data-category="all"
                        class="btn-all filter-btn">All</a>
                    <a href="{{ route('admin.listing', ['category' => 'patient', 'status' => 'new']) }}"
                        data-category="patient" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('admin.listing', ['category' => 'family', 'status' => 'new']) }}"
                        data-category="family" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('admin.listing', ['category' => 'business', 'status' => 'new']) }}"
                        data-category="business" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('admin.listing', ['category' => 'concierge', 'status' => 'new']) }}"
                        data-category="concierge" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill blue"></i>Concierge</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Date Of Birth</th>
                            <th>Requestor</th>
                            <th>Requested Date</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dropdown-data-body">
                        @foreach ($cases as $case)
                            @if (!empty($case->requestClient))
                                <tr class="type-{{ $case->request_type_id }}">
                                    <td>{{ $case->requestClient->first_name }}
                                        {{ $case->requestClient->last_name }}
                                    </td>
                                    <td>{{ $case->requestClient->date_of_birth }}</td>
                                    <td>{{ $case->first_name }} {{ $case->last_name }}</td>
                                    <td>{{ $case->created_at }}</td>
                                    <td>{{ $case->phone_number }}</td>
                                    <td>
                                        {{ $case->requestClient->street }},
                                        {{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                    </td>
                                    <td>{{ $case->requestClient->notes }}</td>
                                    <td>
                                        <div class="action-container">
                                            <button class="table-btn action-btn">Actions</button>
                                            <div class="action-menu">
                                                <button class="assign-case-btn" data-id="{{ $case->id }}"><i
                                                        class="bi bi-journal-check me-2 ms-3"></i>Assign Case</button>
                                                <button class="cancel-case-btn" data-id="{{ $case->id }}"
                                                    data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"><i
                                                        class="bi bi-x-circle me-2 ms-3"></i>Cancel
                                                    Case</button>
                                                <a href="{{ route('admin.view.case', $case->id) }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View
                                                    Case</a>
                                                <a href="{{ route('admin.view.note', $case->id) }}"><i
                                                        class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                                <button class="block-case-btn" data-id="{{ $case->id }}"
                                                    data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}">
                                                    <i class="bi bi-ban me-2 ms-3"></i>
                                                    Block Patient</button>
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
                    @if (!empty($case->requestClient))
                        <div class="mobile-list d-flex justify-content-center align-items-between flex-column">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}
                                </span>
                                <div>
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
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="address-section">
                                    @if ($case->requestClient)
                                        {{ $case->requestClient->street }},{{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                    @endif
                                </span>
                                <button class="map-btn">Map Location</button>
                            </div>
                        </div>
                        <div class="more-info">
                            <a href="{{ route('admin.view.case', $case->id) }}" class="view-btn">View Case</a>
                            <div>
                                <span>
                                    <i class="bi bi-calendar3"></i> Date of birth :
                                    {{ $case->requestClient->date_of_birth }}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-envelope"></i> Email :
                                    {{ $case->requestClient->email }}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-telephone"></i> Patient :
                                    {{ $case->requestClient->phone_number }}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-person-plus-fill"></i> Requestor :
                                    {{ $case->first_name }} {{ $case->last_name }}
                                </span>
                                <div class="grid-2-listing">
                                    <button class="secondary-btn-5 text-center assign-case-btn"
                                        data-id={{ $case->id }}>Assign
                                        Case</button>
                                    <button data-id="{{ $case->id }}"
                                        data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"
                                        class="secondary-btn-4 text-center cancel-case-btn">Cancel
                                        Case</button>
                                    <a href="{{ route('admin.view.note', $case->id) }}"
                                        class="secondary-btn text-center">View
                                        Notes</a>
                                    <button data-id="{{ $case->id }}"
                                        data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"
                                        class="secondary-btn-4 text-center block-case-btn">Block
                                        Patient</button>
                                    <a href="#" class="secondary-btn text-center">Email</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="page">
        {{ $cases->links('pagination::bootstrap-5') }}
    </div>
@endsection

@section('script')
    <script defer src="{{ URL::asset('assets/adminPage/adminExportExcelData.js') }}"></script>
@endsection
