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
                    @foreach ($userAccessData as $data )
                    <tr>
                        <td>{{$data->name}}</td>
                        <td>{{$data->first_name}}</td>
                        <td>{{$data->mobile}}</td>
                        <td>{{$data->status}}</td>
                        <td>123</td>
                        <td><a href="{{route('admin.user.accessEdit',$data->user_id)}}" class="primary-empty" type="button">Edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$userAccessData->links('pagination::bootstrap-5')}}