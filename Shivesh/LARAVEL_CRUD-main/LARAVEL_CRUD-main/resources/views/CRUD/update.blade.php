<!doctype html>
<html lang="en">

<head>
    <title>LARAVEL CRUD</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" ` />

    <style>
        body {
            max-width: 600px;
            margin: auto;
        }

        .upload {
            border: 2px solid black;
        }
    </style>

</head>

<body>
    <header class="text-center">
        <h1>User Update Form</h1>
    </header>
    <main>
        <form action="/update-data/{{$users->id}}" method="post" enctype="multipart/form-data">
            @csrf


            <div class="mb-3">
                <label for="" class="form-label">FirstName</label>
                <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" id=""
                    aria-describedby="helpId" placeholder="" value="{{$users->firstname}}" />
                @error('firstname')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label for="" class="form-label">LastName</label>
                <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" id=""
                    aria-describedby="helpId" placeholder="" value="{{$users->lastname}}" />
            </div>
            @error('lastname')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror


            <div class="mb-3">
                <label for="" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id=""
                    aria-describedby="helpId" placeholder="" value="{{$users->email}}" />
            </div>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                    id="" aria-describedby="helpId" placeholder="" />
            </div>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-3">
                <label for="" class="form-label">Gender</label>
                <br>
                <input class="form-check-input" type="radio" name="gender"
                    class=" @error('gender') is-invalid @enderror" id="male" value=" 
                {{$users->gender}}" {{ $users->gender === "male" ? "checked" : ''}}> Male
                <br>
                <input class="form-check-input" type="radio" name="gender"
                    class=" @error('gender') is-invalid @enderror" id="female" value=" 
                 {{$users->gender}}" {{ $users->gender === "female" ? "checked" : ''}}> Female
            </div>
            @error('gender')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror


            <div class="mb-3 mt-4 d-flex">

                <div class="image me-5"><img src="{{Storage::url($users->image)}}" alt="images" width="150"
                        height="100">
                    <div class="mb-3">
                        <label for="" class="form-label @error('image') is-invalid @enderror">Choose image</label>
                        <input type="file" class="form-control" name="image" id="" value="{{$users->image}}"
                            placeholder="" aria-describedby="fileHelpId" />
                    </div>
                </div>
            </div>

            @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror


            <button type="submit" class="btn btn-primary mt-4">Submit</button>

        </form>

    </main>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>