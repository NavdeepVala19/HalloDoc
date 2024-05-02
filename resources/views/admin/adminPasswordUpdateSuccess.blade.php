@extends('adminIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/adminResetPassword.css') }}">
@endsection

@section('adminContent')

<h1 class="d-flex justify-content-center align-items-center">
    Your Password is Updated
</h1>

@endsection

