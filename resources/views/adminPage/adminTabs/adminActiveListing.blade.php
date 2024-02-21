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
    {{-- This page will display patient requests for which patients have accepted the service agreement and provider is giving service to the patient.  --}}
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
                {{-- If the provider will select house-call, then another dropdown will be visible to select the approximate arrival time of the provider to that patient’s house. That dropdown contains options from 0.5 hour to 6 hour with a 30-minute gap. --}}
                <button type="button" class="primary-empty consult-btn">Consult</button>
                <input type="text" name="caseId" class="case-id" value="" hidden>
                {{--  If the provider selects the consult, then that request will move into Conclude state. --}}
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
                <a href="{{ route('admin-status', ['status' => 'new']) }}" class="nav-link " id="nav-new-tab">
                    <div
                        class="case case-new active p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                        <span>
                            <i class="bi bi-plus-circle"></i> NEW
                        </span>
                        <span>
                            {{ $newCasesCount }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin-status', ['status' => 'pending']) }}" class="nav-link" id="nav-pending-tab">
                    <div class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-person-square"></i> PENDING
                        </span>
                        <span>
                            {{ $pendingCasesCount }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin-status', ['status' => 'active']) }}" class="nav-link active"
                    id="nav-active-tab">
                    <div class="case case-active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-check2-circle"></i> ACTIVE
                        </span>
                        <span>
                            {{ $activeCasesCount }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin-status', ['status' => 'conclude']) }}" class="nav-link" id="nav-conclude-tab">
                    <div class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-clock-history"></i> CONCLUDE
                        </span>
                        <span>
                            {{ $concludeCasesCount }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin-status', ['status' => 'toclose']) }}" class="nav-link" id="nav-conclude-tab">
                    <div class="case case-toclose p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-person-fill-x"></i> TO CLOSE
                        </span>
                        <span>
                            {{ $tocloseCasesCount }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin-status', ['status' => 'unpaid']) }}" class="nav-link" id="nav-conclude-tab">
                    <div class="case case-unpaid p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-cash-coin"></i> UNPAID
                        </span>
                        <span>
                            {{ $unpaidCasesCount }}
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
                <div class=" d-flex gap-2">
                    <button class="primary-fill send-link-btn">
                        <i class="bi bi-send"></i>
                        <span class="txt">
                            Send Link
                        </span>
                    </button>
                    <a href="{{ route('provider-create-request') }}" class="primary-fill">
                        <i class="bi bi-pencil-square"></i>
                        <span class="txt">
                            Create Requests
                        </span>
                    </a>
                    <a href="{{ route('provider-create-request') }}" class="primary-fill">
                        <i class="bi bi-send-arrow-down"></i>
                        <span class="txt">
                            Export
                        </span>
                    </a>
                    <a href="{{ route('provider-create-request') }}" class="primary-fill">
                        <i class="bi bi-send-arrow-down-fill"></i>
                        <span class="txt">
                            Export All
                        </span>
                    </a>
                    <a href="{{ route('provider-create-request') }}" class="primary-fill">
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
                            <input type="text" style="font-family:'Bootstrap-icons';"
                                class="form-control search-patient" placeholder='&#xF52A;  Search Patients'
                                aria-describedby="basic-addon1" name="search">
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
                        <a href="{{ route('provider-listing', ['category' => 'all', 'status' => 'active']) }}"
                            class="btn-all filter-btn">All</a>
                        <a href="{{ route('provider-listing', ['category' => 'patient', 'status' => 'active']) }}"
                            class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                        <a href="{{ route('provider-listing', ['category' => 'family', 'status' => 'active']) }}"
                            class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                        <a href="{{ route('provider-listing', ['category' => 'business', 'status' => 'active']) }}"
                            class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                        <a href="{{ route('provider-listing', ['category' => 'concierge', 'status' => 'active']) }}"
                            class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</a>
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
                                <th>Chat With</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cases as $case)
                                <tr class="type-{{ $case->request_type_id }}">
                                    <td>{{ $case->first_name }} {{ $case->id }}</td>
                                    <td>Patient DOB</td>
                                    <td>Requestor Name</td>
                                    <td>Physician Name</td>
                                    <td>{{ $case->created_at }}</td>
                                    <td>{{ $case->phone_number }}</td>
                                    <td>{{ $case->address }}</td>
                                    <td>Notes</td>
                                    <td>
                                        <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
                                        <button class="table-btn"><i class="bi bi-person-check me-2"></i>Provider</button>
                                    </td>
                                    <td>
                                        <div class="action-container">
                                            <button class="table-btn action-btn"
                                                data-id={{ $case->id }}>Actions</button>
                                            <div class="action-menu">
                                                <a href="/view-case/{{ $case->id }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                                <button><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                    Uploads</button>
                                                <button><i class="bi bi-journal-text me-2 ms-3"></i>View
                                                    Notes</button>
                                                <a href="{{ route('view-order', ['id' => $case->id]) }}"><i
                                                        class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                                                <button><i class="bi bi-text-paragraph me-2 ms-3"></i>Doctors Note</button>
                                                <button class="encounter-btn"><i
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
                                <p>{{ $case->first_name }} </p>
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
                            <a href="/view-case/{{ $case->id }}" class="view-btn">View Case</a>
                            <div>
                                <span>
                                    <i class="bi bi-envelope"></i> Email : example@xyz.com
                                    {{-- {{$case->requestClient->email}} --}}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-telephone"></i> Patient : +91 123456789
                                    {{-- {{$case->requestClient->phone_number}} --}}
                                </span>
                                <div class="grid-2-listing ">
                                    <a href="/view-notes/{{ $case->id }}" class="secondary-btn text-center">View
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
