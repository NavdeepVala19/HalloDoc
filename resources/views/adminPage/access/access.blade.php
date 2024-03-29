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
                                    <a href="{{ route('admin.access.delete', $role->id) }}" class="primary-empty">Delete</a>
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
