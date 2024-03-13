 @foreach ($cases as $case)
 <tr class="type-{{ $case['request_type_id'] }}" id="dropdown-data">
     <td>{{ $case['first_name'] }}</td>
     <td>{{ $case['date_of_birth'] }}</td>
     <td>{{ $case['requestor'] }}</td>
     <td>{{ $case['physician_name'] }}</td>
     <td>{{ $case['created_at'] }}</td>
     <td>{{ $case['phone_number'] }}</td>
     <td>
         {{ $case['street'] }},
         {{ $case['city'] }},{{ $case['state'] }}
     </td>
     <td></td>
     <td>
         <div class="action-container">
             <button class="table-btn action-btn">Actions</button>
             <div class="actions-menubar">
                 <a href="{{ route('provider.view.case', $case->request_id) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                 <a href="{{ route('provider.view.upload', ['id' => $case->request_id]) }}">
                     <i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>
                     View Uploads
                 </a>
                 <button><i class="bi bi-journal-text me-2 ms-3"></i>View Notes</button>
                 <button class="transfer-btn"><i class="bi bi-send me-2 ms-3"></i>Transfer</button>
                 <button class="clear-btn" data-id="{{ $case->request_id }}"><i class="bi bi-x-circle me-2 ms-3"></i>Clear
                     Case</button>
                 <button class="send-agreement-btn" data-id="{{ $case->request_id }}" data-request_type_id="{{ $case->request_type_id }}"><i class="bi bi-text-paragraph me-2 ms-3"></i>Send
                     Agreement</button>
             </div>
         </div>
     </td>
 </tr>
 @endforeach