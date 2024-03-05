@foreach ($cases as $case)
<tr class="type-{{ $case['request_type_id'] }}" id="dropdown-data">
    <td>{{ $case['first_name'] }}</td>
    <td>{{ $case['date_of_birth'] }}</td>
    <td>{{ $case['requestor'] }}</td>
    <td>{{ $case['created_at'] }}</td>
    <td>{{ $case['phone_number'] }}</td>
    <td>
        {{ $case['street'] }},
        {{ $case['city'] }},{{ $case['state'] }}
    </td>
    <td></td>
    <td>
        <button class="table-btn "><i class="bi bi-person me-2"></i>Provider</button>
    </td>
    <td>
        <div class="action-container">
            <button class="table-btn action-btn">Actions</button>
            <div class="actions-menubar">
                <button class="assign-case-btn"><i class="bi bi-journal-check me-2 ms-3"></i>Assign Case</button>
                <button class="cancel-case-btn" data-id="{{ $case['request_id']}}" data-patient_name="{{ $case['first_name'] }} {{ $case['last_name'] }}">
                    <i class="bi bi-x-circle me-2 ms-3"></i>
                    Cancel Case
                </button>
                <button>
                    <i class="bi bi-journal-arrow-down me-2 ms-3"></i>
                    View Case
                </button>
                <a href="/view-notes/{{ $case['request_id'] }}">
                    <i class="bi bi-journal-text me-2 ms-3"></i>
                    View Notes
                </a>
                <button class="block-case-btn" data-id="{{ $case['request_id'] }}" data-patient_name="{{ $case['first_name'] }} {{ $case['last_name'] }}">
                    <i class="bi bi-ban me-2 ms-3"></i>
                    Block Patient
                </button>
            </div>
        </div>
    </td>
</tr>
@endforeach