    {{-- SendLink Completed Successfully --}}
    @include('alertMessages.sendLinkSuccess')

    {{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
    @include('popup.adminSendLink')

    {{-- Request DTY Support pop-up ->  --}}
    @include('popup.requestDTYSupport')

    {{-- Case Cancelled Successfully --}}
    @if (session('caseClosed'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('caseClosed') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

 <table class="table table-hover ">
     <thead class="table-secondary">
         <tr>
             <th>Name</th>
             <th>Physician Name</th>
             <th>Date Of Service</th>
             <th>Phone</th>
             <th>Address</th>
             <th>Actions</th>
         </tr>
     </thead>
     <tbody id="dropdown-data-body">
         @if ($cases->isEmpty())
         <tr>
             <td colspan="100" class="no-record">No Cases Found</td>
         </tr>
         @endif
         @foreach ($cases as $case)
         @if (!empty($case->requestClient))
         <tr class="type-{{ $case->request_type_id }}">
             <td>
                 {{ $case->requestClient->first_name }}
                 {{ $case->requestClient->last_name }}
             </td>
             <td>{{ $case->provider->first_name }} {{ $case->provider->last_name }}</td>
             <td>{{ $case->created_at }}</td>
             <td class="mobile-column">
                 @if ($case->request_type_id == 1)
                 <div class="listing-mobile-container">
                     <i class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
                 </div>
                 <div class="ms-2">
                     (patient)
                 </div>
                 @else
                 <div class="listing-mobile-container">
                     <i class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
                 </div>
                 <div class="ms-2">
                     (patient)
                 </div>
                 <div class="listing-mobile-container">
                     <i class="bi bi-telephone me-2"></i>{{ $case->phone_number }}
                 </div>
                 <div class="ms-2">
                     ({{ $case->requestType->name }})
                 </div>
                 @endif
             </td>
             <td>
                 {{ $case->requestClient->street }},
                 {{ $case->requestClient->city }},{{ $case->requestClient->state }}
             </td>
             <td>
                 <div class="action-container">
                     <button class="table-btn action-btn" data-id={{ $case->id }}>Actions</button>
                     <div class="action-menu">
                         <a href="{{ route('admin.view.case', Crypt::encrypt($case->id)) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                         <a href="{{ route('admin.view.upload', Crypt::encrypt($case->id)) }}"><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                             Uploads</a>
                         <a href="{{ route('admin.view.note', Crypt::encrypt($case->id)) }}"><i class="bi bi-journal-text me-2 ms-3"></i>
                             View Notes</a>
                     </div>
                 </div>
             </td>
         </tr>
         @endif
         @endforeach
     </tbody>
 </table>

 <div class="page ">
     {{ $cases->links('pagination::bootstrap-5') }}
 </div>