<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /*====================== 404 page =======================*/

        body {
            color: #83dec7;
            text-align: center;
            overflow: hidden;
        }

        .container {
            width: 50%;
            height: 50%;
            display: flex;
            flex-direction: column;
            align-content: center;
            justify-content: center;
            background-color: #070F2B;
        }

        section {
            width: 100vw;
            height: 100vh;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .error {
            font-weight: bolder;
            font-style: italic;
            font-size: 78px;
            margin: 0 !important;
        }

        .gif {
            width: 200px;
            display: inline-block;
            align-self: center;

        }

        .redirect-link {
            text-decoration: none;
            background-color: #1B1A55;
            padding: 24px;
            color: #83dec7;
        }

        .redirect-link:hover {
            color: #1B1A55;
            background-color: #83dec7;
        }

        h3 {
            font-size: 32px;
        }
    </style>
</head>

<body>
    <section>
        <img src="{{ asset('assets/logo.png') }}" alt="logo">
        <div class="container">
            <p class="error">Whoops! Something Went Wrong</p>
            <div class="detail">
                <h3>There might be an Server Error!</h3>
                <h3>We're working on fixing the issue. Please try again later.</h3>

                <a href="javascript:history.back()" class="redirect-link">Go Back</a>
            </div>
        </div>
    </section>
</body>

</html>
