@extends('patientSiteIndex')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientProfile.css') }}">
@endsection

@section('nav-links')
<a href="{{route('patientDashboardData')}}" class="">Dashboard</a>
<a href="" class="active-link">Profile</a>
@endsection

@section('patientSiteContent')
<div class="container form-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="heading">User Profile</h2>
        <a href="{{ route('patientProfile') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">
        <iframe src="https://www.google.com/maps?q=[ {{$address}} ]&output=embed" style="width:100%;height:550px"></iframe>
    </div>
</div>

@endsection

@section('script')
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection