@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/scheduling.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="" class="active-link">Providers</a>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="">Access</a>
    <div class="dropdown record-navigation ">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item " href="{{ route('admin.search.records.view') }}">Search Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Requested Shifts</h3>
            <a href="{{ route('admin.scheduling') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <form action="{{ route('admin.shifts.review') }}" method="POST">
            @csrf
            <div class="section">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="region-dropdown">
                        <select name="role_id" class="form-select region-filter" id="floatingSelect">
                            <option value="0" selected>All Regions</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="button-section">
                        <a href="{{ route('admin.scheduling') }}" class="current-shift-btn">View Current Month Shifts</a>
                        <button type="submit" name="action" value="approve" class="approved-selected-btn">Approved
                            Selected</button>
                        <button type="submit" name="action" value="delete" class="delete-selected-btn">Delete
                            Selected</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-secondary">
                            <tr>
                                <th>
                                    <input class="form-check-input master-checkbox" name="" type="checkbox"
                                        value="" id="flexCheckDefault">
                                </th>
                                <th>Staff <i class="bi bi-arrow-up"></i></th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Region</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shiftDetails as $shiftDetail)
                                @if ($shiftDetail)
                                    <tr>
                                        <td>
                                            <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                                                value="{{ $shiftDetail->id }}" id="flexCheckDefault">
                                        </td>
                                        <td>
                                            {{ $shiftDetail->getShiftData->provider->first_name }}
                                            {{ $shiftDetail->getShiftData->provider->last_name }}
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($shiftDetail->shift_date)->format('M d, Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($shiftDetail->start_time)->format('h:i A') }} -
                                            {{ Carbon\Carbon::parse($shiftDetail->end_time)->format('h:i A') }}</td>
                                        <td>{{ $shiftDetail->shiftDetailRegion->region->region_name }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $shiftDetails->links('pagination::bootstrap-5') }}
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Shifts Review Page filtering based on regions
            $(".region-filter").on("change", function() {
                let regionId = $(this).val();

                $.ajax({
                    url: '/filter-regions/' + regionId,
                    type: "GET",
                    success: function() {},
                    error: function(error) {
                        console.error(error);
                    },
                })
            });



        })
    </script>
@endsection
