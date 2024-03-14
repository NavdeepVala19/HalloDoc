@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientAgreement.css') }}">
@endsection


@section('content')
    <div class="container">
        <p>To provide best medical service, we cannot determine the cost right away.If you agree to our service,
            so we provide care and follow-up untill all care is completed.
            So with this points, if you like us to provider care to
            you click on 'Agree' and we'll get started immediately, if you do not agree simply click"Cancel"
        </p>

        <div class="btns mt-5 d-flex flex-row justify-content-around">
            <button class="agree"> I Agree </button>
            <button class="cancel"> Cancel </button>
        </div>

        <!-- Cancel pop-up -->
        <div class="pop-up cancel-pop-up">
            <div class="popup-heading-section d-flex align-items-center justify-content-between">
                <span>Cancel Confirmation</span>
                <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
            </div>
            <p class="mt-4">
                {{ $clientData->requestClient->first_name }}
                {{ $clientData->requestClient->last_name }}
            </p>

            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 120px"></textarea>
                <label for="floatingTextarea2">Please Provide reason for cancellation</label>
            </div>


            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button class="primary-fill ">Confirm</button>
                <button class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </div>


    </div>
@endsection
