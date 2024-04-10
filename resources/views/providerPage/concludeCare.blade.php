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
    {{-- Case Cancelled Successfully --}}
    @if (session('fileUploaded'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('fileUploaded') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- File doesn't exists to download --}}
    @if (session('FileDoesNotExists'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ session('FileDoesNotExists') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    {{-- Encounter Form need to be finalized --}}
    @if (session('encounterFormRequired'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ session('encounterFormRequired') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Conclude Care
            </h1>
            <a href="{{ route('provider.status', 'conclude') }}" class="primary-empty"><i class="bi bi-chevron-left"></i>
                Back</a>
        </div>

        {{-- After concluding the case the request status will change from “Conclude” to
“Closed” and will be shown in To-close dashboard state. To conclude a case finalizing encounter form is
required, without finalizing encounter form provider cannot conclude the case. --}}

        <div class="section">
            <h6>Patient Name</h6>
            <p class="patient-name mb-4">{{ $case->requestClient->first_name }}
                {{ $case->requestClient->last_name }}</p>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3>Encounter Forms</h3>
                <form action="{{ route('upload.conclude.care.docs') }}" method="POST" enctype="multipart/form-data"
                    class="upload-docs">
                    @csrf
                    <input type="text" value="{{ $case->id }}" name="caseId" hidden>
                    <div>
                        <input type="file" name="document" id="document" hidden>
                        <label for="document" class="primary-empty "><i class="bi bi-cloud-upload"></i>
                            <span class="upload-txt">Upload</span> </label>
                    </div>
                </form>
            </div>

            <div class="table-responsive conclude-care-docs">
                <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($docs->isEmpty())
                            <tr>
                                <td colspan="100" class="no-record">No Documents Found</td>
                            </tr>
                        @endif
                        @foreach ($docs as $doc)
                            @if ($doc)
                                <tr>
                                    <td>{{ $doc->file_name }}</td>
                                    <td class="action-column m-2"><a href="{{ route('download', $doc->id) }}"
                                            class="primary-empty"><i class="bi bi-cloud-download"></i></a></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <div>
                    <form action="{{ route('provider.conclude.care') }}" method="POST">
                        @csrf
                        <input type="text" value="{{ $case->id }}" name="caseId" hidden>
                        <h5>Provider Notes</h5>
                        <div class="form-floating mt-2 mb-4">
                            <textarea class="form-control" name="providerNotes" placeholder="notes" id="floatingTextarea2"></textarea>
                            <label for="floatingTextarea2">Provider Notes</label>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="primary-fill">Conclude Care</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#document').change(function() {
                $('.upload-docs').submit();
            })
        })
    </script>
@endsection
