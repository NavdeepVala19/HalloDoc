<?php

namespace App\Services;

use App\Models\HealthProfessional;

class PartnersService
{
    /**
     * it create new business in partners page
     *
     * @param mixed $request
     *
     * @return bool
     */
    public function createBusiness($request)
    {
        HealthProfessional::create([
            'vendor_name' => $request->business_name,
            'profession' => $request->profession,
            'fax_number' => $request->fax_number,
            'phone_number' => $request->mobile,
            'email' => $request->email,
            'business_contact' => $request->business_contact,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'address' => $request->street,
        ]);

        return true;
    }

    /**
     * it update existing business information in partners page
     *
     * @param mixed $request
     *
     * @return bool
     */

    public function updateBusiness($request)
    {
        HealthProfessional::where('id', $request->vendor_id)->update([
            'vendor_name' => $request->business_name,
            'profession' => $request->profession,
            'fax_number' => $request->fax_number,
            'phone_number' => $request->mobile,
            'email' => $request->email,
            'business_contact' => $request->business_contact,
            'address' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
        ]);

        return true;
    }
}
