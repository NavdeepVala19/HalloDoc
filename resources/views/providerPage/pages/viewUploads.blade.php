@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
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
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <p>Patient Name</p>
            <span class="patient-name">{{ $data->first_name }}</span>
            <span class="confirmation-number">({{ $data->confirmation_number }})</span>
            <p>Check here to review and add files that you or the Client/Member has attached to the Request.</p>

            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="custom-file-input mb-4">
                    <input type="text" class="form-control" placeholder="Select File" readonly>
                    <label for="file-upload"><i class="bi bi-cloud-arrow-up me-2"></i><span
                            class="upload-txt">Upload</span></label>
                    <input type="file" name="document" onchange="this.form.submit()" id="file-upload" hidden>
                </div>
            </form>

            <form action="{{ route('operations') }}" method="POST">
                @csrf
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>
                        Documents
                    </h3>
                    <div>
                        <button type="submit" name="operation" value="download_all" class="primary-empty">Download All</button>
                        <button type="submit" name="operation" value="delete_all" class="primary-empty">Delete All</button>
                        <button class="primary-empty">Send Mail</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead class="table-secondary">
                            <tr>
                                <th>
                                    <input class="form-check-input master-checkbox" name="" type="checkbox"
                                        value="" id="flexCheckDefault">
                                </th>
                                <th>Documents</th>
                                <th>Upload Date <i class="bi bi-arrow-up"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td>
                                        <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                                            value="{{ $document->id }}" id="flexCheckDefault">
                                    </td>
                                    <td>
                                        <i class="bi bi-filetype-doc doc-symbol"></i>
                                        {{ $document->file_name }}
                                    </td>
                                    <td>{{ $document->created_at }}</td>
                                    <td class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('download', ['id' => $document->id]) }}" class="primary-empty"><i
                                                class="bi bi-cloud-download"></i></a>
                                        <a href="{{ route('document.delete', ['id' => $document->id]) }}"
                                            class="primary-empty"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
