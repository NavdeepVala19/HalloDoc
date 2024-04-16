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
                    <a href="{{ route('admin.user.accessEdit',Crypt::encrypt( $data->user_id)) }}" class="primary-empty" type="button">Edit</a>
                </div>
            </div>
        </div>
        @endforeach
        {{ $userAccessDataFiltering->links('pagination::bootstrap-5') }}
