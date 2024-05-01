<h1 class="main-heading">Medical Report-Confidential</h1>
<div>
    <div class="grid-2">
        <input type="text" name="request_id" value="{{ $requestId }}" hidden>
        <div class="form-floating ">
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                id="floatingInput1" placeholder="First Name" value="{{ $data->first_name ?? '' }}">
            <label for="floatingInput1">First Name</label>
            @error('first_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating ">
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                id="floatingInput2" placeholder="Last Name" value={{ $data->last_name ?? '' }}>
            <label for="floatingInput2">Last Name</label>
            @error('last_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-floating ">
        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
            id="floatingInput3" placeholder="location" value="{{ $data->location ?? '' }}">
        <label for="floatingInput3">Location</label>
        @error('location')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="grid-2">
        <div class="form-floating ">
            <input type="date" name="date_of_birth"
                class="form-control date_of_birth @error('date_of_birth') is-invalid @enderror" id="floatingInput4"
                placeholder="date of birth" value="{{ $data->date_of_birth ?? '' }}">
            <label for="floatingInput4">Date Of Birth</label>
            @error('date_of_birth')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating ">
            <input type="date" name="service_date" class="form-control @error('service_date') is-invalid @enderror"
                id="floatingInput5" placeholder="date" value="{{ $data->service_date ?? '' }}">
            <label for="floatingInput5">Date</label>
            @error('service_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating">
            <div class="mobile-container">
                <input type="tel" name="mobile" class="form-control phone @error('mobile') is-invalid @enderror "
                    id="telephone" value="{{ $data->mobile ?? '' }}">
            </div>
            @error('mobile')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                id="floatingInput6" placeholder="name@example.com" value="{{ $data->email ?? '' }}">
            <label for="floatingInput6">Email</label>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="grid-2">
        <div class="form-floating">
            <textarea class="form-control note" name="present_illness_history" placeholder="injury" id="floatingTextarea1">{{ $data->present_illness_history ?? '' }}</textarea>
            <label for="floatingTextarea1">History Of Present illness Or injury</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="medical_history" placeholder="Medical History" id="floatingTextarea2">{{ $data->medical_history ?? '' }}</textarea>
            <label for="floatingTextarea2">Medical History</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="medications" placeholder="Medications" id="floatingTextarea3">{{ $data->medications ?? '' }}</textarea>
            <label for="floatingTextarea3">Medications</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note  @error('allergies') is-invalid @enderror" name="allergies" placeholder="allergies"
                id="floatingTextarea4">{{ $data->allergies ?? '' }}</textarea>
            <label for="floatingTextarea4">Allergies</label>
            @error('allergies')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="grid-3">
        <div class="form-floating ">
            <input type="number" name="temperature" class="form-control" id="floatingInput7" placeholder="Temp"
                value={{ $data->temperature ?? '' }}>
            <label for="floatingInput7">Temp (degree C)</label>
        </div>
        <div class="form-floating ">
            <input type="number" name="heart_rate" class="form-control" id="floatingInput8"
                placeholder="heart_rate" value={{ $data->heart_rate ?? '' }}>
            <label for="floatingInput8">HR</label>
        </div>
        <div class="form-floating ">
            <input type="number" name="repository_rate" class="form-control" id="floatingInput9"
                placeholder="repository_rate" value={{ $data->repository_rate ?? '' }}>
            <label for="floatingInput9">RR</label>
        </div>
        <div class="grid-2 blood-pressure">
            <div class="form-floating ">
                <input type="number" name="sis_BP" class="form-control overflow-hidden" id="floatingInput10"
                    placeholder="blood pressure" value={{ $data->sis_BP ?? '' }}>
                <label for="floatingInput10">Blood Pressure(systolic)</label>
            </div>
            <div class="form-floating ">
                <input type="number" name="dia_BP" class="form-control" id="floatingInput11"
                    placeholder="blood pressure" value={{ $data->dia_BP ?? '' }}>
                <label for="floatingInput11">Blood Presure(diastolic)</label>
            </div>
        </div>
        <div class="form-floating ">
            <input type="number" name="oxygen" class="form-control" id="floatingInput12" placeholder="o2"
                value={{ $data->oxygen ?? '' }}>
            <label for="floatingInput12">O2</label>
        </div>
        <div class="form-floating ">
            <input type="text" name="pain" class="form-control" id="floatingInput13" placeholder="pain"
                value="{{ $data->pain ?? '' }}">
            <label for="floatingInput13">Pain</label>
        </div>
    </div>

    <div class="grid-2">
        <div class="form-floating">
            <textarea class="form-control note" name="heent" placeholder="Heent" id="floatingTextarea5">{{ $data->heent ?? '' }}</textarea>
            <label for="floatingTextarea5">Heent</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="cv" placeholder="cv" id="floatingTextarea6">{{ $data->cv ?? '' }}</textarea>
            <label for="floatingTextarea6">CV</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="chest" placeholder="chest" id="floatingTextarea7">{{ $data->chest ?? '' }}</textarea>
            <label for="floatingTextarea7">Chest</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="abd" placeholder="abd" id="floatingTextarea8">{{ $data->abd ?? '' }}</textarea>
            <label for="floatingTextarea8">ABD</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="extr" placeholder="extr" id="floatingTextarea9">{{ $data->extr ?? '' }}</textarea>
            <label for="floatingTextarea9">Extr</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="skin" placeholder="skin" id="floatingTextarea10">{{ $data->skin ?? '' }}</textarea>
            <label for="floatingTextarea10">Skin</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="neuro" placeholder="neuro" id="floatingTextarea11">{{ $data->neuro ?? '' }}</textarea>
            <label for="floatingTextarea11">Neuro</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="other" placeholder="other" id="floatingTextarea12">{{ $data->other ?? '' }}</textarea>
            <label for="floatingTextarea12">Other</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note" name="diagnosis" placeholder="diagnosis" id="floatingTextarea13">{{ $data->diagnosis ?? '' }}</textarea>
            <label for="floatingTextarea13">Diagnosis</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control note  @error('treatment_plan') is-invalid @enderror" name="treatment_plan"
                placeholder="Treatment Plan" id="floatingTextarea14">{{ $data->treatment_plan ?? '' }}</textarea>
            <label for="floatingTextarea14">Treatment Plan</label>
            @error('treatment_plan')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating">
            <textarea class="form-control note @error('medication_dispensed') is-invalid @enderror" name="medication_dispensed"
                placeholder="Medications Dispensed" id="floatingTextarea15">{{ $data->medication_dispensed ?? '' }}</textarea>
            <label for="floatingTextarea15">Medication Dispensed</label>
            @error('medication_dispensed')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-floating">
            <textarea class="form-control note @error('procedure') is-invalid @enderror" name="procedure"
                placeholder="procedures" id="floatingTextarea16">{{ $data->procedure ?? '' }}</textarea>
            <label for="floatingTextarea16">Procedures</label>
            @error('procedure')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-floating">
        <textarea class="form-control note @error('followUp') is-invalid @enderror" name="followUp" placeholder="followup"
            id="floatingTextarea17">{{ $data->followUp ?? '' }}</textarea>
        <label for="floatingTextarea17">Followup</label>
        @error('followUp')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
