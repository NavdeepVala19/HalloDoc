@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/encounterFormProvider.css') }}">
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
    <div class="container form-container">
        <div class="heading-container d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Encounter Form</h1>
            <a href="{{ route('provider.status', $requestData->status != 6 ? 'active' : 'conclude') }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        {{-- Form Starts From Here --}}
        <form action="{{ route('encounter.form.data') }}" method="POST">
            @csrf
            <div class="section">
                <h1 class="main-heading">Medical Report-Confidential</h1>
                <div>
                    <div class="grid-2">
                        <input type="text" name="request_id" value="{{ $id }}" hidden>

                        <div class="form-floating ">
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror" id="floatingInput"
                                placeholder="First Name" value="{{ $data->first_name ?? '' }}">
                            <label for="floatingInput">First Name</label>
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="last_name" class="form-control" id="floatingInput"
                                placeholder="Last Name" value={{ $data->last_name ?? '' }}>
                            <label for="floatingInput">Last Name</label>

                        </div>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="location" class="form-control" id="floatingInput" placeholder="location"
                            value="{{ $data->location ?? '' }}">
                        <label for="floatingInput">Location</label>

                    </div>

                    <div class="grid-2">
                        <div class="form-floating ">
                            <input type="date" name="date_of_birth" class="form-control" id="floatingInput"
                                placeholder="date of birth" value="{{ $data->date_of_birth ?? '' }}" {{-- value="@if {{ \Illuminate\Support\Carbon::parse($data->date_of_birth)->format('Y-m-d') }} @endif" --}}
                                {{-- value={{ $data->requestClient->dob }} --}} {{-- value="{{\Illuminate\Support\Carbon::parse($data->requestClient->dob)->format("Y-m-d")}}"  --}} {{-- value="{{ $shipment->date->format('Y-m-d') }}" --}}>
                            <label for="floatingInput">Date Of Birth</label>
                        </div>
                        <div class="form-floating ">
                            <input type="date" name="service_date" class="form-control" id="floatingInput"
                                placeholder="date" {{-- Displays current Date --}} value="{{ $data->service_date ?? '' }}">
                            <label for="floatingInput">Date</label>
                        </div>

                        <input type="tel" name="mobile" class="form-control phone" id="telephone"
                            placeholder="Phone Number" value="{{ $data->mobile ?? '' }}">


                        <div class="form-floating">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="floatingInput" placeholder="name@example.com" value="{{ $data->email ?? '' }}"
                                {{-- value="{{ $data->requestClient->email }}" --}}>
                            <label for="floatingInput">Email</label>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-floating">
                            <textarea class="form-control note" name="present_illness_history" placeholder="injury" id="floatingTextarea2">{{ $data->present_illness_history ?? '' }}</textarea>
                            <label for="floatingTextarea2">History Of Present illness Or injury</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="medical_history" placeholder="Medical History" id="floatingTextarea2">{{ $data->medical_history ?? '' }}</textarea>
                            <label for="floatingTextarea2">Medical History</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="medications" placeholder="Medications" id="floatingTextarea2">{{ $data->medications ?? '' }}</textarea>
                            <label for="floatingTextarea2">Medications</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="allergies" placeholder="allergies" id="floatingTextarea2">{{ $data->allergies ?? '' }}</textarea>
                            <label for="floatingTextarea2">Allergies</label>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="form-floating ">
                            <input type="number" name="temperature" class="form-control" id="floatingInput"
                                placeholder="Temp" value={{ $data->temperature ?? '' }}>
                            <label for="floatingInput">Temp</label>
                        </div>
                        <div class="form-floating ">
                            <input type="number" name="heart_rate" class="form-control" id="floatingInput"
                                placeholder="Temp" value={{ $data->heart_rate ?? '' }}>
                            <label for="floatingInput">HR</label>
                        </div>
                        <div class="form-floating ">
                            <input type="number" name="repository_rate" class="form-control" id="floatingInput"
                                placeholder="Temp" value={{ $data->repository_rate ?? '' }}>
                            <label for="floatingInput">RR</label>
                        </div>
                        <div class="grid-2 blood-pressure">

                            <div class="form-floating ">
                                <input type="number" name="sis_BP" class="form-control" id="floatingInput"
                                    placeholder="blood pressure" value={{ $data->sis_BP ?? '' }}>
                                <label for="floatingInput">Blood Pressure(systolic)</label>
                            </div>
                            <div class="form-floating ">
                                <input type="number" name="dia_BP" class="form-control" id="floatingInput"
                                    placeholder="blood pressure" value={{ $data->dia_BP ?? '' }}>
                                <label for="floatingInput">Blood Presure(diastolic)</label>
                            </div>
                        </div>

                        <div class="form-floating ">
                            <input type="number" name="oxygen" class="form-control" id="floatingInput"
                                placeholder="o2" value={{ $data->oxygen ?? '' }}>
                            <label for="floatingInput">O2</label>
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="pain" class="form-control" id="floatingInput"
                                placeholder="pain" value="{{ $data->pain ?? '' }}">
                            <label for="floatingInput">Pain</label>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-floating">
                            <textarea class="form-control note" name="heent" placeholder="Heent" id="floatingTextarea2">{{ $data->heent ?? '' }}</textarea>
                            <label for="floatingTextarea2">Heent</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="cv" placeholder="cv" id="floatingTextarea2">{{ $data->cv ?? '' }}</textarea>
                            <label for="floatingTextarea2">CV</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="chest" placeholder="chest" id="floatingTextarea2">{{ $data->chest ?? '' }}</textarea>
                            <label for="floatingTextarea2">Chest</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="abd" placeholder="abd" id="floatingTextarea2">{{ $data->abd ?? '' }}</textarea>
                            <label for="floatingTextarea2">ABD</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="extr" placeholder="extr" id="floatingTextarea2">{{ $data->extr ?? '' }}</textarea>
                            <label for="floatingTextarea2">Extr</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="skin" placeholder="skin" id="floatingTextarea2">{{ $data->skin ?? '' }}</textarea>
                            <label for="floatingTextarea2">Skin</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="neuro" placeholder="neuro" id="floatingTextarea2">{{ $data->neuro ?? '' }}</textarea>
                            <label for="floatingTextarea2">Neuro</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="other" placeholder="other" id="floatingTextarea2">{{ $data->other ?? '' }}</textarea>
                            <label for="floatingTextarea2">Other</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="diagnosis" placeholder="diagnosis" id="floatingTextarea2">{{ $data->diagnosis ?? '' }}</textarea>
                            <label for="floatingTextarea2">Diagnosis</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="treatment_plan" placeholder="Treatment Plan" id="floatingTextarea2">{{ $data->treatment_plan ?? '' }}</textarea>
                            <label for="floatingTextarea2">Treatment Plan</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="medication_dispensed" placeholder="Medications Dispensed"
                                id="floatingTextarea2">{{ $data->medication_dispensed ?? '' }}</textarea>
                            <label for="floatingTextarea2">Medication Dispensed</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="procedure" placeholder="procedures" id="floatingTextarea2">{{ $data->procedure ?? '' }}</textarea>
                            <label for="floatingTextarea2">Procedures</label>
                        </div>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control note" name="followUp" placeholder="followup" id="floatingTextarea2">{{ $data->followUp ?? '' }}</textarea>
                        <label for="floatingTextarea2">Followup</label>
                    </div>

                    {{-- Three buttons at last --}}
                    <div class="button-section">
                        <input type="submit" value="Save Changes" class="primary-fill">
                        <a href="{{ route('encounter.finalized', $id) }}" type="button"
                            class="finalize-btn">Finalize</a>
                        <a href="{{ route('provider.status', $requestData->status != 6 ? 'active' : 'conclude') }}"
                            class="primary-empty">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
