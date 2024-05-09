@extends('patientSiteIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientViewDocument.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('patient.dashboard') }}" class="active-link">Dashboard</a>
    <a href="{{ route('patient.profile.view') }}" class="">Profile</a>
@endsection

@section('patientSiteContent')
    @include('loading')

    <div class="container content mb-3">
        <div class="head-btn">
            <h2>Documents</h2>
            <a type="button" class="primary-empty btn d-flex justify-content-center align-items-center"
                href="{{ route('patient.dashboard') }}"> <i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <form action="{{ route('patient.upload.document') }}" method="post" enctype="multipart/form-data"
            id="patientUploadDocs">
            @csrf
            <input type="hidden" name="request_wise_file_id" value="{{ $documents[0]->request_id }}">
            <input type="hidden" name="request_type" value="1">
            <div class="container main-content">
                <p>Patient Name</p>
                <p class="user-name">{{ $documents->first()->first_name }} <span class="confirmation-no"
                        style="color: gray;">{{ $documents->first()->confirmation_no }} </span> </p>
                <p>Check Here for any files that you or doctors of your subsequents requestors have attached for you to
                    review.</p>

                <div class="custom-file-input mb-4" id="form-floating">
                    <input type="file" name="document" id="file-upload" hidden>
                    <label for="file-upload" class="upload-label @error('document') is-invalid @enderror"
                        style="color: #3c9eff;">Select File </label>
                    <button type="submit" class="primary-fill upload-btn">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        <span class="upload-txt">Upload</span>
                    </button>
                    @error('document')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </form>

        <form action="{{ route('patient.download.multiple.files') }}" method="post">
            @csrf
            <input type="text" name="requestId" value="{{ $documents->first()->request_id }}" hidden>
            <div class="docs-download">
                <h3>Documents</h3>
                <button class="primary-empty btn down-button" type="submit" value="Download" id="docs_download"><i class="bi bi-cloud-arrow-down"></i><span class="download-btn">Download</span> </button>
            </div>

            <table class="table">
                <thead class="table-secondary">
                    <tr>
                        <td><input class="form-check-input master-checkbox" type="checkbox" id="flexCheckDefault"
                                name="" value=""> </td>
                        <td></td>
                        <td>Uploader</td>
                        <td>Upload Date</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td><input class="form-check-input child-checkbox" type="checkbox" id="flexCheckDefault"
                                    name="selected_files[]" value="{{ $document->id }}"></td>
                            <td><i class="bi bi-filetype-doc"></i>{{ substr($document->file_name, 14) }}</td>
                            <td>{{ $document->first_name }}</td>
                            <td>{{ date_format(date_create($document->created_date), 'd-m-Y') }}</td>
                            <td> <a href="{{ route('patient.download.one.document', Crypt::encrypt($document->id)) }}"
                                    class="primary-empty cloud-down"> <i class="bi bi-cloud-download "></i> </a> </td>
                        </tr>
                    @endforeach
                    {{ $documents->links('pagination::bootstrap-5') }}
                </tbody>
            </table>
            <div class="table-content">
                @foreach ($documents as $document)
                    <div class=" patient-content mt-4">
                        <div class="check-docs">
                            <input class="form-check-input child-checkbox" type="checkbox" id="flexCheckDefault" name="selected_files[]" value="{{ $document->id }}">
                            <p>{{ substr($document->file_name, 14) }}</p>
                        </div>
                        <div class="mb-3">{{ $document->first_name }}</div>
                        <p>{{ $document->created_at }}</p>
                        <a href="{{ route('patient.download.one.document', Crypt::encrypt($document->id)) }}"
                            class="primary-empty cloud-down" type="button"> <i class="bi bi-cloud-download "></i>
                        </a>
                    </div>
                @endforeach
                {{ $documents->links('pagination::bootstrap-5') }}
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
    <script defer src="{{ URL::asset('assets/patientSite/patientViewDocs.js') }}"></script>
@endsection
