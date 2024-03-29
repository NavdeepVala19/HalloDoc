@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/partners.css') }}">
@endsection

@section('nav-links')
    <a href="">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="{{ route('admin.partners') }}" class="active-link">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="heading">Add Business</h2>
            <a href="{{ route('admin.partners') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <h4>Submit Information</h4>
            {{-- Health Professional Table --}}
            {{-- Health Professional Type Table -> for profession names --}}

            <form action="{{ route('add.business') }}" method="POST">
                @csrf
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="business_name"
                            class="form-control @error('business_name') is-invalid @enderror" id="floatingInput"
                            placeholder="Business Name">
                        <label for="floatingInput">Business Name</label>
                        @error('business_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <select id="floatingSelect" name="profession"
                            class="form-select @error('profession') is-invalid @enderror">
                            <option selected>Select Profession</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->profession_name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Profession</label>
                        @error('profession')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="number" name="fax_number"
                            class="form-control @error('fax_number') is-invalid @enderror" id="floatingInput"
                            placeholder="Fax Number">
                        <label for="floatingInput">Fax Number</label>
                        @error('fax_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <div>
                            <input type="tel" name="mobile"
                                class="form-control phone @error('mobile') is-invalid @enderror" id="telephone"
                                placeholder="mobile">
                        </div>
                        @error('mobile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="business_contact"
                            class="form-control @error('business_contact') is-invalid @enderror" id="floatingInput"
                            placeholder="Business Contact">
                        <label for="floatingInput">Business Contact</label>
                        @error('business_contact')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="street" class="form-control @error('street') is-invalid @enderror"
                            id="floatingInput" placeholder="Street">
                        <label for="floatingInput">Street</label>
                        @error('street')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            id="floatingInput" placeholder="City">
                        <label for="floatingInput">City</label>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                            id="floatingInput" placeholder="State">
                        <label for="floatingInput">State</label>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="zip" class="form-control @error('zip') is-invalid @enderror"
                            id="floatingInput" placeholder="Zip/postal">
                        <label for="floatingInput">Zip/postal</label>
                        @error('zip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <input type="submit" value="Save" class="primary-fill">
                    <a href="{{ route('admin.partners') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
