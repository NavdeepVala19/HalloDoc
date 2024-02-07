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
                <button class="nav-link active" id="nav-new-tab" data-bs-toggle="tab" data-bs-target="#nav-new"
                    type="button" role="tab" aria-controls="nav-new" aria-selected="true">
                    <div
                        class="case case-new active p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                        <span>
                            <i class="bi bi-plus-circle"></i> NEW
                        </span>
                        <span>
                            1{{-- New Cases --}}
                        </span>
                    </div>
                </button>
                <button class="nav-link" id="nav-pending-tab" data-bs-toggle="tab" data-bs-target="#nav-pending"
                    type="button" role="tab" aria-controls="nav-pending" aria-selected="false">
                    <div class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-person-square"></i> PENDING
                        </span>
                        <span>
                            1{{-- New Cases --}}
                        </span>
                    </div>
                </button>
                <button class="nav-link" id="nav-active-tab" data-bs-toggle="tab" data-bs-target="#nav-active"
                    type="button" role="tab" aria-controls="nav-active" aria-selected="false">
                    <div class="case case-active p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-check2-circle"></i> ACTIVE
                        </span>
                        <span>
                            1{{-- New Cases --}}
                        </span>
                    </div>
                </button>
                <button class="nav-link" id="nav-conclude-tab" data-bs-toggle="tab" data-bs-target="#nav-conclude"
                    type="button" role="tab" aria-controls="nav-conclude" aria-selected="false">
                    <div class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
                        <span>
                            <i class="bi bi-clock-history"></i> CONCLUDE
                        </span>
                        <span>
                            1{{-- New Cases --}}
                        </span>
                    </div>
                </button>
            </div>
        </nav>

        

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-new" role="tabpanel"
                aria-labelledby="nav-new-tab" tabindex="0">
                @include('providerPage.providerTabs.newListing')
            </div>
            <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab" tabindex="0">
                @include('providerPage.providerTabs.pendingListing')
            </div>
            <div class="tab-pane fade" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab" tabindex="0">
                @include('providerPage.providerTabs.activeListing')
            </div>
            <div class="tab-pane fade" id="nav-conclude" role="tabpanel" aria-labelledby="nav-conclude-tab"
                tabindex="0">
                @include('providerPage.providerTabs.concludeListing')
            </div>
        </div>
    </div>
@endsection
