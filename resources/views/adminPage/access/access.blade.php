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
        <h3>Account Access</h3>
        <div class="section">
            <div class="text-end m-3 mb-4">
                <a href="{{ route('admin.create.role.view') }}" class="primary-empty">Create Access</a>
            </div>
            <div>
                <table class="table">
                    <thead class="table-secondary">
                        <td class="nameField">Name</td>
                        <td class="accountField">Account Type</td>
                        <td class="actions">Actions</td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Test Name</td>
                            <td>Admin, Clinical</td>
                            <td class="d-flex justify-content-center gap-2 ">
                                <button class="primary-empty">Edit</button>
                                <button class="primary-empty">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
