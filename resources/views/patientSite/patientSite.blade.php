@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientSite.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('assets/patientSite/patientSite.css') }}"> -->

@endsection

@section('patientContent')

<!-- main content -->
<div>
        <div class="main-container">
                <a href="{{route('submitRequest')}}" class="submitType request" type="button">Submit A Request</a>

                <a href="{{route('loginScreen')}}" class="submitType patients" type="button">Registered Patients</a>

        </div>
</div>

@endsection