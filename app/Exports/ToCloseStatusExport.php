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
            }
            if (isset($adminToClose) && $adminToClose->requestClient) {
                $patientLastName = $adminToClose->requestClient->last_name;
            }
            if (isset($adminToClose) && $adminToClose->requestClient) {
                $dateOfBirth = $adminToClose->requestClient->date_of_birth;
            }
            if (isset($adminToClose) && $adminToClose->requestClient) {
                $street = $adminToClose->requestClient->street;
            }
            if (isset($adminToClose) && $adminToClose->requestClient) {
                $city = $adminToClose->requestClient->city;
            }
            if (isset($adminToClose) && $adminToClose->requestClient) {
                $state = $adminToClose->requestClient->state;
            }
            if (isset($adminToClose) && $adminToClose->provider) {
                $providerFirstName = $adminToClose->provider->first_name;
            }
            if (isset($adminToClose) && $adminToClose->provider) {
                $providerLastName = $adminToClose->requestClient->last_name;
            }


            return [
                'PatientName' => $patientName . " " . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'PhysicianName' => $providerFirstName . ' ' . $providerLastName,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminToClose->requestClient->notes,
            ];
        });
    }
}