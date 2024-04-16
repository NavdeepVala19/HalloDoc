@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    {{-- This page will display patient requests for which medical is completed by the provider. Once the request is transferred into conclude state providers can finally conclude care for the patients. --}}
    <div class="overlay"></div>
    @include('loading')

    {{-- Encounter Form Finalized --}}
    @if (session('encounterFormFinalized'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('encounterFormFinalized') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Case Concluded Successfully --}}
    @if (session('CaseConcluded'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('CaseConcluded') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- SendLink Completed Successfully --}}
    @include('alertMessages.sendLinkSuccess')

    {{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
    @include('popup.providerSendLink')

    {{-- Finalize Pop-up appears when the provider has finalized the encounter form --}}
    {{-- The Encounter form should redirect to conclude page and will show these pop-up --}}
    {{-- The pop-up will give download link of the medical-report(Encounter Form) --}}
    @include('popup.providerEncounterFinalized')


    {{-- Encounter Form Finalized (Success Message) --}}
    @include('alertMessages.formFinalizedSuccess')

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
                <div
                    class="case case-conclude active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
                <form
                    action="{{ route('provider.searching', ['status' => 'conclude', 'category' => request('category', 'all')]) }}"
                    method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                            placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search"
                            value="{{ session('searchTerm') }}">
                    </div>
                </form>
                <div class="src-category d-flex gap-3 align-items-center">
                    <a href="{{ route('provider.listing', ['category' => 'all', 'status' => 'conclude']) }}"
                        data-category="all" class="btn-all filter-btn">All</a>
                    <a href="{{ route('provider.listing', ['category' => 'patient', 'status' => 'conclude']) }}"
                        data-category="patient" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('provider.listing', ['category' => 'family', 'status' => 'conclude']) }}"
                        data-category="family" class="d-flex gap-2 filter-btn "> <i
                            class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('provider.listing', ['category' => 'business', 'status' => 'conclude']) }}"
                        data-category="business" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('provider.listing', ['category' => 'concierge', 'status' => 'conclude']) }}"
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
                        @if ($cases->isEmpty())
                            <tr>
                                <td colspan="100" class="no-record">No Cases Found</td>
                            </tr>
                        @endif
                        @foreach ($cases as $case)
                            <tr class="type-{{ $case->request_type_id }}">
                                <td>{{ $case->requestClient->first_name }}
                                    {{ $case->requestClient->last_name }}</td>
                                <td class="mobile-column">
                                    @if ($case->request_type_id == 1)
                                        <div class="listing-mobile-container">
                                            <i class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
                                        </div>
                                        <div class="ms-2">
                                            (patient)
                                        </div>
                                    @else
                                        <div class="listing-mobile-container">
                                            <i class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
                                        </div>
                                        <div class="ms-2">
                                            (patient)
                                        </div>
                                        <div class="listing-mobile-container">
                                            <i class="bi bi-telephone me-2"></i>{{ $case->phone_number }}
                                        </div>
                                        <div class="ms-2">
                                            ({{ $case->requestType->name }})
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $case->requestClient->street }},
                                    {{ $case->requestClient->city }},
                                    {{ $case->requestClient->state }}</td>
                                <td>
                                    <div class="action-container">
                                        <button class="table-btn action-btn conclude-action-btn">Actions</button>
                                        <div class="action-menu">
                                            <a href="{{ route('provider.conclude.care.view', $case->id) }}"><i
                                                    class="bi bi-heart-pulse me-2 ms-3"></i>Conclude Care</a>
                                            <a href="{{ route('provider.view.case', $case->id) }}"><i
                                                    class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                            <a href="{{ route('provider.view.upload', $case->id) }}"><i
                                                    class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View Uploads</a>
                                            <a href="{{ route('provider.view.notes', $case->id) }}"><i
                                                    class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                            @if ($case->medicalReport && $case->medicalReport->is_finalize)
                                                <button class="encounter-popup-btn" data-id={{ $case->id }}> <i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i> Encounter</button>
                                            @else
                                                <a href="{{ route('provider.encounter.form', $case->id) }}"
                                                    class="encounter-form-btn"><i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mobile-listing">
                @if ($cases->isEmpty())
                    <div class="no-record mt-3 mb-3">
                        <span>No Cases Found</sp>
                    </div>
                @endif
                @foreach ($cases as $case)
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
                    <div class="more-info ">
                        <a href="{{ route('provider.view.case', $case->id) }}" class="view-btn">View Case</a>
                        <div>
                            <span>
                                <i class="bi bi-envelope"></i> Email :
                                {{ $case->requestClient->email }}
                            </span>
                            <br>
                            <span>
                                <i class="bi bi-telephone"></i> Patient :
                                {{ $case->requestClient->phone_number }}
                            </span>
                            <div class="grid-2-listing">
                                <a href="{{ route('provider.conclude.care.view', $case->id) }}"
                                    class="conclude-care-btn text-center">Conclude Care</a>
                                <a href="{{ route('provider.view.notes', $case->id) }}"
                                    class="secondary-btn text-center">View
                                    Notes</a>
                                <a href="{{ route('provider.view.upload', $case->id) }}"
                                    class="secondary-btn text-center">View
                                    Uploads</a>
                                @if ($case->medicalReport && $case->medicalReport->is_finalize)
                                    <button class="secondary-btn encounter-popup-btn" data-id={{ $case->id }}>
                                        Encounter</button>
                                @else
                                    <a href="{{ route('provider.encounter.form', $case->id) }}"
                                        class="secondary-btn encounter-form-btn text-center">Encounter</a>
                                @endif
                                <button class="secondary-btn">Email</button>
                            </div>
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
@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
