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
            max-width: 1000px;
            margin: auto;
            margin-top: 50px;
        }
    </style>

</head>

<body>
    <header class="text-center">
        <h1>Registration Form</h1>
    </header>
    <main>
        <form action="/create-data" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="" class="form-label">FirstName</label>
                <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" id=""
                    aria-describedby="helpId" placeholder="" />
                @error('firstname')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label for="" class="form-label">LastName</label>
                <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" id="" aria-describedby="helpId"
                    placeholder="" />
            </div>
            @error('lastname')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror


            <div class="mb-3">
                <label for="" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="" aria-describedby="helpId" placeholder="" />
            </div>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="" aria-describedby="helpId"
                    placeholder="" />
            </div>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            <div class="mb-3">
                <label for="" class="form-label">Gender</label>
                <br>
                <input type="radio" name="gender" value="male" class=" @error('gender') is-invalid @enderror"> Male
                <br>
                <input type="radio" name="gender" value="female" class=" @error('gender') is-invalid @enderror"> Female
            </div>

            @error('gender')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror


            <div class="mb-3">
                <label for="" class="form-label @error('image') is-invalid @enderror">Choose image</label>
                <input type="file" class="form-control" name="image" id="" placeholder=""
                    aria-describedby="fileHelpId" />
            </div>

            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror


            <button type="submit" class="btn btn-primary">Submit</button>

            <br>
            <br>
            <p >Already a user  <a href="/login">Click Here</a></p>


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