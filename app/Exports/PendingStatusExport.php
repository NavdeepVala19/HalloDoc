<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;


class PendingStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'PhysicianName', 'RequestedDate', 'Mobile', 'Address', 'Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminPendingData = $this->data->get();

        return collect($adminPendingData)->map(function ($adminPending) {
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;

            if (isset($adminPending) && $adminPending->requestClient) {
                $patientName = $adminPending->requestClient->first_name;
                $patientLastName = $adminPending->requestClient->last_name;
                $dateOfBirth = $adminPending->requestClient->date_of_birth;
                $street = $adminPending->requestClient->street;
                $city = $adminPending->requestClient->city;
                $state = $adminPending->requestClient->state;
            }

            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminPending->first_name . ' ' . $adminPending->first_name,
                'PhysicianName' => $adminPending->provider->first_name . ' ' . $adminPending->provider->last_name,
                'RequestedDate' => $adminPending->created_at,
                'Mobile' => $adminPending->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminPending->requestClient->notes,
            ];
        });
    }
}
