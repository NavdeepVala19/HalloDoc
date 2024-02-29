@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="">Access</a>
    <div class="dropdown record-navigation ">
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            <h3>Patient Record</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-secondary">
                        <td>Client/Member</td>
                        <td>Created Date <i class="bi bi-arrow-down"></i></td>
                        <td>Conformation</td>
                        <td>Provider Name</td>
                        <td>Concluded Date</td>
                        <td>Status</td>
                        <td>Final Report</td>
                        <td class="actions">Actions</td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>Date</td>
                            <td>Confirmation Number</td>
                            <td>Provider Name</td>
                            <td>Concluded date</td>
                            <td>Status</td>
                            <td><button class="primary-empty">View</button></td>
                            <td>
                                <div class="dropdown ">
                                    <button class="primary-empty" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-actions">
                                        <li><a class="dropdown-item " href="">View
                                                Case</a></li>
                                        <li><a class="dropdown-item" href="">Chat</a></li>
                                        <li><a class="dropdown-item" href="">(Count) Documents</a>
                                        </li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
