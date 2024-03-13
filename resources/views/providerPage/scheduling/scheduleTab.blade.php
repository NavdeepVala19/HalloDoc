@extends('adminPage.scheduling.scheduling')

@section('sectionView')
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Scheduling</h3>
            <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <div class="d-flex align-items-center justify-content-between">
                <div class=" region-dropdown">
                    <select name="role_id" class="form-select empty-fields" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="0" selected>All Regions</option>
                        <option value="1">Admin</option>
                        <option value="2">Physician</option>
                    </select>
                </div>
                <div>
                    <button class="primary-fill">Providers On Call</button>
                    <button class="primary-fill">Shifts For Review</button>
                    <button class="primary-fill">Add New Shift</button>
                </div>
            </div>
            <h2 class="date-title m-3">Date</h2>
            <div class="d-flex justify-content-end">
                <span class="d-flex align-items-center">
                    <div class="pending-shift m-2"></div>Pending Shifts
                </span>
                <span class="d-flex align-items-center">
                    <div class="approved-shift m-2"></div>Approved Shifts
                </span>
            </div>
            <div id="calendar"></div>
        </div>
    </div>
@endsection
