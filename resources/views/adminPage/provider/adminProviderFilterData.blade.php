    <table class="provider-table table" id="all-providers-data">
        <thead class="table-secondary">
            <tr>
                <td style="width: 7%;" class="theader">Stop Notification</td>
                <td class="theader">Provider Name</td>
                <td class="theader">Role</td>
                <td class="theader">On Call Status</td>
                <td class="theader">Status</td>
                <td style="width: 13%;" class="theader">Actions</td>
            </tr>
        </thead>
        <tbody>
            @if ($providersData->isEmpty())
                <tr>
                    <td colspan="100" class="no-record">No Provider Found</td>
                </tr>
            @endif
            @foreach ($providersData as $data)
                <tr>
                    <td class="checks">
                        <input class="form-check-input checkbox1" type="checkbox" value="1"
                            @checked($data->is_notifications === 1) id="checkbox_{{ $data->id }}">
                    </td>
                    <td class="data"> {{ $data->first_name }} {{ $data->last_name }}</td>
                    <td class="data"> {{ $data->role->name ?? ' ' }}</td>
                    <td class="data">
                        {{ in_array($data->id, $onCallPhysicianIds) ? 'Unavailable' : 'Available' }}
                    </td>
                    <td class="data"> {{ $data->status }} </td>
                    <td class="data gap-1">
                        <button type="button" data-id='{{ $data->id }}'
                            class="primary-empty contact-btn mt-2 mb-2 contact_your_provider"
                            id="contact_btn_{{ $data->id }}">Contact</button>
                        <a href="{{ route('adminEditProvider', Crypt::encrypt($data->id)) }}" type="button"
                            class="primary-empty btn edit-btn mt-2 mb-2">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $providersData->links('pagination::bootstrap-5') }}
