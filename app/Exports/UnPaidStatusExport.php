<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnPaidStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getCsvSettings(): array
    {
        return ['delimiter' => ','];
    }

    public function headings(): array
    {
        return ['PatientName', 'Physician Name', 'RequestedDate', 'PatientMobile','RequestorMobile', 'Mobile', 'Address'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminUnpaidData = $this->data->get();

        return collect($adminUnpaidData)->map(function ($adminUnpaid) {

            $patientName = null;
            $patientLastName = null; 
            $PhysicianFirstName = null;
            $PhysicianLastName = null; 
            $street = null;
            $city = null;
            $patientMobile = null;
            $state = null;


            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $patientName = $adminUnpaid->requestClient->first_name;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $patientLastName = $adminUnpaid->requestClient->last_name;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $dateOfBirth = $adminUnpaid->requestClient->date_of_birth;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $patientMobile = $adminUnpaid->requestClient->phone_number;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $street = $adminUnpaid->requestClient->street;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $city = $adminUnpaid->requestClient->city;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $state = $adminUnpaid->requestClient->state;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $PhysicianFirstName = $adminUnpaid->provider->first_name;
            }
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                $PhysicianLastName = $adminUnpaid->provider->last_name;
            }

            return [
                'PatientName' => $patientName.' '.$patientLastName,
                'Physician Name' => $PhysicianFirstName.' '.$PhysicianLastName,
                'RequestedDate' => $adminUnpaid->created_at,
                'PatientMobile' => $patientMobile,
                'RequestorMobile' => $adminUnpaid->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}
