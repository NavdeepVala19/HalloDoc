<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .error {
            color: red;
        }

        .container {
            padding: 48px;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="container border border-dark ">
        <h1 class="text-center">User Update Form</h1>
        <a href="/read" class="text-center">Read Data</a>
        <form action="/update/{{ $user->id }}" method="post" enctype="multipart/form-data">
            @csrf
            <fieldset>
                <legend>Personal information:</legend>

                <label class="form-label" for="firstname">First name:</label>
                <br>
                <input class="form-control" type="text" id="firstname" name="firstname"
                    value="{{ $user->firstname }}">
                @error('firstname')
                    <span class="error">{{ $message }} </span>
                @enderror

                <br>
                <label class="form-label" for="lastname">Last name:</label>
                <br>
                <input class="form-control" type="text" id="lastname" name="lastname" value="{{ $user->lastname }}"
                    required>

                @error('lastname')
                    <span class="error">{{ $message }} </span>
                @enderror
                <br>
                <label class="form-label" for="email">Email:</label>
                <br>
                <input class="form-control" type="email" id="email" name="email" value="{{ $user->email }}"
                    required>
                @error('email')
                    <span class="error">{{ $message }} </span>
                @enderror

                <br>
                <label class="form-label" for="password">Password:</label>
                <br>
                <input class="form-control" type="password" id="password" name="password" required>


                <br>
                <label class="form-label" for="gender">Gender:</label>
                <br>

                <input class="form-check-input" type="radio" name="gender" id="male" value="Male"
                    {{ $user->gender == 'Male' ? 'checked' : '' }} required>
                <label for="male">Male</label>
                <input class="form-check-input" type="radio" name="gender" id="female" value="Female"
                    {{ $user->gender == 'Female' ? 'checked' : '' }}>
                <label for="female">Female</label>

                @error('gender')
                    <span class="error">{{ $message }} </span>
                @enderror

                <br><br>
                <div>

                    <img src="{{ Storage::url($user->image) }}" class="m-4" height="100px"
                        alt="Image already uploaded">

                    <input class="form-control" type="file" name="image" src="{{ $user->image }}">
                </div>
                {{-- <span>
                    Uploaded Image:
                    <img src="{{ asset('storage/' . Illuminate\Support\Str::after($user['image'], 'public/')) }}"
                        alt="Descriptive alt text">
                    {{ Illuminate\Support\Str::after($user['image'], 'public/') }}
                </span> --}}
                <br><br>
                <input type="submit" class="btn btn-dark" value="update" name="update">
            </fieldset>
        </form>
    </div>
</body>

</html>
