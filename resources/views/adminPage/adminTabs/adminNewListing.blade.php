@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
@endsection

@section('nav-links')
<a href="" class="active-link">Dashboard</a>
<a href="">Provider Location</a>
<a href="">My Profile</a>
<a href="">Providers</a>
<a href="">Partners</a>
<a href="">Access</a>
<a href="">Records</a>
@endsection

@section('content')
<div class="overlay"></div>

@if(session('message'))
<h6 class="alert alert-success">
    {{ session('message') }}
</h6>
@endif

{{-- Cancel Case Pop-up --}}
{{-- This pop-up will open when admin will click on “Cancel case” link from Actions menu. Admin can cancel the request
using this pop-up. --}}
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
                <select class="form-select" name="case_tag" class="cancel-options" id="floatingSelect"
                    aria-label="Floating label select example">
                    <option selected>Reasons</option>
                    {{-- <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option> --}}
                </select>
                <label for="floatingSelect">Reasons for Cancellation</label>
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
    <div class="m-3">
        <div class="form-floating">
            <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                <option selected>Regions</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <label for="floatingSelect">Narrow Search by Region</label>
        </div>
        <div class="form-floating">
            <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                <option selected>Select Physician</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <label for="floatingSelect">Select Physician</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control" placeholder="Description" id="floatingTextarea2"></textarea>
            <label for="floatingTextarea2">Description</label>
        </div>
    </div>
    <div class="p-2 d-flex align-items-center justify-content-end gap-2">
        <button class="primary-fill cancel-case">Confirm</button>
        <button class="primary-empty hide-popup-btn">Cancel</button>
    </div>
</div>


{{-- Block Case Pop-up --}}
{{-- This pop-up will open when admin clicks on “Block Case” link from Actions menu. From the new state, admin
can block any case. All blocked cases can be seen in Block history page. --}}
<div class="pop-up block-case">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Assign Request</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="m-3">
        <span>Patient Name: </span>
        <span class="displayPatientName"></span>
    </div>
    <div class="m-3">
        <form action="route{{'admin.block.case'}}" method="POST">
            @csrf
            <input type="text" class="requestId" name="requestId" value="" hidden>
            <div class="form-floating">
                <textarea class="form-control" name="block_reason" placeholder="Reason for block request"
                    id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Reason for Block Request</label>
            </div>
        </form>
    </div>
    <div class="p-2 d-flex align-items-center justify-content-end gap-2">
        <input type="submit" value="Confirm" class="primary-fill">
        <button class="primary-empty hide-popup-btn">Cancel</button>
    </div>
</div>

{{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
<div class="pop-up send-link">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Send mail to patient for submitting request</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('send.mail') }}" method="POST">
        @csrf
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
                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
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
    <form action="" method="POST">
        @csrf
        <div class="p-4 d-flex flex-column align-items-center justify-content-center gap-2">

        <p>To all unscheduled Physicians:We are short on coverage and needs additional support On Call to respond to Requests</p>

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
    <div class="nav nav-tabs " id="nav-tab">
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

        <a href="{{ route('admin.status', ['status' => 'toclose']) }}" class="nav-link" id="nav-conclude-tab">
            <div class="case case-toclose p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                <span>
                    <i class="bi bi-person-fill-x"></i> TO CLOSE
                </span>
                <span>
                    {{ $count['tocloseCase'] }}
                </span>
            </div>
        </a>

        <a href="{{ route('admin.status', ['status' => 'unpaid']) }}" class="nav-link" id="nav-conclude-tab">
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
            <a href="{{ route('provider.create.request') }}" class="primary-fill">
                <i class="bi bi-pencil-square"></i>
                <span class="txt">
                    Create Requests
                </span>
            </a>
            <a href="{{ route('provider.create.request') }}" class="primary-fill">
                <i class="bi bi-send-arrow-down"></i>
                <span class="txt">
                    Export
                </span>
            </a>
            <a href="{{ route('provider.create.request') }}" class="primary-fill">
                <i class="bi bi-send-arrow-down-fill"></i>
                <span class="txt">
                    Export All
                </span>
            </a>
            <a href="" class="primary-fill request-support-btn">
                <i class="bi bi-pencil-square"></i>
                <span class="txt">
                    Request DTY Support
                </span>
            </a>
        </div>
    </div>

    <div class="listing">
        <div class="search-section d-flex align-items-center  justify-content-between ">
            <form action="{{ route('searching', ['status' => 'new', 'category' => request('category', 'all')]) }}"
                method="GET" class="d-flex align-items-center">
                {{-- @csrf --}}
                <div class="input-group mb-3">
                    <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                        placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search">
                    {{-- <input type="submit" class="primary-fill"> --}}
                </div>
                <select class="form-select">
                    <option selected>All Regions</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </form>
            <div class="src-category d-flex gap-3 align-items-center">
                <a href="{{ route('admin.listing', ['category' => 'all', 'status' => 'new']) }}"
                    class="btn-all filter-btn">All</a>
                <a href="{{ route('admin.listing', ['category' => 'patient', 'status' => 'new']) }}"
                    class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                <a href="{{ route('admin.listing', ['category' => 'family', 'status' => 'new']) }}"
                    class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                <a href="{{ route('admin.listing', ['category' => 'business', 'status' => 'new']) }}"
                    class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                <a href="{{ route('admin.listing', ['category' => 'concierge', 'status' => 'new']) }}"
                    class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</a>
            </div>
        </div>
        
        <div>
            <table class="table table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>Name</th>
                        <th>Date Of Birth</th>
                        <th>Requestor</th>
                        <th>Requested Date</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Notes</th>
                        <th>Chat With</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $case)

                    @if (!empty($case->request) && !empty($case->request->requestClient))
                    <tr class="type-{{ $case->request->request_type_id }}">
                        <td>{{ $case->request->requestClient->first_name }}</td>
                        <td>{{ $case->request->requestClient->date_of_birth }}</td>
                        <td>{{$case->request->first_name}}</td>
                        <td>{{ $case->request->created_at }}</td>
                        <td>{{ $case->request->phone_number }}</td>
                        <td>
                            {{ $case->request->requestClient->street }},
                            {{ $case->request->requestClient->city }},{{ $case->request->requestClient->state }}
                        </td>
                        <td>{{ $case->request->requestClient->notes }}</td>
                        <td>
                            <button class="table-btn "><i class="bi bi-person me-2"></i>Provider</button>
                        </td>
                        <td>
                            <div class="action-container">
                                <button class="table-btn action-btn">Actions</button>
                                <div class="action-menu">
                                    <button class="assign-case-btn"><i class="bi bi-journal-check me-2 ms-3"></i>Assign
                                        Case</button>
                                    <button class="cancel-case-btn" data-id="{{ $case->request->id }}"
                                        data-patient_name="{{ $case->request->requestClient->first_name }} {{ $case->request->requestClient->last_name }}"><i
                                            class="bi bi-x-circle me-2 ms-3"></i>Cancel
                                        Case</button>
                                    <button><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View
                                        Case</button>
                                    <a href="/view-notes/{{ $case->request->id }}"><i
                                            class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                    <button class="block-case-btn" data-id="{{ $case->request->id }}"
                                        data-patient_name="{{ $case->request->requestClient->first_name }} {{ $case->request->requestClient->last_name }}">
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
            <div class="mobile-list d-flex justify-content-between">
                <div class="d-flex flex-column">
                    <p>{{ $case->request->first_name }} </p>
                    <span>Address:
                        @if ($case->request->requestClient)
                        {{ $case->request->requestClient->street }},{{ $case->request->requestClient->city }},{{
            $case->request->requestClient->state }}
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
            <div class="more-info">
                <a href="{{ route('provider.view.case', $case->request->id) }}" class="view-btn">View Case</a>
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
                        <i class="bi bi-telephone"></i> Patient :
                        {{ $case->request->requestClient->phone_number }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-cash"></i> Transfer :Admin transferred to
                        {{ $case->request->requestClient->last_name }}
                    </span>
                    <br>
                    <span>
                        <i class="bi bi-calendar3"></i> Date of services :
                        {{ $case->request->created_at}}
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

                    <div class="grid-2-listing">
                        <a href="/view-notes/{{ $case->request->id }}" class="secondary-btn-5 text-center">Assign
                            Case</a>
                        <a href="/view-notes/{{ $case->request->id }}" class="secondary-btn-4 text-center">Cancel
                            Case</a>
                        <a href="/view-notes/{{ $case->request->id }}" class="secondary-btn text-center">View Notes</a>
                        <a href="/view-notes/{{ $case->request->id }}" class="secondary-btn-4 text-center">Block
                            Patient</a>
                        <a href="/view-notes/{{ $case->request->id }}" class="secondary-btn text-center">Email</a>
                    </div>
                </div>
                <div>
                    Chat With:
                    <button class="more-info-btn"><i class="bi bi-person-check me-2"></i>Admin</button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="page">
            {{ $cases->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
@endsection