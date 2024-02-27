@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/partners/partners.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="{{ route('admin.partners') }}" class="active-link">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a>
@endsection

@section('content')
    <div class="m-5">
        <h3>Vendor(s)</h3>
        <div class="section">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-vendor"
                        placeholder='&#xF52A;  Search Vendors' aria-describedby="basic-addon1" name="search">
                    <select class="form-select select-profession">
                        <option selected>All Profession</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <a href="{{ route('add.business.view') }}" class="primary-empty"><i class="bi bi-plus-lg"></i> Add Business</a>
            </div>
            <div>
                <table class="table ">
                    <thead class="table-secondary">
                        <td>Profession</td>
                        <td>Business Name</td>
                        <td>Email</td>
                        <td>Fax Number</td>
                        <td>Phone Number</td>
                        <td>Business Contact</td>
                        <td>Actions</td>
                    </thead>
                    <tbody>
                        {{-- @foreach ( as ) --}}
                        <tr>
                            <td>Test</td>
                            <td>Spaces</td>
                            <td>new@mail.com</td>
                            <td>123212342</td>
                            <td>+21 123212342</td>
                            <td>Tester</td>
                            <td>
                                <a href="{{ route('update.business.view') }}" class="primary-empty">Edit</a>
                                <button class="primary-empty">Delete</button>
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
