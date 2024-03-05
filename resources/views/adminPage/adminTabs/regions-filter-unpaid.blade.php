 @foreach ($cases as $case)

 <tr class="type-{{ $case->request_type_id }}">
     <td>{{ $case->first_name }}</td>
     <td>Physician Name</td>
     <td>{{ $case->created_at }}</td>
     <td>{{ $case->phone_number }}</td>
     <td>
         {{ $case->request->requestClient->street }},
         {{ $case->request->requestClient->city }},{{ $case->request->requestClient->state }}
     </td>
     <td>
         <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
         <button class="table-btn"><i class="bi bi-person-check me-2"></i>Provider</button>
     </td>
     <td>
         <div class="action-container">
             <button class="table-btn action-btn" data-id={{ $case->request->id }}>Actions</button>
             <div class="actions-menubar">
                 <a href="{{ route('provider.view.case', $case->request_id) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                 <button><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                     Uploads</button>
                 <button><i class="bi bi-journal-text me-2 ms-3"></i>
                     View Notes</button>
             </div>
         </div>
     </td>
 </tr>

 @endforeach