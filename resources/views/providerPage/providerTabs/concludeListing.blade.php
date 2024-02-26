@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    {{-- This page will display patient requests for which medical is completed by the provider. Once the request is transferred into conclude state providers can finally conclude care for the patients. --}}
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

    {{-- Finalize Pop-up appears when the provider has finalized the encounter form --}}
    {{-- The Encounter form should redirect to conclude page and will show these pop-up --}}
    {{-- The pop-up will give download link of the medical-report(Encounter Form) --}}
    <div class="pop-up encounter-finalized">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Encounter Form</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="encounter-finalized-container">
            <p>Encounter Form is finalized successfully!</p>
            <div class="text-center">
                <button class="primary-fill download-btn">Download</button>
            </div>
        </div>
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

            <a href="{{ route('provider.status', ['status' => 'conclude']) }}" class="nav-link active"
                id="nav-conclude-tab">
                <div class="case case-conclude active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
                <h3>Patients </h3> <strong class="case-type ps-2 ">(Conclude)</strong>
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
                <form action="{{ route('searching', ['status' => 'conclude', 'category' => request('category', 'all')]) }}"
                    method="GET">
                    {{-- @csrf --}}
                    <div class="input-group mb-3">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                            placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search">
                        <input type="submit" class="primary-fill">
                    </div>
                </form>
                <div class="src-category d-flex gap-3 align-items-center">
                    <a href="{{ route('provider.listing', ['category' => 'all', 'status' => 'conclude']) }}"
                        class="btn-all filter-btn">All</a>
                    <a href="{{ route('provider.listing', ['category' => 'patient', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('provider.listing', ['category' => 'family', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn "> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('provider.listing', ['category' => 'business', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('provider.listing', ['category' => 'concierge', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</a>
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
                            <th>Chat With</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cases as $case)
                            <tr class="type-{{ $case->request->request_type_id }}">
                                <td>{{ $case->request->requestClient->first_name }}</td>
                                <td>{{ $case->request->requestClient->phone_number }}</td>
                                <td>{{ $case->request->requestClient->address }}</td>
                                <td>
                                    @if ($case->request->call_type)
                                        <span class="primary-fill"> {{ $case->request->call_type }} </span>
                                    @else
                                        Call Type
                                    @endif
                                </td>
                                <td>
                                    <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
                                    <button class="table-btn"><i class="bi bi-person-check me-2"></i>Admin</button>
                                </td>
                                <td>
                                    <div class="action-container">
                                        <button class="table-btn action-btn conclude-action-btn">Actions</button>
                                        <div class="action-menu">
                                            <a href="{{ route('provider.view.case', $case->request->id) }}"><i
                                                    class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                            <button><i class="bi bi-check-square me-2 ms-3"></i>Conclude Case</button>
                                            <a href="{{ route('provider.view.notes', $case->request->id) }}"><i
                                                    class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                            <button><i class="bi bi-journal-check me-2 ms-3"></i>Doctor Notes</button>
                                            <a href="{{ route('provider.view.upload', $case->request->id) }}"><i
                                                    class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View Uploads</a>
                                            <a href="{{ route('provider.encounter.form', $case->request->id) }}"
                                                class="encounter-form-btn"><i
                                                    class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</a>
                                            <button><i class="bi bi-envelope-open me-2 ms-3"></i>Email</button>
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
                            <p>{{ $case->request->requestClient->first_name }} </p>
                            <span>
                                @if ($case->request->requestClient)
                                    {{ $case->requestClient->address }}
                                @endif Address
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
                        <a href="{{ route('provider.view.case', $case->request->id) }}" class="view-btn">View Case</a>
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
                                <button class="conclude-care-btn">Conclude Care</button>
                                <a href="{{ route('provider.view.notes', $case->request->id) }}"
                                    class="secondary-btn text-center">View
                                    Notes</a>
                                <button class="secondary-btn-1">Doctors Notes</button>
                                <a href="{{ route('provider.view.upload', $case->request->id) }}" class="secondary-btn">View
                                    Uploads</a>
                                <button class="secondary-btn">Encouter</button>
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
@endsection
