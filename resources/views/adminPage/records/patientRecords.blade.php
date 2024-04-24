@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
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
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Patient Record</h3>
            <a href="{{ route('admin.patient.records.view') }}" class="primary-empty"><i class="bi bi-chevron-left"></i>
                Back</a>
        </div>
        <div class="section records-container">
            <div class="table-responsive table-view">
                <table class="table">
                    <thead class="table-secondary">
                        <td>Client/Member</td>
                        <td>Created Date</td>
                        <td>Confirmation No.</td>
                        <td>Provider Name</td>
                        <td>Concluded Date</td>
                        <td>Status</td>
                        <td>Final Report</td>
                        <td class="actions">Actions</td>
                    </thead>
                    <tbody>
                        @if ($data->isEmpty())
                            <tr>
                                <td colspan="100" class="no-record">No Records Found</td>
                            </tr>
                        @endif
                        @foreach ($data as $record)
                            @if (!empty($record))
                                <tr>
                                    <td>{{ $record->first_name }} {{ $record->last_name }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $record->request->confirmation_no }}</td>
                                    <td>
                                        @if ($record->request->provider)
                                            Dr. {{ $record->request->provider->first_name }}
                                            {{ $record->request->provider->last_name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @foreach ($record->request->requestStatusTable as $requestStatus)
                                            @if ($requestStatus->status == 6)
                                                {{ \Carbon\Carbon::parse($requestStatus->created_at)->format('d/m/Y') }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($record->request->statusTable)
                                            {{ $record->request->statusTable->status_type }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($isFinalize)
                                            <a href="{{ route('download.encounter.form', $status->request_id) }}"
                                                class="primary-empty">View</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown ">
                                            <button class="primary-empty" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-actions">
                                                <li><a href="{{ route('admin.view.case', Crypt::encrypt($record->id)) }}"
                                                        class="dropdown-item" href="">View
                                                        Case</a></li>
                                                <li><a href="{{ route('admin.view.upload', Crypt::encrypt($record->id)) }}"
                                                        class="dropdown-item" href="">({{ $documentCount }})
                                                        Documents</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mobile-listing">
                @if ($data->isEmpty())
                    <div class="no-record mt-3 mb-3">
                        <span>No Records Found</sp>
                    </div>
                @endif
                @foreach ($data as $record)
                    <div class="mobile-list">
                        <div class="main-section">
                            <h5 class="heading">{{ $record->first_name }} {{ $record->last_name }}</h5>
                            <div class="detail-box">
                                <span>
                                    <strong>{{ $record->request->confirmation_no }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="details">
                            <span>
                                <i class="bi bi-person"></i> Created Date:
                                {{ $record->created_at }}
                            </span>
                            <br>
                            <span><i class="bi bi-calendar3"></i> Provider: @if ($record->request->provider)
                                    Dr. {{ $record->request->provider->first_name }}
                                    {{ $record->request->provider->last_name }}
                                @else
                                    -
                                @endif
                            </span>
                            <br>
                            <span> <i class="bi bi-calendar3"></i>
                                status : @if ($record->request->statusTable)
                                    {{ $record->request->statusTable->status_type }}
                                @endif
                            </span>
                            <br>
                            <div class="mobile-button-section">
                                <a href="{{ route('admin.view.case', $record->id) }}" class="patient-record-btn">View
                                    Case</a>
                                <a href="{{ route('admin.view.upload', $record->id) }}"
                                    class="patient-record-btn">({{ $documentCount }})Documents</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
