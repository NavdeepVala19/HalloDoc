 @foreach ($cases as $case)

 <tr class="type-{{ $case->request_type_id }}">
     <td>{{ $case['first_name'] }}</td>
     <td>{{ $case['date_of_birth'] }}</td>
     <td>{{ $case['physician_name'] }}</td>
     <td>{{ $case['created_at'] }}</td>
     <td>{{ $case['phone_number'] }}</td>
     <td>
         {{ $case['street'] }},
         {{ $case['city'] }},{{ $case['state'] }}
     </td>
     <td>
         <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
         <button class="table-btn"><i class="bi bi-person-check me-2"></i>Provider</button>
     </td>
     <td>
         <div class="action-container">
             <button class="table-btn action-btn" data-id="{{ $case->request_id }}">Actions</button>
             <div class="actions-menubar">
                 <a href="{{ route('provider.view.case', $case->request_id) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                 <button><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                     Uploads</button>
                 <button><i class="bi bi-journal-text me-2 ms-3"></i>View
                     Notes</button>
                 <a href="{{ route('admin.view.order', $case->request_id) }}"><i class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                 <button><i class="bi bi-text-paragraph me-2 ms-3"></i>Doctors Note</button>
                 <button class="encounter-btn"><i class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</button>
             </div>
         </div>
     </td>
 </tr>

 @endforeach