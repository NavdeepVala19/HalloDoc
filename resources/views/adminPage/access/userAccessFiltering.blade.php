<table class="table" id="user-access-table">
    <thead class="table-secondary text-center align-middle">
        <td>Account Type <i class="bi bi-arrow-up"></i></td>
        <td>Account POC</td>
        <td>Phone</td>
        <td>Status</td>
        <td>Open Requests</td>
        <td>Actions</td>
    </thead>
    <tbody class="text-center align-middle">
        @if ($userAccessDataFiltering->isEmpty())
        <tr>
            <td colspan="100" class="no-record">No Users Found</td>
        </tr>
        @endif
        @foreach ($userAccessDataFiltering as $data )
        <tr>
            <td>{{$data->name}}</td>
            <td>{{$data->first_name}}</td>
            <td>{{$data->mobile}}</td>
            <td>{{$data->status}}</td>
            <td>123</td>
            <td><a href="{{route('admin.user.accessEdit',Crypt::encrypt($data->user_id)) }}" class="primary-empty" type="button">Edit</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$userAccessDataFiltering->links('pagination::bootstrap-5')}}


<div class="mobile-listing mt-3">
    @if ($userAccessDataFiltering->isEmpty())
    <div class="no-record mt-3 mb-3">
        <span>No Users Found</sp>
    </div>
    @endif
    @foreach ($userAccessDataFiltering as $data)
    <div class="mobile-list">
        <div class="main-section">
            <h5 class="heading">{{ $data->first_name }}</h5>
            <div class="detail-box">

                <span>
                    Account Type: {{ $data->name }}
                </span>
            </div>
        </div>
        <div class="details">
            <span><i class="bi bi-telephone"></i> Phone: {{ $data->mobile }}</span>
            <br>
            <span><i class="bi bi-check-lg"></i></i>Status: {{ $data->status }}</span>
            <br>
            <span><i class="bi bi-journal"></i>Open Requests: 123</span>
            <br>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.user.accessEdit', Crypt::encrypt($data->user_id)) }}" class="primary-empty" type="button">Edit</a>
            </div>
        </div>
    </div>
    @endforeach
    {{ $userAccessDataFiltering->links('pagination::bootstrap-5') }}
</div>