<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActiveStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'PhysicianName', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Address', 'Notes'];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminActiveData = $this->data->get();

        return collect($adminActiveData)->map(function ($adminActive) {
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $patientMobile = null;
            $state = null;

            if (isset($adminActive) && $adminActive->requestClient) {
                $patientName = $adminActive->requestClient->first_name;
                $patientLastName = $adminActive->requestClient->last_name;
                $dateOfBirth = $adminActive->requestClient->date_of_birth;
                $patientMobile = $adminActive->requestClient->phone_number;
                $street = $adminActive->requestClient->street;
                $city = $adminActive->requestClient->city;
                $state = $adminActive->requestClient->state;
            }

            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminActive->first_name . ' ' . $adminActive->last_name,
                'PhysicianName' => $adminActive->provider->first_name . ' ' . $adminActive->provider->last_name,
                'RequestedDate' => $adminActive->created_at,
                'PatientMobile' => $patientMobile,
                'RequestorMobile' => $adminActive->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminActive->requestClient->notes,
            ];
        });
    }
}
