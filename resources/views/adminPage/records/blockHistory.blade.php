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
<a href="{{route('admin.profile.editing')}}">My Profile</a>
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
    <h3>Block History</h3>
    <div class="section">

        @if (Session::has('message'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        <form action="{{route('admin.block.history.search')}}" method="POST">
            @csrf
            <div class="grid-4">
                <div class="form-floating ">
                    <input type="text" name="patient_name" class="form-control empty-fields" id="floatingInput" placeholder="Name" value="{{old('patient_name' ,request()->input('patient_name'))}}">
                    <label for="floatingInput">Name</label>
                </div>
                <div class="form-floating">
                    <input type="date" name="date" class="form-control empty-fields" id="floatingInput" placeholder="date" value="{{old('date' ,request()->input('date'))}}">
                    <label for="floatingInput">Date</label>
                </div>

                <div class="form-floating ">
                    <input type="email" name="email" class="form-control empty-fields" id="floatingInput" placeholder="name@example.com" value="{{old('email' ,request()->input('email'))}}">
                    <label for="floatingInput">Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone empty-fields" id="telephone" value="{{old('phone_number' ,request()->input('phone_number'))}}">
            </div>
            <div class="text-end mb-3">
                <button type="submit" class="primary-fill">Search</button>
                <a href="{{route('admin.block.history.view')}}" type="button" class="primary-empty clearButton">Clear</a>
            </div>
        </form>

        <div class="table-responsive" id="blockHistoryTable">
            <table class="table" id="blockListTable">
                <thead class="table-secondary">
                    <td>Patient Name</td>
                    <td>Phone</td>
                    <td>Email</td>
                    <td>Created Date</td>
                    <td>Notes</td>
                    <td>is Active</td>
                    <td>Action</td>
                </thead>
                <tbody>
                    @if ($blockData->isEmpty())
                    <tr>
                        <td colspan="100" class="no-record">No Data Found</td>
                    </tr>
                    @endif
                    @foreach ($blockData as $data)
                    <tr>
                        <td>{{$data->patient_name}}</td>
                        <td>{{$data->phone_number}}</td>
                        <td>{{$data->email}}</td>
                        <td>{{$data->created_date}}</td>
                        <td>{{$data->reason}}</td>
                        <td><input class="form-check-input me-3" type="checkbox" value="1" @checked($data->is_active === 1) id="checkbox_{{$data->id}}"></td>
                        <td>
                            <a href="{{route('admin.block.history.unblock',$data->request_id)}}" class="primary-empty"> Unblock </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$blockData->links('pagination::bootstrap-5')}}
        </div>


        <div class="mobile-listing">
            @if ($blockData->isEmpty())
            <div class="d-flex justify-content-center align-items-center">
                <div class="no-record">No Data Found</div>
            </div>

            @endif
            @foreach ($blockData as $data)
            <div class="mobile-list">
                <div class="main-section">
                    <div class="detail-box">
                        <h5>
                            {{$data->patient_name}}
                        </h5>
                        <br>
                        <span>
                            {{$data->email}}
                        </span>
                    </div>
                </div>
                <div class="details">
                    <span><i class="bi bi-telephone"></i> Phone Number : {{$data->phone_number}}</span>
                    <br>
                    <span><i class="bi bi-calendar3"></i>Create Date : {{$data->created_date}}</span>
                    <br>
                    <span><i class="bi bi-journal"></i></i>Notes : {{$data->reason}}</span>
                    <br>
                    <span><i class="bi bi-check2"></i>is Active :
                        @if ($data->is_active === 1)
                        yes
                        @else
                        no
                        @endif
                    </span>
                    <br>
                    <div class="d-flex justify-content-end">
                        <a href="{{route('admin.block.history.unblock',$data->request_id)}}" class="primary-empty"> Unblock </a>
                    </div>
                </div>
            </div>
            @endforeach
            {{$blockData->links('pagination::bootstrap-5')}}
        </div>
    </div>
</div>
@endsection



@section('script')
<script defer src="{{ URL::asset('assets/adminPage/blockHistory.js') }}"></script>
@endsection