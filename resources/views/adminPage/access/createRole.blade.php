@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/access.css') }}">
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
    <div class="m-5 box-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Create Role</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <h4>Details</h4>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="role" class="form-control" id="floatingInput" placeholder="Role Name">
                    <label for="floatingInput">Role Name</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="floatingSelect">
                        <option selected>All</option>
                        <option value="">Admin</option>
                        <option value="">Physician</option>
                        <option value="">Patient</option>
                    </select>
                    <label for="floatingSelect">Account Type</label>
                </div>
            </div>
        </div>
    </div>
@endsection
