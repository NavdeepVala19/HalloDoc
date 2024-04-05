 {{-- Transfer Request Pop-up --}}
 {{-- This pop-up will open when admin clicks on “Transfer” link from Actions menu. From the pending state, admin
can transfer assigned request to another physician. --}}
 <div class="pop-up transfer-case">
     <div class="popup-heading-section d-flex align-items-center justify-content-between">
         <span>Transfer Request</span>
         <button class="hide-popup-btn adminTransferCancel"><i class="bi bi-x-lg"></i></button>
     </div>
     <p class="m-2">To transfer this request, search and select another Physician</p>
     <form action="{{ route('admin.transfer.case') }}" method="POST" id="adminTransferRequest">
         @csrf
         <div class="m-3">
             <input type="text" class="requestId" name="requestId" value="" hidden>
             <div class="form-floating">
                 <select class="form-select select physicianRegionsTransferCase" name="region" id="floatingSelect1"
                     aria-label="Floating label select example">
                     <option selected>Regions</option>
                 </select>
                 <label for="floatingSelect1">Narrow Search by Region</label>
             </div>
             <div class="form-floating">
                 <select
                     class="form-select selectPhysician @error('physician')
                is-invalid
                @enderror"
                     id="floatingSelect2" aria-label="Floating label select example" name="physician">
                     <option selected disabled>Select Physician</option>
                 </select>
                 <label for="floatingSelect2">Select Physician</label>
                 @error('physician')
                     <div class="text-danger">{{ $message }}</div>
                 @enderror
             </div>
             <div class="form-floating">
                 <textarea class="form-control @error('notes')
                    is-invalid
                @enderror" name="notes"
                     placeholder="Description" id="floatingTextarea2"></textarea>
                 <label for="floatingTextarea2">Description</label>
                 @error('notes')
                     <div class="text-danger">{{ $message }}</div>
                 @enderror
             </div>
         </div>
         <div class="p-2 d-flex align-items-center justify-content-end gap-2">
             <button type="submit" class="primary-fill confirm-case" id="adminTransferRequestBtn">Confirm</button>
             <button class="primary-empty hide-popup-btn adminTransferCancel">Cancel</button>
         </div>
     </form>
 </div>
