 {{-- Cancel Case Pop-up --}}
 {{-- This pop-up will open when admin will click on “Cancel case” link from Actions menu. Admin can cancel the request using this pop-up. --}}
 <div class="pop-up cancel-case">
     <div class="popup-heading-section d-flex align-items-center justify-content-between">
         <span>Confirm Cancellation</span>
         <button type="button" class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
     </div>
     <div class="m-3">
         <span>Patient Name: </span> <span class="displayPatientName">patient name</span>
     </div>
     <form action="{{ route('admin.cancel.case') }}" method="POST" id="cancelCaseForm">
         @csrf
         <input type="text" class="requestId" name="requestId" value="" hidden>
         <div class="m-3">
             <div class="form-floating">
                 <select class="form-select" name="case_tag"
                     class="cancel-options @error('case_tag') is-invalid @enderror" id="floatingSelect"
                     aria-label="Floating label select example">
                     <option selected>Reasons</option>
                 </select>
                 <label for="floatingSelect">Reasons for Cancellation</label>
                 @error('case_tag')
                     <div class="text-danger">{{ $message }}</div>
                 @enderror
             </div>
             <div class="form-floating">
                 <textarea class="form-control" name="reason" placeholder="notes" id="floatingTextarea2"></textarea>
                 <label for="floatingTextarea2">Provide Additional Notes</label>
             </div>
         </div>
         <div class="p-2 d-flex align-items-center justify-content-end gap-2">
             <input type="submit" value="Confirm" class="primary-fill cancel-case" id='cancel-case'>
             <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
         </div>
     </form>
 </div>
