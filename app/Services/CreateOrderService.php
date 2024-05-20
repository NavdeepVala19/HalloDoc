<?php

namespace App\Services;

use App\Models\HealthProfessional;
use App\Models\Orders;

class CreateOrderService
{
    public function createOrder($request)
    {
        $healthProfessional = HealthProfessional::where('id', $request->vendor_id)->first();
        Orders::create([
            'vendor_id' => $request->vendor_id,
            'request_id' => $request->requestId,
            'fax_number' => $healthProfessional->fax_number,
            'business_contact' => $healthProfessional->business_contact,
            'email' => $healthProfessional->email,
            'prescription' => $request->prescription,
            'no_of_refill' => $request->refills,
        ]);

        return true;
    }
}
