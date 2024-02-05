@extends('index')

@section('content')
    {{-- Main Content of the Page --}}
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <h1>Edit Physician Account</h1>
            <button class="primary-btn"> &lt; Back </button>
        </div>
        <div>
            <h3>Account Information</h3>
            <form action="">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" placeholder="User Name">
                    <label for="floatingInput">User Name</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    <label for="floatingSelect">Status</label>
                </div>
            </form>
        </div>
    </div>
@endsection
