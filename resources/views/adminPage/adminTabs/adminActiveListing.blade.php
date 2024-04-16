@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
@endsection

@include('adminPage.adminTabs.adminHeader')

@section('content')
    {{-- This page will display patient requests for which patients have accepted the service agreement and provider is
giving service to the patient. --}}
    <div class="overlay"></div>
    @include('loading')

    {{-- SendLink Completed Successfully --}}
    @include('alertMessages.sendLinkSuccess')

    {{-- Order Created Successfully Pop-up Message --}}
    @include('alertMessages.orderPlacedSuccess')

    {{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
    @include('popup.adminSendLink')

    {{-- Request DTY Support pop-up ->  --}}
    @include('popup.requestDTYSupport')

    <nav>
        <div class="nav nav-tabs state-grid-3" id="nav-tab">
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
                <a href="{{ route('adminPatientRequest') }}" class="primary-fill">
                    <i class="bi bi-pencil-square"></i>
                    <span class="txt">
                        Create Requests
                    </span>
                </a>
                <a href="{{ route('exportActive') }}" class="primary-fill" id="filterExportBtnActive">
                    <i class="bi bi-send-arrow-down"></i>
                    <span class="txt">
                        Export
                    </span>
                </a>
                <form action="{{ route('exportActive') }}" method="POST" id="filterExport">
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
                <form action="{{ route('searching', ['status' => 'active', 'category' => request('category', 'all')]) }}"
                    method="POST" class="d-flex align-items-center filter-section">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-patient"
                            placeholder='&#xF52A;  Search Patients' aria-describedby="basic-addon1" name="search"
                            value="{{ session('searchTerm') }}">
                    </div>
                    <select class="form-select listing-region">
                        <option name="regions" selected>All Regions</option>
                    </select>
                </form>
                <div class="src-category d-flex gap-3 align-items-center">
                    <a href="{{ route('admin.listing', ['category' => 'all', 'status' => 'active']) }}" data-category="all"
                        class="btn-all filter-btn">All</a>
                    <a href="{{ route('admin.listing', ['category' => 'patient', 'status' => 'active']) }}"
                        data-category="patient" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill green"></i>Patient</a>
                    <a href="{{ route('admin.listing', ['category' => 'family', 'status' => 'active']) }}"
                        data-category="family" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                    <a href="{{ route('admin.listing', ['category' => 'business', 'status' => 'active']) }}"
                        data-category="business" class="d-flex gap-2 filter-btn"> <i
                            class="bi bi-circle-fill red"></i>Business</a>
                    <a href="{{ route('admin.listing', ['category' => 'concierge', 'status' => 'active']) }}"
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
                            <th>Physician Name</th>
                            <th>Date Of Service</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dropdown-data-body">
                        @if ($cases->isEmpty())
                            <tr>
                                <td colspan="100" class="no-record">No Cases Found</td>
                            </tr>
                        @endif
                        @foreach ($cases as $case)
                            @if (!empty($case->requestClient))
                                <tr class="type-{{ $case->request_type_id }}">
                                    <td>{{ $case->requestClient->first_name }}
                                        {{ $case->requestClient->last_name }}</td>
                                    <td>{{ $case->requestClient->date_of_birth }}</td>
                                    <td>{{ $case->first_name }} {{ $case->last_name }}</td>
                                    <td>{{ $case->provider->first_name }} {{ $case->provider->last_name }}</td>
                                    <td>{{ $case->created_at }}</td>
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
                                        {{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                    </td>
                                    <td>Notes</td>
                                    <td>
                                        <div class="action-container">
                                            <button class="table-btn action-btn"
                                                data-id={{ $case->id }}>Actions</button>
                                            <div class="action-menu">
                                                <a href="{{ route('admin.view.case', $case->id) }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View
                                                    Case</a>
                                                <a href="{{ route('admin.view.upload', ['id' => $case->id]) }}"><i
                                                        class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                    Uploads</a>
                                                <a href="{{ route('admin.view.note', $case->id) }}"><i
                                                        class="bi bi-journal-text me-2 ms-3"></i>View
                                                    Notes</a>
                                                <a href="{{ route('admin.view.order', $case->id) }}"><i
                                                        class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                                                <a href="{{ route('admin.encounter.form', $case->id) }}"
                                                    class="encounter-form-btn"><i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</a>
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
                    @if (!empty($case->requestClient))
                        <div class="mobile-list d-flex justify-content-center align-items-between flex-column">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }} </span>
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
                            <a href="{{ route('admin.view.case', $case->id) }}" class="view-btn">View Case</a>
                            <div>
                                <span>
                                    <i class="bi bi-calendar3"></i> Date of birth :
                                    @isset($case->requestClient)
                                        {{ $case->requestClient->date_of_birth }}
                                    @endisset
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-envelope"></i> Email :
                                    @isset($case->requestClient)
                                        {{ $case->requestClient->email }}
                                    @endisset
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-telephone"></i> Patient :
                                    @isset($case->requestClient)
                                        {{ $case->phone_number }}
                                    @endisset
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-cash"></i> Transfer :Admin transferred to
                                    @isset($case->requestClient)
                                        {{ $case->provider->first_name }} {{ $case->provider->last_name }}
                                    @endisset
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-calendar3"></i> Date of services :
                                    @isset($case->requestClient)
                                        {{ $case->created_at }}
                                    @endisset
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-person-circle"></i> Physician : Dr.
                                    {{ $case->provider->first_name }} {{ $case->provider->last_name }}
                                </span>
                                <br>
                                <span>
                                    <i class="bi bi-person-plus-fill"></i> Requestor:
                                    {{ $case->first_name }} {{ $case->last_name }}
                                </span>
                                <div class="grid-2-listing ">
                                    <a href="{{ route('admin.view.note', $case->id) }}"
                                        class="secondary-btn text-center">View
                                        Notes</a>
                                    <a href="{{ route('admin.view.upload', ['id' => $case->id]) }}"
                                        class="secondary-btn text-center">View Uploads</a>
                                    <a href="{{ route('admin.encounter.form', $case->id) }}"
                                        class="secondary-btn text-center">Encouter</a>
                                    <a href="{{ route('admin.view.order', $case->id) }}"
                                        class="secondary-btn-2 text-center">orders</a>
                                    <button class="secondary-btn">Email</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
    <div class="page adminNewListingPages">
        {{ $cases->links('pagination::bootstrap-5') }}
    </div>
@endsection


@section('script')
    <script defer src="{{ URL::asset('assets/adminPage/adminExportExcelData.js') }}"></script>
    <script defer src="{{ asset('assets/adminPage/RequestSupport.js') }}"></script>
    <script defer src="{{ asset('assets/adminPage/filterRegionActive.js') }}"></script>
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
