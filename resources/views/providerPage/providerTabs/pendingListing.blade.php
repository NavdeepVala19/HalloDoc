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
    {{-- Patient requests that have been accepted by providers or are still pending the acceptance of the service agreement by patients. --}}
    {{-- When providers accept a patient request, they are required to send an agreement video link via email and SMS to the patient's email address and phone number. Once the patient accepts the agreement, their request will transition from the "Pending" state to the "Active" state. --}}
    <div class="overlay"></div>
    @include('loading')

    {{-- Error or Success Message Alerts/Pop-ups --}}
    @if (session('caseAccepted'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('caseAccepted') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    @if (session('transferredCase'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('transferredCase') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Request Created Successfully --}}
    @if (session('requestCreated'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('requestCreated') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- SendLink Completed Successfully --}}
    @include('alertMessages.sendLinkSuccess')

    {{-- Agreement Sent to patient Successfully --}}
    @include('alertMessages.agreementSentSuccess')

    {{-- Send Agreement Pop-up --}}
    {{-- This pop-up will open when admin/provider will click on “Send agreement” link from Actions menu. From the
pending state, providers need to send an agreement link to patients. --}}
    @include('popup.providerSendAgreement')

    {{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
    @include('popup.providerSendLink')

    {{-- Transfer Request --}}
    @include('popup.providerTransferRequest')

    {{-- Send Mail to patient --}}
    @include('popup.sendMail')

    <nav>
        <div class="nav nav-tabs" id="nav-tab">
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

            <a href="{{ route('provider.status', ['status' => 'pending']) }}" class="nav-link active" id="nav-pending-tab">
                <div class="case case-pending active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
                    method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                            placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search"
                            value="{{ session('searchTerm') }}">
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
                        @if ($cases->isEmpty())
                            <tr>
                                <td colspan="100" class="no-record">No Cases Found</td>
                            </tr>
                        @endif
                        @foreach ($cases as $case)
                            @if (!empty($case) && !empty($case->requestClient))
                                <tr class="type-{{ $case->request_type_id }}">
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>
                                                {{ $case->requestClient->first_name }}
                                                {{ $case->requestClient->last_name }}
                                            </span>
                                            <button class="send-mail-btn" data-requestid="{{ $case->id }}"
                                                data-name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"
                                                data-email={{ $case->requestClient->email }}>
                                                <i class="bi bi-envelope"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="mobile-column">
                                        @if ($case->request_type_id == 1)
                                            <div class="listing-mobile-container">
                                                <i
                                                    class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
                                            </div>
                                            <div class="ms-2">
                                                (patient)
                                            </div>
                                        @else
                                            <div class="listing-mobile-container">
                                                <i
                                                    class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
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
                                            <button class="table-btn action-btn">Actions</button>
                                            <div class="action-menu">
                                                <button class="send-agreement-btn" data-id="{{ $case->id }}"
                                                    data-request_type_id={{ $case->request_type_id }}
                                                    data-phone_number="{{ $case->requestClient->phone_number }}"
                                                    data-email={{ $case->requestClient->email }}><i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i>Send Agreement</button>
                                                <a href="{{ route('provider.view.case', Crypt::encrypt($case->id)) }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                                <a href="{{ route('provider.view.upload', Crypt::encrypt($case->id)) }}"><i
                                                        class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                    Uploads</a>
                                                <a href="{{ route('provider.view.notes', Crypt::encrypt($case->id)) }}"><i
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
                @if ($cases->isEmpty())
                    <div class="no-record mt-3 mb-3">
                        <span>No Cases Found</sp>
                    </div>
                @endif
                @foreach ($cases as $case)
                    @if (!empty($case) && !empty($case->requestClient))
                        <div class="mobile-list d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <p>{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }} </p>
                                <span>
                                    @if ($case->requestClient)
                                        {{ $case->requestClient->street }},{{ $case->requestClient->city }},{{ $case->requestClient->state }}
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
                                        Concierge
                                        <i class="bi bi-circle-fill ms-1 blue"></i>
                                    </span>
                                @elseif ($case->request_type_id == 4)
                                    <span>
                                        Business
                                        <i class="bi bi-circle-fill ms-1 red"></i>
                                    </span>
                                @endif
                                <button class="map-btn">Map Location</button>
                            </div>
                        </div>
                        <div class="more-info ">
                            <a href="{{ route('provider.view.case', Crypt::encrypt($case->id)) }}" class="view-btn">View
                                Case</a>
                            <div>
                                <span>
                                    <i class="bi bi-envelope"></i> Email :
                                    @if ($case->requestClient)
                                        {{ $case->requestClient->email }}
                                    @endif
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-geo-alt"></i> Address :
                                    @if ($case->requestClient)
                                        {{ $case->requestClient->street }},{{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                    @endif
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-telephone"></i> Patient :
                                    {{ $case->requestClient->phone_number }}
                                </span>
                                <div class="grid-2-listing ">
                                    <button class="agreement-btn">Send Agreement</button>
                                    <a href="{{ route('provider.view.notes', Crypt::encrypt($case->id)) }}"
                                        class="secondary-btn text-center">View
                                        Notes</a>
                                    <a class="secondary-btn text-center"
                                        href="{{ route('provider.view.upload', Crypt::encrypt($case->id)) }}">View
                                        Uploads</a>
                                    <button class="secondary-btn">Email</button>
                                </div>
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
@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
