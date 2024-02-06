<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Include css links from links.css blade file --}}
    @include('links.css')
    <title>Document</title>
</head>

<body>
    {{-- HEADER SECTION --}}
    @include('layouts.header')


    @yield('content')

    {{-- FOOTER SECTION --}}
    @include('layouts.footer')

    {{-- Include script links from links.script blade file --}}
    @include('links.script')
</body>

</html>
