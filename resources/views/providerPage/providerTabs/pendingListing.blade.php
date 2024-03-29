@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('username')
    {{ !empty($userData) ? $userData->username : '' }}
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
{{-- Patient requests that have been accepted by providers or are still pending the acceptance of the service agreement by patients. --}}
{{-- When providers accept a patient request, they are required to send an agreement video link via email and SMS to the patient's email address and phone number. Once the patient accepts the agreement, their request will transition from the "Pending" state to the "Active" state. --}}
<div class="overlay"></div>

{{-- Error or Success Message Alerts/Pop-ups --}}
@if (session('caseAccepted'))
<div class="alert alert-success popup-message ">
    <span>
        {{ session('caseAccepted') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif

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
                    for the responsible party. </p>
            </div>
            <form action="{{ route('send.agreement') }}" method="POST">
                @csrf
                <input type="text" class="send-agreement-id" name="request_id" value="" hidden>
                <div>
                    <div class="form-floating ">
                        <input type="text" name="phone_number" class="form-control agreement-phone-number"
                            id="floatingInput" placeholder="Phone Number">
                        <label for="floatingInput">Phone Number</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control agreement-email" id="floatingInput"
                            placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>
                </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <input type="submit" value="Send" class="primary-fill send-case">
            <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
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
        <span class="p-2">This request will be transferred to admin.</span>
        <form action="{{ route('provider.transfer.case') }}" method="POST">
            @csrf
            <input type="text" class="requestId" name="requestId" hidden>
            <div class="d-flex align-items-center justify-content-center gap-2">
                <div class="form-floating">
                    <textarea name="notes" class="form-control transfer-description" placeholder="notes" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Description</label>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button class="primary-fill">Submit</button>
                <button class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>

    <nav>
        <div class="nav nav-tabs " id="nav-tab">
            <a href="{{ route('provider.status', ['status' => 'new']) }}" class="nav-link" id="nav-new-tab">
                <div class="case case-new  p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                    <span>
                        <i class="bi bi-plus-circle"></i> NEW
                    </span>
                    <span>
                        {{ $count['newCase'] }}
                    </span>
                </div>
            </a>

            <a href="{{ route('provider.status', ['status' => 'pending']) }}" class="nav-link active"
                id="nav-pending-tab">
                <div
                    class="case case-pending active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                    <span>
                        <i class="bi bi-person-square"></i> PENDING
                    </span>
                    <span>
                        {{ $count['pendingCase'] }}
                    </span>
                </div>
            </a>

            <a href="{{ route('provider.status', ['status' => 'active']) }}" class="nav-link" id="nav-active-tab">
                <div class="case case-active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                    <span>
                        <i class="bi bi-check2-circle"></i> ACTIVE
                    </span>
                    <span>
                        {{ $count['activeCase'] }}
                    </span>
                </div>
            </a>

            <a href="{{ route('provider.status', ['status' => 'conclude']) }}" class="nav-link" id="nav-conclude-tab">
                <div class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                    <span>
                        <i class="bi bi-clock-history"></i> CONCLUDE
                    </span>
                    <span>
                        {{ $count['concludeCase'] }}
                    </span>
                </div>
            </a>
        </div>
    </nav>

    <div class="main">
        <div class="heading-section d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <h3>Patients </h3> <strong class="case-type ps-2 ">(Pending)</strong>
            </div>
            <div>
                <button class="primary-fill me-3 send-link-btn">
                    <i class="bi bi-send"></i>
                    <span class="txt">
                        Send Link
                    </span>
                </button>
                <a class="primary-fill" href="{{ route('provider.create.request') }}">
                    <i class="bi bi-pencil-square"></i>
                    <span class="txt">
                        Create Requests
                    </span>
                </a>
            </div>
        </div>


        <div class="listing">
            <div class="search-section d-flex align-items-center  justify-content-between ">
                <form
                    action="{{ route('provider.searching', ['status' => 'pending', 'category' => request('category', 'all')]) }}"
                    method="GET">
                    {{-- @csrf --}}
                    <div class="input-group mb-3">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                            placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search">
                        <input type="submit" class="primary-fill">
                    </div>
                </form>
                <div class="src-category d-flex gap-3 align-items-center">
                    <a href="{{ route('provider.listing', ['category' => 'all', 'status' => 'pending']) }}"
                        data-category="all" class="btn-all filter-btn">All</a>
                    <a href="{{ route('provider.listing', ['category' => 'patient', 'status' => 'pending']) }}"
                        data-category="patient" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('provider.listing', ['category' => 'family', 'status' => 'pending']) }}"
                        data-category="family" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('provider.listing', ['category' => 'business', 'status' => 'pending']) }}"
                        data-category="business" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('provider.listing', ['category' => 'concierge', 'status' => 'pending']) }}"
                        data-category="concierge" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill blue"></i>Concierge</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cases as $case)
                            @if (!empty($case) && !empty($case->requestClient))
                                <tr class="type-{{ $case->request_type_id }}">
                                    <td>{{ $case->requestClient->first_name }}
                                        {{ $case->requestClient->last_name }}</td>
                                    <td>{{ $case->requestClient->phone_number }}</td>
                                    <td>{{ $case->requestClient->street }},
                                        {{ $case->requestClient->city }},
                                        {{ $case->requestClient->state }}</td>
                                    <td>
                                        <div class="action-container">
                                            <button class="table-btn action-btn">Actions</button>
                                            <div class="action-menu">
                                                <button class="send-agreement-btn" data-id="{{ $case->id }}"
                                                    data-request_type_id={{ $case->request_type_id }}
                                                    data-phone_number={{ $case->phone_number }}
                                                    data-email={{ $case->email }}><i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i>Send Agreement</button>
                                                <a href="{{ route('provider.view.case', $case->id) }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                                <a href="{{ route('provider.view.upload', $case->id) }}"><i
                                                        class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                    Uploads</a>
                                                <a href="{{ route('provider.view.notes', $case->id) }}"><i
                                                        class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                                <button class="transfer-btn" data-id="{{ $case->id }}"><i
                                                        class="bi bi-send me-2 ms-3"></i>Transfer</button>
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
                    @if (!empty($case) && !empty($case->requestClient))
                        <div class="mobile-list d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <p>{{ $case->requestClient->first_name }} </p>
                                <span>
                                    @if ($case->requestClient)
                                        {{ $case->requestClient->address }}
                                    @endif Address
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
                            <a href="{{ route('provider.view.case', $case->id) }}" class="view-btn">View
                                Case</a>
                            <div>
                                <span>
                                    <i class="bi bi-envelope"></i> Email : example@xyz.com
                                    {{-- {{$case->requestClient->email}} --}}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-geo-alt"></i> Address :
                                    {{-- {{$case->requestClient->email}} --}}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-telephone"></i> Patient : +91 123456789
                                    {{-- {{$case->requestClient->phone_number}} --}}
                                </span>
                                <div class="grid-2-listing ">
                                    <button class="agreement-btn">Send Agreement</button>
                                    <a href="{{ route('provider.view.notes', $case->id) }}"
                                        class="secondary-btn text-center">View
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
