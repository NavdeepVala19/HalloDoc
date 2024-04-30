
                <div class="table-responsive">
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
                                        <td>
                                            @if ($case->provider)
                                                {{ $case->provider->first_name }} {{ $case->provider->last_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
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
                                        <td>
                                            {{ $case->requestClient->street }},
                                            {{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                        </td>
                                        <td>
                                            <div class="action-container">
                                                <button class="table-btn action-btn"
                                                    data-id={{ $case->id }}>Actions</button>
                                                <div class="action-menu">
                                                    <a href="{{ route('admin.view.case', Crypt::encrypt($case->id)) }}"><i
                                                            class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                                                    <a href="{{ route('admin.view.upload', Crypt::encrypt($case->id)) }}"><i
                                                            class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                                                        Uploads</a>
                                                    <a href="{{ route('admin.view.note', Crypt::encrypt($case->id)) }}"><i
                                                            class="bi bi-journal-text me-2 ms-3"></i>
                                                        View Notes</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mobile-listing">
                    @if ($cases->isEmpty())
                        <div class="no-record mt-3 mb-3">
                            <span>No Cases Found</sp>
                        </div>
                    @endif
                    @foreach ($cases as $case)
                        @if (!empty($case) && !empty($case->requestClient))
                            <div class="mobile-list d-flex justify-content-center align-items-between flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>{{ $case->requestClient->first_name }} {{ $case->requestClient->last_name }}
                                    </span>
                                    <div>
                                        @if ($case->request_type_id == 1)
                                            <span>
                                                Patient
                                                <i class="bi bi-circle-fill ms-1 green"></i>
                                            </span>
                                        @elseif ($case->request_type_id == 2)
                                            <span>
                                                Family/Friend
                                                <i class="bi bi-circle-fill ms-1 yellow"></i>
                                            </span>
                                        @elseif ($case->request_type_id == 3)
                                            <span>
                                                Concierge
                                                <i class="bi bi-circle-fill ms-1 blue"></i>
                                            </span>
                                        @elseif ($case->request_type_id == 4)
                                            <span>
                                                Business
                                                <i class="bi bi-circle-fill ms-1 red"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="address-section">
                                        @if ($case->requestClient)
                                            {{ $case->requestClient->street }},{{ $case->requestClient->city }},{{ $case->requestClient->state }}
                                        @endif
                                    </span>
                                    <button class="map-btn">Map Location</button>
                                </div>
                            </div>
                            <div class="more-info ">
                                <a href="{{ route('admin.view.case', Crypt::encrypt($case->id)) }}" class="view-btn">View
                                    Case</a>
                                <div>
                                    <span>
                                        <i class="bi bi-envelope"></i> Email :
                                        {{ $case->requestClient->email }}
                                    </span>
                                    <br>
                                    <span>
                                        <i class="bi bi-telephone"></i> Patient :
                                        {{ $case->requestClient->phone_number }}
                                    </span>
                                    <br>
                                    <span>
                                        <i class="bi bi-calendar3"></i> Date of services :
                                        {{ $case->created_at }}
                                    </span>
                                    <br>
                                    <span>
                                        <i class="bi bi-person-circle"></i> Physician : Dr.
                                        @if ($case->provider)
                                            {{ $case->provider->first_name }} {{ $case->provider->last_name }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                    <div class="grid-2-listing ">
                                        <a href="{{ route('admin.view.note', Crypt::encrypt($case->id)) }}"
                                            class="secondary-btn text-center">
                                            View Notes</a>
                                        <a href="{{ route('admin.view.upload', Crypt::encrypt($case->id)) }}"
                                            class="secondary-btn text-center">View
                                            Uploads</a>
                                        <button class="secondary-btn">Email</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
<div class="page ">
    {{ $cases->links('pagination::bootstrap-5') }}
</div>
