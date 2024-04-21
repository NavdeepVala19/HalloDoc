@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    @include('loading')
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
                'provider.status',
                $data->status == 1
                    ? 'new'
                    : ($data->status == 3
                        ? 'pending'
                        : ($data->status == 4 || $data->status == 5
                            ? 'active'
                            : 'conclude')),
            ) }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <p>Patient Name</p>
            <span class="patient-name">{{ $data->first_name }} {{ $data->last_name }}</span>
            <span class="confirmation-number">({{ $data->confirmation_no }})</span>
            <p>Check here to review and add files that you or the Client/Member has attached to the Request.</p>

            <form action="{{ route('proivder.upload.doc', $data->id) }}" method="POST" enctype="multipart/form-data"
                id="providerViewUploadsForm">
                @csrf
                <div class="custom-file-input mb-4">
                    <input type="file" name="document" id="file-upload" hidden>
                    <label for="file-upload"
                        class="upload-label @error('document')
                        is-invalid
                    @enderror">
                        Select File </label>
                    <button type="submit" class="primary-fill upload-btn" id="providerUploadBtn">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        <span class="upload-txt">Upload</span>
                    </button>
                    @error('document')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </form>

            <form action="{{ route('operations') }}" method="POST" id="providerViewUploadOperationsForm">
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
                        <button type="submit" name="operation" value="send_mail" class="primary-empty sendMailBtn">Send
                            Mail</button>
                    </div>
                    <div class="small-screen-btn">
                        <button type="submit" name="operation" value="download_all" class="primary-empty"><i
                                class="bi bi-cloud-arrow-down-fill"></i></button>
                        <button type="submit" name="operation" value="delete_all" class="primary-empty"><i
                                class="bi bi-trash-fill"></i></button>
                        <button type="submit" name="operation" value="send_mail" class="primary-empty sendMailBtn"><i
                                class="bi bi-envelope"></i></button>
                    </div>
                </div>
                <div id="error-container"></div>
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
                                <th>Upload Date</th>
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
                                @if ($document)
                                    <tr>
                                        <td>
                                            <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                                                value="{{ $document->id }}" id="flexCheckDefault">
                                        </td>
                                        <td>
                                            <i class="bi bi-filetype-doc doc-symbol"></i>
                                            {{-- {{ $document->file_name }} --}}
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
                                @endif
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
    <div class="page">
        {{ $documents->links('pagination::bootstrap-5') }}
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/validation.js') }}"></script>
@endsection
