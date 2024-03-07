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
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Email Logs (Gmail)</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        {{-- d-flex align-items-center justify-content-between gap-3 --}}
        <div class="section">
            <form action="{{ route('search.filter.email') }}" method="POST">
                @csrf
                <div class="grid-6 email-search-box">
                    <div class="form-floating">
                        <select name="role_id" class="form-select empty-fields" id="floatingSelect"
                            aria-label="Floating label select example">
                            <option value="0" selected>All</option>
                            <option value="1">Admin</option>
                            <option value="2">Physician</option>
                        </select>
                        <label for="floatingSelect">Search by role</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="receiver_name" class="form-control empty-fields" id="floatingInput"
                            placeholder="Receiver Name">
                        <label for="floatingInput">Receiver Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control empty-fields" id="floatingInput"
                            placeholder="name@example.com">
                        <label for="floatingInput">Email Id</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="created_date" class="form-control empty-fields" id="floatingInput"
                            placeholder="Created Date">
                        <label for="floatingInput">Created Date</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="sent_date" class="form-control empty-fields" id="floatingInput"
                            placeholder="Sent Date">
                        <label for="floatingInput">Sent Date</label>
                    </div>
                    <div class="button-section">
                        <button type="submit" class="primary-fill">Search</button>
                        <button type="button" class="primary-empty clearButton">Clear</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive table-view">
                <table class="table">
                    <thead class="table-secondary">
                        <td>Recipient</td>
                        <td>Action</td>
                        <td>Role Name</td>
                        <td>Email Id</td>
                        <td>Create Date <i class="bi bi-arrow-down"></i></td>
                        <td>Sent Date</td>
                        <td>Sent</td>
                        <td>Sent Tries</td>
                        <td>Confirmation Number</td>
                    </thead>
                    <tbody>
                        @foreach ($emails as $email)
                            @if (!empty($email))
                                <tr>
                                    <td>Name</td>
                                    <td>Request Monthly Data</td>
                                    <td>{{ $email->roles->name }}</td>
                                    <td>{{ $email->email }}</td>
                                    <td>{{ $email->created_at }}</td>
                                    <td>{{ $email->sent_date }}</td>
                                    <td>Yes</td>
                                    <td>{{ $email->sent_tries }}</td>
                                    <td>-</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mobile-listing">
                {{-- @foreach ($vendors as $vendor) --}}
                <div class="mobile-list">
                    <div class="main-section">
                        <h5 class="heading">Name</h5>
                        <div class="detail-box">
                            <span>
                                Action Name: <strong>Test</strong>
                            </span>
                            <br>
                            <span>
                                Email: <strong>Mail</strong>
                            </span>
                        </div>
                    </div>
                    <div class="details">
                        <span><i class="bi bi-person"></i> Role Name:</span>
                        <br>
                        <span><i class="bi bi-calendar3"></i>Create Date:</span>
                        <br>
                        <span><i class="bi bi-calendar3"></i>Sent Date:</span>
                        <br>
                        <span><i class="bi bi-check2"></i>Sent:</span>
                        <br>
                        <span><i class="bi bi-envelope"></i>Sent Tries:</span>
                        <br>
                        <span><i class="bi bi-check2"></i>Confirmation Number:</span>

                        <div class="role-name">
                            <span>Name</span>
                        </div>
                    </div>
                </div>


                {{-- @endforeach --}}
            </div>

        </div>
    </div>
@endsection
