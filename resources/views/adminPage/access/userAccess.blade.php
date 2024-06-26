@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <!-- <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/access.css') }}"> -->
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/userAccess.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('provider.location') }}">Provider Location</a>
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
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="m-5 spacing">
        <h3 class="main-heading">User Access</h3>
        <div class="section">
            <div>
                <form action="" method="post" id="userAccessFilteredData">
                    <div class="" id="accountType-CreateBtns">
                        <div class="form-floating m-3 account-type-drop-down">
                            <select class="form-select" name="role_name" id="accountType">
                                <option value="all">All</option>
                                <option value="admin" id="accountAdmin">Admin</option>
                                <option value="physician" id="accountPhysician">Physician</option>
                            </select>
                            <label for="floatingSelect">Account Type</label>

                            <select class="form-select" name="role_name" id="accountTypeMobile">
                                <option value="all">All</option>
                                <option value="admin" id="accountAdmin">Admin</option>
                                <option value="physician" id="accountPhysician">Physician</option>
                            </select>
                            <label for="floatingSelect">Account Type</label>
                        </div>

                        <div class="mt-4" id="createAccBtns">
                            <a href="{{ route('create.new.admin.view') }}" class="primary-fill" id="createAdmin">Create
                                Admin</a>
                            <a href="{{ route('admin.create.new.provider') }}" class="primary-fill" id="createPhysician">Create
                                Physician</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="userAccessData">
                <div class="table-responsive table-view " id="user-access-data">
                    <table class="table" id="user-access-table">
                        <thead class="table-secondary text-center align-middle">
                            <td>Account Type</td>
                            <td>Account POC</td>
                            <td>Phone</td>
                            <td>Status</td>
                            <td>Open Requests</td>
                            <td>Actions</td>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach ($userAccessData as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->first_name }}</td>
                                    <td>{{ $data->mobile }}</td>
                                    <td>{{ $data->status }}</td>
                                    <td>123</td>
                                    <td><a href="{{ route('admin.user.accessEdit', Crypt::encrypt($data->user_id)) }}"
                                            class="primary-empty" type="button">Edit</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $userAccessData->links('pagination::bootstrap-5') }}
                </div>

                <div class="mobile-listing mt-3">
                    @foreach ($userAccessData as $data)
                        <div class="mobile-list">
                            <div class="main-section">
                                <h5 class="heading">{{ $data->first_name }}</h5>
                                <div class="detail-box">

                                    <span>
                                        Account Type: {{ $data->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="details">
                                <span><i class="bi bi-telephone"></i> Phone: {{ $data->mobile }}</span>
                                <br>
                                <span><i class="bi bi-check-lg"></i></i>Status: {{ $data->status }}</span>
                                <br>
                                <span><i class="bi bi-journal"></i>Open Requests: 123</span>
                                <br>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.user.accessEdit', Crypt::encrypt($data->user_id)) }}"
                                        class="primary-empty" type="button">Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{ $userAccessData->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script defer src="{{ URL::asset('assets/adminPage/userAccessFilterPage.js') }}"></script>
@endsection
