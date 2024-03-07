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
            <h3>Edit Role Access</h3>
            <a href="{{ route('admin.access.view') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <form action="{{ route('admin.create.access') }}" method="POST">
                @csrf
                <h4>Details</h4>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="role" value="{{ $role->name }}" class="form-control"
                            id="floatingInput" placeholder="Role Name">
                        <label for="floatingInput">Role Name</label>
                        @error('role')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <select class="form-select role-selected" name="role_name" id="floatingSelect">
                            <option value="0">All</option>
                            <option value="1" {{ $role->account_type == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $role->account_type == 'physician' ? 'selected' : '' }}>Physician
                            </option>
                            <option value="3">Patient</option>
                        </select>
                        <label for="floatingSelect">Account Type</label>
                        @error('role_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="menu-section">
                    @foreach ($menus as $menu)
                        @if (!empty($menus))
                            <div class="form-check">
                                <input class="form-check-input" name="menu_checkbox[]"
                                    @foreach ($roleMenus as $roleMenu)
                                        {{ $roleMenu->menu_id == $menu->id ? 'checked' : '' }} 
                                    @endforeach
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
