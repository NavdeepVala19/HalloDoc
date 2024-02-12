<div class="table-responsive">
    <table class="table table-hover ">
        <thead class="table-secondary">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                @if ($tabName != 'New')
                    <th>Status</th>
                @endif
                <th>Chat With</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cases as $case)
                <tr class="type-{{ $case->request_type_id }}">
                    <td>{{ $case->first_name }}</td>
                    <td>{{ $case->phone_number }}</td>
                    <td>
                        @if ($case->requestClient)
                            {{ $case->requestClient->address }}
                        @endif
                    </td>
                    @if ($tabName != 'New')
                        <td>Status</td>
                    @endif
                    <td>
                        @if ($tabName != 'New')
                            <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
                        @endif
                        <button class="table-btn"><i class="bi bi-person-check me-2"></i>Admin</button>
                    </td>
                    <td><button class="table-btn">Actions</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mobile-listing">
    @foreach ($cases as $case)
        <div class="mobile-list d-flex justify-content-between">
            <div class="d-flex flex-column">
                <p>{{ $case->first_name }} </p>
                <span>
                    @if ($case->requestClient)
                        {{ $case->requestClient->address }}
                    @endif Address
                </span>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-around">
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
                        Business
                        <i class="bi bi-circle-fill ms-1 red"></i>
                    </span>
                @elseif ($case->request_type_id == 4)
                    <span>
                        Concierge
                        <i class="bi bi-circle-fill ms-1 blue"></i>
                    </span>
                @endif
                <button class="map-btn">Map Location</button>
            </div>
        </div>
        <div class="more-info ">
            <button class="view-btn">View Case</button>
            <div>
                <span>
                    <i class="bi bi-envelope"></i> Email : example@xyz.com
                    {{-- {{$case->requestClient->email}} --}}
                </span>
                <br>
                <span>
                    <i class="bi bi-telephone"></i> Patient : +91 123456789
                    {{-- {{$case->requestClient->phone_number}} --}}
                </span>
                <div class="grid-2 ">
                    <button class="secondary-btn">View Notes</button>
                    <button class="secondary-btn-1">Doctors Notes</button>
                    <button class="secondary-btn">View Uploads</button>
                    <button class="secondary-btn">Encouter</button>
                    <button class="secondary-btn-2">orders</button>
                    <button class="secondary-btn-3">House Call</button>
                    <button class="secondary-btn">Email</button>
                </div>
            </div>
            <div >
                    Chat With:
                    <button class="more-info-btn"><i class="bi bi-person me-2"></i>Patient</button>
                    <button class="more-info-btn"><i class="bi bi-person-check me-2"></i>Admin</button>

            </div>
        </div>
    @endforeach
</div>

<div class="page">
    {{ $cases->links('pagination::bootstrap-5') }}
</div>
