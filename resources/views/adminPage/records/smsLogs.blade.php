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
            <h3>SMS Logs (Twilio)</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        {{-- d-flex align-items-center justify-content-between gap-3 --}}
        <div class="section">

            <form action="{{ route('admin.sms.records.search') }}" method="post">
                @csrf

                 {{--  The currentPage() method retrieves the current page number of the paginator. --}}
                 <input type="hidden" name="page" value="{{ $sms->currentPage() }}">

                 {{--  The perPage() method retrieves the number of items per page in the paginator. --}}
                 <input type="hidden" name="per_page" value="{{ $sms->perPage() }}"> 


                <div class="grid-6 email-search-box">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                            name="role_type">
                            <option selected>All</option>
                            <option value="1" {{ Session::get('role_type') == '1' ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ Session::get('role_type') == '2' ? 'selected' : '' }}>Physician
                            </option>
                            <option value="3" {{ Session::get('role_type') == '3' ? 'selected' : '' }}>Patient
                            </option>
                        </select>
                        <label for="floatingSelect">Search by role</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="receiver_name" class="form-control" id="floatingInput"
                            placeholder="Receiver Name"
                            value="{{ old('receiver_name', request()->input('receiver_name')) }}">
                        <label for="floatingInput">Receiver Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="tel" class="form-control" id="floatingInput" placeholder="name@example.com"
                            name="phone_number" value="{{ old('phone_number', request()->input('phone_number')) }}">
                        <label for="floatingInput">Mobile Number</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="created_date" class="form-control" id="floatingInput"
                            placeholder="Created Date" name="created_date"
                            value="{{ old('created_date', request()->input('created_date')) }}">
                        <label for="floatingInput">Created Date</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="sent_date" class="form-control" id="floatingInput"
                            placeholder="Sent Date" name="sent_date"
                            value="{{ old('sent_date', request()->input('sent_date')) }}">
                        <label for="floatingInput">Sent Date</label>
                    </div>
                    <div class="button-section">
                        <button class="primary-fill" type="submit">Search</button>
                        <a href="{{ route('admin.sms.records.view') }}" class="primary-empty" type="button">Clear</a>
                    </div>

                </div>
            </form>

            <div class="table-responsive table-view">
                <table class="table">
                    <thead class="table-secondary">
                        <td>Recipient</td>
                        <td>Action</td>
                        <td>Role Name</td>
                        <td>Mobile Number</td>
                        <td>Create Date <i class="bi bi-arrow-down"></i></td>
                        <td>Sent Date</td>
                        <td>Sent</td>
                        <td>Sent Tries</td>
                        <td>Confirmation Number</td>
                    </thead>
                    <tbody>
                        @foreach ($sms as $data)
                            <tr>
                                <td>{{ $data->recipient_name }}</td>
                                <td>-</td>
                                <td>
                                    @if ($data->role_id == 1)
                                        admin
                                    @elseif ($data->role_id == 2)
                                        physician
                                    @elseif ($data->role_id == 3)
                                        patient
                                    @endif
                                </td>
                                <td>{{ $data->mobile_number }}</td>
                                <td>{{ $data->created_date }} </td>
                                <td>{{ $data->sent_date }} </td>
                                <td>
                                    @if ($data->is_sms_sent == 1)
                                        1
                                    @endif
                                </td>
                                <td>{{ $data->sent_tries }}</td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
             {{-- This ensures that the search criteria are preserved in the pagination links. --}}
            {{ $sms->appends(request()->except('page'))->links('pagination::bootstrap-5') }}

            </div>

            <div class="mobile-listing">
                @foreach ($sms as $data)
                    <div class="mobile-list">
                        <div class="main-section">
                            <h5 class="heading">{{ $data->recipient_name }}</h5>
                            <div class="detail-box">
                                <span>
                                    Action Name: <strong>Test</strong>
                                </span>
                                <br>
                                <span>
                                    Mobile Number: <strong>{{ $data->mobile_number }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="details">
                            <span><i class="bi bi-person"></i>
                                Role Name :
                                @if ($data->role_id == 1)
                                    admin
                                @elseif ($data->role_id == 2)
                                    physician
                                @elseif ($data->role_id == 3)
                                    patient
                                @endif
                            </span>
                            <br>
                            <span><i class="bi bi-calendar3"></i>Create Date : {{ $data->created_date }}</span>
                            <br>
                            <span><i class="bi bi-calendar3"></i>Sent Date : {{ $data->sent_date }}</span>
                            <br>
                            <span><i class="bi bi-check2"></i>
                                Sent : @if ($data->is_sms_sent == 1)
                                    yes
                                @else
                                    no
                                @endif
                            </span>
                            <br>
                            <span><i class="bi bi-envelope"></i>Sent Tries : {{ $data->sent_tries }}</span>
                            <br>
                            <span><i class="bi bi-check2"></i>Confirmation Number : </span>

                        </div>
                    </div>
                @endforeach
                {{ $sms->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
