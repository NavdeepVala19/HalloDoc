@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientViewDocument.css') }}">

@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

@section('nav-links')

<a href="">Dashboard</a>
<a href="">Profile</a>

@endsection

@section('content')

<div class="container content">

    <div class="head-btn">
        <h2>Documents</h2>
        <a href="" type="button" class="primary-empty btn"> <i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="container main-content">

        <p>Patient Name</p>
        <p class="user-name">Testing Test <span class="confirmation-no">(MD05434JSUIRSA)</span> </p>
        <p>Check Here for any files that you or doctors of your subsequents requestors have attached for you to review.
        </p>

        <div class="input-group mb-3">
            <!-- <input type="text" class="form-control" placeholder="Select File" readonly> -->
            <!-- <button class="btn primary-fill" type="button" id="button-addon"><i class="bi bi-cloud-upload"></i> <span
                    class="upload-text">Upload</span> </button> -->

            <!-- <div class="d-flex align-items-center gap-1"></div> -->
            {{-- Select Signature --}}
            <!-- <div class="custom-file-input">
                    <input type="text" placeholder="Select File" readonly class="form-control">
                    <label for="signature"> <i class="bi bi-cloud-arrow-up me-2"> </i> <span class="upload-txt">Upload</span> </label>
                    <input type="file" id="upload-file" hidden>
                </div> -->



            <div class="file-selection-container" onclick="openFileSelection()">
                <input type="file" id="fileInput" class="file-input" />
                <div class="file-button">Upload</div>
                <div id="demo"></div>
            </div>

        </div>

        <div class="docs-download">
            <h3>Documents</h3>
            <a href="" type="button" class="primary-empty btn down-button">Download All</a>
            <a href="" type="button" class="primary-empty btn-down"><i class="bi bi-cloud-download"></i></a>


        </div>

        <table class="table">
            <thead class="table-secondary">
                <tr>
                    <td><input class="form-check-input" type="checkbox" id="flexCheckDefault"></td>
                    <td></td>
                    <td>Uploader</td>
                    <td>Upload Date</td>
                    <td>Actions</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><input class="form-check-input" type="checkbox" id="flexCheckDefault"></td>
                    <td>dummy.pdf</td>
                    <td>Testing test</td>
                    <td>Aug 4 2023</td>
                    <td> <a href="" class="primary-empty cloud-down"> <i class="bi bi-cloud-download "></i> </a> </td>
                </tr>
            </tbody>

        </table>

        <div class="table-content">

            <div class="check-docs">
                <input class="form-check-input" type="checkbox" id="flexCheckDefault">
                <p>dummy.pdf</p>
            </div>

        
                <p>Aug 4 2023</p>
                <a href="" class="primary-empty cloud-down"> <i class="bi bi-cloud-download "></i> </a>
      


        </div>

    </div>
</div>

@endsection