@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    {{-- Main Content of the Page --}}
    <div class="">
        {{-- Tabs Section --}}
        <nav>
            <div class=" nav nav-tabs " id="nav-tab" role="tablist">

                <a href="
                {{ route('provider-status', ['status' => 'new']) }}
                "
                    class="nav-link active" id="nav-new-tab" data-bs-toggle="tab" data-bs-target="#nav-new" type="button"
                    role="tab" aria-controls="nav-new" aria-selected="true">
                    <div
                        class="case case-new active p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                        <span>
                            <i class="bi bi-plus-circle"></i> NEW
                        </span>
                        <span>
                            {{ $newCases->total() }} {{-- New Cases --}}
                        </span>
                    </div>
                </a>

                <a href="{{ route('provider-status', ['status' => 'pending']) }}" class="nav-link" id="nav-pending-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-pending" type="button" role="tab"
                    aria-controls="nav-pending" aria-selected="false">
                    <div class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-person-square"></i> PENDING
                        </span>
                        <span>
                            {{ $pendingCases->total() }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('provider-status', ['status' => 'active']) }}" class="nav-link" id="nav-active-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-active" type="button" role="tab"
                    aria-controls="nav-active" aria-selected="false">
                    <div class="case case-active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-check2-circle"></i> ACTIVE
                        </span>
                        <span>
                            {{ $activeCases->total() }}
                        </span>
                    </div>
                </a>

                <a href="{{ route('provider-status', ['status' => 'conclude']) }}" class="nav-link" id="nav-conclude-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-conclude" type="button" role="tab"
                    aria-controls="nav-conclude" aria-selected="false">
                    <div class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-clock-history"></i> CONCLUDE
                        </span>
                        <span>
                            {{ $concludeCases->total() }}
                        </span>
                    </div>
                </a>
            </div>
        </nav>
        <div class="main">
            <div class="heading-section d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h3>Patients </h3> <strong class="case-type ps-2 " id="selectedTab">(New)</strong>
                </div>
                <div>
                    <a href="" class="primary-btn me-3">
                        <i class="bi bi-send"></i>
                        <span class="txt">
                            Send Link
                        </span>
                    </a>
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
                    <form action="{{ route('searching') }}" method="GET">
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

                        {{-- Working on filtering --}}
                        <a href="{{ route('provider-listing', ['category' => 'all', 'status' => 'new']) }}"
                            class="btn-all status-link status-all">All</a>

                        <a class="status-link status-patient"
                            href="{{ route('provider-listing', ['category' => 'patient', 'status' => 'new']) }}"
                            class="d-flex gap-2 green-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>

                        <a class="status-link status-family"
                            href="{{ route('provider-listing', ['category' => 'family', 'status' => 'new']) }}"
                            class="d-flex gap-2 "> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>

                        <a class="status-link status-business"
                            href="{{ route('provider-listing', ['category' => 'business', 'status' => 'new']) }}"
                            class="d-flex gap-2 "> <i class="bi bi-circle-fill red"></i>Business</a>

                        <a class="status-link status-concierge"
                            href="{{ route('provider-listing', ['category' => 'concierge', 'status' => 'new']) }}"
                            class="d-flex gap-2 "> <i class="bi bi-circle-fill blue"></i>Concierge</a>

                    </div>
                </div>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab"
                        tabindex="0">

                        {{-- @if (!$cases && !$search)
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $newCases,
                                'tabName' => 'New',
                            ])
                        @endif --}}

                        @if (!empty($cases))
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $cases,
                                'tabName' => 'New',
                            ])
                        @endif

                        @if ($search)
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $search,
                                'tabName' => 'New',
                            ])
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab"
                        tabindex="0">

                        {{-- @include('providerPage.providerTabs.tab', [
                            'cases' => $pendingCases,
                            'tabName' => 'Pending',
                        ]) --}}

                        @if (!empty($cases))
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $cases,
                                'tabName' => 'Pending',
                            ])
                        @endif

                        @if ($search)
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $search,
                                'tabName' => 'Pending',
                            ])
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab"
                        tabindex="0">

                        {{-- @include('providerPage.providerTabs.tab', [
                            'cases' => $activeCases,
                            'tabName' => 'Active',
                        ]) --}}

                        @if (!empty($cases))
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $cases,
                                'tabName' => 'Active',
                            ])
                        @endif

                        @if ($search)
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $search,
                                'tabName' => 'Active',
                            ])
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-conclude" role="tabpanel" aria-labelledby="nav-conclude-tab"
                        tabindex="0">
                        {{-- @include('providerPage.providerTabs.tab', [
                            'cases' => $pendingCases,
                            'tabName' => 'Conclude',
                        ]) --}}


                        @if (!empty($cases))
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $cases,
                                'tabName' => 'Conclude',
                            ])
                        @endif

                        @if ($search)
                            @include('providerPage.providerTabs.tab', [
                                'cases' => $search,
                                'tabName' => 'Conclude',
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection
