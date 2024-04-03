@extends('patientSiteIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientViewDocument.css') }}">

@endsection

@section('nav-links')

<a href="{{route('patientDashboardData')}}" class="active-link">Dashboard</a>
<a href="{{route('patientProfile')}}" class="">Profile</a>

@endsection

@section('patientSiteContent')


<div class="container content">

    <div class="head-btn">
        <h2>Documents</h2>
        <a type="button" class="primary-empty btn d-flex justify-content-center align-items-center" href="{{route('patientDashboardData')}}"> <i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <form action="{{route('patientViewDocuments')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="request_wise_file_id" value="{{$documents[0]->request_id}}">
        <input type="hidden" name="request_type" value="1">
        <div class="container main-content">
            <p>Patient Name</p>
            <p class="user-name">{{$documents->first()->first_name}} <span class="confirmation-no">{{$documents->first()->confirmation_no}} </span> </p>
            <p>Check Here for any files that you or doctors of your subsequents requestors have attached for you to review.</p>

            <div class="input-group mb-3">
                <div class="file-selection-container" onclick="openFileSelection()">
                    <input type="file" id="fileInput" class="file-input" name="docs" onchange="this.form.submit()" />
                    <div class="file-button">Upload</div>
                </div>
                <p id="demo"></p>
            </div>
    </form>

    <form action="{{route('downloadAllFiles')}}" method="post">
        @csrf
        <div class="docs-download">
            <h3>Documents</h3>
            <input type="submit" value="Download" class="primary-empty btn down-button">
        </div>

        <table class="table">
            <thead class="table-secondary">
                <tr>
                    <td><input class="form-check-input master-checkbox" type="checkbox" id="flexCheckDefault" name="" value="">
                    </td>
                    <td></td>
                    <td>Uploader</td>
                    <td>Upload Date</td>
                    <td>Actions</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @foreach ($documents as $document)
                    <td><input class="form-check-input child-checkbox" type="checkbox" id="flexCheckDefault" name="selected_files[]" value="{{$document->id}}"></td>
                    <td><i class="bi bi-filetype-doc"></i> {{$document->file_name}}</td>
                    <td>{{$document->first_name}}</td>
                    <td>{{$document->created_at}}</td>
                    <td> <a href="{{route('downloadOne', $document->id)}}" class="primary-empty cloud-down"> <i class="bi bi-cloud-download "></i> </a> </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="table-content">
            @foreach ($documents as $document)
            <div class=" patient-content mt-4">
                <div class="check-docs">
                    <input class="form-check-input" type="checkbox" id="flexCheckDefault">
                    <p>{{$document->file_name}}</p>
                </div>
                <div class="mb-3">Testing test</div>
                <p>{{$document->created_at}}</p>
                <a href="{{route('downloadOne', $document->id)}}" class="primary-empty cloud-down" type="button"> <i class="bi bi-cloud-download "></i>
                </a>
            </div>
            @endforeach
        </div>
    </form>
</div>


@endsection


@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection