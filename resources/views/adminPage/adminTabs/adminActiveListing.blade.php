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
{{-- This page will display patient requests for which patients have accepted the service agreement and provider is
giving service to the patient. --}}
<div class="overlay"></div>

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

{{-- Encounter --}}
<div class="pop-up encounter">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Select Type Of Care</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('encounter') }}" method="GET">
        <div class="p-4 d-flex align-items-center justify-content-center gap-2">
            <button type="button" class="primary-empty housecall-btn">Housecall</button>
            {{-- If the provider will select house-call, then another dropdown will be visible to select the approximate
            arrival time of the provider to that patient’s house. That dropdown contains options from 0.5 hour to 6 hour
            with a 30-minute gap. --}}
            <button type="button" class="primary-empty consult-btn">Consult</button>
            <input type="text" name="caseId" class="case-id" value="" hidden>
            {{-- If the provider selects the consult, then that request will move into Conclude state. --}}
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <div class="form-floating time-dropdown">
                <select class="form-select" id="floatingSelect">
                    <option selected value="30min">30 Minutes</option>
                    <option value="1hour">1 Hour </option>
                    <option value="1hour 30Minutes">1 Hour 30 Minutes</option>
                    <option value="2Hour">2 Hour</option>
                    <option value="2Hour 30Minutes">2 Hour 30 Minutes</option>
                    <option value="3Hour">3 Hour</option>
                    <option value="3Hour 30Minutes">3 Hour 30 Minutes</option>
                    <option value="4Hour ">4 Hour</option>
                    <option value="4Hour 30Minutes">4 Hour 30 Minutes</option>
                    <option value="5Hour">5 Hour</option>
                    <option value="5Hour 30Minutes">5 Hour 30 Minutes</option>
                    <option value="6Hour">6 Hour</option>
                </select>
                <label for="floatingSelect">Select Approximate Arrival Time</label>
            </div>
            {{-- <button class="primary-fill encounter-save-btn">Save</button> --}}
            <input type="submit" class="primary-fill encounter-save-btn" id="save-btn" value="Save">
            <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </form>
</div>


