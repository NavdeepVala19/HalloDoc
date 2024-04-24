@extends('patientRequests')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientAgreement.css') }}">
@endsection


@section('patientRequests')
    <div class="overlay"></div>

    <!-- Cancel pop-up -->
    <form action="{{ route('patient.cancel.agreement') }}" method="POST" id="cancelAgreementPatient">
        @csrf
        <input type="text" name="requestId" value="{{ $clientData->id }}" hidden>
        <div class="pop-up cancel-pop-up">
            <div class="popup-heading-section d-flex align-items-center justify-content-between">
                <span>Cancel Confirmation</span>
                <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="m-2">
                {{ $clientData->requestClient->first_name }}
                {{ $clientData->requestClient->last_name }}
            </div>
            <div class="form-floating">
                <textarea class="form-control @error('cancelReason')
                    is-invalid
                @enderror"
                    name="cancelReason" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 120px"></textarea>
                <label for="floatingTextarea2">Please Provide reason for cancellation</label>
                @error('cancelReason')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="submit" class="primary-fill" id="cancelAgreementPatientBtn">Confirm</button>
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </div>
    </form>


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
    </div>
@endsection


@section('script')
    <script>
        $(".cancel").click(function() {
            $(".cancel-pop-up").show();
            $(".overlay").show();
        });
    </script>

    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
