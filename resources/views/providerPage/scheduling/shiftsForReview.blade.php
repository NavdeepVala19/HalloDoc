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
            <h3>Requested Shifts</h3>
            <a href="{{ route('scheduling') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class=" region-dropdown">
                    <select name="role_id" class="form-select empty-fields" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="0" selected>All Regions</option>
                        <option value="1">Admin</option>
                        <option value="2">Physician</option>
                    </select>
                </div>
                <div class="button-section">
                    <a href="{{ route('scheduling') }}" class="current-shift-btn">View Current Month Shifts</a>
                    <a href="" class="approved-selected-btn">Approved Selected</a>
                    <a href="" class="delete-selected-btn">Delete Selected</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>
                                <input class="form-check-input master-checkbox" name="" type="checkbox"
                                    value="" id="flexCheckDefault">
                            </th>
                            <th>Staff <i class="bi bi-arrow-up"></i></th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Region</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($documents as $document)  --}}
                        <tr>
                            <td>
                                <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                                    value="" id="flexCheckDefault">
                            </td>
                            <td>
                                Name
                            </td>
                            <td>Date</td>
                            <td>Shift-timing</td>
                            <td>Region</td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
