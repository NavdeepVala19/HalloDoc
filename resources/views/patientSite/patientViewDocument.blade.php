@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientViewDocument.css') }}">

@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

@section('nav-links')

<a href="" class="active-link">Dashboard</a>
<a href="">Profile</a>

@endsection

@section('content')

<div class="container content">

    <div class="head-btn">
        <h2>Documents</h2>
        <a type="button" class="primary-empty btn" href="{{route('patientDashboardData')}}"> <i
                class="bi bi-chevron-left"></i> Back</a>
    </div>

    <form action="{{route('patientViewDocuments')}}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="request_type" value="1">

        <div class="container main-content">


            <p>Patient Name</p>
            <p class="user-name">Testing Test <span class="confirmation-no">(MD05434JSUIRSA)</span> </p>
            <p>Check Here for any files that you or doctors of your subsequents requestors have attached for you to
                review.
            </p>

            <div class="input-group mb-3">


                <div class="file-selection-container" onclick="openFileSelection()">
                    <input type="file" id="fileInput" class="file-input" name="docs" onchange="this.form.submit()" />
                    <div class="file-button">Upload</div>
                </div>

                <p id="demo"></p>

            </div>

            <div class="docs-download">
                <h3>Documents</h3>
                <a href="{{route('download-selected-files')}}" type="button"
                    class="primary-empty btn down-button">Download</a>


            </div>

            <table class="table">
                <thead class="table-secondary">
                    <tr>
                        <td><input class="form-check-input master-checkbox" type="checkbox" id="flexCheckDefault"></td>
                        <td></td>
                        <td>Uploader</td>
                        <td>Upload Date</td>
                        <td>Actions</td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        @foreach ($documents as $document)
                        <td><input class="form-check-input child-checkbox" type="checkbox" id="flexCheckDefault"
                                name="selected_files[]"></td>
                        <td><i class="bi bi-filetype-doc"></i> {{$document->file_name}}</td>
                        <td>Testing test</td>
                        <td>{{$document->created_at}}</td>
                        <td> <a href="{{route('download',['id'=>$document->id])}}" class="primary-empty cloud-down"> <i
                                    class="bi bi-cloud-download "></i> </a> </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            {{$documents->links('pagination::bootstrap-5')}}

            <div class="table-content">

                @foreach ($documents as $document)
                <div class=" patient-content mt-4">

                    <div class="check-docs">
                        <input class="form-check-input" type="checkbox" id="flexCheckDefault">
                        <p>{{$document->file_name}}</p>
                    </div>
                    <div class="mb-3">Testing test</div>
                    <p>{{$document->created_at}}</p>
                    <a href="{{route('download',['id'=>$document->id])}}" class="primary-empty cloud-down"
                        type="button"> <i class="bi bi-cloud-download "></i>
                    </a>

                </div>
                @endforeach

                {{$documents->links('pagination::bootstrap-5')}}
            </div>

        </div>
    </form>
</div>

@endsection