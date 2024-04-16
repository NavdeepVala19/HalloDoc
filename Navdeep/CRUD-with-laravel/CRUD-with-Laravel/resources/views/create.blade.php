<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Registration Form</title>

    <style>
        .error {
            color: red;
        }

        .container {
            padding: 48px;
            margin-top: 50px;
        }

        input {
            border: 1px solid darkgrey !important;
        }
    </style>
</head>

<body>
    <div class="container border border-dark">
        <h2 class="text-center">Registration Form</h2>
        <form action="/register" method="POST" enctype="multipart/form-data">
            @csrf
            <fieldset>
                <legend>Information:</legend>
                <label class="form-label" for="firstname">First name:</label>
                <br>
                {{-- <x-inputField title="firstname"/> --}}
                <input class="form-control" type="text" id="firstname" name="firstname"
                    value="{{ old('firstname') }}">
                @error('firstname')
                    <span class="error">{{ $message }} </span>
                @enderror
                <br>
                <label class="form-label" for="lastname">Last Name:</label>
                <br>
                <input class="form-control" type="text" id="lastname" name="lastname"
                    value="{{ old('lastname') }} ">
                @error('lastname')
                    <span class="error">{{ $message }} </span>
                @enderror
                <br>
                <label class="form-label" for="email">Email :</label>
                <br>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <span class="error">{{ $message }} </span>
                @enderror
                <br>
                <label class="form-label" for="password">Password :</label>
                <br>
                <input class="form-control" type="password" id="password" name="password">
                @error('password')
                    <span class="error">{{ $message }} </span>
                @enderror
                <br>
                <label class="form-label" for="gender">Gender:</label>
                <br>
                <input class="form-check-input" id="male" type="radio" name="gender" value="Male"
                    @if (old('gender') == 'Male') checked @endif>

                <label class="form-check-label" for="male">Male</label>
                <input class="form-check-input" id="female" type="radio" name="gender" value="Female"
                    @if (old('gender') == 'Female') checked @endif>

                <label class="form-check-label" for="female">Female</label>
                @error('gender')
                    <span class="error">{{ $message }}</span>
                @enderror
                <br>
                <br>
                <!-- *************************** -->
                <input class="form-control" type="file" name="image">
                <br>
                <br>
                <!-- *************************** -->

                <input type="submit" name="submit" class="btn btn-dark" value="submit">
            </fieldset>
        </form>
        <a href="/login">Already have an Account? Login &rAarr;</a>

        {{-- Show different buttons or text based on, if the user is signed in or yet to register --}}
        {{-- @auth --}}
        {{-- If the user is has registered and logged in display, welcome message with the name --}}
        {{-- <span>Welcome, {{ auth()->user()->name }}</span> --}}
        {{-- @else --}}
        {{-- If the user has yet to register, display the link --}}
        {{-- <a href="/register">Register</a> --}}
        {{-- @endauth --}}
        {{-- let the user logout - this logic will be in cotroller and will use session  --}}
        {{-- auth()->logout(); --}}
        {{-- return redirect('/')->with("success", "GoodBye!!!"); --}}
    </div>
</body>

</html>
