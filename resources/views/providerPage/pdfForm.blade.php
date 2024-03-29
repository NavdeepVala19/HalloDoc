<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .main-heading {
            color: rgb(0, 190, 232);
            text-align: center;
        }

        /* Text area height */
        .note {
            height: 100px !important;
        }

        /* Make the text not to overflow */
        .blood-pressure input {
            overflow: hidden;
        }

        .finalize-btn {
            border: 1px solid #6d128f;
            background-color: #6d128f;
            padding: 8px 12px;
            color: #fff;
            border-radius: 8px;
        }

        .blood-pressure {
            margin: 0;
        }

        .button-section {
            margin: 25px 0;
            text-align: right;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0;
        }

        /* .details h3 {
            display: inline-block;
        } */

        .section {
            /* border: 1px solid grey; */
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 24px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em,
                rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em,
                rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
        }

        h3 span {
            color: rgb(53, 74, 99);
        }
    </style>
    <title>Document</title>
</head>

<body>
    <div class="section">
        <h1 class="main-heading">Medical Report-Confidential</h1>
        <div>
            <div class="details">
                <h3>First Name : <span> {{ $data->first_name ?? '-' }} </span> </h3>
                <h3>Last Name : <span>{{ $data->last_name ?? '-' }}</span> </h3>
                <h3>Location : <span>{{ $data->location ?? '-' }}</span></h3>
                <h3>Date Of Birth : <span>{{ $data->date_of_birth ?? '-' }}</span></h3>
                <h3>Service Date : <span>{{ $data->service_date ?? '-' }}</span></h3>
                <h3>Phone Number : <span>{{ $data->mobile ?? '-' }}</span></h3>
                <h3>Email : <span>{{ $data->email ?? '-' }}</span></h3>
                <h3>History Of Present illness Or injury : <span>{{ $data->present_illness_history ?? '-' }}</span></h3>
                <h3>Medical History : <span>{{ $data->medical_history ?? '-' }}</span></h3>
                <h3>Medications : <span>{{ $data->medications ?? '-' }}</span></h3>
                <h3>Allergies : <span>{{ $data->allergies ?? '-' }}</span></h3>
                <h3>Temperature : <span>{{ $data->temperature ?? '-' }}</span></h3>
                <h3>Heart Rate : <span>{{ $data->heart_rate ?? '-' }}</span></h3>
                <h3>Repository Rate : <span>{{ $data->repository_rate ?? '-' }}</span></h3>
                <h3>Blood Pressure(systolic) : <span>{{ $data->sis_BP ?? '-' }}</span></h3>
                <h3>Blood Pressure(diastolic) : <span>{{ $data->dia_BP ?? '-' }}</span></h3>
                <h3>Oxygen : <span>{{ $data->oxygen ?? '-' }}</span></h3>
                <h3>Pain : <span>{{ $data->pain ?? '-' }}</span></h3>
                <h3>Heent : <span>{{ $data->heent ?? '-' }}</span></h3>
                <h3>CV : <span>{{ $data->cv ?? '-' }}</span></h3>
                <h3>Chest : <span>{{ $data->chest ?? '-' }}</span></h3>
                <h3>ABD : <span>{{ $data->abd ?? '-' }}</span></h3>
                <h3>Extr : <span>{{ $data->extr ?? '-' }}</span></h3>
                <h3>Skin : <span>{{ $data->skin ?? '-' }}</span></h3>
                <h3>Neuro : <span>{{ $data->neuro ?? '-' }}</span></h3>
                <h3>Other : <span>{{ $data->other ?? '-' }}</span></h3>
                <h3>Diagnosis : <span>{{ $data->diagnosis ?? '-' }}</span></h3>
                <h3>Treatment Plan : <span>{{ $data->treatment_plan ?? '-' }}</span></h3>
                <h3>Medication Dispensed : <span>{{ $data->medication_dispensed ?? '-' }}</span></h3>
                <h3>Procedure : <span>{{ $data->procedure ?? '-' }}</span></h3>
                <h3>Followup : <span>{{ $data->followUp ?? '-' }}</span></h3>
            </div>
        </div>
    </div>
</body>

</html>
