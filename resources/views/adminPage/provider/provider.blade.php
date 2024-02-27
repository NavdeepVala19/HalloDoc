@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminProvider.css') }}">

@endsection

@section('nav-links')

<a href="">Dashboard</a>
<a href="">Provider Location</a>
<a href="" class="active-link">My Profile</a>
<a href="">Providers</a>
<a href="">Partners</a>
<a href="">Access</a>
<a href="">Records</a>


@endsection

@section('content')

<div class="container">

    <h2>Provider Information</h2>

    <div class="main-info-content">

        <div class="content-header d-flex flex-row justify-content-between align-items-center">

            <select class="form-select">
                <option selected>All </option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>

            <div class="provider-btn">
                <button class="btn primary-fill create-provider-btn mt-1 me-2 mb-2">Create Provider Account</button>
            </div>

        </div>

        <div class="listing-table mt-3">

            <table class="provider-table table">
                <thead class="table-secondary">
                    <tr>
                        <td style="width: 7%;" class="theader">Stop Notification</td>
                        <td class="theader">Provider Name</td>
                        <td class="theader">Role</td>
                        <td class="theader">On Call Status</td>
                        <td class="theader">Status</td>
                        <td style="width: 13%;" class="theader">Actions</td>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td class="checks"> <input class="form-check-input" type="checkbox" value="" id="checkbox">
                        </td>
                        <td> Provider Name</td>
                        <td> Physician</td>
                        <td> Un available</td>
                        <td> Pending </td>
                        <td class="gap-1">
                            <a href="" type="button" class="primary-empty btn mt-2 mb-2">Contact</a>
                            <a href="" type="button" class="primary-empty btn mt-2 mb-2">Edit</a>
                        </td>

                    </tr>
                </tbody>

            </table>


            <!-- contact your provider pop-up -->

            <div class="pop-up new-provider">
                <div class="popup-heading-section d-flex align-items-center justify-content-between">
                    <span class="ms-3">Contact Your Provider</span>
                    <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
                </div>
                <p class="mt-4 ms-3">Choose communication to send message</p>
                <div class="ms-3">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                </div>
                <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                    <button class="primary-fill continue-btn">Continue</button>
                    <button class="primary-empty hide-popup-btn">Cancel</button>
                </div>
            </div>

        </div>


    </div>

</div>


@endsection