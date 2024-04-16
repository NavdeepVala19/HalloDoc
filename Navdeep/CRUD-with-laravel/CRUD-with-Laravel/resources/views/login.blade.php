<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <style>
        .container {
            margin-top: 50px;
        }

        form {
            width: 400px;
            margin: 0 auto;
        }

        .error {
            color: red;
        }

        .submit {}
    </style>
    <title>Login Form</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Login Form</h1>

        <form action="/login" method="POST">
            @csrf
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            <label for="email" class="form-label">Email :</label>
            <input class="form-control" type="email" id="email" name="email">

            @error('email')
                <span class="error">{{ $message }} </span>
            @enderror
            <br>

            <label for="password">Password :</label>
            <input class="form-control" type="password" id="password" name="password">

            @error('password')
                <span class="error">{{ $message }} </span>
            @enderror
            <br>

            <input type="submit" value="Login" class="btn btn-primary submit">
        </form>

    </div>
    
</body>

</html>
