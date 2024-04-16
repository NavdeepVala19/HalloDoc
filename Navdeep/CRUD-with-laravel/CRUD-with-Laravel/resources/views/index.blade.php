<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"
        integrity="sha512-WW8/jxkELe2CAiE4LvQfwm1rajOS8PHasCCx+knHG0gBHt8EXxS6T6tJRTGuDQVnluuAvMxWF4j8SNFDKceLFg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .box {
            margin-top: 150px;
        }
    </style>
    <title>Laravel CRUD & Login</title>
</head>

<body>
    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <!-- Navbar content -->
        <div class="container-fluid">
            <a class="navbar-brand">Laravel - CRUD | Login | Authentication</a>
            <div class="buttons">
                <a href="/login" class="btn btn-outline-light" role="button">Login</a>
                <a href="/register" class="btn btn-primary" role="button">Register</a>
            </div>
        </div>
    </nav>
    <main>
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="box">
            <h1 class="text-center">Home Page</h1>
            <h3 class="text-center">
                Login to View/Read Data.
            </h3>
        </div>
    </main>

    <script>
        let alert = document.querySelector('.alert');
        setTimeout(() => {
            alert.style.display = 'none';
        }, 2000);
    </script>
</body>

</html>
