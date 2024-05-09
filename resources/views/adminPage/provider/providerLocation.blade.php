@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/providerLocation.css') }}">
@endsection
@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection
@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('provider.location') }}" class="active-link">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.providers.list') }}">Provider</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.scheduling') }}">Scheduling</a></li>
            <li><a class="dropdown-item" href="#">Invoicing</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Access
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.user.access') }}">User Access</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.access.view') }}">Account Access</a></li>
        </ul>
    </div>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.search.records.view') }}">Search Records</a></li>
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

        <div id="map-container" style="width:100%;height:660px" class="mt-3">
           <iframe src="https://www.google.com/maps?q=[
                    @foreach ($providers as $data )
                            {{$data->address1}}+{{$data->address2}}+{{$data->city}}
                    @endforeach
                ]&output=embed" style="width:100%;height:660px"></iframe>
        </div>
    </div>

@endsection


@section('script')
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2SMA7GMfSg2E9equWVM1JIasVo3igTuc&loading=async&callback=getAddressFromCoordinates">
</script>
<script defer src="{{ URL::asset('assets/adminProvider/providerMapLocation.js') }}"></script>
<script defer  src="{{ URL::asset('assets/adminProvider/providerLocationRender.js') }}"></script>
@endsection
