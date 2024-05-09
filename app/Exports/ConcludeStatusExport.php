<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ConcludeStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date Of Birth', 'PhysicianName', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Address'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminConcludeData = $this->data->get();

        return collect($adminConcludeData)->map(function ($adminConclude) {
            $patientName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;
            $patientMobile = null;

            if (isset($adminConclude) && $adminConclude->requestClient) {
                $patientName = $adminConclude->requestClient->first_name;
                $dateOfBirth = $adminConclude->requestClient->date_of_birth;
                $patientMobile = $adminConclude->requestClient->phone_number;
                $street = $adminConclude->requestClient->street;
                $city = $adminConclude->requestClient->city;
                $state = $adminConclude->requestClient->state;
            }

            return [
                'PatientName' => $patientName,
                'Date of Birth' => $dateOfBirth,
                'PhysicianName' => $adminConclude->provider->first_name . ' ' . $adminConclude->provider->last_name,
                'RequestedDate' => $adminConclude->created_at,
                'PatientMobile' => $patientMobile,
                'RequestorMobile' => $adminConclude->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}
