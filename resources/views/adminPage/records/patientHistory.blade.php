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
            <li><a class="dropdown-item" href="{{ route('admin.providers.list') }}">Provider</a></li>
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
        <h3>Patient History</h3>
        <div class="section">
            <form action="{{ route('admin.search.patient') }}" method="get">
                @csrf
                <div class="grid-4">
                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control empty-fields" id="floatingInput"
                            placeholder="First Name" value="@if (isset($firstName)) {{ $firstName }} @endif">
                        <label for="floatingInput">First Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control empty-fields" id="floatingInput"
                            placeholder="Last Name" value="@if (isset($lastName)) {{ $lastName }} @endif">
                        <label for="floatingInput">Last Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control empty-fields" id="floatingInput"
                            placeholder="name@example.com"
                            value="@if (isset($email)) {{ $email }} @endif">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="tel" name="phone_number" class="form-control" id="floatingInput3"
                            placeholder="phone" value="@if (isset($phoneNumber)) {{ $phoneNumber }} @endif">
                        <label for="floatingInput3">Phone Number</label>
                    </div>
                </div>
                <div class="text-end mb-3">
                    <a href="{{ route('admin.patient.records.view') }}" type="button"
                        class="primary-empty clearButton">Clear</a>
                    <button type="submit" class="primary-fill">Search</button>
                </div>
            </form>
            <div class="table-responsive table-view">
                <table class="table">
                    <thead class="table-secondary">
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Address</td>
                        <td class="actions">Actions</td>
                    </thead>
                    <tbody>
                        @if ($patients->isEmpty())
                            <tr>
                                <td colspan="100" class="no-record">No Records Found</td>
                            </tr>
                        @endif
                        @foreach ($patients as $patient)
                            <tr>
                                <td>{{ $patient->first_name }}</td>
                                <td>{{ $patient->last_name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone_number }}</td>
                                <td>{{ $patient->street }}, {{ $patient->city }}, {{ $patient->state }}</td>
                                <td><a href="{{ route('patient.records', Crypt::encrypt($patient->id)) }}"
                                        class="primary-empty">Explore</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mobile-listing">
                @if ($patients->isEmpty())
                    <div class="no-record mt-3 mb-3">
                        <span>No Records Found</sp>
                    </div>
                @endif
                @foreach ($patients as $patient)
                    <div class="mobile-list">
                        <div class="main-section">
                            <h5 class="heading">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                            <div class="detail-box">
                                <span>
                                    <strong>{{ $patient->phone_number }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="details">
                            <span>
                                <i class="bi bi-person"></i> Email:
                                {{ $patient->email }}
                            </span>
                            <br>
                            <span><i class="bi bi-calendar3"></i> Phone: {{ $patient->phone_number }}</span>
                            <div class="text-end">
                                <a href="{{ route('patient.records', $patient->id) }}" class="primary-empty">Explore</a>
                                </td>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $patients->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
