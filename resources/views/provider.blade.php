@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerDashboard/providerDashboard.css') }}">
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
        <div>
            <nav>
                <div class=" nav nav-tabs " id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-new-tab" data-bs-toggle="tab" data-bs-target="#nav-new"
                        type="button" role="tab" aria-controls="nav-new" aria-selected="true">

                        <div
                            class="case case-new active p-1 ps-3 d-flex flex-column justify-content-between align-items-start ">
                            <span>
                                logo NEW
                            </span>
                            <span>
                                1{{-- New Cases --}}
                            </span>
                        </div>
                    </button>
                    <button class="nav-link" id="nav-pending-tab" data-bs-toggle="tab" data-bs-target="#nav-pending"
                        type="button" role="tab" aria-controls="nav-pending" aria-selected="false">
                        <div
                            class="case case-pending p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
                        <div
                            class="case case-conclude p-1 ps-3 d-flex flex-column justify-content-between align-items-start">
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
        </div>

        <div class="main">
            <div class="heading-section d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h3>Patients </h3> <span>(New)</span>
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
            <div class="search-section d-flex align-items-center  justify-content-between ">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control search-patient" placeholder="Search Patients"
                        aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="src-category d-flex gap-3 align-items-center">
                    <button class="btn-all">All</button>
                    <button class="d-flex gap-2 "> <i class="bi bi-circle-fill green"></i> Patient</button>
                    <button class="d-flex gap-2 "> <i class="bi bi-circle-fill yellow"></i>Family/Frient</button>
                    <button class="d-flex gap-2 "> <i class="bi bi-circle-fill red"></i>Buisness</button>
                    <button class="d-flex gap-2 "> <i class="bi bi-circle-fill blue"></i>Concierge</button>
                    <button class="d-flex gap-2 "><i class="bi bi-circle-fill purple"></i>VIP</button>
                </div>
            </div>


            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active table-responsive " id="nav-new" role="tabpanel"
                    aria-labelledby="nav-new-tab" tabindex="0">
                    New Case Section

                    <table class="table table-hover ">
                        <thead class="table-secondary">
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Chat With</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab"
                    tabindex="0">
                    Pending Case Section
                </div>
                <div class="tab-pane fade" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab" tabindex="0">
                    Active Case Section
                </div>
                <div class="tab-pane fade" id="nav-conclude" role="tabpanel" aria-labelledby="nav-conclude-tab"
                    tabindex="0">
                    conclude Case Section
                </div>
            </div>
        </div>



    </div>
@endsection
