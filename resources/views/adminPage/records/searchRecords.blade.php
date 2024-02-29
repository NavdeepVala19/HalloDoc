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
    <a href="{{ route('admin.access.view') }}">Access</a>
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
            <h3>Search Records</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
        </div>
    </div>
@endsection
