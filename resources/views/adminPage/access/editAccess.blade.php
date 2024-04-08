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
    <div class="m-5 box-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Edit Role Access</h3>
            <a href="{{ route('admin.access.view') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <form action="{{ route('admin.edit.access.data') }}" method="POST">
                @csrf
                <input type="text" name="roleId" value="{{ $role->id }}" hidden>
                <h4>Details</h4>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="role" value="{{ $role->name }}" class="form-control"
                            id="floatingInput" placeholder="Role Name">
                        <label for="floatingInput">Role Name</label>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <select class="form-select role-selected" name="role_name" id="floatingSelect" disabled>
                            <option value="0">All</option>
                            <option value="1" {{ $role->account_type == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $role->account_type == 'physician' ? 'selected' : '' }}>Physician
                            </option>
                            <option value="3">Patient</option>
                        </select>
                        <label for="floatingSelect">Account Type</label>
                        @error('role_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" name="role_name" value="{{ $role->account_type }}" hidden>
                    </div>
                </div>
                <div class="menu-section">
                    @foreach ($menus as $menu)
                        @if (!empty($menus))
                            <div class="form-check">
                                <input class="form-check-input" name="menu_checkbox[]"
                                    @foreach ($roleMenus as $roleMenu)
                                        {{ $roleMenu->menu_id == $menu->id ? 'checked' : '' }} @endforeach
                                    value={{ $menu->id }} type="checkbox" id="menu_check_{{ $menu->id }}">
                                <label class="form-check-label" for="menu_check_{{ $menu->id }}">
                                    {{ $menu->name }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="text-end m-3">
                    <button type="submit" class="primary-fill">Save</button>
                    <a href="{{ route('admin.access.view') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
