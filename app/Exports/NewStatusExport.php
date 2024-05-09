<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;


class NewStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Address', 'Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminNewData = $this->data->get();
        return collect($adminNewData)->map(function ($adminNew) {
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;
            $patientMobile = null;

            if (isset($adminNew) && $adminNew->requestClient) {
                $patientName = $adminNew->requestClient->first_name;
                $patientLastName = $adminNew->requestClient->last_name;
                $patientMobile = $adminNew->requestClient->phone_number;
                $dateOfBirth = $adminNew->requestClient->date_of_birth;
                $street = $adminNew->requestClient->street;
                $city = $adminNew->requestClient->city;
                $state = $adminNew->requestClient->state;
            }

            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminNew->first_name . ' ' . $adminNew->last_name,
                'RequestedDate' => $adminNew->created_at,
                'PatientMobile' => $patientMobile,
                'RequestorMobile' => $adminNew->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminNew->requestClient->notes,
            ];
        });
    }
}
