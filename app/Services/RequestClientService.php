<?php

namespace App\Services;

use App\Models\RequestClient;

class RequestClientService
{
    public function createEntry($request, $requestId)
    {
        RequestClient::create([
            'request_id' => $requestId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'room' => $request->room,
            'notes' => $request->symptoms,
        ]);
    }
}
