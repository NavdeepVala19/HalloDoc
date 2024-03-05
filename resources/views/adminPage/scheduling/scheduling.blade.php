@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/scheduling.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="" class="active-link">Providers</a>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="">Access</a>
    <div class="dropdown record-navigation ">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item " href="{{ route('admin.search.records.view') }}">Search Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Scheduling</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        {{-- d-flex align-items-center justify-content-between gap-3 --}}
        <div class="section">
            <div class="d-flex align-items-center justify-content-between">
                <div class=" region-dropdown">
                    <select name="role_id" class="form-select empty-fields" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="0" selected>All Regions</option>
                        <option value="1">Admin</option>
                        <option value="2">Physician</option>
                    </select>
                    {{-- <label for="floatingSelect">All Regions</label> --}}
                </div>
                <div>
                    <button class="primary-fill">Providers On Call</button>
                    <button class="primary-fill">Shifts For Review</button>
                    <button class="primary-fill">Add New Shift</button>
                </div>
            </div>
            <div>Date</div>
            <div class="d-flex justify-content-end">
                <span class="d-flex align-items-center">
                    <div class="pending-shift m-2"></div>Pending Shifts
                </span>
                <span class="d-flex align-items-center">
                    <div class="approved-shift m-2"></div>Approved Shifts
                </span>
            </div>
            <div id="calendar"></div>
        </div>
    </div>
@endsection
