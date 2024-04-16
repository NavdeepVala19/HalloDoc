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
        img {
            width: 200px;
        }
    </style>
    <title>Home|Logged In|Read Data</title>
</head>

<body>
    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <!-- Navbar content -->
        <div class="container-fluid">
            <a href="/" class="navbar-brand">Navbar</a>
            <div class="buttons">
                <a href="{{ route('logout') }}" class="btn btn-outline-light" role="button">Logout</a>
            </div>
        </div>
    </nav>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container border">
        <h1 class="text-center">Users</h1>
        <a href="/">Create a User</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                {{-- {{ dd($users) }} --}}
                @foreach ($users as $user)
                    <tr>
                        <td> {{ $user->id }} </td>
                        <td>{{ $user->firstname }}</td>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->gender }}</td>
                        <td>

                            <img src="{{ Storage::url($user->image) }}" alt="images">
                        </td>
                        <td>
                            <a class="btn btn-info" href="/edit/{{ $user->id }}">Edit</a>

                            <a class="btn btn-danger" href="/delete/{{ $user->id }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        let alert = document.querySelector('.alert');
        setTimeout(() => {
            alert.style.display = 'none';
        }, 2000);
    </script>
</body>

</html>
