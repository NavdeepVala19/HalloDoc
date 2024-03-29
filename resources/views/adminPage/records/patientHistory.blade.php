@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="">Access</a>
    <div class="dropdown record-navigation ">
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item " href="{{ route('admin.search.records.view') }}">Search Records</a></li>
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
            <form action="{{ route('admin.search.patient') }}" method="POST">
                @csrf
                <div class="grid-4">
                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control empty-fields" id="floatingInput"
                            placeholder="First Name">
                        <label for="floatingInput">First Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control empty-fields" id="floatingInput"
                            placeholder="Last Name">
                        <label for="floatingInput">Last Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control empty-fields" id="floatingInput"
                            placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>
                    <input type="tel" name="phone_number" class="form-control phone empty-fields" id="telephone"
                        placeholder="Phone Number">
                </div>
                <div class="text-end mb-3">
                    <button type="button" class="primary-empty clearButton">Clear</button>
                    <button type="submit" class="primary-fill">Search</button>
                </div>
            </form>
            <div class="table-responsive">
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
                        @foreach ($patients as $patient)
                            <tr>
                                <td>{{ $patient->first_name }}</td>
                                <td>{{ $patient->last_name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone_number }}</td>
                                <td>{{ $patient->street }}, {{ $patient->city }}, {{ $patient->state }}</td>
                                <td><a href="{{ route('patient.records', $patient->id) }}" class="primary-empty">Explore</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $patients->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
