<div class="main">
    <div class="heading-section d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h3>Patients </h3> <strong class="case-type ps-2 ">(Pending)</strong>
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
    </div>


    <div class="listing">
        <div class="search-section d-flex align-items-center  justify-content-between ">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control search-patient" placeholder="Search Patients"
                    aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="src-category d-flex gap-3 align-items-center">
                <button class="btn-all filter-btn">All</button>
                <button class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill green"></i>Patient</button>
                <button class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill yellow"></i>Family/Friend</button>
                <button class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill red"></i>Buisness</button>
                <button class="d-flex gap-2 filter-btn"> <i class="bi bi-circle-fill blue"></i>Concierge</button>
                {{-- <button class="d-flex gap-2 "><i class="bi bi-circle-fill purple"></i>VIP</button> --}}
            </div>
        </div>
        <div class="table-responsive">



            <table class="table table-hover ">
                <thead class="table-secondary">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Chat With</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingCases as $pendingCase)
                        <tr class="type-{{ $pendingCase->request_type_id }}">
                        <td>{{ $pendingCase->first_name }}</td>
                        <td>{{ $pendingCase->phone_number }}</td>
                        <td>{{ $pendingCase->address }}</td>
                        <td>Status</td>
                            <td>
                                <button class="table-btn"><i class="bi bi-person me-2"></i>Patient</button>
                                <button class="table-btn"><i class="bi bi-person-check me-2"></i>Admin</button>
                            </td>
                        <td><button class="table-btn">Actions</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="page">
            {{ $pendingCases->links('pagination::bootstrap-5') }}
        </div>
    </div>


</div>
