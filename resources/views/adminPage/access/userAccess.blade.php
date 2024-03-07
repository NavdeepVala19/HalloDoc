@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
<!-- <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/access.css') }}"> -->
<link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
@endsection

@section('nav-links')
<a href="{{ route('admin.dashboard') }}">Dashboard</a>
<a href="">Provider Location</a>
<a href="">My Profile</a>
<a href="">Providers</a>
<a href="{{ route('admin.partners') }}">Partners</a>
<a href="" class="active-link">Access</a>
<a href="">Records</a>
@endsection

@section('content')
<div class="m-5 spacing">
    <h3 class="main-heading">User Access</h3>
    <div class="section">
        <div>
            <div class="form-floating w-25 m-3">
                <select class="form-select role-selected" name="role_name" id="floatingSelect">
                    <option value="0">All</option>
                    <option value="1">Admin</option>
                    <option value="2">Physician</option>
                    <option value="3">Patient</option>
                </select>
                <label for="floatingSelect">Account Type</label>
            </div>
        </div>

        <div class="table-responsive table-view">
            <table class="table">
                <thead class="table-secondary text-center align-middle">
                    <td>Account Type <i class="bi bi-arrow-up"></i></td>
                    <td>Account POC</td>
                    <td>Phone</td>
                    <td>Status</td>
                    <td>Open Requests</td>
                    <td>Actions</td>
                </thead>
                <tbody class="text-center align-middle">
                    @foreach ($userAccessData as $data )

                    <tr>
                        <td>{{$data->name}}</td>
                        <td>{{$data->first_name}}</td>
                        <td>{{$data->mobile}}</td>
                        <td>Pending</td>
                        <td>123</td>
                        <td><a href="" class="primary-empty" type="button">Edit</a></td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
            {{$userAccessData->links('pagination::bootstrap-5')}}
        </div>

        <div class="mobile-listing">
            <div class="mobile-list">
                <div class="main-section">
                    <h5 class="heading">John Smith</h5>
                    <div class="detail-box">
                        <span>
                            Account Type: Admin
                        </span>
                    </div>
                </div>
                <div class="details">
                    <span><i class="bi bi-telephone"></i> Phone: 1234567890</span>
                    <br>
                    <span><i class="bi bi-check-lg"></i></i>Status: Pending</span>
                    <br>
                    <span><i class="bi bi-journal"></i>Open Requests: 123</span>
                    <br>
                    <div class="d-flex justify-content-end">
                        <a href="" class="primary-empty" type="button">Edit</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection