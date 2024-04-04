@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/access.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="{{route('admin.profile.editing')}}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn " type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Access
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.user.access') }}">User Access</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.access.view') }}">Account Access</a></li>
        </ul>
    </div>
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
    <div class="m-5 spacing">
        <h3 class="main-heading">Account Access</h3>
        <div class="section">
            <div class="text-end m-3 mb-4">
                <a href="{{ route('admin.create.role.view') }}" class="primary-empty role-text-btn">Create Access</a>
                <a href="{{ route('admin.create.role.view') }}" class="primary-empty role-add-btn"><i
                        class="bi bi-plus-lg"></i></a>
            </div>
            <div>
                <table class="table table-view">
                    <thead class="table-secondary">
                        <td class="nameField">Name</td>
                        <td class="accountField">Account Type</td>
                        <td class="actions">Actions</td>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->account_type }}</td>
                                <td class="d-flex justify-content-center gap-2 ">
                                    <a href="{{ route('admin.edit.access', $role->id) }}" class="primary-empty">Edit</a>
                                    <a href="{{ route('admin.access.delete', $role->id) }}"
                                        class="primary-empty">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mobile-listing">
                    @foreach ($roles as $role)
                        <div class="mobile-list">
                            <div class="m-2">Name: {{ $role->name }} </div>
                            <div class="m-2 mb-2">Account Type: {{ $role->account_type }}</div>
                            <div class="m-3">
                                <a href="{{ route('admin.edit.access', $role->id) }}" class="primary-empty">Edit</a>
                                <a href="{{ route('admin.access.delete', $role->id) }}" class="primary-empty">Delete</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
