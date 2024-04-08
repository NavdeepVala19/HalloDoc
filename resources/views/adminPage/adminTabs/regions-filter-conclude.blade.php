 <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Date Of Birth</th>
                            <th>Physician Name</th>
                            <th>Date Of Service</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dropdown-data-body">
                        @foreach ($cases as $case)
                            @if (!empty($case->requestClient))
                                <tr class="type-{{ $case->request_type_id }}">
                                    <td>{{ $case->requestClient->first_name }}
                                        {{ $case->requestClient->last_name }}
                                    </td>
                                    <td>{{ $case->requestClient->date_of_birth }}</td>
                                    <td>{{ $case->provider->first_name }} {{ $case->provider->last_name }}</td>
                                    <td>{{ $case->created_at }}</td>
                                    <td class="mobile-column">
                                        @if ($case->request_type_id == 1)
                                            <div class="listing-mobile-container">
                                                <i
                                                    class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
                                            </div>
                                            <div class="ms-2">
                                                (patient)
                                            </div>
                                        @else
                                            <div class="listing-mobile-container">
                                                <i
                                                    class="bi bi-telephone me-2"></i>{{ $case->requestClient->phone_number }}
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
                                    <td>{{ $case->requestClient->street }},
                                        {{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                    </td>
                                    <td>
                                        <div class="action-container">
                                            <button class="table-btn action-btn"
                                                data-id="{{ $case->id }}">Actions</button>
                                            <div class="action-menu">
                                                <a href="{{ route('admin.view.case', $case->id) }}"><i
                                                        class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                                <a href="{{ route('admin.view.upload', ['id' => $case->id]) }}"><i
                                                        class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                    Uploads</a>
                                                <a href={{ route('admin.view.note', $case->id) }}><i
                                                        class="bi bi-journal-text me-2 ms-3"></i>View
                                                    Notes</a>
                                                <a href="{{ route('admin.view.order', $case->id) }}"><i
                                                        class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                                                <a href="{{ route('admin.encounter.form', $case->id) }}"
                                                    class="encounter-form-btn"><i
                                                        class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</a>
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