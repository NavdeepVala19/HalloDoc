@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/encounterFormProvider.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="heading-container d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Encounter Form</h1>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        {{-- Form Starts From Here --}}
        <form action="{{ route('encounter.form.data') }}" method="POST">
            @csrf
            <div class="section">
                <h1 class="main-heading">Medical Report-Confidential</h1>

                <div>
                    <div class="grid-2">
                        <input type="text" name="request_type_id" value="{{ $id }}" hidden>
                        <div class="form-floating ">
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror" id="floatingInput"
                                placeholder="First Name"
                                value="@if ($data) {{ $data->first_name }} @endif">
                            <label for="floatingInput">First Name</label>
                            @error('first_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="last_name" class="form-control" id="floatingInput"
                                placeholder="Last Name"
                                value=@if ($data) {{ $data->last_name }} @endif>
                            <label for="floatingInput">Last Name</label>

                        </div>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="location" class="form-control" id="floatingInput" placeholder="location"
                            value="@if ($data) {{ $data->location }} @endif">
                        <label for="floatingInput">Location</label>

                    </div>

                    <div class="grid-2">
                        <div class="form-floating ">
                            <input type="date" name="date_of_birth" class="form-control" id="floatingInput"
                                placeholder="date of birth" {{-- value="@if {{ \Illuminate\Support\Carbon::parse($data->date_of_birth)->format('Y-m-d') }} @endif" --}} {{-- value={{ $data->requestClient->dob }} --}}
                                {{-- value="{{\Illuminate\Support\Carbon::parse($data->requestClient->dob)->format("Y-m-d")}}"  --}} {{-- value="{{ $shipment->date->format('Y-m-d') }}" --}}>
                            <label for="floatingInput">Date Of Birth</label>
                        </div>
                        <div class="form-floating ">
                            <input type="date" name="service_date" class="form-control" id="floatingInput"
                                placeholder="date" {{-- Displays current Date --}}
                                value="@if ($data) {{ $data->service_date }} @endif">
                            <label for="floatingInput">Date</label>
                        </div>

                        <input type="tel" name="mobile" class="form-control phone" id="telephone"
                            placeholder="Phone Number" value="@if ($data) {{ $data->mobile }} @endif">


                        <div class="form-floating">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="floatingInput" placeholder="name@example.com"
                                value="@if ($data) {{ $data->email }} @endif"
                                {{-- value="{{ $data->requestClient->email }}" --}}>
                            <label for="floatingInput">Email</label>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-floating">
                            <textarea class="form-control note" name="present_illness_history" placeholder="injury" id="floatingTextarea2">
@if ($data)
{{ $data->present_illness_history }}
@endif
</textarea>
                            <label for="floatingTextarea2">History Of Present illness Or injury</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="medical_history" placeholder="Medical History" id="floatingTextarea2"> @if ($data)
{{ $data->medical_history }}
@endif
</textarea>
                            <label for="floatingTextarea2">Medical History</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="medications" placeholder="Medications" id="floatingTextarea2">
@if ($data)
{{ $data->medications }}
@endif
</textarea>
                            <label for="floatingTextarea2">Medications</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="allergies" placeholder="allergies" id="floatingTextarea2">
@if ($data)
{{ $data->allergies }}
@endif
</textarea>
                            <label for="floatingTextarea2">Allergies</label>
                        </div>
                    </div>

                    <div class="grid-3">

                        <div class="form-floating ">
                            <input type="text" name="temperature" class="form-control" id="floatingInput"
                                placeholder="Temp"
                                value="@if ($data) {{ $data->temperature }} @endif">
                            <label for="floatingInput">Temp</label>
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="heart_rate" class="form-control" id="floatingInput"
                                placeholder="Temp"
                                value="@if ($data) {{ $data->heart_rate }} @endif">
                            <label for="floatingInput">HR</label>
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="repository_rate" class="form-control" id="floatingInput"
                                placeholder="Temp"
                                value="@if ($data) {{ $data->repository_rate }} @endif">
                            <label for="floatingInput">RR</label>
                        </div>
                        <div class="grid-2 blood-pressure">

                            <div class="form-floating ">
                                <input type="text" name="sis_BP" class="form-control" id="floatingInput"
                                    placeholder="blood pressure"
                                    value="@if ($data) {{ $data->sis_BP }} @endif">
                                <label for="floatingInput">Blood Pressure(systolic)</label>
                            </div>
                            <div class="form-floating ">
                                <input type="text" name="dia_BP" class="form-control" id="floatingInput"
                                    placeholder="blood pressure"
                                    value="@if ($data) {{ $data->dia_BP }} @endif">
                                <label for="floatingInput">Blood Presure(diastolic)</label>
                            </div>
                        </div>

                        <div class="form-floating ">
                            <input type="text" name="oxygen" class="form-control" id="floatingInput"
                                placeholder="o2" value="@if ($data) {{ $data->oxygen }} @endif">
                            <label for="floatingInput">O2</label>
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="pain" class="form-control" id="floatingInput"
                                placeholder="pain"
                                value="@if ($data) {{ $data->pain }} @endif">
                            <label for="floatingInput">Pain</label>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-floating">
                            <textarea class="form-control note" name="heent" placeholder="Heent" id="floatingTextarea2">
@if ($data)
{{ $data->heent }}
@endif
</textarea>
                            <label for="floatingTextarea2">Heent</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="cv" placeholder="cv" id="floatingTextarea2">
@if ($data)
{{ $data->cv }}
@endif
</textarea>
                            <label for="floatingTextarea2">CV</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="chest" placeholder="chest" id="floatingTextarea2">
@if ($data)
{{ $data->chest }}
@endif
</textarea>
                            <label for="floatingTextarea2">Chest</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="abd" placeholder="abd" id="floatingTextarea2">
@if ($data)
{{ $data->abd }}
@endif
</textarea>
                            <label for="floatingTextarea2">ABD</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="extr" placeholder="extr" id="floatingTextarea2">
@if ($data)
{{ $data->extr }}
@endif
</textarea>
                            <label for="floatingTextarea2">Extr</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="skin" placeholder="skin" id="floatingTextarea2">
@if ($data)
{{ $data->skin }}
@endif
</textarea>
                            <label for="floatingTextarea2">Skin</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="neuro" placeholder="neuro" id="floatingTextarea2">
@if ($data)
{{ $data->neuro }}
@endif
</textarea>
                            <label for="floatingTextarea2">Neuro</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="other" placeholder="other" id="floatingTextarea2">
@if ($data)
{{ $data->other }}
@endif
</textarea>
                            <label for="floatingTextarea2">Other</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="diagnosis" placeholder="diagnosis" id="floatingTextarea2">
@if ($data)
{{ $data->diagnosis }}
@endif
</textarea>
                            <label for="floatingTextarea2">Diagnosis</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="treatment_plan" placeholder="Treatment Plan" id="floatingTextarea2">
@if ($data)
{{ $data->treatment_plan }}
@endif
</textarea>
                            <label for="floatingTextarea2">Treatment Plan</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="medication_dispensed" placeholder="Medications Dispensed"
                                id="floatingTextarea2">
@if ($data)
{{ $data->medication_dispensed }}
@endif
</textarea>
                            <label for="floatingTextarea2">Medication Dispensed</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control note" name="procedure" placeholder="procedures" id="floatingTextarea2">
@if ($data)
{{ $data->procedure }}
@endif
</textarea>
                            <label for="floatingTextarea2">Procedures</label>
                        </div>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control note" name="followUp" placeholder="followup" id="floatingTextarea2">
@if ($data)
{{ $data->followUp }}
@endif
</textarea>
                        <label for="floatingTextarea2">Followup</label>
                    </div>


                    {{-- Three buttons at last --}}
                    <div class="button-section">
                        <input type="submit" value="Save Changes" class="primary-fill">
                        <a href="{{ route('generate.pdf', ['id' => $id]) }}" type="button"
                            class="finalize-btn">Finalize</a>
                        <button class="primary-empty">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
