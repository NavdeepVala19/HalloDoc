@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/submitScreen.css') }}">
@endsection

@section('content')

<!-- main content of submit request screen -->

<div class="container">

    <!-- this div is for heading and back button -->

    <div class="header_part">
        <h1 class="heading">I am a..</h1>
        <a href="{{route('patientSite')}}" type="button" class="primary-empty"> <i class="bi bi-chevron-left"></i> Back</a>
    </div>


    <!-- this div is for main content -->

    <div class=" main-container d-flex flex-column justify-content-evenly align-items-center ">

        <div class="case active ps-3 d-flex flex-column justify-content-between patient">

            <a href="{{route('patient')}}" class="submitType" type="button" id="patient">PATIENT</a>

        </div>

        <div class="case active ps-3 d-flex flex-column justify-content-between family">

            <a href="{{route('family')}}" class="submitType" type="button" id="family">FAMILY/FRIEND</a>

        </div>

        <div class="case active ps-3 d-flex flex-column justify-content-between conceirge">

            <a href="{{route('concierge')}}" class="submitType" type="button" id="conceirge">CONCEIRGE</a>

        </div>

        <div class="case active ps-3 d-flex flex-column justify-content-between  business">


            <a href="{{route('business')}}" class="submitType" type="button" id="business">BUSINESS PARTNERS</a>
        </div>
    </div>

</div>

@endsection