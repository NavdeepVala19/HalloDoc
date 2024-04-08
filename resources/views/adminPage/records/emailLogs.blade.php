@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
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
            <h3>Email Logs (Gmail)</h3>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <form action="{{ route('search.filter.email') }}" method="get">
                @csrf
                {{-- Pagination details for filtering --}}
                {{--  The currentPage() method retrieves the current page number of the paginator. --}}
                <input type="hidden" name="page" value="{{ $emails->currentPage() }}">

                {{--  The perPage() method retrieves the number of items per page in the paginator. --}}
                <input type="hidden" name="per_page" value="{{ $emails->perPage() }}">
                <div class="grid-6 email-search-box">
                    <div class="form-floating">
                        <select name="role_id" class="form-select empty-fields" id="floatingSelect"
                            aria-label="Floating label select example">
                            <option value="0" selected @if (isset($roleId) && $roleId == 0) selected @endif>All</option>
                            <option value="1" @if (isset($roleId) && $roleId == 1) selected @endif>Admin</option>
                            <option value="2" @if (isset($roleId) && $roleId == 2) selected @endif>Physician</option>
                            <option value="3" @if (isset($roleId) && $roleId == 3) selected @endif>Patient</option>
                        </select>
                        <label for="floatingSelect">Search by role</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="receiver_name" class="form-control empty-fields" id="floatingInput"
                            placeholder="Receiver Name"
                            value="@if (isset($receiverName)) {{ $receiverName }} @endIf">
                        <label for="floatingInput">Receiver Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control empty-fields" id="floatingInput"
                            placeholder="name@example.com"
                            value="@if (isset($email)) {{ $email }} @endIf">
                        <label for="floatingInput">Email Id</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="created_date" class="form-control empty-fields" id="floatingInput"
                            placeholder="Created Date" value=@if (isset($createdDate)) {{ $createdDate }} @endIf>
                        <label for="floatingInput">Created Date</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="sent_date" class="form-control empty-fields" id="floatingInput"
                            placeholder="Sent Date" value=@if (isset($sentDate)) {{ $sentDate }} @endIf>
                        <label for="floatingInput">Sent Date</label>
                    </div>
                    <div class="button-section">
                        <button type="submit" class="primary-fill">Search</button>
                        <a href="{{ route('admin.email.records.view') }}" type="button"
                            class="primary-empty clearButton">Clear</a>
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
                                    <td>
                                        @if ($email->recipient_name)
                                            {{ $email->recipient_name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $email->action }}</td>
                                    <td>
                                        @if ($email->roles)
                                            {{ $email->roles->name }}
                                        @endif
                                    </td>
                                    <td>{{ $email->email }}</td>
                                    <td>{{ $email->create_date }}</td>
                                    <td>{{ $email->sent_date }}</td>
                                    <td>Yes</td>
                                    <td>{{ $email->sent_tries }}</td>
                                    <td>
                                        @if ($email->request_id)
                                            {{ $email->request->confirmation_no }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mobile-listing">
                @foreach ($emails as $email)
                    <div class="mobile-list">
                        <div class="main-section">
                            <h5 class="heading">Name</h5>
                            <div class="detail-box">
                                <span>
                                    Action Name: <strong>{{ $email->action }}</strong>
                                </span>
                                <br>
                                <span>
                                    Email: <strong>{{ $email->email }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="details">
                            <span><i class="bi bi-person"></i> Role Name: @if ($email->roles)
                                    {{ $email->roles->name }}
                                @endif
                            </span>
                            <br>
                            <span><i class="bi bi-calendar3"></i>Create Date: {{ $email->create_date }}</span>
                            <br>
                            <span><i class="bi bi-calendar3"></i>Sent Date: {{ $email->sent_date }}</span>
                            <br>
                            <span><i class="bi bi-check2"></i>Sent: Yes</span>
                            <br>
                            <span><i class="bi bi-envelope"></i>Sent Tries: {{ $email->sent_tries }}</span>
                            <br>
                            <span><i class="bi bi-check2"></i>Confirmation Number: @if ($email->request_id)
                                    {{ $email->request->confirmation_no }}
                                @endif
                            </span>

                            <div class="role-name">
                                <span>
                                    @if ($email->recipient_name)
                                        {{ $email->recipient_name }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- {{ $emails->appends(request()->except('page'))->links('pagination::bootstrap-5') }} --}}
            {{ $emails->appends(request()->query())->links('pagination::bootstrap-5') }}
            {{-- {{ $emails->paginate->appends(request()->except('page'))->links('pagination::bootstrap-5') }} --}}
        </div>
    </div>
@endsection
