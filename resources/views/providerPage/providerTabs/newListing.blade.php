<div class="main">
    {{-- <div class="heading-section d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h3>Patients </h3> <strong class="case-type ps-2 ">(New)</strong>
        </div>
        <div>
            <a href="" class="primary-btn me-3">
                <i class="bi bi-send"></i>
                <span class="txt">
                    Send Link
                </span>
            </a>
            <a class="primary-btn" href="{{ route('provider-create-request') }}">
                <i class="bi bi-pencil-square"></i>
                <span class="txt">
                    Create Requests
                </span>
            </a>
        </div>
    </div> --}}

    <div class="listing">
        <div class="search-section d-flex align-items-center  justify-content-between ">
            <form action="{{ route('searching') }}" method="GET">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text" class="form-control search-patient" placeholder="Search Patients"
                        aria-label="Username" aria-describedby="basic-addon1" name="search">
                    <input type="submit" class="primary-fill">
                </div>
            </form>
            <div class="src-category d-flex gap-3 align-items-center">
                <a href="{{ route('provider-listing', ['category' => 'all', 'status' => 'new']) }}" class="btn-all filter-btn">All</button>
                <a href="{{ route('provider-listing', ['category' => 'patient', 'status' => 'new']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</a>
                <a href="{{ route('provider-listing', ['category' => 'family', 'status' => 'new']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill yellow"></i>Family/Friend</a>
                <a href="{{ route('provider-listing', ['category' => 'business', 'status' => 'new']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Business</a>
                <a href="{{ route('provider-listing', ['category' => 'concierge', 'status' => 'new']) }}" class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover ">
                <thead class="table-secondary">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Chat With</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $case)
                        <tr class="type-{{ $case->request_type_id }}">
                            <td>{{ $case->first_name }}</td>
                            <td>{{ $case->phone_number }}</td>
                            <td>{{ $case->address }}</td>
                            <td>
                                <button class="table-btn "><i class="bi bi-person-check me-2"></i>Admin</button>
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

    </div>
</div>




