<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ToCloseStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date Of Birth', 'Date of Service', 'Address', 'Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminToCloseData = $this->data->get();

        return collect($adminToCloseData)->map(function ($adminToClose) {
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;
            $providerFirstName = null;
            $providerLastName = null;

            if (isset($adminToClose) && $adminToClose->requestClient) {
                $patientName = $adminToClose->requestClient->first_name;
                $patientLastName = $adminToClose->requestClient->last_name;
                $dateOfBirth = $adminToClose->requestClient->date_of_birth;
                $street = $adminToClose->requestClient->street;
                $city = $adminToClose->requestClient->city;
                $state = $adminToClose->requestClient->state;
            }
            if (isset($adminToClose) && $adminToClose->provider) {
                $providerFirstName = $adminToClose->provider->first_name;
                $providerLastName = $adminToClose->requestClient->last_name;
            }

            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'PhysicianName' => $providerFirstName . ' ' . $providerLastName,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminToClose->requestClient->notes,
            ];
        });
    }
}
