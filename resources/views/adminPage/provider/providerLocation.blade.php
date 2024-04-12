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
    <a href="{{ route('providerLocation') }}" class="active-link">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('adminProvidersInfo') }}">Provider</a></li>
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

        <!-- <iframe src="https://www.google.com/maps?q=[shivanjali heights-2,abrama road,mota varachha,394101,surat]&output=embed" style="width:100%;height:660px"></iframe> -->

        <div id="map-container" style="width:100%;height:660px" class="mt-3">
            <iframe id="map-iframe" style="width:100%;height:660px" frameborder="0" scrolling="no" marginheight="0"
                marginwidth="0"></iframe>
        </div>
    </div>

    <script>
        function updateMap() {
            var addresses = @json($providers);
            var mapUrl = "https://www.google.com/maps?q=";

            addresses.forEach(function(provider, index) {
                if (index !== 0) {
                    mapUrl += "+";
                }

                mapUrl += encodeURIComponent(provider.address1 + ", " + provider.address2 + ", " + provider.city +
                    ", " + provider.zipcode);
            });

            document.getElementById('map-iframe').src = mapUrl + "&output=embed";
        }

        // Call the function to update the map when the page loads
        updateMap();
    </script>
    </body>

    </html>
    </body>

    </html>
@endsection
