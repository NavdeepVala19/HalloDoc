@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/encounterFormProvider.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Encounter Form</h1>
            <a href="{{ route('provider-dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        {{-- Form Starts From Here --}}
        <div class="section">
            <h1 class="main-heading">Medical Report-Confidential</h1>
            <form action="" method="POST"></form>

            <div>
                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control" id="floatingInput"
                        placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control" id="floatingInput" placeholder="Last Name">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="location" class="form-control" id="floatingInput" placeholder="location">
                    <label for="floatingInput">Location</label>
                    @error('location')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="date" class="form-control" id="floatingInput" placeholder="date of birth">
                    <label for="floatingInput">Date Of Birth</label>
                </div>
                <div class="form-floating ">
                    <input type="date" class="form-control" id="floatingInput" placeholder="date">
                    <label for="floatingInput">Date</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone" id="telephone"
                    placeholder="Phone Number">
                @error('phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email</label>
                </div>

                <div class="form-floating">
                    <textarea class="form-control note" placeholder="injury" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">History Of Present illness Or injury</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="Medical History" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Medical History</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="Medications" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Medications</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="allergies" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Allergies</label>
                </div>


                <div class="form-floating ">
                    <input type="text" name="temp" class="form-control" id="floatingInput" placeholder="Temp">
                    <label for="floatingInput">Temp</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="hr" class="form-control" id="floatingInput" placeholder="Temp">
                    <label for="floatingInput">HR</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="rr" class="form-control" id="floatingInput" placeholder="Temp">
                    <label for="floatingInput">RR</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="blood pressure" class="form-control" id="floatingInput"
                        placeholder="blood pressure">
                    <label for="floatingInput">Blood Pressure</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="blood pressure" class="form-control" id="floatingInput"
                        placeholder="blood pressure">
                    <label for="floatingInput">Blood Presure</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="o2" class="form-control" id="floatingInput" placeholder="o2">
                    <label for="floatingInput">O2</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="pain" class="form-control" id="floatingInput" placeholder="pain">
                    <label for="floatingInput">Pain</label>
                </div>

                <div class="form-floating">
                    <textarea class="form-control note" placeholder="Heent" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Heent</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="cv" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">CV</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="chest" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Chest</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="abd" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">ABD</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="extr" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Extr</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="skin" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Skin</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="neuro" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Neuro</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="other" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Other</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="diagnosis" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Diagnosis</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="Treatment Plan" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Treatment Plan</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="Medications Dispensed" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Medication Dispensed</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="procedures" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Procedures</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" placeholder="followup" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Followup</label>
                </div>


                {{-- Three buttons at last --}}
                <div>
                    <input type="submit" value="Save Changes" class="primary-fill">
                    <button class="finalize-btn">Finalize</button>
                    <button class="primary-empty">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection
