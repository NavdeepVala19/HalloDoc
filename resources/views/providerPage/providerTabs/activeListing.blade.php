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
        <form action="{{ route('provider.active.encounter') }}" method="GET">
            <div class="p-4 d-flex align-items-center justify-content-center gap-2">
                <input type="text" name="requestId" class="case-id" value="" hidden>
                {{-- If the provider selects the housecall, then that request will be in same state, but status changes from MdEnRoute to MdEnSite --}}
                <button type="button" class="primary-empty housecall-btn">Housecall</button>
                <input type="text" class="house_call" name="house_call" hidden required>
                {{-- If the provider selects the consult, then that request will move into Conclude state. --}}
                <button type="button" class="primary-empty consult-btn">Consult</button>
                <input type="text" class="consult" name="consult" hidden required>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                {{-- <button class="primary-fill encounter-save-btn">Save</button> --}}
                <input type="submit" class="primary-fill encounter-save-btn" id="save-btn" value="Save">
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>


    <div class="bg-blur">
        <nav>
            <div class="nav nav-tabs " id="nav-tab">
                <a href="{{ route('provider.status', ['status' => 'new']) }}" class="nav-link " id="nav-new-tab">
                    <div class="case case-new  p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                        <span>
                            <i class="bi bi-plus-circle"></i> NEW
                        </span>
                        <span>
                            {{ $count['newCase'] }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('provider.status', ['status' => 'pending']) }}" class="nav-link" id="nav-pending-tab">
                    <div class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-person-square"></i> PENDING
                        </span>
                        <span>
                            {{ $count['pendingCase'] }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('provider.status', ['status' => 'active']) }}" class="nav-link active"
                    id="nav-active-tab">
                    <div
                        class="case case-active active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
                    <h3>Patients </h3> <strong class="case-type ps-2 ">(Active)</strong>
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
                        action="{{ route('provider.searching', ['status' => 'active', 'category' => request('category', 'all')]) }}"
                        method="GET">
                        {{-- @csrf --}}
                        <div class="input-group mb-3">
                            <input type="text" style="font-family:'Bootstrap-icons';"
                                class="form-control search-patient" placeholder='&#xF52A;  Search Patients'
                                aria-describedby="basic-addon1" name="search">
                            <input type="submit" class="primary-fill">
                        </div>
                    </form>
                    <div class="src-category d-flex gap-3 align-items-center">
                        <a href="{{ route('provider.listing', ['category' => 'all', 'status' => 'active']) }}"
                            data-category="all" class="btn-all filter-btn">All</a>
                        <a href="{{ route('provider.listing', ['category' => 'patient', 'status' => 'active']) }}"
                            data-category="patient" class="d-flex gap-2 filter-btn"> <i
                                class="bi bi-circle-fill green"></i>Patient</a>
                        <a href="{{ route('provider.listing', ['category' => 'family', 'status' => 'active']) }}"
                            data-category="family" class="d-flex gap-2 filter-btn"> <i
                                class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                        <a href="{{ route('provider.listing', ['category' => 'business', 'status' => 'active']) }}"
                            data-category="business" class="d-flex gap-2 filter-btn"> <i
                                class="bi bi-circle-fill red"></i>Business</a>
                        <a href="{{ route('provider.listing', ['category' => 'concierge', 'status' => 'active']) }}"
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
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cases as $case)
                                <tr class="type-{{ $case->request->request_type_id }}">
                                    <td>{{ $case->request->requestClient->first_name }}
                                        {{ $case->request->requestClient->last_name }}</td>
                                    <td>{{ $case->request->requestClient->phone_number }}</td>
                                    <td>{{ $case->request->requestClient->street }},
                                        {{ $case->request->requestClient->city }},
                                        {{ $case->request->requestClient->state }}</td>
                                    <td>
                                        @if ($case->request->call_type && $case->request->call_type == 'house_call')
                                            <a href="{{ route('provider.houseCall.encounter', $case->request->id) }}"
                                                class="primary-fill houseCallBtn"> House Call </a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-container">
                                            <button class="table-btn action-btn"
                                                data-id="{{ $case->request->id }}">Actions</button>
                                            <div class="action-menu">
                                                <a href="{{ route('provider.view.case', $case->request->id) }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                                <a href="{{ route('provider.view.upload', $case->request->id) }}"><i
                                                        class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                    Uploads</a>
                                                <a href="{{ route('provider.view.notes', $case->request->id) }}"><i
                                                        class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                                <a href="{{ route('provider.view.order', $case->request->id) }}"><i
                                                        class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                                                <button class="encounter-btn" data-id={{ $case->request->id }}><i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mobile-listing">
                    @foreach ($cases as $case)
                        <div class="mobile-list d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <p> patient name </p>
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
                                    <i class="bi bi-envelope"></i> Email : example@xyz.com
                                    {{-- {{$case->requestClient->email}}
                        </span>
                        <br>
                        <span>
                            <i class="bi bi-telephone"></i> Patient : +91 123456789
                            {{-- {{$case->requestClient->phone_number}} --}}
                                </span>
                                <div class="grid-2-listing ">
                                    <a href="{{ route('provider.view.notes', $case->request->id) }}"
                                        class="secondary-btn text-center">View
                                        Notes</a>
                                    <button class="secondary-btn-1">Doctors Notes</button>
                                    <button class="secondary-btn">View Uploads</button>
                                    <button class="secondary-btn">Encouter</button>
                                    <button class="secondary-btn-2">orders</button>
                                    <button class="secondary-btn-3">House Call</button>
                                    <button class="secondary-btn">Email</button>
                                </div>
                            </div>
                            <div>
                                Chat With:
                                <button class="more-info-btn"><i class="bi bi-person me-2"></i>Patient</button>
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
    </div>
@endsection
