   @if ($providersData->isEmpty())
       <div class="d-flex justify-content-center align-items-center">
           <div class="no-record">No Provider Found</div>
       </div>
   @endif
   @foreach ($providersData as $data)
       <div class="mobile-list">
           <div class="main-section mt-3">
               <h5 class="heading">
                   <input class="form-check-input checkbox2" type="checkbox" value="1" @checked($data->is_notifications === 1)
                       id="checkbox1_{{ $data->id }}">
                   {{ $data->first_name }} {{ $data->last_name }}
               </h5>
               <div class="detail-box">
                   <span>
                       On Call Status:
                       <strong>
                           {{ in_array($data->id, $onCallPhysicianIds) ? 'Unavailable' : 'Available' }}
                       </strong>
                   </span>
               </div>
           </div>
           <div class="details mt-3">
               <span><i class="bi bi-person"></i> Role Name :
                   {{ $data->role->name ?? ' ' }}</span>
               <br>
               <span><i class="bi bi-check2"></i>Status : {{ $data->status }} </span>
               <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                   <button type="button" data-id='{{ $data->id }}'
                       class="primary-empty contact-btn contact-provider-btn mt-2 mb-2"
                       id="contact_button_{{ $data->id }}">Contact</button>
                   <a href="{{ route('adminEditProvider', Crypt::encrypt($data->id)) }}" type="button"
                       class="primary-empty btn edit-btn mt-2 mb-2">Edit</a>
               </div>
           </div>
       </div>
   @endforeach
   {{ $providersData->links('pagination::bootstrap-5') }}
