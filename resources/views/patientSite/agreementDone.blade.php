@extends('patientRequests')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientAgreement.css') }}">
    <style>
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 500px !important;
        }
    </style>
@endsection


@section('patientRequests')
    <div class="overlay"></div>

    <div class="container">
        <h2>You have already
            @if ($caseStatus == 4)
                accepted the agreement.
            @else
                cancelled the agreement.
            @endif
        </h2>
    </div>
@endsection
