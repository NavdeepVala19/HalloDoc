@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Close Case
            </h1>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>



        <form action="{{ route('admin.close.case.save') }}" method="POST" id="closeCase">
            @csrf
            <input type="text" class="request_id" value="{{ $data->id }}" name="requestId" hidden>
            <div class="section">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p>Patient Name</p>
                        <span class="patient-name">{{ $data->requestClient->first_name }}
                            {{ $data->requestClient->last_name }}</span>
                        <span class="confirmation-number">({{ $data->confirmation_no }})
                        </span>
                    </div>

                    <div>
                        <button class="primary-empty">Create Invoice Through Quickbooks</button>
                    </div>
                </div>
                <h3>
                    Documents
                </h3>


                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead class="table-secondary">
                            <tr>
                                <th class="w-50"></th>
                                <th class="w-25">Upload Date <i class="bi bi-arrow-up"></i></th>
                                <th class="w-25">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($files as $file)
                                    <td>
                                        <i class="bi bi-filetype-doc doc-symbol"></i> {{ $file->file_name }}
                                    </td>
                                    <td>{{ $file->created_at }}</td>
                                    <td>
                                        <a href="{{ route('download', $file->id) }}" class="primary-empty"><i
                                                class="bi bi-cloud-download"></i></a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Patient</h3>
                <div class="mb-4 grid-2">
                    <input type="text" name="request_type_id" value="1" hidden>
                    <div class="form-floating ">
                        <input type="text" name="first_name" value="{{ $data->requestClient->first_name }}"
                            class="form-control @error('first_name') is-invalid @enderror" id="floatingInput"
                            placeholder="First Name" disabled>
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" value="{{ $data->requestClient->last_name }}"
                            class="form-control @error('last_name') is-invalid @enderror" id="floatingInput"
                            placeholder="Last Name" disabled>
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="date" class="form-control" value="{{ $data->requestClient->date_of_birth }}"
                            id="floatingInput" placeholder="date of birth" disabled>
                        <label for="floatingInput">Date Of Birth</label>
                    </div>

                    <div class="d-flex gap-2 align-items-center">
                        <input type="tel" name="phone_number" value="{{ $data->requestClient->phone_number }}"
                            class="form-control phone @error('last_name') is-invalid @enderror" id="telephone"
                            placeholder="Phone Number" disabled>
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button class="primary-empty"><i class="bi bi-telephone"></i></button>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" value="{{ $data->requestClient->email }}"
                            class="form-control email @error('email') is-invalid @enderror" id="floatingInput"
                            placeholder="name@example.com" disabled>
                        <label for="floatingInput">Email address</label>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="text-end default-buttons">
                    <button type="button" class="primary-fill edit-btn">Edit</button>
                    <input type="submit" value="Close Case" name="closeCaseBtn" class="primary-empty close-edit-btn">
                    {{-- Clicking on this button, admin can close the case and that request will be moved into "Unpaid" --}}
                </div>

                <div class="text-end new-buttons">
                    <input type="submit" value="Save" name="closeCaseBtn" class="primary-fill save-edit-btn">
                    <button type="button" class="primary-empty cancel-edit-btn">Cancel</button>
                </div>
            </div>
        </form>
    </div>
@endsection
