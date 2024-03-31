@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientAgreement.css') }}">
@endsection


@section('content')
    <div class="overlay"></div>

    {{-- Agreement Agreed by Patient, these pop-up/alert will be shown --}}
    @if (session('agreementAgreed'))
    <div class="alert alert-success popup-message ">
        <span>
            {{ session('agreementAgreed') }}
        </span>
        <i class="bi bi-check-circle-fill"></i>
    </div>
    @endif
    
    {{-- Agreement Cancelled by Patient, these pop-up/alert will be shown --}}
    @if (session('agreementCancelled'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('agreementCancelled') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container">
        <p>To provide best medical service, we cannot determine the cost right away.If you agree to our service,
            so we provide care and follow-up untill all care is completed.
            So with this points, if you like us to provider care to
            you click on 'Agree' and we'll get started immediately, if you do not agree simply click"Cancel"
        </p>

        <form action="{{ route('patient.agree.agreement') }}" method="POST">
            <input type="text" name="requestId" value="{{ $clientData->id }}" hidden>
            @csrf
            <div class="btns mt-5 d-flex flex-row justify-content-around">
                <button type="submit" class="agree"> I Agree </button>
                <button type="button" class="cancel"> Cancel </button>
            </div>
        </form>

        <!-- Cancel pop-up -->
        <form action="{{ route('patient.cancel.agreement') }}" method="POST">
            @csrf
            <input type="text" name="requestId" value="{{ $clientData->id }}" hidden>
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
                    <textarea class="form-control" name="cancelReason" placeholder="Leave a comment here" id="floatingTextarea2"
                        style="height: 120px"></textarea>
                    <label for="floatingTextarea2">Please Provide reason for cancellation</label>
                </div>

                <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                    <button type="submit" class="primary-fill ">Confirm</button>
                    <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
                </div>
            </div>
        </form>
    </div>
@endsection


@section('script')
    <script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
