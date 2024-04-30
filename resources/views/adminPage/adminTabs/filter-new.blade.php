<table class="table table-hover ">
    <thead class="table-secondary">
        <tr>
            <th>Name</th>
            <th>Date Of Birth</th>
            <th>Requestor</th>
            <th>Requested Date</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Notes</th>
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
                        <div class="d-flex align-items-center justify-content-between">
                            <span>
                                {{ $case->requestClient->first_name }}
                                {{ $case->requestClient->last_name }}
                            </span>
                            <button class="send-mail-btn" data-requestid="{{ $case->id }}"
                                data-name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"
                                data-email={{ $case->requestClient->email }}>
                                <i class="bi bi-envelope"></i>
                            </button>
                        </div>
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
                                <button class="assign-case-btn" data-id="{{ $case->id }}"><i
                                        class="bi bi-journal-check me-2 ms-3"></i>Assign Case</button>
                                <button class="cancel-case-btn" data-id="{{ $case->id }}"
                                    data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}"><i
                                        class="bi bi-x-circle me-2 ms-3"></i>Cancel
                                    Case</button>
                                <a href="{{ route('admin.view.case', Crypt::encrypt($case->id)) }}"><i
                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View
                                    Case</a>
                                <a href="{{ route('admin.view.note', Crypt::encrypt($case->id)) }}"><i
                                        class="bi bi-journal-text me-2 ms-3"></i>View Notes</a>
                                <button class="block-case-btn" data-id="{{ $case->id }}"
                                    data-patient_name="{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}">
                                    <i class="bi bi-ban me-2 ms-3"></i>
                                    Block Patient</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<div class="page">
    {{ $cases->links('pagination::bootstrap-5') }}
</div>
