@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider-dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Documents
            </h1>
            <a href="{{ route('provider-dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <p>Patient Name</p>
            <span class="patient-name">PatientName Bold with Blue color</span>
            <span class="confirmation-number">(Confirmation Number)</span>
            <p>Check here to review and add files that you or the Client/Member has attached to the Request.</p>

            <div class="custom-file-input">
                <input type="text" class="form-control" placeholder="Select File" readonly>
                <label for="file-upload"><i class="bi bi-cloud-arrow-up me-2"></i><span
                        class="upload-txt">Upload</span></label>
                <input type="file" id="file-upload" hidden>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3>
                    Documents
                </h3>
                <div>
                    <button class="primary-empty">Download All</button>
                    <button class="primary-empty">Delete All</button>
                    <button class="primary-empty">Send Mail</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </th>
                            <th>Documents</th>
                            <th>Upload Date <i class="bi bi-arrow-up"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($cases as $case)
                            <tr class="type-{{ $case->request_type_id }}">
                                <td>{{ $case->first_name }}</td>
                                <td>{{ $case->phone_number }}</td>
                                <td>{{ $case->address }}</td>
                                <td>
                                    <button class="table-btn "><i class="bi bi-person-check me-2"></i>Admin</button>
                                </td>
                                <td><button class="table-btn">Actions</button></td>
                            </tr>
                        @endforeach --}}
                        <tr>
                            <td>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </td>
                            <td>
                                <i class="bi bi-filetype-doc doc-symbol"></i>
                            </td>
                            <td></td>
                            <td>
                                <button class="primary-empty"><i class="bi bi-cloud-download"></i></button>
                                <button class="primary-empty"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
