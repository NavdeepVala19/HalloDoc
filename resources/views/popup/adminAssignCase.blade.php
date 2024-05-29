{{-- Assign Case Pop-up --}}
{{-- This pop-up will open when admin clicks on “Assign case” link from Actions menu. Admin can assign the case
to providers based on patient’s region using this pop-up. --}}
<div class="pop-up assign-case">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Assign Request</span>
        <button class="hide-popup-btn adminAssignCancel"><i class="bi bi-x-lg"></i></button>
    </div>
    <p class="m-2">To assign this request, search and select another Physician</p>
    <form action="{{ route('admin.assign.case') }}" method="POST" id="adminAssignCase">
        @csrf
        <div class="m-3">
            <input type="text" class="requestId" name="requestId" value="" hidden>
            <div class="form-floating">
                <select class="form-select physicianRegions @error('region') is-invalid @enderror" name="region" id="floatingSelect1"
                    aria-label="Floating label select example">
                    <option selected>Regions</option>
                </select>
                <label for="floatingSelect1">Narrow Search by Region</label>
                @error('region')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating">
                <select
                    class="form-select selectPhysician @error('physician')
                is-invalid
                @enderror"
                    name="physician" id="floatingSelect2" aria-label="Floating label select example" required>
                    <option disabled selected>Physicians</option>
                </select>
                <label for="floatingSelect2">Select Physician</label>
                @error('physician')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating">
                <textarea class="form-control @error('assign_note')
                    is-invalid
                @enderror"
                    name="assign_note" placeholder="Description" id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Description</label>
                @error('assign_note')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <button type="submit" class="primary-fill confirm-case" id="adminAssignCaseBtn">Submit</button>
            <button class="primary-empty hide-popup-btn adminAssignCancel">Cancel</button>
        </div>
    </form>
</div>
