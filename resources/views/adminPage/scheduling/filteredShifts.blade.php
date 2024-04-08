    @foreach ($shiftDetails as $shiftDetail)
        @if ($shiftDetail)
            <tr>
                <td>
                    <input class="form-check-input child-checkbox" name="selected[]" type="checkbox"
                        value="{{ $shiftDetail->id }}" id="flexCheckDefault">
                </td>
                <td>
                    {{ $shiftDetail->getShiftData->provider->first_name }}
                    {{ $shiftDetail->getShiftData->provider->last_name }}
                </td>
                <td>{{ Carbon\Carbon::parse($shiftDetail->shift_date)->format('M d, Y') }}</td>
                <td>{{ Carbon\Carbon::parse($shiftDetail->start_time)->format('h:i A') }} -
                    {{ Carbon\Carbon::parse($shiftDetail->end_time)->format('h:i A') }}</td>
                <td>{{ $shiftDetail->shiftDetailRegion->region->region_name }}</td>
            </tr>
        @endif
    @endforeach
