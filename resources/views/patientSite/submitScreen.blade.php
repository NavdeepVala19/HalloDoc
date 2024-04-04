@extends('patientSiteIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/submitScreen.css') }}">
@endsection

@section('patientSiteContent')
    @if (Session::has('message'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    <!-- main content of submit request screen -->
    <div class="container">
        <!-- this div is for heading and back button -->
        <div class="header_part">
            <h1 class="heading">I am a..</h1>
            <a href="{{ route('patientSite') }}" type="button"
                class="primary-empty d-flex justify-content-center align-items-center"> <i class="bi bi-chevron-left"></i>
                Back</a>
        </div>

        <!-- this div is for main content -->
        <div class=" main-container d-flex flex-column justify-content-evenly align-items-center ">
            <a href="{{ route('patient') }}" class="case patient submitType" type="button">PATIENT</a>
            <a href="{{ route('family') }}" class="case family submitType" type="button">FAMILY/FRIEND</a>
            <a href="{{ route('concierge') }}" class="case conceirge submitType" type="button" id="conceirge">CONCEIRGE</a>
            <a href="{{ route('business') }}" class="case business submitType" type="button" id="business">BUSINESS
                PARTNERS</a>
        </div>
    </div>
@endsection
@section('script')
    <script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
