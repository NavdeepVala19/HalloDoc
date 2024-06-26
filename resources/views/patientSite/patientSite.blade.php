@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ asset('assets/patientSite/patientSite.css') }}">

@endsection

@section('patientContent')

<!-- main content -->
<div>
        <div class="main-container">
                <a href="{{route('submit.request')}}" class="submitType request" type="button">Submit A Request</a>
                <a href="{{route('patient.login.view')}}" class="submitType patients" type="button">Registered Patients</a>
        </div>
</div>

@endsection