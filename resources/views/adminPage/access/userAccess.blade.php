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
            <div>
                <table class="table">
                    <thead class="table-secondary">
                        <td>Account Type <i class="bi bi-arrow-up"></i></td>
                        <td>Account POC</td=>
                        <td>Phone</td>
                        <td>Status</td>
                        <td>Open Requests</td>
                        <td>Actions</td>
                    </thead>
                    <tbody>
                        {{-- @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->account_type }}</td>
                                <td class="d-flex justify-content-center gap-2 ">
                                    <a href="{{ route('admin.edit.access', $role->id) }}" class="primary-empty">Edit</a>
                                    <a href="{{ route('admin.access.delete', $role->id) }}" class="primary-empty">Delete</a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
