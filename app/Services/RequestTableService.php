<?php

namespace App\Services;

use App\Models\RequestTable;

class RequestTableService
{
    public function createEntry($request, $userId, $confirmationNumber, $providerId = null)
    {
        return RequestTable::create([
            'status' => $providerId ? 3 : 1,
            'physician_id' => $providerId,
            'user_id' => $userId,
            'request_type_id' => $request->request_type_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'confirmation_no' => $confirmationNumber,
        ]);
    }
}
