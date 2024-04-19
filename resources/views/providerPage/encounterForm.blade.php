@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/encounterFormProvider.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    {{-- Encounter Form Changes Saved --}}
    @if (session('encounterChangesSaved'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('encounterChangesSaved') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Enter Data first then finalize it --}}
    @if (session('saveFormToFinalize'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ session('saveFormToFinalize') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container form-container">
        <div class="heading-container d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Encounter Form</h1>
            <a href="{{ route('provider.status', $requestData->status != 6 ? 'active' : 'conclude') }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        {{-- Form Starts From Here --}}
        <form action="{{ route('encounter.form.data') }}" method="POST" id="providerEncounterForm">
            @csrf
            <div class="section">
                @include('adminPage.encounter')
                {{-- Three buttons at last --}}
                <div class="button-section">
                    <input type="submit" value="Save Changes" class="primary-fill" id="providerEncounterFormBtn">
                    <a href="{{ route('encounter.finalized', $requestId) }}" type="button" class="finalize-btn">Finalize</a>
                    <a href="{{ route('provider.status', $requestData->status != 6 ? 'active' : 'conclude') }}"
                        class="primary-empty">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Get the pre-filled values from the input fields
            var initialFirstName = $("#providerEncounterForm #floatingInput1").val();
            var initialLastName = $("#providerEncounterForm #floatingInput2").val();
            var initialLocation = $("#providerEncounterForm #floatingInput3").val();
            var initialDateOfBirth = $("#providerEncounterForm #floatingInput4").val();
            var initialServiceDate = $("#providerEncounterForm #floatingInput5").val();
            var initialMobile = $("#providerEncounterForm #telephone").val();
            var initialEmail = $("#providerEncounterForm #floatingInput6").val();
            var initialPresentIllnessHistory = $("#providerEncounterForm #floatingTextarea1").val();
            var initialMedicalHistory = $("#providerEncounterForm #floatingTextarea2").val();
            var initialMedications = $("#providerEncounterForm #floatingTextarea3").val();
            var initialAllergies = $("#providerEncounterForm #floatingTextarea4").val();
            var initialTemperature = $("#providerEncounterForm #floatingInput7").val();
            var initialHeartRate = $("#providerEncounterForm #floatingInput8").val();
            var initialRepositoryRate = $("#providerEncounterForm #floatingInput9").val();
            var initialSisBP = $("#providerEncounterForm #floatingInput10").val();
            var initialDiaBP = $("#providerEncounterForm #floatingInput11").val();
            var initialOxygen = $("#providerEncounterForm #floatingInput12").val();
            var initialPain = $("#providerEncounterForm #floatingInput13").val();
            var initialHeent = $("#providerEncounterForm #floatingTextarea5").val();
            var initialCv = $("#providerEncounterForm #floatingTextarea6").val();
            var initialChest = $("#providerEncounterForm #floatingTextarea7").val();
            var initialAbd = $("#providerEncounterForm #floatingTextarea8").val();
            var initialExtr = $("#providerEncounterForm #floatingTextarea9").val();
            var initialSkin = $("#providerEncounterForm #floatingTextarea10").val();
            var initialNeuro = $("#providerEncounterForm #floatingTextarea11").val();
            var initialOther = $("#providerEncounterForm #floatingTextarea12").val();
            var initialDiagnosis = $("#providerEncounterForm #floatingTextarea13").val();
            var initialTreatmentPlan = $("#providerEncounterForm #floatingTextarea14").val();
            var initialMedicationDispensed = $("#providerEncounterForm #floatingTextarea15").val();
            var initialProcedure = $("#providerEncounterForm #floatingTextarea16").val();
            var initialFollowUp = $("#providerEncounterForm #floatingTextarea17").val();

            // Encounter Form Finalize Button
            $(".finalize-btn").click(function(e) {
                e.preventDefault();

                if (
                    initialFirstName == "" ||
                    initialLastName == "" ||
                    initialLocation == "" ||
                    initialDateOfBirth == "" ||
                    initialServiceDate == "" ||
                    initialMobile == "" ||
                    initialEmail == "" ||
                    initialAllergies == "" ||
                    initialTreatmentPlan == "" ||
                    initialMedicationDispensed == "" ||
                    initialProcedure == "" ||
                    initialFollowUp == ""
                ) {
                    // Remove any existing error messages
                    $(".button-section .text-danger").remove();

                    let error =
                        "<div class='text-danger'>Save form and then finalize!</div>";
                    $(".button-section").append(error);

                    setTimeout(() => {
                        $(".button-section .text-danger").fadeOut("slow");
                    }, 2000);

                    console.log(firstName, email);
                } else if (
                    initialFirstName !== $("#floatingInput1").val() ||
                    initialLastName !== $("#floatingInput2").val() ||
                    initialLocation !== $("#floatingInput3").val() ||
                    initialDateOfBirth !== $("#floatingInput4").val() ||
                    initialServiceDate !== $("#floatingInput5").val() ||
                    // initialMobile !== $("#telephone").val() ||
                    initialEmail !== $("#floatingInput6").val() ||
                    initialPresentIllnessHistory !== $("#floatingTextarea1").val() ||
                    initialMedicalHistory !== $("#floatingTextarea2").val() ||
                    initialMedications !== $("#floatingTextarea3").val() ||
                    initialAllergies !== $("#floatingTextarea4").val() ||
                    initialTemperature !== $("#floatingInput7").val() ||
                    initialHeartRate !== $("#floatingInput8").val() ||
                    initialRepositoryRate !== $("#floatingInput9").val() ||
                    initialSisBP !== $("#floatingInput10").val() ||
                    initialDiaBP !== $("#floatingInput11").val() ||
                    initialOxygen !== $("#floatingInput12").val() ||
                    initialPain !== $("#floatingInput13").val() ||
                    initialHeent !== $("#floatingTextarea5").val() ||
                    initialCv !== $("#floatingTextarea6").val() ||
                    initialChest !== $("#floatingTextarea7").val() ||
                    initialAbd !== $("#floatingTextarea8").val() ||
                    initialExtr !== $("#floatingTextarea9").val() ||
                    initialSkin !== $("#floatingTextarea10").val() ||
                    initialNeuro !== $("#floatingTextarea11").val() ||
                    initialOther !== $("#floatingTextarea12").val() ||
                    initialDiagnosis !== $("#floatingTextarea13").val() ||
                    initialTreatmentPlan !== $("#floatingTextarea14").val() ||
                    initialMedicationDispensed !== $("#floatingTextarea15").val() ||
                    initialProcedure !== $("#floatingTextarea16").val() ||
                    initialFollowUp !== $("#floatingTextarea17").val()
                ) {
                    // Remove any existing error messages
                    $(".button-section .text-danger").remove();

                    let error =
                        "<div class='text-danger'>Form has unsaved changes! Save the form before finalizing.</div>";
                    $(".button-section").append(error);

                    setTimeout(() => {
                        $(".button-section .text-danger").fadeOut("slow");
                    }, 2000);
                    console.log("Changes Detected");
                } else {
                    // Proceed with finalization
                    window.location.href = "{{ route('encounter.finalized', $requestId) }}";
                }
            });

        });
    </script>
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
