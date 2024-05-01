@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
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
    {{-- File doesn't exists to download --}}
    @if (session('FileDoesNotExists'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ session('FileDoesNotExists') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Close Case
            </h1>
            <a href="{{ route('admin.status', 'toclose') }}" class="primary-empty"><i class="bi bi-chevron-left"></i>
                Back</a>
        </div>
        <form action="{{ route('admin.close.case.save') }}" method="POST" id="closeCase">
            @csrf
            <input type="text" class="request_id" value="{{ $data->id }}" name="requestId" hidden>
            <div class="section">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p>Patient Name</p>
                        <span class="patient-name">{{ $data->requestClient->first_name }}
                            {{ $data->requestClient->last_name }}</span>
                        <span class="confirmation-number">({{ $data->confirmation_no }})
                        </span>
                    </div>
                    <div>
                        {{-- <button class="primary-empty">Create Invoice Through Quickbooks</button> --}}
                    </div>
                </div>
                <h3>
                    Documents
                </h3>
                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead class="table-secondary">
                            <tr>
                                <th></th>
                                <th class="date-column">Upload Date</th>
                                <th class="download-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($files->isEmpty())
                                <tr>
                                    <td colspan="100" class="no-record">No Documents Found</td>
                                </tr>
                            @endif
                            @foreach ($files as $file)
                                <tr>
                                    <td>
                                        <i class="bi bi-filetype-doc doc-symbol"></i>
                                        @if ($file->is_finalize)
                                            {{ $file->file_name }}
                                        @else
                                            {{ substr($file_name->file_name, 14) }}
                                        @endif
                                    </td>
                                    <td>{{ $file->created_at }}</td>
                                    <td class="download-column">
                                        <a href="{{ route('download', $file->id) }}" class="primary-empty"><i
                                                class="bi bi-cloud-download"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h3>Patient</h3>
                <div class="mb-4 grid-2">
                    <input type="text" name="request_type_id" value="1" hidden>
                    <div class="form-floating ">
                        <input type="text" name="first_name" value="{{ $data->requestClient->first_name }}"
                            class="form-control" id="floatingInput" placeholder="First Name" disabled>
                        <label for="floatingInput">First Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" value="{{ $data->requestClient->last_name }}"
                            class="form-control" id="floatingInput" placeholder="Last Name" disabled>
                        <label for="floatingInput">Last Name</label>
                    </div>

                    <div class="form-floating ">
                        <input type="date" class="form-control" value="{{ $data->requestClient->date_of_birth }}"
                            id="floatingInput" placeholder="date of birth" disabled>
                        <label for="floatingInput">Date Of Birth</label>
                    </div>

                    <div class="form-floating">
                        <div class="d-flex gap-2 align-items-center phone-number-container">
                            <input type="tel" name="phone_number" value="{{ $data->requestClient->phone_number }}"
                                class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone"
                                disabled>
                            <button type="button" class="primary-empty"><i class="bi bi-telephone"></i></button>
                        </div>
                        @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="email" name="email" value="{{ $data->requestClient->email }}"
                            class="form-control email @error('email') is-invalid @enderror" id="floatingInput"
                            placeholder="name@example.com" disabled>
                        <label for="floatingInput">Email address</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end default-buttons">
                    <button type="button" class="primary-fill edit-btn">Edit</button>
                    <input type="submit" value="Close Case" name="closeCaseBtn" class="primary-empty close-edit-btn">
                    {{-- Clicking on this button, admin can close the case and that request will be moved into "Unpaid" --}}
                </div>

                <div class="text-end new-buttons">
                    <input type="submit" value="Save" name="closeCaseBtn" class="primary-fill save-edit-btn"
                        id="saveCloseCase">
                    <a type="button" class="primary-empty cancel-edit-btn">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
