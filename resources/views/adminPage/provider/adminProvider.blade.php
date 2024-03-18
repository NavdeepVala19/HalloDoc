@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminProvider.css') }}">

@endsection

@section('nav-links')
<a href="{{route('admin.dashboard')}}">Dashboard</a>
<a href="{{route('providerLocation')}}">Provider Location</a>
<a href="">My Profile</a>
<div class="dropdown record-navigation">
    <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Providers
    </button>
    <ul class="dropdown-menu records-menu">
        <li><a class="dropdown-item active-link" href="">Provider</a></li>
        <li><a class="dropdown-item" href="">Scheduling</a></li>
        <li><a class="dropdown-item" href="">Invoicing</a></li>
    </ul>
</div>
<a href="{{ route('admin.partners') }}">Partners</a>
<a href="{{ route('admin.access.view') }}">Access</a>
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

<div class="overlay"> </div>

@if(session('message'))
<h6 class="alert alert-success">
    {{ session('message') }}
</h6>
@endif

<div class="container">

    <h2>Provider Information</h2>

    <div class="main-info-content">

        <div class="content-header d-flex flex-row justify-content-between align-items-center">

            <select class="form-select">
                <option selected>All </option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>

            <div class="provider-btn">
                <a href="{{route('adminNewProvider')}}" type="button" class="btn primary-fill create-provider-btn mt-1 me-2 mb-2">Create Provider Account</a>
            </div>

        </div>

        <div class="listing-table mt-3">

            <table class="provider-table table">
                <thead class="table-secondary">
                    <tr>
                        <td style="width: 7%;" class="theader">Stop Notification</td>
                        <td class="theader">Provider Name</td>
                        <td class="theader">Role</td>
                        <td class="theader">On Call Status</td>
                        <td class="theader">Status</td>
                        <td style="width: 13%;" class="theader">Actions</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($providersData as $data)
                    <tr>
                        <td class="checks"> <input class="form-check-input" type="checkbox" value="" id="checkbox">
                        </td>
                        <td class="data"> {{$data->first_name}}</td>
                        <td class="data"> Physician</td>
                        <td class="data"> Available</td>
                        <td class="data"> Active </td>
                        <td class="data gap-1">
                            <button type="button" data-id='{{$data->id}}' class="primary-empty contact-btn mt-2 mb-2">Contact</button>
                            <a href="{{route('adminEditProvider', $data->id) }}" type="button" class="primary-empty btn edit-btn mt-2 mb-2">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            {{$providersData->links('pagination::bootstrap-5')}}


            <!-- contact your provider pop-up -->

            <div class="pop-up new-provider-pop-up">
                <div class="popup-heading-section d-flex align-items-center justify-content-between">
                    <span class="ms-3">Contact Your Provider</span>
                    <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
                </div>
                <p class="mt-4 ms-3">Choose communication to send message</p>
                <div class="ms-3 ">

                    <form action="#" method="post" id="ContactProviderForm">
                        @csrf

                        <input type="text" name="provider_id" class="provider_id" hidden>

                        <div class="radio-sms">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label ms-1" for="flexRadioSMS">
                                SMS
                            </label>
                        </div>

                        <div class="radio-email">
                            <input class="form-check-input" type="radio" value="email" name="emailContact" id="flexRadioDefault2" checked>
                            <label class="form-check-label ms-1" for="flexRadioEmail">
                                Email
                            </label>
                        </div>

                        <div class="radio-both">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                            <label class="form-check-label ms-1" for="flexRadioBoth">
                                Both
                            </label>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="contact_msg" style="height: 120px"></textarea>
                            <label for="floatingTextarea2">Message</label>
                        </div>

                </div>

                <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                    <button class="primary-fill sen-btn" type="submit">Send</button>
                    <button class="primary-empty hide-popup-btn">Cancel</button>
                </div>
                </form>
            </div>

        </div>


    </div>

</div>


@endsection