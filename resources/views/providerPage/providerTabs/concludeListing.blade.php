@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="{{ route('provider-profile') }}">My Profile</a>
@endsection

@section('content')
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
            <a href="{{ route('provider-status', ['status' => 'new']) }}" class="nav-link" id="nav-new-tab">
                <div class="case case-new active p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                    <span>
                        <i class="bi bi-plus-circle"></i> NEW
                    </span>
                    <span>
                        {{ $newCasesCount }}
                    </span>
                </div>
            </a>

            <a href="{{ route('provider-status', ['status' => 'pending']) }}" class="nav-link" id="nav-pending-tab">
                <div class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                    <span>
                        <i class="bi bi-person-square"></i> PENDING
                    </span>
                    <span>
                        {{ $pendingCasesCount }}
                    </span>
                </div>
            </a>

            <a href="{{ route('provider-status', ['status' => 'active']) }}" class="nav-link" id="nav-active-tab">
                <div class="case case-active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                    <span>
                        <i class="bi bi-check2-circle"></i> ACTIVE
                    </span>
                    <span>
                        {{ $activeCasesCount }}
                    </span>
                </div>
            </a>

            <a href="{{ route('provider-status', ['status' => 'conclude']) }}" class="nav-link active"
                id="nav-conclude-tab">
                <div class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                    <span>
                        <i class="bi bi-clock-history"></i> CONCLUDE
                    </span>
                    <span>
                        {{ $concludeCasesCount }}
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
                <button class="primary-btn me-3 send-link-btn">
                    <i class="bi bi-send"></i>
                    <span class="txt">
                        Send Link
                    </span>
                </button>
                <a class="primary-btn" href="{{ route('provider-create-request') }}">
                    <i class="bi bi-pencil-square"></i>
                    <span class="txt">
                        Create Requests
                    </span>
                </a>
            </div>
        </div>


        <div class="listing">
            <div class="search-section d-flex align-items-center  justify-content-between ">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" class="form-control search-patient" placeholder="Search Patients"
                            aria-label="Username" aria-describedby="basic-addon1" name="search">
                        <input type="submit" class="primary-fill">
                    </div>
                </form>
                <div class="src-category d-flex gap-3 align-items-center">
                    <a href="{{ route('provider-listing', ['category' => 'all', 'status' => 'conclude']) }}"
                        class="btn-all filter-btn">All</a>
                    <a href="{{ route('provider-listing', ['category' => 'patient', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('provider-listing', ['category' => 'family', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn "> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('provider-listing', ['category' => 'business', 'status' => 'conclude']) }}"
                        class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('provider-listing', ['category' => 'concierge', 'status' => 'conclude']) }}"
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
                            <tr class="type-{{ $case->request_type_id }}">
                                <td>{{ $case->first_name }}</td>
                                <td>{{ $case->phone_number }}</td>
                                <td>{{ $case->address }}</td>
                                <td>Status</td>
                                <td>
                                    <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
                                    <button class="table-btn"><i class="bi bi-person-check me-2"></i>Admin</button>
                                </td>
                                <td><a href="{{ route('encounter-form') }}"
                                        class="table-btn encounter-form-btn">Actions</a></td>
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
                        <button class="view-btn">View Case</button>
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
                            <div class="grid-2 ">
                                <button class="secondary-btn">View Notes</button>
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
@endsection
