@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/searchRecords.css') }}">
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

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3>Search Records</h3>
        <a href="#" class="primary-empty export-data-to-excel"> <i class="bi bi-send-arrow-down"></i> Export Data To Excel </a>
    </div>  
    <div class="section">
        <form action="{{route('admin.search.records')}}" method="post" id="exportSearchForm">
            @csrf
            {{--  The currentPage() method retrieves the current page number of the paginator. --}}
            <input type="hidden" name="page" value="{{ $combinedData->currentPage() }}">

            {{--  The perPage() method retrieves the number of items per page in the paginator. --}}
            <input type="hidden" name="per_page" value="{{ $combinedData->perPage() }}"> 

            <div class="grid-4">
                <div class="form-floating request-status-select">
                    <select class="form-select status-type" name="request_status">
                        <option selected>Select Request Status</option>
                        <option value="1" {{ Session::get('request_status') == '1' ? 'selected' : '' }}>Unassigned</option>
                        <option value="2" {{ Session::get('request_status') == '2' ? 'selected' : '' }}>Cancelled</option>
                        <option value="3" {{ Session::get('request_status') == '3' ? 'selected' : '' }}>Accepted</option>
                        <option value="4" {{ Session::get('request_status') == '4' ? 'selected' : '' }}>MDEnRoute</option>
                        <option value="5" {{ Session::get('request_status') == '5' ? 'selected' : '' }}>MDOnSite</option>
                        <option value="6" {{ Session::get('request_status') == '6' ? 'selected' : '' }}>Conclude</option>
                        <option value="7" {{ Session::get('request_status') == '7' ? 'selected' : '' }}>Closed</option>
                        <option value="8" {{ Session::get('request_status') == '8' ? 'selected' : '' }}>Clear</option>
                        <option value="9" {{ Session::get('request_status') == '9' ? 'selected' : '' }}>UnPaid</option>
                        <option value="10" {{ Session::get('request_status') == '10' ? 'selected' : '' }}>Block</option>
                    </select>
                    </input>
                </div>

                <div class="form-floating ">
                    <input type="text" name="patient_name" class="form-control patient-name" id="floatingInput" placeholder="Patient Name" value="{{old('patient_name' ,request()->input('patient_name'))}}">
                    <label for="floatingInput">Patient Name</label>
                </div>

                <div class="form-floating request-type-select">
                    <select class="form-select request-type" name="request_type">
                        <option selected>Select Request Type</option>
                        <option value="1" {{ Session::get('request_type') == '1' ? 'selected' : '' }}>Patient</option>
                        <option value="2" {{ Session::get('request_type') == '2' ? 'selected' : '' }}>Family/Friend</option>
                        <option value="3" {{ Session::get('request_type') == '3' ? 'selected' : '' }}>Concierge</option>
                        <option value="4" {{ Session::get('request_type') == '4' ? 'selected' : '' }}>Business</option>
                    </select>
                    </input>
                </div>

                <div class="form-floating ">
                    <input type="date" class="form-control from-date-of-service" id="floatingInput" placeholder="From the Date of Service" name="from_date_of_service" value="{{old('from_date_of_service' ,request()->input('from_date_of_service'))}}">
                    <label for="floatingInput">From the Date of Service</label>
                </div>


                <div class="form-floating ">
                    <input type="date" class="form-control to-date-of-service" id="floatingInput" placeholder="To the Date of Service" name="to_date_of_service" value="{{old('to_date_of_service' ,request()->input('to_date_of_service'))}}">
                    <label for="floatingInput">To the Date of Service</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="provider_name" class="form-control provider-name" id="floatingInput" placeholder="Provider Name" value="{{old('provider_name' ,request()->input('provider_name'))}}">
                    <label for="floatingInput">Provider Name</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control email" id="floatingInput" placeholder="name@example.com" name="email" value="{{old('email' ,request()->input('email'))}}">
                    <label for="floatingInput">Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone-number" id="telephone" value="{{old('phone_number' ,request()->input('phone_number'))}}">
            </div>
            <div class=" mt-4 d-flex justify-content-end gap-2">
                <button class="primary-fill" type="submit">
                    Search
                </button>
                <a href="{{route('admin.search.records.view')}}" class="primary-empty" type="button">
                    Clear
                </a>
            </div>

        </form>

        <div class="table-responsive search-record-table">
            <table class="provider-table table mt-3">
                <thead class="table-secondary">
                    <tr>
                        <td>Patient Name</td>
                        <td>Requestor</td>
                        <td>Date-of-service</td>
                        <td>Close Case Date</td>
                        <td>Email</td>
                        <td>Phone Number</td>
                        <td>Address</td>
                        <td>Zip</td>
                        <td>Request Status</td>
                        <td>Physician</td>
                        <td>Physician Note</td>
                        <td>Cancelled By Provider Note</td>
                        <td>Admin Note</td>
                        <td>Patient Note</td>
                        <td>Delete Permanently</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($combinedData as $data )
                    <tr>
                        <td>{{$data->first_name}}</td>
                        <td>
                            @if ($data->request_type_id ==1)
                            Patient
                            @elseif ($data->request_type_id ==2)
                            Family/Friend
                            @elseif ($data->request_type_id ==3)
                            Concierge
                            @elseif ($data->request_type_id ==4)
                            Business
                            @endif
                        </td>
                        <td>{{date_format(date_create($data->created_date), 'd-m-Y')}}</td>
                        <td> @if ($data->closed_date)
                            {{date_format(date_create($data->closed_date), 'd-m-Y')}}
                            @else 
                        @endif </td>
                        <td>{{$data->email}}</td>
                        <td>{{$data->phone_number}}</td>
                        <td>{{$data->street}},{{$data->city}},{{$data->state}}</td>
                        <td>{{$data->zipcode}}</td>
                        <td>
                            @if ($data->status ==1)
                            Unassigned
                            @elseif ($data->status ==2)
                            Cancelled
                            @elseif ($data->status ==3)
                            Accepted
                            @elseif ($data->status ==4 )
                            MDEnRoute
                            @elseif ($data->status ==5 )
                            MDOnSite
                            @elseif ($data->status ==6 )
                            Conclude
                            @elseif ($data->status ==7 )
                            Closed
                            @elseif ($data->status ==8 )
                            Clear
                            @elseif ($data->status ==9 )
                            UnPaid
                            @elseif ($data->status ==10 )
                            Block
                            @endif
                        </td>
                        <td>{{$data->physician_first_name}}</td>
                        <td>{{$data->physician_notes}}</td>
                        <td></td>
                        <td>{{$data->admin_notes}}</td>
                        <td>{{$data->patient_notes}}</td>

                        <td class="text-center align-middle"> <a href="{{route('admin.search.records.delete', $data->id)}}" class="primary-empty" type="button">Delete</a> </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- This ensures that the search criteria are preserved in the pagination links. --}}
            {{ $combinedData->appends(request()->except('page'))->links('pagination::bootstrap-5') }}


        </div>


        <div class="mobile-listing mt-3">
            <div class="mobile-list">
                @foreach ( $combinedData as $data )

                <div class="main-section">
                    <h5 class="heading"> <input class="form-check-input" type="checkbox" value="" id="checkbox"> {{$data->first_name}} </h5>
                    <div class="detail-box">
                        <span>
                            Action Name: <strong>Test</strong>
                        </span>
                        <br>
                        <span>
                            Email: <strong>{{$data->email}}</strong>
                        </span>
                    </div>
                </div>
                <div class="details">
                    <span><i class="bi bi-person"></i>Requestor :
                        @if ($data->request_type_id ==1)
                        Patient
                        @elseif ($data->request_type_id ==2)
                        Family/Friend
                        @elseif ($data->request_type_id ==3)
                        Concierge
                        @elseif ($data->request_type_id ==4)
                        Business
                        @endif </span>
                    <br>
                    <span><i class="bi bi-calendar3"></i>Date of service : {{date_format(date_create($data->created_date), 'd-m-Y')}}</span>
                    <br>
                    <span><i class="bi bi-calendar3"></i>Case Closed Date : Oct 10,2023</span>
                    <br>
                    <span><i class="bi bi-envelope"></i>email : {{$data->email}}</span>
                    <br>
                    <span><i class="bi bi-telephone"></i>phone : {{$data->phone_number}} :</span>
                    <br>
                    <span><i class="bi bi-geo-alt"></i>address : {{$data->street}},{{$data->city}},{{$data->state}}</span>
                    <br>
                    <span><i class="bi bi-geo-alt"></i>zipcode : {{$data->zipcode}}</span>
                    <br>
                    <span><i class="bi bi-check2"></i>Request Status :

                        @if ($data->status ==1)
                        Unassigned
                        @elseif ($data->status ==2)
                        Cancelled
                        @elseif ($data->status ==3)
                        Accepted
                        @elseif ($data->status ==4 )
                        MDEnRoute
                        @elseif ($data->status ==5 )
                        MDOnSite
                        @elseif ($data->status ==6 )
                        Conclude
                        @elseif ($data->status ==7 )
                        Closed
                        @elseif ($data->status ==8 )
                        Clear
                        @elseif ($data->status ==9 )
                        UnPaid
                        @elseif ($data->status ==10 )
                        Block
                        @endif
                    </span>
                    <br>
                    <span><i class="bi bi-person"></i>Provider : {{$data->physician_first_name}}</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Provider Note : {{$data->physician_notes}}</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Cancelled by Provider Note :</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Admin Note : {{$data->admin_notes}}</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Cancellation Reason : </span>
                    <br>
                    <span><i class="bi bi-journal"></i>Patient Note : {{$data->patient_notes}} </span>

                    <div class="d-flex justify-content-end gap-2">
                        <a class="primary-empty" type="button" href="{{route('admin.search.records.delete', $data->id)}}">
                            Delete Permanently
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{ $combinedData->appends(request()->except('page'))->links('pagination::bootstrap-5') }}

        </div>
    </div>
</div>



@endsection

@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js')}}"></script>
<script defer src="{{ asset('assets/adminPage/searchRecords.js') }}"></script>
@endsection