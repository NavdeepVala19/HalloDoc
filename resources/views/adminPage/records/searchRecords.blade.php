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
<a href="{{ route('admin.access.view') }}">Access</a>
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
        <h3>Search Records</h3>
        <a href="" class="primary-empty"> <i class="bi bi-send-arrow-down"></i> Export Data To Excel </a>
    </div>
    <div class="section ">
        <div class="grid-4">

            <div class="form-floating request-status-select">
                <select class="form-select">
                    <option selected>Select Request Status</option>
                    <option value="1">Pending</option>
                    <option value="2">Active</option>
                    <option value="3">Not Active</option>
                </select>
                </input>
            </div>

            <div class="form-floating ">
                <input type="text" name="patient_name" class="form-control" id="floatingInput" placeholder="Patient Name">
                <label for="floatingInput">Patient Name</label>
            </div>

            <div class="form-floating request-type-select">
                <select class="form-select">
                    <option selected>Select Request Type</option>
                    <option value="1">Pending</option>
                    <option value="2">Active</option>
                    <option value="3">Not Active</option>
                </select>
                </input>
            </div>

            <div class="form-floating ">
                <input type="date" class="form-control" id="floatingInput" placeholder="From the Date of Service">
                <label for="floatingInput">From the Date of Service</label>
            </div>


            <div class="form-floating ">
                <input type="date" class="form-control" id="floatingInput" placeholder="To the Date of Service">
                <label for="floatingInput">To the Date of Service</label>
            </div>

            <div class="form-floating ">
                <input type="text" name="provider_name" class="form-control" id="floatingInput" placeholder="Provider Name">
                <label for="floatingInput">Provider Name</label>
            </div>

            <div class="form-floating ">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                <label for="floatingInput">Email</label>
            </div>

            <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number">
        </div>


        <div class="mt-4 d-flex justify-content-end gap-2">
            <button class="primary-empty">
                Clear
            </button>
            <button class="primary-fill">
                Search
            </button>
        </div>

        <div class="table-responsive search-record-table">
            <table class="provider-table table mt-3">
                <thead class="table-secondary">
                    <tr>
                        <td>Patient Name</td>
                        <td>Requestor</td>
                        <td>Date-of-service</td>
                        <td>Close-Case</td>
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
                    <tr>
                        <td>Patient Name</td>
                        <td>Requestor</td>
                        <td>Aug 23,2023</td>
                        <td>Oct 10,2023</td>
                        <td>test@gmail.com</td>
                        <td>123456789</td>
                        <td>123,baltimore,maryland</td>
                        <td>20810</td>
                        <td>closed</td>
                        <td>Physician</td>
                        <td>concluded</td>
                        <td>Cancelled By Provider Note</td>
                        <td>Admin Note</td>
                        <td>Patient Note</td>
                        <td class="text-center align-middle"><a href="" class="primary-empty" type="button">Delete</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mobile-listing mt-3">

            <div class="mobile-list">
                <div class="main-section">
                     <h5 class="heading"> <input class="form-check-input" type="checkbox" value="" id="checkbox"> Name</h5>
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
                    <span><i class="bi bi-person"></i>Requestor : Family/Friend </span>
                    <br>
                    <span><i class="bi bi-calendar3"></i>Date of service : Aug 23,2023</span>
                    <br>
                    <span><i class="bi bi-calendar3"></i>Case Closed Date : Oct 10,2023</span>
                    <br>
                    <span><i class="bi bi-envelope"></i>email : test@gmail.com</span>
                    <br>
                    <span><i class="bi bi-telephone"></i>phone : 123456789 :</span>
                    <br>
                    <span><i class="bi bi-geo-alt"></i>address : 123,baltimore,maryland</span>
                    <br>
                    <span><i class="bi bi-geo-alt"></i>zipcode : 20810</span>
                    <br>
                    <span><i class="bi bi-check2"></i>Request Status :closed </span>
                    <br>
                    <span><i class="bi bi-person"></i>Provider :</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Provider Note : Physician</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Cancelled by Provider Note :</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Admin Note :</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Cancellation Reason : </span>
                    <br>
                    <span><i class="bi bi-journal"></i>Patient Note :</span>

                    <div class="d-flex justify-content-end gap-2">
                        <button class="primary-empty">
                            Delete Permanently
                        </button>

                        <button class="primary-empty">
                            View Case
                        </button>
                    </div>
                </div>
            </div>

     

        </div>


    </div>

    @endsection