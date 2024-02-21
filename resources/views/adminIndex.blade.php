<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminStyle.css') }}"">
    @include('links.css')
    <title>Document</title>
</head>

<body>


<div class=" container-fluid admin-container">

    <div class="row rows">
        <div class="col-lg-6 logo-doctor">
            <img class="doctor-logo" src="{{ URL::asset('/assets/11116016_415.jpg') }}" alt="">
        </div>

        <div class="col-lg-6 second-col">
            <div class="moon-icon">
                <a href="" class="primary-empty toggle-mode"> <i class="bi bi-moon"></i> </a>
            </div>

            <div class="app-logo">
                <a href=""><img class="hallodoc-logo" src="{{ URL::asset('/assets/logo.png') }}" alt=""></a>
            </div>

            @yield('adminContent')

            <div class="admin-footer">
                <div class="foot">
                    <span>Terms of Conditions</span> | <span>Privacy Policy</span>
                </div>
            </div>

        </div>


    </div>

    </div>

    {{-- Include script links from links.script blade file --}}
    @include('links.script')

    </body>

</html>