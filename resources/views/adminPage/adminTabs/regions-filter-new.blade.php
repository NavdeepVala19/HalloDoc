@foreach ($cases as $case)
@if (!empty($case->requestClient))
<tr class="type-{{ $case->request_type_id }}">
    <td>{{ $case->requestClient->first_name }}
        {{ $case->requestClient->last_name }}
    </td>
    <td>{{ $case->requestClient->date_of_birth }}</td>
    <td>{{ $case->first_name }} {{ $case->last_name }}</td>
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
    <td>{{ $case->requestClient->notes }}</td>
    <td>
        <div class="action-container">
            <button class="table-btn action-btn">Actions</button>
            <div class="action-menu">
                <button class="assign-case-btn" data-id="{{ $case->id }}"><i class="bi bi-journal-check me-2 ms-3"></i>Assign Case</button>
                <button class="cancel-case-btn" data-id="{{ $case->id }}" data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"><i class="bi bi-x-circle me-2 ms-3"></i>Cancel
                    Case</button>
                <a href="{{ route('admin.view.case', $case->id) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View
                    Case</a>
                <a href="{{ route('admin.view.note', $case->id) }}"><i class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                <button class="block-case-btn" data-id="{{ $case->id }}" data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}">
                    <i class="bi bi-ban me-2 ms-3"></i>
                    Block Patient</button>
            </div>
        </div>
    </td>
</tr>
@endif
@endforeach