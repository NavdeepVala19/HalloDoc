@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/encounterFormProvider.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('adminProvidersInfo') }}">Provider</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.scheduling') }}">Scheduling</a></li>
            <li><a class="dropdown-item" href="#">Invoicing</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Access
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.user.access') }}">User Access</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.access.view') }}">Account Access</a></li>
        </ul>
    </div>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.search.records.view') }}">Search Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
        </ul>
    </div>
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
            <a href="{{ route(
                'admin.status',
                $requestData->status == 4 || $requestData->status == 5
                    ? 'active'
                    : ($requestData->status == 6
                        ? 'conclude'
                        : 'toclose'),
            ) }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        {{-- Form Starts From Here --}}
        <form action="{{ route('admin.medical.data') }}" method="POST" id="adminEncounterForm">
            @csrf
            <div class="section">
                <h1 class="main-heading">Medical Report-Confidential</h1>
                <div>
                    <div class="grid-2">
                        <input type="text" name="request_id" value="{{ $id }}" hidden>
                        <div class="form-floating ">
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror" id="floatingInput1"
                                placeholder="First Name" value="{{ $data->first_name ?? '' }}">
                            <label for="floatingInput1">First Name</label>
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
                                placeholder="date of birth" value="{{ $data->date_of_birth ?? '' }}">
                            <label for="floatingInput">Date Of Birth</label>
                        </div>
                        <div class="form-floating ">
                            <input type="date" name="service_date" class="form-control" id="floatingInput"
                                placeholder="date" value="{{ $data->service_date ?? '' }}">
                            <label for="floatingInput">Date</label>
                        </div>
                        <div class="form-floating">
                            <div>
                                <input type="tel" name="mobile"
                                    class="form-control phone @error('mobile') is-invalid @enderror " id="telephone"
                                    placeholder="Phone Number" value="{{ $data->mobile ?? '' }}">
                            </div>
                            @error('mobile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating">
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" id="floatingInput2"
                                placeholder="name@example.com" value="{{ $data->email ?? '' }}">
                            <label for="floatingInput2">Email</label>
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
                                <label for="floatingInput" style="font-size: 12px">Blood Pressure(systolic)</label>
                            </div>
                            <div class="form-floating ">
                                <input type="number" name="dia_BP" class="form-control" id="floatingInput"
                                    placeholder="blood pressure" value={{ $data->dia_BP ?? '' }}>
                                <label for="floatingInput" style="font-size: 12px">Blood Presure(diastolic)</label>
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
                        <input type="submit" value="Save Changes" class="primary-fill" id="adminEncounterFormBtn">
                        <a href="{{ route(
                            'admin.status',
                            $requestData->status == 4 || $requestData->status == 5
                                ? 'active'
                                : ($requestData->status == 6
                                    ? 'conclude'
                                    : 'toclose'),
                        ) }}"
                            class="primary-empty">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
