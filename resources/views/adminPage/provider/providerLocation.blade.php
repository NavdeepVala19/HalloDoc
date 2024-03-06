@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/providerLocation.css') }}">
@endsection

@section('nav-links')
<a href="{{ route('admin.dashboard') }}">Dashboard</a>
<a href="" class="active-link">Provider Location</a>
<a href="">My Profile</a>
<a href="{{route('adminProvidersInfo')}}">Providers</a>
<a href="{{ route('admin.partners') }}">Partners</a>
<a href="{{ route('admin.access.view') }}">Access</a>
<div class="dropdown record-navigation ">
    <button class="record-btn " type="button" data-bs-toggle="dropdown" aria-expanded="false">
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


<div class="container">

    <div class="header-part d-flex justify-content-between">
        <h3>Provider Location</h3>
        <a href="{{ route('admin.dashboard') }}" class="primary-empty"> <i class="bi bi-chevron-left"></i> Back</a>
    </div>


    <div id="map" style="width:100%;height:660px" class="mt-3">

        <iframe src="https://www.google.com/maps?q=[ADDRESS]&output=embed" style="width:100%;height:660px"></iframe>
        <!-- <iframe src="https://www.google.com/maps?q=[mumbai]&output=embed" style="width:100%;height:660px"></iframe> -->
    </div>
</div>




@endsection