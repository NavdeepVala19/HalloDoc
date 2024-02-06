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
        <button type="button" class="btn btn-outline-primary">
            < Back</button>
    </div>


    <!-- this div is for main content -->

    <div class=" main-container d-flex flex-column justify-content-evenly align-items-center ">

        <div class="case active ps-3 d-flex flex-column justify-content-between">

            <a href="" class="submitType" type="button">PATIENT</a>

        </div>

        <div class="case active ps-3 d-flex flex-column justify-content-between">

            <a href="" class="submitType" type="button">FAMILY/FRIEND</a>

        </div>

        <div class="case active ps-3 d-flex flex-column justify-content-between">

            <a href="" class="submitType" type="button">CONCEIRGE</a>

        </div>

        <div class="case active ps-3 d-flex flex-column justify-content-between">


            <a href="" class="submitType" type="button">BUSINESS PARTNERS</a>
        </div>
    </div>

</div>

@endsection