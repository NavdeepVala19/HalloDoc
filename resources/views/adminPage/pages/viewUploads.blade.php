@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
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
    {{-- Document Upload Was Successfully --}}
    @include('alertMessages.uploadDocSuccess')

    {{-- Mail of All The selected Documents are sent --}}
    @include('alertMessages.mailDocsSentSuccess')

    {{-- No Records Found Error Message --}}
    @include('alertMessages.noRecordFound')

    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Documents
            </h1>
            <a href="{{ route(
                'admin.status',
                $data->status == 1
                    ? 'new'
                    : ($data->status == 3
                        ? 'pending'
                        : ($data->status == 4 || $data->status == 5
                            ? 'active'
                            : ($data->status == 6
                                ? 'conclude'
                                : ($data->status == 2 || $data->status == 7 || $data->status == 11
                                    ? 'toclose'
                                    : 'unpaid')))),
            ) }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <p>Patient Name</p>
            <span class="patient-name">{{ $data->first_name }} {{ $data->last_name }}</span>
            <span class="confirmation-number">({{ $data->confirmation_no }})</span>
            <p>Check here to review and add files that you or the Client/Member has attached to the Request.</p>

            <form action="{{ route('admin.upload.doc', $data->id) }}" method="POST" enctype="multipart/form-data"
                id="adminViewUploadsForm">
                @csrf
                <div class="custom-file-input mb-4">
                    <input type="file" name="document" id="file-upload" hidden>
                    <label for="file-upload"
                        class="upload-label @error('document')
                    is-invalid
                @enderror">
                        Select File </label>
                    <button type="submit" class="primary-fill upload-btn" id="adminUploadBtn">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        <span class="upload-txt">Upload</span>
                    </button>
                    @error('document')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </form>

            <form action="{{ route('operations') }}" method="POST">
                @csrf
                <input type="text" name="requestId" value="{{ $data->id }}" hidden>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>
                        Documents
                    </h3>
                    <div class="large-screen-btn">
                        <button type="submit" name="operation" value="download_all" class="primary-empty">Download
                            All</button>
                        <button type="submit" name="operation" value="delete_all" class="primary-empty">Delete All</button>
                        <button type="submit" name="operation" value="send_mail" class="primary-empty">Send Mail</button>
                    </div>
                    <div class="small-screen-btn">
                        <button type="submit" name="operation" value="download_all" class="primary-empty"><i
                                class="bi bi-cloud-arrow-down-fill"></i></button>
                        <button type="submit" name="operation" value="delete_all" class="primary-empty"><i
                                class="bi bi-trash-fill"></i></button>
                        <button type="submit" name="operation" value="send_mail" class="primary-empty"><i
                                class="bi bi-envelope"></i></button>
                    </div>
                </div>
                @error('selected')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
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
                            @if ($documents->isEmpty())
                                <tr>
                                    <td colspan="100" class="no-record">No Documents Found</td>
                                </tr>
                            @endif
                            @foreach ($documents as $document)
                                <tr>
                                    <td>
                                        <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                                            value="{{ $document->id }}" id="flexCheckDefault">
                                    </td>
                                    <td>
                                        <i class="bi bi-filetype-doc doc-symbol"></i>
                                        {{ substr($document->file_name, 14) }}
                                    </td>
                                    <td>{{ $document->created_at }}</td>
                                    <td class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('download', ['id' => $document->id]) }}"
                                            class="primary-empty"><i class="bi bi-cloud-download"></i></a>
                                        <a href="{{ route('document.delete', ['id' => $document->id]) }}"
                                            class="primary-empty"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mobile-listing">
                    @foreach ($documents as $document)
                        @if ($document)
                            <div class="list">
                                <div class="d-flex align-items-center gap-2">
                                    <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                                        value="{{ $document->id }}" id="flexCheckDefault">
                                    <span><i class="bi bi-filetype-doc doc-symbol"></i>
                                        {{ $document->file_name }}</span>
                                </div>
                                <div class="mb-3">
                                    {{ $document->created_at }}
                                </div>
                                <div>
                                    <a href="{{ route('download', ['id' => $document->id]) }}" class="primary-empty"><i
                                            class="bi bi-cloud-download"></i></a>
                                    <a href="{{ route('document.delete', ['id' => $document->id]) }}"
                                        class="primary-empty"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/validation.js') }}"></script>
@endsection