<div class="bg-blur">
    <nav>
        <div class="nav nav-tabs " id="nav-tab">
            <a href="{{ route('admin.status', ['status' => 'new']) }}" class="nav-link" id="nav-new-tab">
                <div class="case case-new p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
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

            <a href="{{ route('admin.status', ['status' => 'active']) }}" class="nav-link active" id="nav-active-tab">
                <div class="case case-active active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
                <h3>Patients </h3> <strong class="case-type ps-2 ">(Active)</strong>
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
                <a href="{{route('exportActive')}}" class="primary-fill">
                    <i class="bi bi-send-arrow-down"></i>
                    <span class="txt">
                        Export
                    </span>
                </a>
                <form action="{{route('exportActive')}}" method="POST" id="filterExport" class="d-none">
                    @csrf
                    <input name="filter_search" value="" hidden>
                    <input name="filter_region" value="" hidden>
                    <input name="filter_category" value="" hidden>
                    <button type="submit" hidden>export</button>
                </form>
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
                <form action="{{ route('searching', ['status' => 'active', 'category' => request('category', 'all')]) }}" method="GET" class="d-flex align-items-center">
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
                    <a href="{{ route('admin.listing', ['category' => 'all', 'status' => 'active']) }}" data-category="all" class="btn-all filter-btn">All</a>
                    <a href="{{ route('admin.listing', ['category' => 'patient', 'status' => 'active']) }}" data-category="patient" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('admin.listing', ['category' => 'family', 'status' => 'active']) }}" data-category="family" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('admin.listing', ['category' => 'business', 'status' => 'active']) }}" data-category="business" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('admin.listing', ['category' => 'concierge', 'status' => 'active']) }}" data-category="concierge" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Date Of Birth</th>
                            <th>Requestor</th>
                            <th>Physician Name</th>
                            <th>Date Of Service</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dropdown-data-body">
                        @foreach ($cases as $case)
                        @if (!empty($case->request) && !empty($case->request->requestClient))
                        <tr class="type-{{ $case->request->request_type_id }}">
                            <td>{{ $case->request->requestClient->first_name }}</td>
                            <td>{{ $case->request->requestClient->date_of_birth }}</td>
                            <td>Requestor Name</td>
                            <td>Physician Name</td>
                            <td>{{ $case->request->created_at }}</td>
                            <td>{{ $case->request->phone_number }}</td>
                            <td>{{ $case->request->requestClient->street }},
                                {{ $case->request->requestClient->city }},{{ $case->request->requestClient->state }}
                            </td>
                            <td>Notes</td>
                            <td>
                                <div class="action-container">
                                    <button class="table-btn action-btn" data-id={{ $case->request->id }}>Actions</button>
                                    <div class="action-menu">
                                        <a href="{{ route('provider.view.case', $case->request->id) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View
                                            Case</a>
                                        <a href="{{ route('provider.view.upload', ['id' => $case->request->id]) }}"><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                            Uploads</a>
                                        <button><i class="bi bi-journal-text me-2 ms-3"></i>View
                                            Notes</button>
                                        <a href="{{ route('admin.view.order', $case->request->id) }}"><i class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                                        <button><i class="bi bi-text-paragraph me-2 ms-3"></i>Doctors
                                            Note</button>
                                        <button class="encounter-btn"><i class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                    @if ($case->request->request_type_id == 1)
                    <span>
                        Patient
                        <i class="bi bi-circle-fill ms-1 green"></i>
                    </span>
                    @elseif ($case->request->request_type_id == 2)
                    <span>
                        Family/Friend
                        <i class="bi bi-circle-fill ms-1 yellow"></i>
                    </span>
                    @elseif ($case->request->request_type_id == 3)
                    <span>
                        Business
                        <i class="bi bi-circle-fill ms-1 red"></i>
                    </span>
                    @elseif ($case->request->request_type_id == 4)
                    <span>
                        Concierge
                        <i class="bi bi-circle-fill ms-1 blue"></i>
                    </span>
                    @endif
                    <button class="map-btn">Map Location</button>
                </div>
            </div>
            <div class="more-info ">
                <a href="{{ route('provider.view.case', $case->request->id) }}" class="view-btn">View
                    Case</a>
                <div>
                    <span>
                        <i class="bi bi-calendar3"></i> Date of birth :
                        @isset($case->request->requestClient)
                        {{ $case->request->requestClient->date_of_birth }}
                        @endisset
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-envelope"></i> Email :
                        @isset($case->request->requestClient)
                        {{ $case->request->requestClient->email }}
                        @endisset
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-telephone"></i> Patient :
                        @isset($case->request->requestClient)
                        {{ $case->request->phone_number }}
                        @endisset
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-cash"></i> Transfer :Admin transferred to
                        @isset($case->request->requestClient)
                        {{ $case->request->requestClient->last_name }}
                        @endisset
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-calendar3"></i> Date of services :
                        @isset($case->request->requestClient)
                        {{ $case->request->created_at }}
                        @endisset
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-person-circle"></i> Physician :
                        @isset($case->request->requestClient)
                        {{ $case->request->last_name }}
                        @endisset
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-person-plus-fill"></i> Requestor:
                        @isset($case->request->requestClient)
                        {{ $case->request->first_name }}
                        @endisset
                    </span>
                    <div class="grid-2-listing ">
                        <a href="{{ route('provider.view.notes', $case->request->id) }}" class="secondary-btn text-center">View
                            Notes</a>
                        <button class="secondary-btn-1">Doctors Notes</button>
                        <button class="secondary-btn">View Uploads</button>
                        <button class="secondary-btn">Encouter</button>
                        <button class="secondary-btn-2">orders</button>
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
</div>
@endsection