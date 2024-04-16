<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<style>
    main {
        max-width: 90%;
        margin: auto;
    }

    .image {
        max-width: 90px;
    }

    .anchor {
        text-decoration: none;
        color: white;
    }
</style>


<body>
    <header>
        <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="">Navbar</a>
                <div class="buttons">
                    <a class="btn btn-primary" href="/logout" role="button">LogOut</a>

                </div>
            </div>
        </nav>
    </header>
    <div>
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <strong>{{ $message }}</strong>
        </div>
        @endif

        <main class="mt-5">

            <a class="btn btn-primary" href="/create" role="button">Create new user</a>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th class="image">Image</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        @foreach($users as $user)
                        <td>{{$user->id}}</td>
                        <td>{{$user->firstname}}</td>
                        <td>{{$user->lastname}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->gender}}</td>
                        <td class="image"> <img src="{{Storage::url($user->image)}}" alt="images" width="150"
                                height="100"></td>
                        <td class="buttons">
                            <a class="btn btn-success" href="/update/{{$user->id}}">Edit</a>&nbsp;
                            <a class="btn btn-danger" href="/delete/{{$user->id}}">Delete</a>
                        </td>
                    </tr>
                    @endforeach

        </main>



        <script>

            const alert = document.querySelector(".alert")

            setTimeout(() =>{
                alert.style.display = "none"
            },2000)
           

        </script>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
</body>

</html>