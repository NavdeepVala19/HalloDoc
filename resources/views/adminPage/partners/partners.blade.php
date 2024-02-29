@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/partners.css') }}">
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
    <div class="m-5 spacing">
        <h3>Vendor(s)</h3>
        <div class="section">
            <div class="mb-3 option-section">
                <form action="{{ route('search.partners') }}" method="POST" class="vendorSearchForm">
                    @csrf
                    <div class="gap-3 filter-section">
                        <input type="text" style="font-family:'Bootstrap-icons';" class="form-control search-vendor"
                            placeholder='&#xF52A;  Search Vendors' aria-describedby="basic-addon1" name="search">
                        <select name="profession" class="form-select select-profession">
                            <option value="0">All Profession</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}" {{ $id == $profession->id ? 'selected' : '' }}>
                                    {{ $profession->profession_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="btn-box">
                    <a href="{{ route('add.business.view') }}" class="primary-empty add-btn">
                        <i class="bi bi-plus-lg"></i>
                        <span class="text-none">
                            Add Business
                        </span>
                    </a>
                </div>
            </div>
            <div class="table-responsive table-view">
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
                        @foreach ($vendors as $vendor)
                            {{-- {{ dd($vendor->healthProfessionalType->profession_name) }} --}}
                            @if (!empty($vendor->healthProfessionalType))
                                <tr>
                                    <td>{{ $vendor->healthProfessionalType->profession_name }}</td>
                                    <td>{{ $vendor->vendor_name }}</td>
                                    <td>{{ $vendor->email }}</td>
                                    <td>{{ $vendor->fax_number }}</td>
                                    <td>{{ $vendor->phone_number }}</td>
                                    <td>{{ $vendor->business_contact }}</td>
                                    <td class="d-flex gap-2 ">
                                        <a href="{{ route('update.business.view', $vendor->id) }}"
                                            class="primary-empty">Edit</a>
                                        <a href="{{ route('delete.business', $vendor->id) }}"
                                            class="primary-empty">Delete</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mobile-listing">
                @foreach ($vendors as $vendor)
                    <div class="mobile-list">
                        <h4 class="heading">{{ $vendor->vendor_name }}</h4>
                        <div class="details">
                            <span>
                                <i class="bi bi-person-check"></i> Profession:
                                {{ $vendor->healthProfessionalType->profession_name }}
                            </span>
                            <br>
                            <span>
                                <i class="bi bi-envelope"></i> Email : {{ $vendor->email }}
                            </span>
                            <br>
                            <span>
                                <i class="bi bi-telephone"></i> Fax:{{ $vendor->fax_number }}
                            </span>
                            <br>
                            <span>
                                <i class="bi bi-telephone"></i> Phone Number:{{ $vendor->phone_number }}
                            </span>
                            <br>
                            <span>
                                <i class="bi bi-envelope"></i> Business Contact : {{ $vendor->business_contact }}
                            </span>
                        </div>
                        <div class="text-end mobile-btn">
                            <a href="{{ route('update.business.view', $vendor->id) }}" class="primary-empty">Edit</a>
                            <a href="{{ route('delete.business', $vendor->id) }}" class="primary-empty">Delete</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
